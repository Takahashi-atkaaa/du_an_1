<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xuất tài liệu - Booking #<?php echo isset($booking['booking_id']) ? $booking['booking_id'] : 'N/A'; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <script>
        // Define functions in HEAD to ensure they're available when onclick fires
        var BOOKING_ID = <?php echo isset($booking['booking_id']) ? (int)$booking['booking_id'] : 0; ?>;
        var BOOKING_EMAIL = <?php echo json_encode(isset($booking['email']) ? $booking['email'] : ''); ?>;
        
        function showDocument(type) {
            try {
                var noDoc = document.getElementById('no-document-selected');
                if (noDoc) noDoc.style.display = 'none';
                
                var allDocs = document.querySelectorAll('[id$="-content"]');
                for (var i = 0; i < allDocs.length; i++) {
                    allDocs[i].style.display = 'none';
                }
                
                var content = document.getElementById(type + '-content');
                if (content) {
                    content.style.display = 'block';
                    setTimeout(function() {
                        content.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 100);
                } else {
                    alert('Không tìm thấy tài liệu!');
                }
            } catch (e) {
                alert('Lỗi: ' + e.message);
            }
        }

        function sendEmail(type) {
            try {
                if (!BOOKING_EMAIL) {
                    alert('Không có địa chỉ email của khách hàng!');
                    return;
                }
                
                if (!BOOKING_ID) {
                    alert('Không có booking ID!');
                    return;
                }
                
                var docName = getDocumentName(type);
                if (confirm('Gửi ' + docName + ' đến ' + BOOKING_EMAIL + '?')) {
                    var btn = window.event ? window.event.target : null;
                    if (btn) {
                        btn = btn.closest ? btn.closest('button') : btn;
                        var originalHTML = btn.innerHTML;
                        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang gửi...';
                        btn.disabled = true;
                    }
                    
                    var url = 'index.php?act=booking/sendEmail&id=' + BOOKING_ID + '&type=' + type;
                    
                    fetch(url)
                        .then(function(response) { return response.text(); })
                        .then(function(text) {
                            try {
                                var data = JSON.parse(text);
                                if (btn) {
                                    btn.innerHTML = originalHTML;
                                    btn.disabled = false;
                                }
                                
                                if (data.success) {
                                    alert('✅ Đã gửi email thành công!');
                                } else {
                                    alert('❌ Lỗi: ' + (data.message || 'Không thể gửi email'));
                                }
                            } catch (e) {
                                if (btn) {
                                    btn.innerHTML = originalHTML;
                                    btn.disabled = false;
                                }
                                alert('❌ Lỗi server. Kiểm tra Console để xem chi tiết.');
                                console.error('Response:', text);
                            }
                        })
                        .catch(function(error) {
                            if (btn) {
                                btn.innerHTML = originalHTML;
                                btn.disabled = false;
                            }
                            alert('❌ Lỗi kết nối: ' + error.message);
                        });
                }
            } catch (e) {
                alert('Lỗi: ' + e.message);
            }
        }

        function getDocumentName(type) {
            var names = {
                'bao-gia': 'Báo giá',
                'hop-dong': 'Hợp đồng',
                'hoa-don': 'Hóa đơn'
            };
            return names[type] || type;
        }
        
        console.log('Functions loaded in HEAD');
    </script>
    
    <style>
        .page-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(102, 126, 234, 0.3);
        }
        .document-card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
            margin-bottom: 1.5rem;
            border-radius: 0.5rem;
            transition: all 0.3s;
        }
        .document-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.15);
        }
        .document-card .card-body {
            padding: 2rem;
        }
        .document-icon {
            width: 5rem;
            height: 5rem;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        @media print {
            body * {
                visibility: hidden;
            }
            #printContent, #printContent * {
                visibility: visible;
            }
            #printContent {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
            .no-print {
                display: none !important;
            }
        }
        .preview-section {
            background: white;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        }
        .company-header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }
        .document-title {
            text-align: center;
            font-size: 1.75rem;
            font-weight: bold;
            margin: 2rem 0;
            text-transform: uppercase;
        }
        .info-table {
            width: 100%;
            margin-bottom: 1.5rem;
        }
        .info-table td {
            padding: 0.5rem;
            border: 1px solid #dee2e6;
        }
        .info-table td:first-child {
            font-weight: 600;
            width: 30%;
            background: #f8f9fa;
        }
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1.5rem 0;
        }
        .detail-table th {
            background: #667eea;
            color: white;
            padding: 0.75rem;
            text-align: left;
            border: 1px solid #dee2e6;
        }
        .detail-table td {
            padding: 0.75rem;
            border: 1px solid #dee2e6;
        }
        .total-section {
            text-align: right;
            margin-top: 2rem;
            font-size: 1.1rem;
        }
        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 3rem;
        }
        .signature-box {
            text-align: center;
            width: 45%;
        }
    </style>
