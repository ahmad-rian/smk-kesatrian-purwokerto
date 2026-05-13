<div class="min-h-screen bg-base-100 transition-colors duration-300">
    {{-- Hero Section --}}
    @if($page->show_hero)
        @if($page->hero_style === 'image' && $page->featured_image)
            {{-- Hero with Featured Image Background --}}
            <section class="relative py-20 md:py-28 overflow-hidden">
                <div class="absolute inset-0">
                    <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}"
                        class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-b from-black/60 via-black/50 to-black/70"></div>
                </div>
                <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4"
                        style="font-family: 'Bricolage Grotesque', sans-serif;">
                        {{ $page->title }}
                    </h1>
                    @if($page->excerpt)
                        <p class="text-lg md:text-xl text-white/80 max-w-3xl mx-auto leading-relaxed"
                            style="font-family: 'Inter', sans-serif;">
                            {{ $page->excerpt }}
                        </p>
                    @endif
                    <div class="w-20 h-1 bg-gradient-to-r from-green-400 to-emerald-400 mx-auto rounded-full mt-6"></div>
                </div>
            </section>
        @elseif($page->hero_style === 'gradient')
            {{-- Hero with Gradient Background --}}
            <section class="relative bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900 py-20 md:py-28 overflow-hidden">
                <div class="absolute inset-0 opacity-10">
                    <div class="absolute top-0 left-0 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
                    <div class="absolute top-0 right-0 w-96 h-96 bg-indigo-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
                    <div class="absolute bottom-0 left-1/2 w-96 h-96 bg-slate-500 rounded-full mix-blend-multiply filter blur-xl animate-pulse"></div>
                </div>
                <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-4"
                        style="font-family: 'Bricolage Grotesque', sans-serif;">
                        {{ $page->title }}
                    </h1>
                    @if($page->excerpt)
                        <p class="text-lg md:text-xl text-slate-200 max-w-3xl mx-auto leading-relaxed"
                            style="font-family: 'Inter', sans-serif;">
                            {{ $page->excerpt }}
                        </p>
                    @endif
                    <div class="w-20 h-1 bg-gradient-to-r from-blue-400 to-indigo-400 mx-auto rounded-full mt-6"></div>
                </div>
            </section>
        @else
            {{-- Simple Hero --}}
            <section class="relative bg-gradient-to-r from-base-200 via-base-200 to-base-300 py-16 transition-colors duration-300">
                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-green-500/5 to-green-600/5"></div>
                <div class="relative container mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 text-base-content"
                        style="font-family: 'Bricolage Grotesque', sans-serif;">
                        {{ $page->title }}
                    </h1>
                    @if($page->excerpt)
                        <p class="text-lg md:text-xl text-base-content/70 max-w-3xl mx-auto"
                            style="font-family: 'Inter', sans-serif;">
                            {{ $page->excerpt }}
                        </p>
                    @endif
                    <div class="w-20 h-1 bg-gradient-to-r from-green-500 to-green-600 mx-auto rounded-full mt-6"></div>
                </div>
            </section>
        @endif
    @endif

    {{-- Page Content --}}
    <section class="py-12 md:py-16">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-4xl mx-auto">
                {{-- Featured Image (if hero is hidden but image exists) --}}
                @if(!$page->show_hero && $page->featured_image)
                    <div class="mb-8 rounded-2xl overflow-hidden shadow-lg">
                        <img src="{{ Storage::url($page->featured_image) }}" alt="{{ $page->title }}"
                            class="w-full h-auto object-cover max-h-96">
                    </div>
                @endif

                {{-- Title (if hero is hidden) --}}
                @if(!$page->show_hero)
                    <h1 class="text-3xl sm:text-4xl font-bold text-base-content mb-6"
                        style="font-family: 'Bricolage Grotesque', sans-serif;">
                        {{ $page->title }}
                    </h1>
                    @if($page->excerpt)
                        <p class="text-lg text-base-content/70 mb-8" style="font-family: 'Inter', sans-serif;">
                            {{ $page->excerpt }}
                        </p>
                    @endif
                @endif

                {{-- Content Body --}}
                <div class="prose prose-lg max-w-none
                    prose-headings:font-bold prose-headings:text-base-content
                    prose-p:text-base-content/80 prose-p:leading-relaxed
                    prose-a:text-primary prose-a:no-underline hover:prose-a:underline
                    prose-strong:text-base-content
                    prose-img:rounded-xl prose-img:shadow-lg
                    prose-blockquote:border-primary prose-blockquote:text-base-content/70
                    prose-code:text-primary prose-code:bg-base-200 prose-code:px-1 prose-code:rounded
                    prose-pre:bg-base-200 prose-pre:text-base-content
                    prose-ul:text-base-content/80 prose-ol:text-base-content/80
                    prose-li:marker:text-primary
                    prose-table:text-base-content prose-th:bg-base-200
                    prose-hr:border-base-300"
                    style="font-family: 'Inter', sans-serif;">
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </section>
</div>
