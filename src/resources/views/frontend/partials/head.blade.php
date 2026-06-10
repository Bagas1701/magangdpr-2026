<meta charset="utf-8" />
<meta http-equiv="x-ua-compatible" content="ie=edge" />
<title>@yield('title', 'SIMALEX - Sistem Aspirasi Konstituen')</title>
<meta name="description" content="Sistem Informasi Manajemen Aspirasi Legislatif untuk pengelolaan aspirasi konstituen secara digital." />
<meta name="viewport" content="width=device-width, initial-scale=1" />

<link rel="stylesheet" href="{{ asset('front/assets/css/bootstrap-5.0.0-beta1.min.css') }}">
<link rel="stylesheet" href="{{ asset('front/assets/css/LineIcons.2.0.css') }}">
<link rel="stylesheet" href="{{ asset('front/assets/css/tiny-slider.css') }}">
<link rel="stylesheet" href="{{ asset('front/assets/css/animate.css') }}">

{{-- sementara jangan load lindy-uikit dulu karena isinya legacy 500vw --}}
{{-- <link rel="stylesheet" href="{{ asset('front/assets/css/lindy-uikit.css') }}"> --}}

<link rel="stylesheet" href="{{ asset('front/assets/css/simalex.css') }}?v={{ time() }}">