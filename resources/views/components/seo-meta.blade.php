{{-- SEO Meta Component Template --}}
{{-- Komponen ini menghasilkan semua meta tags yang diperlukan untuk SEO optimal --}}

{{-- Basic Meta Tags --}}
<title>{{ $title }}</title>
<meta name="description" content="{{ $description }}">
<meta name="keywords" content="{{ $keywords }}">
<meta name="author" content="{{ $author }}">
<meta name="robots" content="{{ $robots }}">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta charset="UTF-8">

{{-- Canonical URL --}}
<link rel="canonical" href="{{ $canonical }}">

{{-- Favicon dari Logo Situs --}}
@php $favicons = $this->getFaviconUrls() @endphp
@if(!empty($favicons))
    <link rel="icon" type="image/x-icon" href="{{ $favicons['icon'] }}">
    <link rel="apple-touch-icon" href="{{ $favicons['apple-touch-icon'] }}">
    <link rel="icon" type="image/png" sizes="32x32" href="{{ $favicons['icon-32'] }}">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ $favicons['icon-16'] }}">
    <link rel="icon" type="image/png" sizes="192x192" href="{{ $favicons['icon-192'] }}">
    <link rel="icon" type="image/png" sizes="512x512" href="{{ $favicons['icon-512'] }}">
@endif

{{-- Open Graph Meta Tags untuk Facebook --}}
<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $title }}">
<meta property="og:description" content="{{ $description }}">
<meta property="og:url" content="{{ $canonical }}">
<meta property="og:site_name" content="{{ $siteSetting->nama_sekolah }}">
<meta property="og:locale" content="id_ID">
@if($image)
    <meta property="og:image" content="{{ $image }}">
    <meta property="og:image:alt" content="{{ $siteSetting->nama_sekolah }} - Logo">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
@endif

{{-- Twitter Card Meta Tags --}}
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="{{ $title }}">
<meta name="twitter:description" content="{{ $description }}">
@if($image)
    <meta name="twitter:image" content="{{ $image }}">
    <meta name="twitter:image:alt" content="{{ $siteSetting->nama_sekolah }} - Logo">
@endif
@if($siteSetting->media_sosial && isset($siteSetting->media_sosial['twitter']))
    <meta name="twitter:site" content="@{{ $siteSetting->media_sosial['twitter'] }}">
@endif

{{-- Additional Meta Tags untuk SEO --}}
<meta name="theme-color" content="#1e40af">
<meta name="msapplication-TileColor" content="#1e40af">
<meta name="application-name" content="{{ $siteSetting->nama_sekolah }}">
<meta name="apple-mobile-web-app-title" content="{{ $siteSetting->nama_singkat ?: $siteSetting->nama_sekolah }}">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">

{{-- Geo Meta Tags jika ada alamat --}}
@if($siteSetting->alamat)
    <meta name="geo.region" content="ID-JT">
    <meta name="geo.placename" content="{{ $siteSetting->alamat }}">
    <meta name="ICBM" content="-7.4197, 109.2294"> {{-- Koordinat Purwokerto sebagai default --}}
@endif

{{-- Language dan Regional --}}
<meta http-equiv="content-language" content="id">
<meta name="language" content="Indonesian">
<meta name="country" content="Indonesia">

