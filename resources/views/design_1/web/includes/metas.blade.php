<meta charset="utf-8">
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-9MV3TJX04F"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());
    gtag('config', 'G-9MV3TJX04F');
</script>

<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

@php
    $siteName = !empty($generalSettings['site_name']) ? $generalSettings['site_name'] : config('app.name');
    $pageTitleValue = $pageTitle ?? $siteName;
    $metaDescription = !empty($pageDescription) ? $pageDescription : $siteName;
    $canonicalUrl = $pageCanonicalUrl ?? request()->url();
    $isSensitiveRoute = request()->is('panel*')
        || request()->is('admin*')
        || request()->is('login')
        || request()->is('register*')
        || request()->is('forget-password')
        || request()->is('reset-password*')
        || request()->is('verification*');
    $defaultRobotValue = $isSensitiveRoute ? 'noindex, nofollow' : 'index, follow';
    $pageRobotValue = $pageRobot ?? $defaultRobotValue;

    if (empty($pageMetaImage)) {
        $pageMetaImage = !empty($generalSettings['fav_icon']) ? $generalSettings['fav_icon'] : '/favicon.ico';
    }

    $metaImageUrl = url($pageMetaImage);
    $twitterCard = !empty($twitterCard) ? $twitterCard : 'summary_large_image';
    $localeValue = str_replace('-', '_', app()->getLocale() ?? 'en_US');
    $pageOgType = $pageOgType ?? (!empty($post) ? 'article' : (!empty($course) ? 'course' : (!empty($product) ? 'product' : 'website')));

    $favIconPath = !empty($generalSettings['fav_icon']) ? $generalSettings['fav_icon'] : '/favicon.ico';
    $logoPath = !empty($generalSettings['logo']) ? $generalSettings['logo'] : $favIconPath;

    $jsonLdSchemas = [];

    $organizationSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $siteName,
        'url' => url('/'),
        'logo' => url($logoPath),
    ];

    $websiteSchema = [
        '@context' => 'https://schema.org',
        '@type' => 'WebSite',
        'name' => $siteName,
        'url' => url('/'),
        'potentialAction' => [
            '@type' => 'SearchAction',
            'target' => url('/search') . '?search={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ];

    $jsonLdSchemas[] = $organizationSchema;
    $jsonLdSchemas[] = $websiteSchema;

    $segments = request()->segments();
    if (!empty($segments)) {
        $breadcrumbItems = [[
            '@type' => 'ListItem',
            'position' => 1,
            'name' => 'Home',
            'item' => url('/'),
        ]];

        $position = 2;
        $path = '';
        foreach ($segments as $segment) {
            $path .= '/' . $segment;

            $breadcrumbItems[] = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => ucfirst(str_replace(['-', '_'], ' ', $segment)),
                'item' => url($path),
            ];

            $position++;
        }

        $jsonLdSchemas[] = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $breadcrumbItems,
        ];
    }

    if (!empty($pageSchema)) {
        $jsonLdSchemas[] = $pageSchema;
    }

    if (!empty($pageSchemas) and is_array($pageSchemas)) {
        foreach ($pageSchemas as $schema) {
            if (!empty($schema) and is_array($schema)) {
                $jsonLdSchemas[] = $schema;
            }
        }
    }
@endphp

<meta name='robots' content="{{ $pageRobotValue }}">
<link rel="canonical" href="{{ $canonicalUrl }}">

<meta name="description" content="{{ $metaDescription }}">
<meta property="og:description" content="{{ (!empty($ogDescription)) ? $ogDescription : $metaDescription }}">
<meta name='twitter:description' content='{{ (!empty($ogDescription)) ? $ogDescription : $metaDescription }}'>


<link rel='shortcut icon' type='image/x-icon' href="{{ url($favIconPath) }}">
<link rel="manifest" href="/mix-manifest.json?v=4">
<meta name="theme-color" content="#FFF">
<!-- Windows Phone -->
<meta name="msapplication-starturl" content="/">
<meta name="msapplication-TileColor" content="#FFF">
<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
<!-- iOS Safari -->
<meta name="apple-mobile-web-app-title" content="{{ !empty($generalSettings['site_name']) ? $generalSettings['site_name'] : '' }}">
<link rel="apple-touch-icon" href="{{ url($favIconPath) }}">
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="default">
<!-- Android -->
<link rel='icon' href='{{ url($favIconPath) }}'>
<meta name="application-name" content="{{ !empty($generalSettings['site_name']) ? $generalSettings['site_name'] : '' }}">
<meta name="mobile-web-app-capable" content="yes">
<!-- Other -->
<meta name="layoutmode" content="fitscreen/standard">
<link rel="home" href="{{ url('') }}">

<!-- Open Graph -->
<meta property='og:title' content='{{ $pageTitleValue }}'>
<meta property='og:site_name' content='{{ $siteName }}'>
<meta property='og:url' content='{{ $canonicalUrl }}'>
<meta property='og:image' content='{{ $metaImageUrl }}'>
<meta property='og:locale' content='{{ $localeValue }}'>
<meta property='og:type' content='{{ $pageOgType }}'>

<meta name='twitter:card' content='{{ $twitterCard }}'>
<meta name='twitter:title' content='{{ $pageTitleValue }}'>
<meta name='twitter:image' content='{{ $metaImageUrl }}'>

{!! getSeoMetas('extra_meta_tags') !!}

@foreach($jsonLdSchemas as $jsonLdSchema)
    @if(!empty($jsonLdSchema))
        <script type="application/ld+json">{{ json_encode($jsonLdSchema, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT) }}</script>
    @endif
@endforeach

