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

    <!-- AOS Animation -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js"></script>

    <!-- Sweetalert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')
</head>
<body class="min-h-screen bg-gray-100 flex flex-col">
    <!-- Header -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-3">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="text-2xl font-bold text-blue-600">
                    {{-- <i class="fas fa-utensils mr-2"></i> Quán Quê --}}
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Quán Quê" style="width: 80px; height: 60px;"> 
                </a>

                <!-- Search Bar -->
                <div class="hidden md:block flex-1 max-w-md mx-4">
                    <form action="{{ route('products.index') }}" method="GET">
                        <div class="relative">
                            <input type="text" name="search" placeholder="Tìm kiếm món ăn..." class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <button type="submit" class="absolute right-0 top-0 mt-2 mr-3 text-gray-500">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Navigation -->
                <nav class="flex items-center space-x-4">
                    <a href="{{ route('reservation.create') }}" class="text-gray-700 hover:text-blue-600 transition">
                        <i class="fas fa-table mr-1"></i>
                        <span class="hidden md:inline">Đặt bàn</span>
                    </a>
                    <a href="{{ route('products.index') }}" class="text-gray-700 hover:text-blue-600 transition">
                        <i class="fas fa-store mr-1"></i>
                        <span class="hidden md:inline">Món ăn</span>
                    </a>

                    @auth
                        <a href="{{ route('cart.index') }}" class="text-gray-700 hover:text-blue-600 transition relative">
                            <i class="fas fa-shopping-cart mr-1"></i>
                            <span class="hidden md:inline">Giỏ hàng</span>
                            @if(Auth::user()->cart()->count() > 0)
                                <span class="absolute -top-2 -right-2 bg-red-500 text-white rounded-full text-xs w-5 h-5 flex items-center justify-center">
                                    {{ Auth::user()->cart()->sum('quantity') }}
                                </span>
                            @endif
                        </a>

                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-gray-700 hover:text-blue-600 transition">
                                <i class="fas fa-user-circle mr-1"></i>
                                <span class="hidden md:inline">{{ Auth::user()->name }}</span>
                                <i class="fas fa-chevron-down text-xs ml-1"></i>
                            </button>

                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50">
                                @if(Auth::user()->isAdmin())
                                    <a href="{{ route('admin.dashboard') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-tachometer-alt mr-2"></i> Quản trị
                                    </a>
                                @endif

                                <a href="{{ route('profile.show') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-user mr-2"></i> Thông tin cá nhân
                                </a>

                                <a href="{{ route('shop.orders.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fas fa-clipboard-list mr-2"></i> Đơn hàng của tôi
                                </a>

                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                        <i class="fas fa-sign-out-alt mr-2"></i> Đăng xuất
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="text-gray-700 hover:text-blue-600 transition">
                            <i class="fas fa-sign-in-alt mr-1"></i>
                            <span class="hidden md:inline">Đăng nhập</span>
                        </a>

                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded-md transition">
                            <i class="fas fa-user-plus mr-1"></i>
                            <span class="hidden md:inline">Đăng ký</span>
                        </a>
                    @endauth
                </nav>
            </div>

            <!-- Mobile Search -->
            <div class="md:hidden mt-3">
                <form action="{{ route('products.index') }}" method="GET">
                    <div class="relative">
                        <input type="text" name="search" placeholder="Tìm kiếm món ăn..." class="w-full px-4 py-2 border border-gray-300 rounded-full focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <button type="submit" class="absolute right-0 top-0 mt-2 mr-3 text-gray-500">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">Quán Quê</h3>
                    <p class="mb-4">Món ăn ngon, chất lượng, giao hàng nhanh chóng.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-white hover:text-blue-400 transition">
                            <i class="fab fa-facebook-f text-xl"></i>
                        </a>
                        <a href="#" class="text-white hover:text-blue-400 transition">
                            <i class="fab fa-twitter text-xl"></i>
                        </a>
                        <a href="#" class="text-white hover:text-blue-400 transition">
                            <i class="fab fa-instagram text-xl"></i>
                        </a>
                    </div>
                </div>

                <div>
                    <h3 class="text-xl font-bold mb-4">Liên kết nhanh</h3>
                    <ul class="space-y-2">
                        <li>
                            <a href="{{ route('home') }}" class="hover:text-blue-400 transition">
                                <i class="fas fa-chevron-right mr-2 text-sm"></i> Trang chủ
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('products.index') }}" class="hover:text-blue-400 transition">
                                <i class="fas fa-chevron-right mr-2 text-sm"></i> Sản phẩm
                            </a>
                        </li>
                        <li>
                            <a href="#" class="hover:text-blue-400 transition">
                                <i class="fas fa-chevron-right mr-2 text-sm"></i> Về chúng tôi
                            </a>
                        </li>
                        <li>
                            <a href="#" class="hover:text-blue-400 transition">
                                <i class="fas fa-chevron-right mr-2 text-sm"></i> Liên hệ
                            </a>
                        </li>
                    </ul>
                </div>

                <div>
                    <h3 class="text-xl font-bold mb-4">Thông tin liên hệ</h3>
                    <ul class="space-y-2">
                        <li>
                            <i class="fas fa-map-marker-alt mr-2"></i> 123 Đường ABC, Quận XYZ, Hà Nội
                        </li>
                        <li>
                            <i class="fas fa-phone-alt mr-2"></i> (+84) 1234 5678
                        </li>
                        <li>
                            <i class="fas fa-envelope mr-2"></i> info@foodstore.com
                        </li>
                        <li>
                            <i class="fas fa-clock mr-2"></i> 8:00 - 22:00, Thứ 2 - Chủ nhật
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-700 mt-8 pt-6 text-center">
                <p>&copy; {{ date('Y') }} FoodStore. Tất cả các quyền được bảo lưu.</p>
            </div>
        </div>
    </footer>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Initialize AOS -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            AOS.init({
                duration: 800,
                easing: 'ease-in-out',
                once: true
            });
        });
    </script>

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
