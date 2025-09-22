<x-layouts.admin title="Tạo khuyến mãi mới">
    <div class="max-w-3xl mx-auto mt-10 bg-white shadow-md rounded-lg p-8">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">🎁 Tạo khuyến mãi mới</h2>

        <form action="{{ route('admin.promotions.store') }}" method="POST" class="space-y-6">
            @csrf
            <!-- Mã giảm giá -->
            <div>
                <label for="code" class="block text-sm font-medium text-gray-700 mb-1">Mã giảm giá (nếu có)</label>
                <input type="text" required name="code" id="code"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Ví dụ: SALE2025">
            </div>

            <!-- Tên chương trình -->
            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Tên chương trình</label>
                <input type="text"  name="name" id="name"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Ví dụ: Giảm giá mùa thu" required>
            </div>

            <!-- Loại khuyến mãi -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-1">Loại khuyến mãi</label>
                <select name="type" id="type"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="percentage">Giảm theo phần trăm (%)</option>
                    <option value="fixed">Giảm theo số tiền (VNĐ)</option>
                </select>
            </div>

            <!-- Giá trị giảm -->
            <div>
                <label for="value" class="block text-sm font-medium text-gray-700 mb-1">Giá trị giảm</label>
                <input type="number" name="value" id="value" step="0.01"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    placeholder="Ví dụ: 10 hoặc 50000" required>
            </div>

            <!-- Ngày bắt đầu -->
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700 mb-1">Ngày bắt đầu</label>
                <input type="datetime-local" name="start_date" id="start_date"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Ngày kết thúc -->
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700 mb-1">Ngày kết thúc</label>
                <input type="datetime-local" name="end_date" id="end_date"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <!-- Trạng thái -->
            <div>
                <label for="is_active" class="block text-sm font-medium text-gray-700 mb-1">Trạng thái</label>
                <select name="is_active" id="is_active"
                    class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                    required>
                    <option value="1" selected>Đang hoạt động</option>
                    <option value="0">Tạm ngưng</option>
                </select>
            </div>

            <!-- Nút submit -->
            <div>
                <button type="submit"
                    class="w-full py-2 px-4 bg-blue-600 text-white font-semibold rounded-md hover:bg-blue-700 transition duration-200">
                    ✅ Tạo khuyến mãi
                </button>
            </div>
        </form>
    </div>
</x-layouts.admin>