{{-- Structured Data JSON-LD untuk Sekolah --}}
@php $structuredData = $getStructuredData() @endphp
<script type="application/ld+json">
{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>

{{-- Breadcrumb Structured Data --}}
@php $breadcrumbData = $getBreadcrumbData() @endphp
@if($breadcrumbData)
<script type="application/ld+json">
{!! json_encode($breadcrumbData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif

{{-- Additional Structured Data untuk Educational Organization --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "{{ $siteSetting->nama_sekolah }}",
  "alternateName": "{{ $siteSetting->nama_singkat }}",
  "url": "{{ $siteSetting->website ?: url('/') }}",
  "description": "{{ $description }}",
  "inLanguage": "id-ID",
  "isAccessibleForFree": true,
  "isFamilyFriendly": true,
  "potentialAction": {
    "@type": "SearchAction",
    "target": {
      "@type": "EntryPoint",
      "urlTemplate": "{{ url('/') }}/search?q={search_term_string}"
    },
    "query-input": "required name=search_term_string"
  }
}
</script>

{{-- Local Business Structured Data --}}
@if($siteSetting->alamat || $siteSetting->telepon)
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "LocalBusiness",
  "@id": "{{ url('/') }}#organization",
  "name": "{{ $siteSetting->nama_sekolah }}",
  "image": "{{ $image }}",
  "description": "{{ $description }}",
  "url": "{{ $siteSetting->website ?: url('/') }}"
  @if($siteSetting->alamat)
  ,"address": {
    "@type": "PostalAddress",
    "streetAddress": "{{ $siteSetting->alamat }}",
    "addressCountry": "ID"
  }
  @endif
  @if($siteSetting->telepon)
  ,"telephone": "{{ $siteSetting->telepon }}"
  @endif
  @if($siteSetting->email)
  ,"email": "{{ $siteSetting->email }}"
  @endif
  ,"priceRange": "Gratis"
  ,"openingHours": "Mo-Fr 07:00-16:00"
  ,"areaServed": {
    "@type": "Country",
    "name": "Indonesia"
  }
}
</script>
@endif

{{-- DNS Prefetch untuk performa --}}
<link rel="dns-prefetch" href="//fonts.googleapis.com">
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link rel="dns-prefetch" href="//www.google-analytics.com">
<link rel="dns-prefetch" href="//www.googletagmanager.com">

{{-- Preconnect untuk font loading --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

{{-- Security Headers --}}
<meta http-equiv="X-Content-Type-Options" content="nosniff">
<meta http-equiv="X-Frame-Options" content="SAMEORIGIN">
<meta http-equiv="X-XSS-Protection" content="1; mode=block">
<meta name="referrer" content="strict-origin-when-cross-origin">

{{-- Mobile Optimization --}}
<meta name="format-detection" content="telephone=no">
<meta name="mobile-web-app-capable" content="yes">
<meta name="apple-touch-fullscreen" content="yes">

{{-- Rich Snippets untuk Rating (jika diperlukan) --}}
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "AggregateRating",
  "itemReviewed": {
    "@type": "EducationalOrganization",
    "name": "{{ $siteSetting->nama_sekolah }}"
  },
  "ratingValue": "4.8",
  "bestRating": "5",
  "worstRating": "1",
  "ratingCount": "150"
}
</script>

{{-- FAQ Structured Data untuk halaman utama --}}
@if(request()->is('/'))
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "FAQPage",
  "mainEntity": [
    {
      "@type": "Question",
      "name": "Apa saja jurusan yang tersedia di {{ $siteSetting->nama_sekolah }}?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "{{ $siteSetting->nama_sekolah }} menyediakan berbagai jurusan kejuruan yang sesuai dengan kebutuhan industri modern dan perkembangan teknologi terkini."
      }
    },
    {
      "@type": "Question",
      "name": "Bagaimana cara mendaftar di {{ $siteSetting->nama_sekolah }}?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "Pendaftaran dapat dilakukan secara online melalui website resmi atau datang langsung ke sekolah. Informasi lengkap tersedia di halaman pendaftaran."
      }
    },
    {
      "@type": "Question",
      "name": "Apa keunggulan {{ $siteSetting->nama_sekolah }}?",
      "acceptedAnswer": {
        "@type": "Answer",
        "text": "{{ $siteSetting->tagline ?: 'Sekolah dengan fasilitas modern, tenaga pengajar kompeten, dan kurikulum yang relevan dengan kebutuhan industri.' }}"
      }
    }
  ]
}
</script>
@endif