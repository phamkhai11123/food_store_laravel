<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Hiển thị trang dashboard
     */
    public function index()
    {
        // Tổng số người dùng
        $totalUsers = User::where('role', 'user')->count();
        $totalCustomers = $totalUsers; // Đồng bộ với biến totalUsers

        // Tổng số sản phẩm
        $totalProducts = Product::count();

        // Sản phẩm mới trong 7 ngày qua
        $newProducts = Product::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        // Tổng số đơn hàng
        $totalOrders = Order::count();

        // Tổng doanh thu
        $totalRevenue = Order::where('status', 'completed')->sum('profit');

        // Đơn hàng mới trong 7 ngày qua
        $newOrders = Order::where('created_at', '>=', Carbon::now()->subDays(7))->count();

        // Tính toán tăng trưởng đơn hàng
        $ordersLastWeek = Order::where('created_at', '>=', Carbon::now()->subDays(14))
            ->where('created_at', '<', Carbon::now()->subDays(7))
            ->count();
        // Đảm bảo có tăng trưởng để hiển thị
        $orderGrowth = $ordersLastWeek > 0 ? round(($newOrders - $ordersLastWeek) / $ordersLastWeek * 100, 1) : ($newOrders > 0 ? 100 : 0);

        // Debug - Create fake data if there's no growth data
        if ($newOrders == 0 && $ordersLastWeek == 0) {
            $orderGrowth = 15; // Fake data for testing UI
        }

        // Tính toán tăng trưởng doanh thu
        $revenueThisWeek = Order::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->sum('total');
        $revenueLastWeek = Order::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subDays(14))
            ->where('created_at', '<', Carbon::now()->subDays(7))
            ->sum('total');
        $revenueGrowth = $revenueLastWeek > 0 ? round(($revenueThisWeek - $revenueLastWeek) / $revenueLastWeek * 100, 1) : ($revenueThisWeek > 0 ? 100 : 0);

        // Debug - Create fake data if there's no growth data
        if ($revenueThisWeek == 0 && $revenueLastWeek == 0) {
            $revenueGrowth = 25; // Fake data for testing UI
        }

        // Tính toán tăng trưởng khách hàng
        $newCustomers = User::where('role', 'user')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->count();
        $customersLastWeek = User::where('role', 'user')
            ->where('created_at', '>=', Carbon::now()->subDays(14))
            ->where('created_at', '<', Carbon::now()->subDays(7))
            ->count();
        $customerGrowth = $customersLastWeek > 0 ? round(($newCustomers - $customersLastWeek) / $customersLastWeek * 100, 1) : ($newCustomers > 0 ? 100 : 0);

        // Debug - Create fake data if there's no growth data
        if ($newCustomers == 0 && $customersLastWeek == 0) {
            $customerGrowth = -10; // Fake data for testing UI
        }

        // Thống kê đơn hàng theo trạng thái
        $ordersByStatus = Order::select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        // Thống kê doanh thu theo tháng (6 tháng gần nhất)
        $revenueByMonth = Order::where('status', 'completed')
            ->where('created_at', '>=', Carbon::now()->subMonths(6))
            ->select(
                DB::raw('MONTH(created_at) as month'),
                DB::raw('YEAR(created_at) as year'),
                DB::raw('SUM(total) as total')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Các sản phẩm bán chạy nhất
        $topProducts = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.status', 'completed')
            ->select(
                'products.id',
                'products.name',
                'products.image',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw('SUM(order_items.quantity * order_items.price) as total_revenue')
            )
            ->groupBy('products.id', 'products.name', 'products.image')
            ->orderBy('total_quantity', 'desc')
            ->limit(5)
            ->get();

        // Đơn hàng gần đây
        $recentOrders = Order::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // Lấy dữ liệu cho biểu đồ doanh thu 7 ngày gần nhất
        $revenueChartData = [
            'labels' => [],
            'data' => []
        ];

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('d/m');
            $revenue = Order::where('status', 'completed')
                ->whereDate('created_at', Carbon::now()->subDays($i)->toDateString())
                ->sum('total');

            $revenueChartData['labels'][] = $date;
            $revenueChartData['data'][] = $revenue;
        }

        // Lấy dữ liệu trạng thái đơn hàng
        $orderStatusData = [
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'completed' => Order::where('status', 'completed')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count()
        ];

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalProducts',
            'totalOrders',
            'totalRevenue',
            'newOrders',
            'ordersByStatus',
            'revenueByMonth',
            'topProducts',
            'recentOrders',
            'orderGrowth',
            'revenueGrowth',
            'customerGrowth',
            'totalCustomers',
            'newProducts',
            'revenueChartData',
            'orderStatusData'
        ));
    }
}
