<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>{{ $title ?? config('app.name') }}</title>

<!-- Fonts -->
<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=bricolage-grotesque:400,500,600,700|inter:400,500,600,700" rel="stylesheet" />

<!-- Styles & Scripts -->
@vite(['resources/css/app.css', 'resources/js/app.js'])
@livewireStyles
