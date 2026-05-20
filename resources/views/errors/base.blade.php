@extends('base')

@section('meta_description')
    Ошибка: @yield('title')
@endsection
@section('favicon_href', '/img/valuta.ico')
@section('favicon_type', 'image/x-icon')
@section('header_logo_image', '/img/pig.svg')
@section('header_location')
@endsection
@section('header_form')
@endsection
@section('header_theme_toggle')
@endsection
@section('header_action')
@endsection
@section('canonical_url', Request::url())
@section('show_right_column', '0')
@section('show_scroll_to_top', '0')
@section('show_gdpr', '0')
@section('show_modal', '0')
@section('show_page_scripts', '0')
@section('show_app_js', '0')
@section('show_yandex_ad_script', '0')
