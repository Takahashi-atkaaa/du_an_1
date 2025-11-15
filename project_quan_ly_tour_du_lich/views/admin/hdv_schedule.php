<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Lịch HDV</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.css' rel='stylesheet' />
  <style>.fc { background: #fff; }</style>
</head>
<body class="bg-light">
<div class="container py-4">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Lịch phân công HDV</h3>
    <div>
      <a href="index.php?act=admin/quanLyHDV" class="btn btn-outline-secondary btn-sm">Quản lý HDV</a>
    </div>
  </div>

  <div class="mb-3">
    <label>Chọn HDV:</label>
    <select id="selectHdv" class="form-select form-select-sm" style="width:300px">
      <option value="">-- Chọn HDV --</option>
      <?php foreach($hdv_list as $h): ?>
        <option value="<?php echo $h['nhan_su_id'] ?>"><?php echo htmlspecialchars($h['ho_ten']) ?></option>
      <?php endforeach; ?>
    </select>
  </div>

  <div id='calendar'></div>

  <!-- Modal phân công -->
  <div class="modal fade" id="assignModal" tabindex="-1">
    <div class="modal-dialog">
      <div class="modal-content">
        <form id="assignForm">
        <div class="modal-header"><h5 class="modal-title">Phân công HDV</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="hdv_id" id="form_hdv_id">
          <div class="mb-2"><label>Tour ID (nếu có)</label><input name="tour_id" class="form-control form-control-sm"></div>
          <div class="mb-2"><label>Bắt đầu</label><input name="start" id="form_start" class="form-control form-control-sm" readonly></div>
          <div class="mb-2"><label>Kết thúc</label><input name="end" id="form_end" class="form-control form-control-sm" readonly></div>
          <div class="mb-2"><label>Ghi chú</label><input name="note" class="form-control form-control-sm"></div>
          <div class="mb-2">
            <button type="button" id="btnSuggest" class="btn btn-sm btn-outline-primary">Đề xuất HDV rảnh</button>
            <select id="suggestList" class="form-select form-select-sm mt-2"></select>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-primary">Phân công</button>
        </div>
        </form>
      </div>
    </div>
  </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/main.min.js'></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: 'timeGridWeek',
    height: 650,
    events: function(info, successCallback, failureCallback) {
      var hdvId = document.getElementById('selectHdv').value;
      if (!hdvId) { successCallback([]); return; }
      fetch('index.php?act=admin/hdv_api_get_schedule&hdv_id='+hdvId+'&from='+info.startStr+'&to='+info.endStr)
        .then(r=>r.json()).then(data=>successCallback(data)).catch(e=>failureCallback(e));
    },
    selectMirror: true,
    selectable: true,
    select: function(info) {
      var hdvId = document.getElementById('selectHdv').value;
      if (!hdvId) { alert('Chọn HDV trước'); return; }
      document.getElementById('form_hdv_id').value = hdvId;
      document.getElementById('form_start').value = info.startStr;
      document.getElementById('form_end').value = info.endStr;
      var modal = new bootstrap.Modal(document.getElementById('assignModal'));
      modal.show();
    }
  });
  calendar.render();

  document.getElementById('selectHdv').addEventListener('change', function(){ calendar.refetchEvents(); });

  // Suggest button
  document.getElementById('btnSuggest').addEventListener('click', function(){
    var start = document.getElementById('form_start').value;
    var end = document.getElementById('form_end').value;
    var hdvId = document.getElementById('selectHdv').value;
    fetch('index.php?act=admin/hdv_api_suggest&start='+encodeURIComponent(start)+'&end='+encodeURIComponent(end))
      .then(r=>r.json()).then(data=>{
        var sel = document.getElementById('suggestList'); sel.innerHTML='';
        data.available.forEach(function(a){
          var opt = document.createElement('option'); opt.value = a.id; opt.text = a.ho_ten; sel.appendChild(opt);
        });
      });
  });

  // Assign form submit via AJAX
  document.getElementById('assignForm').addEventListener('submit', function(ev){
    ev.preventDefault();
    var form = ev.target;
    var formData = new FormData(form);
    // if suggestion selected, use that hdv
    var suggested = document.getElementById('suggestList').value;
    if (suggested) formData.set('hdv_id', suggested);
    fetch('index.php?act=admin/hdv_api_assign', { method:'POST', body: formData })
      .then(r=>r.json()).then(data=>{
        if (data.ok) {
          bootstrap.Modal.getInstance(document.getElementById('assignModal')).hide();
          calendar.refetchEvents();
          alert('Phân công thành công');
        } else {
          alert('Lỗi: '+(data.msg||'Thất bại'));
        }
      });
  });
});
</script>
</body>
</html>
