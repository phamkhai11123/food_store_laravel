<x-layouts.admin title="Quản lý đặt bàn">
    <h1 class="text-2xl font-semibold mb-4">Quản lý đặt bàn</h1>

    {{-- Hiển thị số ghế còn lại --}}
    @php
        $seat = \App\Models\RestaurantSeat::first();
    @endphp
    <div class="mb-6 p-4 bg-blue-50 border border-blue-200 rounded text-blue-800 font-medium">
        🪑 Ghế còn lại trong kho: <span class="font-bold">{{ $seat->available_seats ?? '—' }}</span> / {{ $seat->total_seats ?? '—' }}
    </div>

    <table class="table-auto w-full border border-gray-300 rounded shadow-sm">
        <thead class="bg-gray-100 text-gray-700 text-sm">
            <tr>
                <th class="px-4 py-3 text-center border">Tên KH</th>
                <th class="px-4 py-3 text-center border">SDT</th>
                <th class="px-4 py-3 text-center border">Thời gian</th>
                <th class="px-4 py-3 text-center border">Số người</th>
                <th class="px-4 py-3 text-center border">Ghi chú</th>
                <th class="px-4 py-3 text-center border">Trạng thái</th>
                <th class="px-4 py-3 text-center border">Hành động</th>
            </tr>
        </thead>
        <tbody class="text-sm text-gray-800 divide-y divide-gray-200">
            @foreach($reservations as $r)
                <tr class="hover:bg-gray-50 transition">
                    <td class="px-4 py-3 text-center align-middle">{{ $r->customer_name }}</td>
                    <td class="px-4 py-3 text-center align-middle">{{ $r->phone }}</td>
                    <td class="px-4 py-3 text-center align-middle">{{ \Carbon\Carbon::parse($r->arrival_time)->format('d/m/Y H:i') }}</td>
                    <td class="px-4 py-3 text-center align-middle">{{ $r->guest_count }}</td>
                    <td class="px-4 py-3 text-center align-middle text-gray-600 italic">{{ $r->note ?? '—' }}</td>
                    <td class="px-4 py-3 text-center align-middle font-semibold">
                        @switch($r->status)
                            @case('approved') <span class="text-green-600">✔ Phê duyệt</span> @break
                            @case('cancelled') <span class="text-red-600">✖ Hủy</span> @break
                            @case('done') <span class="text-blue-600">✅ Hoàn tất</span> @break
                            @default <span class="text-gray-500">⏳ Chờ duyệt</span>
                        @endswitch
                    </td>
                    <td class="px-4 py-3 text-center align-middle">
                        <form method="POST" action="{{ route('admin.reservations.updateStatus', $r->id) }}" class="inline-flex items-center gap-2">
                            @csrf
                            <select name="status" class="border rounded px-2 py-1 text-sm">
                                <option value="approved">Phê duyệt</option>
                                <option value="cancelled">Hủy</option>
                                <option value="done">Hoàn tất</option>
                            </select>
                            <button type="submit" class="bg-green-600 text-white px-3 py-1 rounded hover:bg-green-700 text-sm">✔</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</x-layouts.admin>