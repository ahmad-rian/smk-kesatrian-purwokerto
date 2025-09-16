{{--
    Structured Data Component
    Menampilkan JSON-LD schema untuk SEO dan AI understanding
--}}

@if(!empty($schemas))
    @foreach($schemas as $schema)
        <script type="application/ld+json">
            {!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
        </script>
    @endforeach
@endif