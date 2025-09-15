{{--
    Scroll to Top Component
    
    Komponen tombol scroll to top yang responsif dan profesional
    - Menggunakan Alpine.js untuk interaktivitas
    - Icon dari MaryUI yang konsisten
    - Smooth scroll animation
    - Auto hide/show berdasarkan scroll position
--}}

<div x-data="{
    show: false,
    init() {
        // Monitor scroll position
        window.addEventListener('scroll', () => {
            this.show = window.pageYOffset > 300;
        });
    },
    scrollToTop() {
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    }
}" x-cloak>
    <!-- Scroll to Top Button -->
    <button
        @click="scrollToTop()"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-75 translate-y-4"
        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100 translate-y-0"
        x-transition:leave-end="opacity-0 scale-75 translate-y-4"
        class="fixed bottom-24 right-6 z-50 bg-primary hover:bg-primary-focus text-primary-content p-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 group"
        aria-label="Scroll to top"
        title="Kembali ke atas"
    >
        <!-- Icon Arrow Up dari MaryUI -->
        <x-mary-icon name="o-arrow-up" class="w-5 h-5 group-hover:scale-110 transition-transform duration-200" />
    </button>
</div>