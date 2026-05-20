@extends('base')

@section('body_class', 'landing')
@section('meta_description')
    @yield('description')
@endsection
@section('og_description')
    @yield('description')
@endsection
@section('extra_stylesheets')
    <link href="/css/landings.css?v=@isset($hash){{$hash}}@endisset" rel="stylesheet">
@endsection
@section('favicon_href', '/img/valuta.ico')
@section('favicon_type', 'image/x-icon')
@section('apple_touch_icon', '/img/pig.svg')
@section('apple_touch_type', 'image/svg+xml')
@section('header_logo_image', '/img/pig.svg')
@section('header_form')
    @if($locale['domain'] == 'valuta-dn')
        @include('parts.form')
    @endif
@endsection
@section('header_action')
@endsection
@section('after_header')
    <a class="back-home landing-back-home" href="/">Вернуться</a>
@endsection
@section('canonical_url')
    @if($table != 'donetsk' && trim($__env->yieldContent('canonical')) !== '')
        https://@yield('canonical')
    @endif
@endsection
@section('show_right_column', '0')
@section('show_scroll_to_top', '0')
@section('show_gdpr', '0')
@section('show_modal', '0')
@section('show_page_scripts', '0')
@section('show_yandex_ad_script', '0')
