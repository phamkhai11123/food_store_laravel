<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>

    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


    @stack('styles')
</head>
<body class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-800 text-white h-screen sticky top-0 overflow-y-auto" x-data="{ open: true }">
        <div class="p-4 flex justify-between items-center">
            <a href="{{ route('admin.dashboard') }}" class="text-xl font-bold flex items-center">
                <i class="fas fa-utensils mr-2"></i> DASHBOARD
            </a>
            <button @click="open = !open" class="md:hidden text-gray-300 hover:text-white">
                <i class="fas fa-bars"></i>
            </button>
        </div>

        <nav class="mt-4" :class="{'hidden': !open, 'block': open}">
            <ul class="space-y-1">
                <li>
                    <a href="{{ route('admin.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.dashboard') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-tachometer-alt mr-2"></i> Thống kê dữ liệu
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.categories.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.categories.*') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-list mr-2"></i> Quản lý danh mục
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.products.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.products.*') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-utensils mr-2"></i> Quản lý sản phẩm
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.orders.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.orders.*') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-shopping-cart mr-2"></i> Quản lý đơn hàng
                    </a>
                </li>

                <li>
                    <a href="{{ route('admin.users.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-users mr-2"></i> Quản lý người dùng
                    </a>
                </li>
                 <li>
                    <a href="{{ route('admin.ingredients.index') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700 {{ request()->routeIs('admin.ingredients.*') ? 'bg-blue-600' : '' }}">
                        <i class="fas fa-boxes mr-2"></i> Quản lý nguyên liệu
                    </a>
                </li>

                <li class="border-t border-gray-700 my-2 pt-2">
                    <a href="{{ route('home') }}" class="block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                        <i class="fas fa-store mr-2"></i> Xem cửa hàng
                    </a>
                </li>

                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full text-left block py-2.5 px-4 rounded transition duration-200 hover:bg-gray-700">
                            <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
                        </button>
                    </form>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="flex-1 h-screen overflow-y-auto">
        <!-- Header -->
        <header class="bg-white shadow">
            <div class="p-4 flex justify-between items-center">
                <h1 class="text-xl font-semibold">Hệ thống nhà hàng Quán Quê</h1>

                <div class="flex items-center space-x-4">
                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open" class="flex items-center text-gray-700">
                            <span class="mr-2">{{ Auth::user()->name }}</span>
                            <i class="fas fa-user-circle text-xl"></i>
                        </button>

                        <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                            <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fas fa-user mr-2"></i> Thông tin cá nhân
                            </a>

                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <!-- Page Content -->
        <div class="p-6">
            {{ $slot }}
        </div>
    </main>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- SweetAlert2 Flash Messages -->
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Thành công!',
                text: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Lỗi!',
                text: "{{ session('error') }}",
                showConfirmButton: true
            });
        </script>
    @endif

    @if(session('info'))
        <script>
            Swal.fire({
                icon: 'info',
                title: 'Thông báo!',
                text: "{{ session('info') }}",
                showConfirmButton: true
            });
        </script>
    @endif

    @stack('scripts')
</body>
</html>
