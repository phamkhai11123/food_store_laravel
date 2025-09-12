<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Hiển thị danh sách người dùng
     */
    public function index(Request $request)
    {
        $query = User::query();

        // Tìm kiếm theo từ khóa
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Lọc theo vai trò
        if ($request->filled('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }

        // Sắp xếp dữ liệu
        if ($request->filled('sort')) {
            $sortParams = explode('-', $request->sort);
            if (count($sortParams) === 2) {
                $sortField = $sortParams[0];
                $sortDirection = $sortParams[1];
                $query->orderBy($sortField, $sortDirection);
            } else {
                $query->latest();
            }
        } else {
            $query->latest();
        }

        // Đếm số đơn hàng
        $query->withCount('orders');

        $users = $query->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    /**
     * Hiển thị form tạo người dùng
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Lưu người dùng mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|size:10|regex:/^0[0-9]{9}$/',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:user,admin',
            'address' => 'nullable|string|max:255',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Thêm người dùng thành công!');
    }

    /**
     * Hiển thị chi tiết người dùng
     */
    public function show(User $user)
    {
        // Get order statistics
        $orderStats = [
            'total' => $user->orders()->count(),
            'completed' => $user->orders()->where('status', 'completed')->count(),
            'pending' => $user->orders()->whereIn('status', ['pending', 'processing'])->count(),
            'cancelled' => $user->orders()->where('status', 'cancelled')->count(),
            'total_spent' => $user->orders()->where('status', 'completed')->sum('total'),
        ];

        // Get recent orders
        $recentOrders = $user->orders()->orderBy('created_at', 'desc')->take(5)->get();

        // Get all orders with pagination
        $orders = $user->orders()->orderBy('created_at', 'desc')->paginate(10);

        // Get recent reviews
        $reviews = $user->reviews()->with('product')->orderBy('created_at', 'desc')->take(5)->get();

        return view('admin.users.show', [
            'user' => $user,
            'orderStats' => $orderStats,
            'recentOrders' => $recentOrders,
            'orders' => $orders,
            'reviews' => $reviews,
        ]);
    }

    /**
     * Hiển thị form chỉnh sửa người dùng
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Cập nhật người dùng
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($user->id),
            ],
            'phone' => 'required|string|size:10|regex:/^0[0-9]{9}$/',
            'role' => 'required|in:user,admin',
            'address' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'role' => $request->role,
            'address' => $request->address,
        ]);

        // Cập nhật mật khẩu nếu có
        if ($request->filled('password')) {
            $request->validate([
                'password' => 'string|min:6|confirmed',
            ]);

            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        return redirect()->route('admin.users.index')->with('success', 'Cập nhật người dùng thành công!');
    }

    /**
     * Xóa người dùng
     */
    public function destroy(User $user)
    {
        // Không cho phép xóa chính mình
        if (Auth::check() && $user->id === Auth::id()) {
            return back()->with('error', 'Bạn không thể xóa tài khoản của chính mình.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'Xóa người dùng thành công!');
    }
}
