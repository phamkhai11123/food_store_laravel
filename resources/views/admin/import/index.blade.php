<x-layouts.admin title="Lịch sử nhập hàng">
    <div class="max-w-7xl mx-auto mt-0">
        <h1 class="text-2xl font-bold mb-4">Lịch sử nhập hàng</h1>

        <div class="mb-4">
            <a href="{{ route('admin.import.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Nhập hàng mới</a>
        </div>

        <form method="GET" class="mb-6 bg-gray-50 p-4 rounded shadow flex flex-wrap items-end gap-4">
            <div>
                <label for="code" class="block text-sm text-gray-600 mb-1">Mã đơn</label>
                <input type="text" name="code" id="code" value="{{ request('code') }}"
                    class="border rounded px-3 py-2 w-48" placeholder="IMP20250912-001">
            </div>

            <div>
                <label for="supplier" class="block text-sm text-gray-600 mb-1">Nhà cung cấp</label>
                <input type="text" name="supplier" id="supplier" value="{{ request('supplier') }}"
                    class="border rounded px-3 py-2 w-48" placeholder="Công ty ABC">
            </div>

            <div>
                <label for="from" class="block text-sm text-gray-600 mb-1">Từ ngày</label>
                <input type="date" name="from" id="from" value="{{ request('from') }}"
                    class="border rounded px-3 py-2 w-44">
            </div>

            <div>
                <label for="to" class="block text-sm text-gray-600 mb-1">Đến ngày</label>
                <input type="date" name="to" id="to" value="{{ request('to') }}"
                    class="border rounded px-3 py-2 w-44">
            </div>

            <div>
                <label for="sort" class="block text-sm text-gray-600 mb-1">Sắp xếp theo giá</label>
                <select name="sort" id="sort" class="border rounded px-3 py-2 w-44">
                    <option value="">— Mặc định —</option>
                    <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
                    <option value="desc" {{ request('sort') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
                </select>
            </div>

            <div>
                <button type="submit"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 mt-6">
                🔍 <span>Lọc</span>
                </button>
                <a href="{{ route('admin.import.index') }}"
                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">
                        🧹 <span>Xóa</span>
                    </a>

            </div>
        </form>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200 rounded shadow">
                <thead class="bg-gray-100">
                    <tr class="text-left text-sm font-semibold text-gray-700">
                        <th class="px-4 py-3 border">Mã đơn</th>
                        <th class="px-4 py-3 border">Ngày nhập</th>
                        <th class="px-4 py-3 border">Nhà cung cấp</th>
                        <th class="px-4 py-3 border">Số loại nguyên liệu</th>
                        <th class="px-4 py-3 border">Tổng tiền</th>
                        <th class="px-4 py-3 border">Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($imports as $import)
                        <tr class="text-sm text-gray-800">
                            <td class="px-4 py-2 border">{{ $import->code }}</td>
                            <td class="px-4 py-2 border">{{ \Carbon\Carbon::parse($import->import_date)->format('d/m/Y') }}</td>
                            <td class="px-4 py-2 border">{{ $import->supplier }}</td>
                            <td class="px-4 py-2 border">{{ $import->details->count() }} loại</td>
                            <td class="px-4 py-2 border">{{ number_format($import->adjusted_total, 0, ',', '.') }} đ</td>
                            <td class="px-4 py-2 border space-x-2">
                                <a href="{{ route('admin.import.show', $import->id) }}" class="text-blue-600 hover:underline">Chi tiết</a>
                                <form action="{{ route('admin.import.destroy', $import->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline" onclick="return confirm('Xóa đơn nhập này?')">Xóa</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach

                    @if($imports->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center py-4 text-gray-500">Chưa có đơn nhập nào</td>
                        </tr>
                    @endif
                </tbody>
            </table>   
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Revenue Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="font-bold text-lg mb-4">Tiền nhập hàng 7 ngày qua</h2>
                <div class="h-64">
                    <canvas id="dailyImportChart"></canvas>
                </div>
            </div>

            <!-- Order Status Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="font-bold text-lg mb-4">Tiền nhập hàng theo tháng</h2>
                <div class="h-64">
                    <canvas id="monthlyImportChart"></canvas>
                </div>
            </div>

            <!-- Monthly Revenue Chart -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="font-bold text-lg mb-4">Tiền nhập hàng theo năm</h2>
                <div class="h-64">
                    <canvas id="yearlyImportChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    @push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    function renderChart(id, labels, datasets) {
        const canvas = document.getElementById(id);
        if (!canvas) {
            console.warn(`Không tìm thấy canvas với id: ${id}`);
            return;
        }

        const ctx = canvas.getContext('2d');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: datasets
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                const value = context.raw;
                                return context.dataset.label + ': ' +
                                    new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                            }
                        }
                    },
                    legend: {
                        display: true,
                        labels: {
                            boxWidth: 12,
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
    }

    // 📅 Theo ngày
    renderChart('dailyImportChart',
        {!! json_encode($dailyLabels) !!},
        {!! json_encode($dailyDatasets) !!}
    );

    // 📆 Theo tháng
    renderChart('monthlyImportChart',
        {!! json_encode($monthLabels) !!},
        {!! json_encode($monthlyDatasets) !!}
    );

    // 📈 Theo năm
    renderChart('yearlyImportChart',
        {!! json_encode($yearLabels) !!},
        {!! json_encode($yearlyDatasets) !!}
    );
});
</script>
@endpush

</x-layouts.admin>