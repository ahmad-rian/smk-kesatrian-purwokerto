<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Halaman Tidak Ditemukan | SMK Kesatrian</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,200..800&family=Inter:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Lottie Player -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <style>
        .title-font {
            font-family: 'Bricolage Grotesque', sans-serif;
        }

        .body-font {
            font-family: 'Inter', sans-serif;
        }

        .gradient-bg {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }

        .floating-animation {
            animation: floating 3s ease-in-out infinite;
        }

        @keyframes floating {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-20px);
            }
        }

        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(30px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .btn-hover {
            transition: all 0.3s ease;
        }

        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }
    </style>
</head>

<body class="body-font bg-gray-50 min-h-screen flex items-center justify-center">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto text-center">


            <!-- Animasi 404 -->
            <div class="mb-8 fade-in floating-animation">
                <div class="flex justify-center">
                    <lottie-player src="{{ asset('assets/animations/404.json') }}" background="transparent"
                        speed="1" style="width: 400px; height: 300px;" loop autoplay>
                    </lottie-player>
                </div>
            </div>

            <!-- Konten Utama -->
            <div class="mb-12 fade-in">
                <h2 class="title-font text-6xl font-bold text-gray-800 mb-4">404</h2>
                <h3 class="title-font text-3xl font-semibold text-gray-700 mb-6">Halaman Tidak Ditemukan</h3>
                <p class="body-font text-lg text-gray-600 mb-8 max-w-2xl mx-auto leading-relaxed">
                    Maaf, halaman yang Anda cari tidak dapat ditemukan. Mungkin halaman tersebut telah dipindahkan,
                    dihapus, atau URL yang Anda masukkan salah.
                </p>
            </div>

            <!-- Tombol Navigasi -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12 fade-in">
                <a href="{{ route('home') }}"
                    class="btn-hover inline-flex items-center px-8 py-4 bg-blue-600 text-white font-semibold rounded-xl shadow-lg hover:bg-blue-700 transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    Kembali ke Beranda
                </a>

                <button onclick="history.back()"
                    class="btn-hover inline-flex items-center px-8 py-4 bg-gray-600 text-white font-semibold rounded-xl shadow-lg hover:bg-gray-700 transition-all duration-300">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Halaman Sebelumnya
                </button>
            </div>

            <!-- Menu Navigasi Cepat -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 max-w-3xl mx-auto fade-in">
                <a href="{{ route('profil') }}"
                    class="btn-hover bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 group">
                    <div class="text-blue-600 mb-3">
                        <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <h4 class="title-font font-semibold text-gray-800 mb-2">Profil Sekolah</h4>
                    <p class="body-font text-sm text-gray-600">Informasi lengkap tentang SMK Kesatrian</p>
                </a>

                <a href="{{ route('fasilitas.index') }}"
                    class="btn-hover bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 group">
                    <div class="text-green-600 mb-3">
                        <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                            </path>
                        </svg>
                    </div>
                    <h4 class="title-font font-semibold text-gray-800 mb-2">Fasilitas</h4>
                    <p class="body-font text-sm text-gray-600">Fasilitas unggulan sekolah</p>
                </a>

                <a href="{{ route('kontak') }}"
                    class="btn-hover bg-white rounded-xl p-6 shadow-lg hover:shadow-xl transition-all duration-300 group">
                    <div class="text-purple-600 mb-3">
                        <svg class="w-8 h-8 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z">
                            </path>
                        </svg>
                    </div>
                    <h4 class="title-font font-semibold text-gray-800 mb-2">Kontak</h4>
                    <p class="body-font text-sm text-gray-600">Hubungi kami untuk informasi lebih lanjut</p>
                </a>
            </div>

            <!-- Footer -->
            <div class="mt-12 pt-8 border-t border-gray-200 fade-in">
                <p class="body-font text-gray-500 text-sm">
                    © {{ date('Y') }} SMK Kesatrian. Semua hak cipta dilindungi.
                </p>
            </div>
        </div>
    </div>

    <!-- Script untuk animasi tambahan -->
    <script>
        // Animasi fade-in saat halaman dimuat
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.fade-in');
            elements.forEach((element, index) => {
                setTimeout(() => {
                    element.style.opacity = '1';
                    element.style.transform = 'translateY(0)';
                }, index * 200);
            });
        });

        // Efek parallax ringan pada scroll
        window.addEventListener('scroll', function() {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.floating-animation');
            if (parallax) {
                const speed = scrolled * 0.5;
                parallax.style.transform = `translateY(${speed}px)`;
            }
        });
    </script>
</body>

</html>
