#!/usr/bin/env python3
"""
Convert MySQL schema (CREATE TABLE + ALTER TABLE) into DBML for dbdiagram.io.

Usage:
    python scripts/sql_to_dbml.py \
        --input "quan_ly_tour_du_lich (2).sql" \
        --output schema.dbml
"""

from __future__ import annotations

import argparse
import pathlib
import re
from dataclasses import dataclass, field
from typing import Dict, List, Optional, Tuple


@dataclass
class ColumnDef:
    name: str
    data_type: str
    nullable: bool = True
    auto_increment: bool = False
    default: Optional[str] = None
    note: Optional[str] = None


@dataclass
class ForeignKey:
    columns: List[str]
    ref_table: str
    ref_columns: List[str]
    on_delete: Optional[str] = None
    on_update: Optional[str] = None


@dataclass
class TableDef:
    name: str
    columns: Dict[str, ColumnDef] = field(default_factory=dict)
    primary_keys: List[str] = field(default_factory=list)
    foreign_keys: List[ForeignKey] = field(default_factory=list)


def split_definitions(block: str) -> List[str]:
    items: List[str] = []
    current: List[str] = []
    depth = 0
    in_string = False
    prev_char = ""
    for ch in block:
        if ch == "'" and prev_char != "\\":
            in_string = not in_string
        elif not in_string:
            if ch == "(":
                depth += 1
            elif ch == ")" and depth > 0:
                depth -= 1

        if ch == "," and not in_string and depth == 0:
            token = "".join(current).strip()
            if token:
                items.append(token)
            current = []
        else:
            current.append(ch)
        prev_char = ch

    tail = "".join(current).strip()
    if tail:
        items.append(tail)
    return items


def clean_type(raw: str) -> Tuple[str, Optional[str]]:
    raw = raw.strip()
    # Remove COLLATE/CHARACTER SET noise
    raw = re.sub(r"COLLATE\s+\S+", "", raw, flags=re.I)
    raw = re.sub(r"CHARACTER SET\s+\S+", "", raw, flags=re.I)
    parts = raw.split()
    base = parts[0]
    note = None
    if base.lower().startswith("enum"):
        note = base
        base = "varchar"
    type_match = re.match(r"([a-zA-Z]+)(\([0-9,]+\))?", base)
    data_type = base
    if type_match:
        keyword = type_match.group(1)
        length = type_match.group(2) or ""
        keyword_lower = keyword.lower()
        if keyword_lower in {"int", "tinyint", "smallint", "mediumint", "bigint"}:
            data_type = keyword_lower
        else:
            data_type = keyword + length
    return data_type, note


def parse_column(token: str) -> ColumnDef:
    match = re.match(r"`([^`]+)`\s+(.*)", token, flags=re.S)
    if not match:
        raise ValueError(f"Cannot parse column definition: {token}")
    name = match.group(1)
    rest = match.group(2).strip()

    type_match = re.match(r"([^\s]+)(.*)", rest, flags=re.S)
    data_type = "text"
    modifiers = ""
    if type_match:
        data_type = type_match.group(1)
        modifiers = type_match.group(2)

    base_type, enum_note = clean_type(data_type)
    modifiers_upper = modifiers.upper()

    nullable = "NOT NULL" not in modifiers_upper
    auto_increment = "AUTO_INCREMENT" in modifiers_upper

    default_value = None
    default_match = re.search(
        r"DEFAULT\s+((?:'[^']*')|(?:\"[^\"]*\")|(?:[^\s,]+))", modifiers, flags=re.I
    )
    if default_match:
        default_value = default_match.group(1)

    note = None
    comment_match = re.search(r"COMMENT\s+'([^']*)'", modifiers, flags=re.I | re.S)
    if comment_match:
        note = comment_match.group(1).replace("\n", " ")

    if enum_note:
        note = f"{enum_note}" if not note else f"{enum_note}; {note}"

    if "UNSIGNED" in modifiers_upper:
        note = "unsigned" if not note else f"unsigned; {note}"

    if "ON UPDATE" in modifiers_upper:
        on_update = modifiers.split("ON UPDATE", 1)[1].strip()
        note = f"on update {on_update}" if not note else f"{note}; on update {on_update}"

    default_clean = default_value
    if default_clean:
        if default_clean.upper() == "NULL":
            default_clean = None
        elif "(" in default_clean:
            # Functions like current_timestamp() are not accepted by dbdiagram defaults.
            # Drop them to avoid validation errors.
            default_clean = None
        elif default_clean.strip().isdigit():
            pass
        else:
            # Ensure quoted strings stay quoted
            default_clean = default_clean

    return ColumnDef(
        name=name,
        data_type=base_type,
        nullable=nullable,
        auto_increment=auto_increment,
        default=default_clean,
        note=note,
    )


