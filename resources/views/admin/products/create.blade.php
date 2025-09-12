@extends('admin.products.form')

@push('styles')
    <!-- Thêm các style riêng cho trang tạo mới nếu cần -->
    <style>
        /* Thêm các style tùy chỉnh tại đây */
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            display: none;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Xử lý hiển thị ảnh xem trước
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');

        if (imageInput && imagePreview) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagePreview.src = e.target.result;
                        imagePreview.style.display = 'block';
                    }
                    reader.readAsDataURL(file);
                }
            });
        }
    </script>
@endpush
