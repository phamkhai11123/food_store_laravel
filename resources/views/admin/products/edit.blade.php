@extends('admin.products.form')

@push('styles')
    <!-- Thêm các style riêng cho trang chỉnh sửa nếu cần -->
    <style>
        /* Thêm các style tùy chỉnh tại đây */
        .image-preview {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            display: none;
        }
        .current-image {
            max-width: 200px;
            max-height: 200px;
            margin-top: 10px;
            border: 1px solid #e2e8f0;
            border-radius: 0.375rem;
        }
    </style>
@endpush

@push('scripts')
    <script>
        // Xử lý hiển thị ảnh xem trước
        const imageInput = document.getElementById('image');
        const imagePreview = document.getElementById('imagePreview');
        const currentImage = document.getElementById('currentImage');
        const removeImageBtn = document.getElementById('removeImage');
        const hiddenRemoveImage = document.getElementById('remove_image');

        if (imageInput) {
            imageInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        if (imagePreview) {
                            imagePreview.src = e.target.result;
                            imagePreview.style.display = 'block';
                        }
                        if (currentImage) {
                            currentImage.style.display = 'none';
                        }
                    }
                    reader.readAsDataURL(file);
                }
            });
        }

        // Xử lý nút xóa ảnh
        if (removeImageBtn) {
            removeImageBtn.addEventListener('click', function() {
                if (confirm('Bạn có chắc chắn muốn xóa ảnh này không?')) {
                    if (currentImage) {
                        currentImage.style.display = 'none';
                    }
                    if (imagePreview) {
                        imagePreview.style.display = 'none';
                    }
                    if (imageInput) {
                        imageInput.value = '';
                    }
                    if (hiddenRemoveImage) {
                        hiddenRemoveImage.value = '1';
                    }
                }
            });
        }

        // Tự động tạo slug từ tên sản phẩm
        const nameInput = document.getElementById('name');
        const slugInput = document.getElementById('slug');
        const generateSlugBtn = document.getElementById('generateSlug');

        function generateSlug(text) {
            return text.normalize('NFD')
                .toLowerCase()
                .replace(/[\u0300-\u036f]/g, '')
                .replace(/[^\w\s-]/g, '')
                .replace(/\s+/g, '-')
                .replace(/--+/g, '-');
        }

        if (nameInput && slugInput) {
            // Tự động tạo slug khi nhập tên sản phẩm
            nameInput.addEventListener('input', function() {
                if (!slugInput.dataset.manual) {
                    slugInput.value = generateSlug(this.value);
                }
            });

            // Nút tạo lại slug
            if (generateSlugBtn) {
                generateSlugBtn.addEventListener('click', function() {
                    slugInput.value = generateSlug(nameInput.value);
                    slugInput.dataset.manual = false;
                });
            }

            // Đánh dấu slug đã được chỉnh sửa thủ công
            slugInput.addEventListener('input', function() {
                this.dataset.manual = true;
            });
        }
    </script>
@endpush
