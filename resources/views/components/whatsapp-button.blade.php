{{--
    WhatsApp Floating Button Component
    
    Komponen tombol WhatsApp floating yang responsif dan profesional
    - Mengambil nomor telepon dari site settings database
    - Menggunakan Alpine.js untuk interaktivitas
    - Icon WhatsApp yang konsisten
    - Animasi hover yang smooth
    - Link langsung ke WhatsApp dengan pesan default
--}}

@php
    $siteSetting = \App\Models\SiteSetting::getInstance();
    $whatsappNumber = $siteSetting->telepon ?? '';
    
    // Format nomor WhatsApp (hapus karakter non-digit dan tambahkan kode negara jika perlu)
    $whatsappNumber = preg_replace('/[^0-9]/', '', $whatsappNumber);
    if ($whatsappNumber && !str_starts_with($whatsappNumber, '62')) {
        $whatsappNumber = '62' . ltrim($whatsappNumber, '0');
    }
    
    $whatsappMessage = urlencode('Halo, saya ingin bertanya tentang ' . ($siteSetting->nama_sekolah ?? 'SMK Kesatrian'));
@endphp

@if($whatsappNumber)
<div x-data="{
    show: true,
    pulse: true,
    init() {
        // Pulse animation setiap 3 detik
        setInterval(() => {
            this.pulse = false;
            setTimeout(() => {
                this.pulse = true;
            }, 100);
        }, 3000);
    }
}" x-cloak>
    <!-- WhatsApp Floating Button -->
    <a
        href="https://wa.me/{{ $whatsappNumber }}?text={{ $whatsappMessage }}"
        target="_blank"
        rel="noopener noreferrer"
        x-show="show"
        x-transition:enter="transition ease-out duration-300 delay-500"
        x-transition:enter-start="opacity-0 scale-75 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        class="fixed bottom-6 right-6 z-50 bg-green-500 hover:bg-green-600 text-white p-4 rounded-full shadow-2xl hover:shadow-3xl transition-all duration-300 group border-2 border-green-400"

        aria-label="Chat via WhatsApp"
        title="Chat via WhatsApp"
    >
        <!-- WhatsApp Icon -->
        <svg class="w-7 h-7 group-hover:scale-110 transition-transform duration-200" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
        </svg>
        
        <!-- Tooltip -->
        <div class="absolute bottom-full right-0 mb-3 px-4 py-2 bg-gray-900 text-white text-sm font-medium rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap pointer-events-none shadow-lg">
            Chat via WhatsApp
            <div class="absolute top-full right-4 w-0 h-0 border-l-4 border-r-4 border-t-4 border-transparent border-t-gray-900"></div>
        </div>
    </a>
</div>
@endif