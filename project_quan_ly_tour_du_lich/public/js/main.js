// Main JavaScript file

document.addEventListener('DOMContentLoaded', function() {
    // Xử lý form đặt tour
    const bookingForm = document.querySelector('form[action*="booking/create"]');
    if (bookingForm) {
        const soLuongInput = bookingForm.querySelector('input[name="so_luong_nguoi"]');
        const tongTienInput = bookingForm.querySelector('input[name="tong_tien"]');
        
        if (soLuongInput && tongTienInput) {
            soLuongInput.addEventListener('input', function() {
                // Tính tổng tiền dựa trên số lượng và giá tour
                // Logic này cần được điều chỉnh dựa trên cấu trúc dữ liệu
            });
        }
    }
    
    // Xử lý các thông báo
    const errorMessages = document.querySelectorAll('.error');
    errorMessages.forEach(error => {
        setTimeout(() => {
            error.style.opacity = '0';
            setTimeout(() => error.remove(), 300);
        }, 5000);
    });
});


