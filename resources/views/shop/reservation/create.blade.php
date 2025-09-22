<x-layouts.app title="Đặt bàn">
    <div class="min-h-screen bg-gray-50 px-6 flex items-center justify-center">
        <div class="w-full max-w-xl bg-white p-8 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold mb-6 text-center text-gray-800">🪑 Đặt bàn trước</h1>

            @if(session('success'))
                <div class="bg-green-100 text-green-800 p-3 rounded mb-4 text-center">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 text-red-700 p-3 rounded mb-4 text-center">
                    {{ session('error') }}
                </div>
            @endif

            @php
                $seat = \App\Models\RestaurantSeat::first();
                $available = $seat->available_seats ?? '—';
            @endphp

            <form method="POST" action="{{ route('reservation.store') }}" class="space-y-6">
                @csrf

                {{-- Tên --}}
                <div>
                    <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-1">👤 Tên của bạn</label>
                    <input type="text" name="customer_name" id="customer_name" required value="{{ old('customer_name') }}"
                           class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
                </div>

                {{-- Số điện thoại --}}
                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">📞 Số điện thoại</label>
                    <input type="text" name="phone" id="phone" required value="{{ old('phone') }}"
                           class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
                </div>

                {{-- Ngày đến & Giờ ăn --}}
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="arrival_date" class="block text-sm font-medium text-gray-700 mb-1">📅 Ngày đến</label>
                        <input type="date" name="arrival_date" id="arrival_date" required value="{{ old('arrival_date') }}"
                               class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                    <div>
                        <label for="arrival_time" class="block text-sm font-medium text-gray-700 mb-1">⏰ Giờ ăn</label>
                        <input type="time" name="arrival_time" id="arrival_time" required value="{{ old('arrival_time') }}"
                               class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                </div>

                {{-- Số người & Ghế còn lại --}}
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label for="guest_count" class="block text-sm font-medium text-gray-700 mb-1">👥 Số người ăn</label>
                        <input type="number" name="guest_count" id="guest_count" required min="1" max="20" value="{{ old('guest_count') }}"
                               class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">🪑 Ghế còn lại</label>
                        <input type="text" readonly value="{{ $available }}"
                               class="w-full bg-gray-100 border px-3 py-2 rounded text-gray-700">
                    </div>
                </div>

                {{-- Ghi chú --}}
                <div>
                    <label for="note" class="block text-sm font-medium text-gray-700 mb-1">📝 Ghi chú</label>
                    <textarea name="note" id="note" rows="3"
                              class="w-full border px-3 py-2 rounded focus:outline-none focus:ring focus:border-blue-300"
                              value="{{ old('note') }}"
                              placeholder="Ví dụ: cần bàn gần cửa sổ, có trẻ em..."></textarea>
                </div>

                {{-- Submit --}}
                <div class="text-center pt-2">
                    <button type="submit"
                            class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                        ✅ Đặt bàn
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app>