def parse_foreign_key(token: str) -> Optional[ForeignKey]:
    fk_match = re.search(
        r"FOREIGN KEY\s*\((?P<src>[^\)]+)\)\s*REFERENCES\s*`(?P<ref_table>[^`]+)`\s*\((?P<ref>[^\)]+)\)",
        token,
        flags=re.I | re.S,
    )
    if not fk_match:
        return None
    src_cols = [c.strip(" `") for c in fk_match.group("src").split(",")]
    ref_cols = [c.strip(" `") for c in fk_match.group("ref").split(",")]

    on_delete = None
    on_update = None
    delete_match = re.search(r"ON DELETE\s+(\w+)", token, flags=re.I)
    update_match = re.search(r"ON UPDATE\s+(\w+)", token, flags=re.I)
    allowed_actions = {
        "cascade": "cascade",
        "no action": "no action",
        "restrict": "restrict",
        "set null": "set null",
        "set default": "set default",
    }
    if delete_match:
        action = delete_match.group(1).lower()
        on_delete = allowed_actions.get(action)
    if update_match:
        action = update_match.group(1).lower()
        on_update = allowed_actions.get(action)

    return ForeignKey(
        columns=src_cols,
        ref_table=fk_match.group("ref_table"),
        ref_columns=ref_cols,
        on_delete=on_delete,
        on_update=on_update,
    )


def collect_tables(sql_text: str) -> Dict[str, TableDef]:
    table_pattern = re.compile(
        r"CREATE TABLE\s+`(?P<name>[^`]+)`\s*\((?P<body>.*?)\)\s*ENGINE",
        flags=re.I | re.S,
    )
    tables: Dict[str, TableDef] = {}
    for match in table_pattern.finditer(sql_text):
        name = match.group("name")
        body = match.group("body")
        table = TableDef(name=name)
        for token in split_definitions(body):
            token = token.strip()
            if not token:
                continue
            if token.startswith("`"):
                column = parse_column(token)
                table.columns[column.name] = column
            else:
                # Primary key or foreign key inline
                if token.upper().startswith("PRIMARY KEY"):
                    cols = re.findall(r"`([^`]+)`", token)
                    table.primary_keys = cols
                elif "FOREIGN KEY" in token.upper():
                    fk = parse_foreign_key(token)
                    if fk:
                        table.foreign_keys.append(fk)
        tables[name] = table
    return tables


def apply_alter_statements(sql_text: str, tables: Dict[str, TableDef]) -> None:
    alter_pattern = re.compile(
        r"ALTER TABLE\s+`(?P<name>[^`]+)`\s*(?P<body>.*?);",
        flags=re.I | re.S,
    )
    for match in alter_pattern.finditer(sql_text):
        name = match.group("name")
        if name not in tables:
            continue
        body = match.group("body")
        for token in split_definitions(body):
            up = token.upper()
            if "ADD PRIMARY KEY" in up:
                cols = re.findall(r"`([^`]+)`", token)
                tables[name].primary_keys = cols
            elif "ADD CONSTRAINT" in up and "FOREIGN KEY" in up:
                fk = parse_foreign_key(token)
                if fk:
                    tables[name].foreign_keys.append(fk)


def escape_note(text: str) -> str:
    return text.replace("\\", "\\\\").replace("'", "\\'")


def render_column(col: ColumnDef, is_pk: bool) -> str:
    options: List[str] = []
    if is_pk:
        options.append("pk")
    if not col.nullable:
        options.append("not null")
    if col.auto_increment:
        options.append("increment")
    if col.default is not None:
        options.append(f"default: {col.default}")
    if col.note:
        options.append(f"note: '{escape_note(col.note)}'")
    line = f"  {col.name} {col.data_type}"
    if options:
        line += " [" + ", ".join(options) + "]"
    return line


def render_dbml(tables: Dict[str, TableDef]) -> str:
    lines: List[str] = []
    for table_name in sorted(tables.keys()):
        table = tables[table_name]
        lines.append(f"Table {table.name} {{")
        for column_name, column in table.columns.items():
            is_pk = column_name in table.primary_keys
            lines.append(render_column(column, is_pk))
        lines.append("}\n")

    # Foreign keys
    for table in sorted(tables.values(), key=lambda t: t.name):
        for fk in table.foreign_keys:
            for src_col, ref_col in zip(fk.columns, fk.ref_columns):
                ref_line = f"Ref: {table.name}.{src_col} > {fk.ref_table}.{ref_col}"
                options = []
                if fk.on_delete:
                    options.append(f"delete: {fk.on_delete}")
                if fk.on_update:
                    options.append(f"update: {fk.on_update}")
                if options:
                    ref_line += " [" + ", ".join(options) + "]"
                lines.append(ref_line)
    return "\n".join(lines).strip() + "\n"


def main() -> None:
    parser = argparse.ArgumentParser(description="Convert MySQL schema to DBML.")
    parser.add_argument("--input", "-i", required=True, type=pathlib.Path)
    parser.add_argument("--output", "-o", required=True, type=pathlib.Path)
    args = parser.parse_args()

    sql_text = args.input.read_text(encoding="utf-8")
    tables = collect_tables(sql_text)
    apply_alter_statements(sql_text, tables)
    dbml_text = render_dbml(tables)
    args.output.write_text(dbml_text, encoding="utf-8")
    print(f"Wrote {len(tables)} tables to {args.output}")


if __name__ == "__main__":
    main()

