import './bootstrap';

// Import Sweetalert2
import Swal from 'sweetalert2';
window.Swal = Swal;

// Thông báo thành công
window.showSuccessAlert = (message) => {
    Swal.fire({
        icon: 'success',
        title: 'Thành công!',
        text: message,
        showConfirmButton: false,
        timer: 2000
    });
};

// Thông báo lỗi
window.showErrorAlert = (message) => {
    Swal.fire({
        icon: 'error',
        title: 'Lỗi!',
        text: message,
    });
};

// Xác nhận xóa
window.showDeleteConfirm = (callback) => {
    Swal.fire({
        title: 'Bạn chắc chắn muốn xóa?',
        text: "Dữ liệu sẽ không thể khôi phục!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#6b7280',
        confirmButtonText: 'Xác nhận xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed && typeof callback === 'function') {
            callback();
        }
    });
};
