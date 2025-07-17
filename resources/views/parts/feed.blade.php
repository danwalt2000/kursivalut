@if( isset($geodata) && !empty($geodata['geo_allowed']) )
    @forelse($ads as $ad)
        @include('parts.post', ['ad' => $ad])
    @empty
        @include('parts.no-posts')
    @endforelse
@else
    <article class="post no-ads">
        <img class="no-ads-img" src="/img/no-ads.svg" title="Не найдено" alt="Не найдено" loading="lazy">
        <h2 class="">Доступ к ленте ограничен для вашего региона</h2>
        <a class="no-ads-tg-link" href="https://t.me/kursivalut_ru_{{ $locale['name'] }}" target="_blank"><strong>Открыть <span class="no-ads-hidden-mobile">ленту </span>в Telegram</strong></a>
        <div class="post-content post-content_left">В соответствии с требованием РКН от 01.11.2024 года, доступ к объявлениям, на основании которых собирается статистика, ограничен в вашем регионе.</div>
        {{-- <div class="post-content post-content_left mt-10"><span class="hidden_phone spoiler_text">Лента доступна с VPN.</span></div> --}}
    </article>
@endif