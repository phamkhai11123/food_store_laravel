<x-layouts.app title="Trang chủ">
    <!-- Hero Section -->
    <section class="relative bg-blue-600 text-white py-16">
        <div class="container mx-auto px-4 z-10 relative">
            <div class="flex flex-col md:flex-row items-center">
                <div class="md:w-1/2 mb-8 md:mb-0" data-aos="fade-right">
                    <h1 class="text-4xl md:text-5xl font-bold mb-4">Đặt đồ ăn ngon mỗi ngày</h1>
                    <p class="text-xl mb-6">Dễ dàng đặt món ăn yêu thích với dịch vụ giao hàng nhanh chóng và đảm bảo chất lượng.</p>
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('products.index') }}" class="bg-white text-blue-600 hover:bg-gray-100 px-6 py-3 rounded-full font-bold transition">
                            Xem thực đơn
                        </a>
                        @guest
                            <a href="{{ route('register') }}" class="bg-transparent hover:bg-blue-700 border-2 border-white px-6 py-3 rounded-full font-bold transition">
                                Đăng ký ngay
                            </a>
                        @endguest
                    </div>
                </div>
                <div class="md:w-1/2" data-aos="fade-left">
                    <img src="{{ asset('storage/hero-image.jpg') }}" alt="Đồ ăn ngon" class="rounded-lg shadow-xl max-w-full mx-auto" onerror="this.onerror=null; this.src='https://via.placeholder.com/600x400?text=Food+Store';">
                </div>
            </div>
        </div>

        <!-- Background pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute inset-0" style="background-image: url('data:image/svg+xml,%3Csvg width=\'100\' height=\'100\' viewBox=\'0 0 100 100\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cpath d=\'M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z\' fill=\'%23ffffff\' fill-opacity=\'1\' fill-rule=\'evenodd\'/%3E%3C/svg%3E');"></div>
        </div>
    </section>

    <!-- Danh mục Section -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold mb-2">Danh mục món ăn</h2>
                <p class="text-gray-600">Khám phá các danh mục món ăn đa dạng của chúng tôi</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach(\App\Models\Category::all() as $category)
                    <div class="bg-white rounded-lg shadow-md overflow-hidden group hover:shadow-xl transition duration-300" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                        <a href="{{ route('products.index', ['category' => $category->id]) }}">
                            <div class="h-40 bg-gray-200 overflow-hidden">
                                @if($category->image)
                                    <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-300">
                                @else
                                    <div class="w-full h-full flex items-center justify-center bg-blue-100 text-blue-500">
                                        <i class="fas fa-utensils text-5xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="p-4">
                                <h3 class="font-bold text-xl mb-2 group-hover:text-blue-600 transition">{{ $category->name }}</h3>
                                <p class="text-gray-600 text-sm">{{ $category->description }}</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Sản phẩm nổi bật -->
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold mb-2">Món ăn nổi bật</h2>
                <p class="text-gray-600">Những món ăn được yêu thích nhất tại FoodStore</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach(\App\Models\Product::inRandomOrder()->take(8)->get() as $product)
                    <x-ui.product-card :product="$product" />
                @endforeach
            </div>

            <div class="text-center mt-10">
                <a href="{{ route('products.index') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-full font-bold transition inline-block">
                    Xem tất cả món ăn
                </a>
            </div>
        </div>
    </section>

    <!-- Tại sao chọn chúng tôi -->
    <section class="py-16">
        <div class="container mx-auto px-4">
            <div class="text-center mb-12" data-aos="fade-up">
                <h2 class="text-3xl font-bold mb-2">Tại sao chọn FoodStore?</h2>
                <p class="text-gray-600">Chúng tôi cam kết mang đến trải nghiệm tuyệt vời nhất cho bạn</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div class="bg-white p-6 rounded-lg shadow-md text-center" data-aos="fade-up" data-aos-delay="0">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-utensils text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Món ăn chất lượng</h3>
                    <p class="text-gray-600">Chúng tôi chỉ sử dụng nguyên liệu tươi ngon và chất lượng cao để chế biến món ăn.</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md text-center" data-aos="fade-up" data-aos-delay="100">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-shipping-fast text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Giao hàng nhanh chóng</h3>
                    <p class="text-gray-600">Cam kết giao hàng đúng giờ và nhanh chóng để đảm bảo món ăn vẫn còn nóng hổi.</p>
                </div>

                <div class="bg-white p-6 rounded-lg shadow-md text-center" data-aos="fade-up" data-aos-delay="200">
                    <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-headset text-blue-600 text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Dịch vụ khách hàng</h3>
                    <p class="text-gray-600">Đội ngũ nhân viên thân thiện, luôn sẵn sàng hỗ trợ và giải đáp mọi thắc mắc của bạn.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Đăng ký nhận thông báo -->
    <section class="py-16 bg-blue-600 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center" data-aos="fade-up">
                <h2 class="text-3xl font-bold mb-4">Đăng ký nhận thông báo</h2>
                <p class="text-xl mb-8">Nhận thông tin về các ưu đãi và món ăn mới của chúng tôi.</p>

                <form class="flex flex-col sm:flex-row max-w-lg mx-auto">
                    <input type="email" placeholder="Nhập email của bạn" class="flex-1 px-4 py-3 rounded-l-md sm:rounded-r-none rounded-r-md sm:mb-0 mb-2 text-gray-800 focus:outline-none">
                    <button type="submit" class="bg-white text-blue-600 hover:bg-gray-100 px-6 py-3 rounded-r-md sm:rounded-l-none rounded-l-md font-bold transition">
                        Đăng ký
                    </button>
                </form>
            </div>
        </div>
    </section>
</x-layouts.app>
