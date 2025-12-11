# Pattern chuyển đổi View sang Layout Aventura

## Cấu trúc cơ bản

```php
<?php
$pageTitle = 'Tên trang';
$currentPage = 'menu-item';
ob_start();
?>

<style>
    /* Dark theme styles */
    .page-header-section {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 40px;
        margin-bottom: 40px;
        backdrop-filter: blur(10px);
    }

    .info-card {
        background: rgba(45, 45, 45, 0.5);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 2px;
        padding: 25px;
        margin-bottom: 30px;
        backdrop-filter: blur(10px);
    }

    /* Thêm các styles khác theo nhu cầu */
</style>

<!-- Nội dung view -->

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/aventura.php';
?>
```

## Thay thế Bootstrap classes

- `card` → `info-card` với dark theme styles
- `btn btn-primary` → `btn btn-primary` (giữ nguyên class, style trong aventura.css)
- `form-control` → `input` hoặc `select` với dark theme styles
- `table` → giữ nguyên nhưng thêm dark theme styles
- `alert alert-success` → `alert alert-success` với dark theme styles
- `badge` → `badge` với dark theme styles

## Màu sắc

- Background: `rgba(45, 45, 45, 0.5)`
- Border: `rgba(255, 255, 255, 0.1)`
- Text light: `var(--text-light)`
- Text muted: `var(--text-muted)`
- Accent: `var(--accent-gold)`

## Lưu ý

- KHÔNG thay đổi đường dẫn (href, action)
- KHÔNG thay đổi logic PHP
- CHỈ thay đổi HTML structure và CSS
- Giữ nguyên tất cả form actions, routes, IDs