</head>
<body class="bg-light">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary no-print">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php?act=admin/dashboard">
                <i class="bi bi-speedometer2"></i> Quản trị
            </a>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?act=admin/quanLyBooking">
                            <i class="bi bi-calendar-check"></i> Booking
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="#">
                            <i class="bi bi-file-earmark-text"></i> Xuất tài liệu
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid px-4 py-4">
        <!-- Page Header -->
        <div class="page-header no-print">
            <div class="container-fluid">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h1 class="display-6 fw-bold mb-2">
                            <i class="bi bi-file-earmark-pdf-fill"></i> Xuất Tài Liệu
                        </h1>
                        <p class="mb-0 opacity-75">Booking #<?php echo $booking['booking_id']; ?> - <?php echo htmlspecialchars($booking['ho_ten']); ?></p>
                    </div>
                    <div>
                        <a href="index.php?act=booking/chiTiet&id=<?php echo $booking['booking_id']; ?>" class="btn btn-light btn-lg">
                            <i class="bi bi-arrow-left"></i> Quay lại
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Type Selection -->
        <div class="row g-4 mb-4 no-print">
            <div class="col-md-4">
                <div class="document-card card h-100">
                    <div class="card-body text-center">
                        <div class="document-icon bg-primary bg-opacity-10 text-primary mx-auto">
                            <i class="bi bi-file-earmark-text"></i>
                        </div>
                        <h4 class="mb-3">Báo Giá</h4>
                        <p class="text-muted mb-4">Tài liệu báo giá chi tiết gửi cho khách hàng</p>
                        <div class="action-buttons justify-content-center">
                            <button onclick="showDocument('bao-gia')" class="btn btn-primary">
                                <i class="bi bi-eye"></i> Xem trước
                            </button>
                            <a href="index.php?act=booking/exportPDF&id=<?php echo $booking['booking_id']; ?>&type=bao-gia" class="btn btn-success">
                                <i class="bi bi-download"></i> Tải PDF
                            </a>
                            <button onclick="sendEmail('bao-gia')" class="btn btn-info">
                                <i class="bi bi-envelope"></i> Gửi email
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="document-card card h-100">
                    <div class="card-body text-center">
                        <div class="document-icon bg-success bg-opacity-10 text-success mx-auto">
                            <i class="bi bi-file-earmark-check"></i>
                        </div>
                        <h4 class="mb-3">Hợp Đồng</h4>
                        <p class="text-muted mb-4">Hợp đồng dịch vụ du lịch giữa hai bên</p>
                        <div class="action-buttons justify-content-center">
                            <button onclick="showDocument('hop-dong')" class="btn btn-primary">
                                <i class="bi bi-eye"></i> Xem trước
                            </button>
                            <a href="index.php?act=booking/exportPDF&id=<?php echo $booking['booking_id']; ?>&type=hop-dong" class="btn btn-success">
                                <i class="bi bi-download"></i> Tải PDF
                            </a>
                            <button onclick="sendEmail('hop-dong')" class="btn btn-info">
                                <i class="bi bi-envelope"></i> Gửi email
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="document-card card h-100">
                    <div class="card-body text-center">
                        <div class="document-icon bg-warning bg-opacity-10 text-warning mx-auto">
                            <i class="bi bi-receipt"></i>
                        </div>
                        <h4 class="mb-3">Hóa Đơn</h4>
                        <p class="text-muted mb-4">Hóa đơn VAT thanh toán dịch vụ</p>
                        <div class="action-buttons justify-content-center">
                            <button onclick="showDocument('hoa-don')" class="btn btn-primary">
                                <i class="bi bi-eye"></i> Xem trước
                            </button>
                            <a href="index.php?act=booking/exportPDF&id=<?php echo $booking['booking_id']; ?>&type=hoa-don" class="btn btn-success">
                                <i class="bi bi-download"></i> Tải PDF
                            </a>
                            <button onclick="sendEmail('hoa-don')" class="btn btn-info">
                                <i class="bi bi-envelope"></i> Gửi email
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Document Preview -->
        <div class="preview-section" id="printContent">
            <div class="text-center text-muted py-5" id="no-document-selected">
                <i class="bi bi-file-earmark-text" style="font-size: 4rem;"></i>
                <h4 class="mt-3">Chọn loại tài liệu để xem trước</h4>
                <p>Click vào nút "Xem trước" ở trên để hiển thị tài liệu</p>
            </div>

            <div id="bao-gia-content" style="display: none;">
                <?php 
                try {
                    include 'templates/bao_gia_template.php'; 
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">Lỗi: ' . htmlspecialchars($e->getMessage()) . '</div>';
                }
                ?>
            </div>

            <div id="hop-dong-content" style="display: none;">
                <?php 
                try {
                    include 'templates/hop_dong_template.php'; 
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">Lỗi: ' . htmlspecialchars($e->getMessage()) . '</div>';
                }
                ?>
            </div>

            <div id="hoa-don-content" style="display: none;">
                <?php 
                try {
                    include 'templates/hoa_don_template.php'; 
                } catch (Exception $e) {
                    echo '<div class="alert alert-danger">Lỗi: ' . htmlspecialchars($e->getMessage()) . '</div>';
                }
                ?>
            </div>
        </div>

        <!-- Print Button (Fixed) -->
        <div class="no-print" style="position: fixed; bottom: 2rem; right: 2rem;">
            <button onclick="window.print()" class="btn btn-lg btn-primary shadow-lg">
                <i class="bi bi-printer"></i> In tài liệu
            </button>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Check on page load
        window.addEventListener('DOMContentLoaded', function() {
            console.log('=== PAGE READY ===');
            console.log('Booking ID:', BOOKING_ID, 'Email:', BOOKING_EMAIL);
            console.log('Functions:', typeof showDocument, typeof sendEmail, typeof getDocumentName);
            
            var docs = ['bao-gia-content', 'hop-dong-content', 'hoa-don-content'];
            for (var i = 0; i < docs.length; i++) {
                var el = document.getElementById(docs[i]);
                console.log(docs[i] + ':', el ? 'EXISTS' : 'NOT FOUND');
            }
        });
    </script>
</body>
</html>