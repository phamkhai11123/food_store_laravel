<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đơn hàng #{{ $order->order_number }} - FoodStore</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                font-size: 12pt;
                color: #000;
                background-color: #fff;
            }
            .page-break {
                page-break-after: always;
            }
        }
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 14px;
            line-height: 24px;
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="p-6">
        <div class="invoice-box bg-white mb-6">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">FoodStore</h1>
                    <p class="text-gray-600">Cửa hàng đồ ăn trực tuyến</p>
                </div>

                <div class="text-right">
                    <h2 class="text-xl font-bold">HÓA ĐƠN</h2>
                    <p class="text-gray-600">#{{ $order->order_number }}</p>
                    <p class="text-gray-600">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-8">
                <div>
                    <h3 class="font-bold text-gray-800 mb-2">Thông tin khách hàng:</h3>
                    <p class="mb-1"><strong>Họ tên:</strong> {{ $order->name }}</p>
                    <p class="mb-1"><strong>Email:</strong> {{ $order->email }}</p>
                    <p class="mb-1"><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
                </div>

                <div>
                    <h3 class="font-bold text-gray-800 mb-2">Địa chỉ giao hàng:</h3>
                    <p class="mb-1">{{ $order->name }}</p>
                    <p class="mb-1">{{ $order->address }}</p>
                    <p class="mb-1">{{ $order->city }}</p>
                    <p class="mb-1">{{ $order->phone }}</p>
                </div>
            </div>

            <div class="mb-8">
                <h3 class="font-bold text-gray-800 mb-4">Thông tin đơn hàng:</h3>
                <table class="min-w-full bg-white">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="py-2 px-4 border-b text-left">#</th>
                            <th class="py-2 px-4 border-b text-left">Sản phẩm</th>
                            <th class="py-2 px-4 border-b text-right">Đơn giá</th>
                            <th class="py-2 px-4 border-b text-right">Số lượng</th>
                            <th class="py-2 px-4 border-b text-right">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $index => $item)
                            <tr>
                                <td class="py-2 px-4 border-b">{{ $index + 1 }}</td>
                                <td class="py-2 px-4 border-b">{{ $item->product->name }}</td>
                                <td class="py-2 px-4 border-b text-right">{{ number_format($item->price) }}đ</td>
                                <td class="py-2 px-4 border-b text-right">{{ $item->quantity }}</td>
                                <td class="py-2 px-4 border-b text-right">{{ number_format($item->price * $item->quantity) }}đ</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="4" class="py-2 px-4 border-b text-right"><strong>Tạm tính:</strong></td>
                            <td class="py-2 px-4 border-b text-right">{{ number_format($order->subtotal) }}đ</td>
                        </tr>
                        <tr>
                            <td colspan="4" class="py-2 px-4 border-b text-right"><strong>Phí vận chuyển:</strong></td>
                            <td class="py-2 px-4 border-b text-right">{{ number_format($order->shipping_fee) }}đ</td>
                        </tr>
                        <tr class="bg-gray-50">
                            <td colspan="4" class="py-2 px-4 border-b text-right"><strong>Tổng cộng:</strong></td>
                            <td class="py-2 px-4 border-b text-right font-bold">{{ number_format($order->total) }}đ</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="mb-8">
                <h3 class="font-bold text-gray-800 mb-2">Phương thức thanh toán:</h3>
                <p>
                    @if($order->payment_method == 'cod')
                        <span class="flex items-center">
                            <i class="fas fa-money-bill-wave text-green-600 mr-1"></i> Thanh toán khi nhận hàng
                        </span>
                    @elseif($order->payment_method == 'bank')
                        <span class="flex items-center">
                            <i class="fas fa-university text-blue-600 mr-1"></i> Chuyển khoản ngân hàng
                        </span>
                    @elseif($order->payment_method == 'momo')
                        <span class="flex items-center">
                            <i class="fas fa-wallet text-pink-600 mr-1"></i> Ví MoMo
                        </span>
                    @endif
                </p>
                <p class="mt-2">
                    <strong>Trạng thái thanh toán:</strong>
                    @if($order->payment_status)
                        <span class="text-green-600">Đã thanh toán</span>
                    @else
                        <span class="text-yellow-600">Chờ xác nhận</span>
                    @endif
                </p>
            </div>

            @if($order->note)
                <div class="mb-8 border-t pt-4">
                    <h3 class="font-bold text-gray-800 mb-2">Ghi chú:</h3>
                    <p>{{ $order->note }}</p>
                </div>
            @endif

            <div class="border-t pt-6">
                <div class="flex justify-between">
                    <div>
                        <h4 class="font-bold mb-2">Thông tin liên hệ:</h4>
                        <p class="text-sm">Email: info@foodstore.com</p>
                        <p class="text-sm">Điện thoại: (+84) 1234 5678</p>
                        <p class="text-sm">Địa chỉ: 123 Đường ABC, Quận XYZ, Hà Nội</p>
                    </div>

                    <div class="text-right">
                        <p class="text-sm">Cảm ơn quý khách đã đặt hàng!</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="text-center mb-6 no-print">
            <button onclick="window.print()" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                <i class="fas fa-print mr-2"></i> In hóa đơn
            </button>
            <a href="{{ route('admin.orders.show', $order) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded inline-flex items-center ml-2">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại
            </a>
        </div>
    </div>

    <script>
        // Auto print when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Slight delay to ensure page is fully loaded
            setTimeout(function() {
                if (window.location.search.includes('autoprint=true')) {
                    window.print();
                }
            }, 1000);
        });
    </script>
</body>
</html>
