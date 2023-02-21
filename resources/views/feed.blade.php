@if($ads)
@forelse($ads as $ad)
    <article class="post">
        <header class="post-header">
            <div class="info">
                <img width="50px" height="50px" src="/img/groups/{!! str_replace('-', '', $ad->owner_id) !!}.webp" title="Группа" alt="Группа">
                <div class="time-info">
                    <p class="time-published">Опубликовано в</p>
                    <p class="time">{{ gmdate("H:i", ($ad->date + 3 * 60 * 60)) }}
                    @if( $ad->date > strtotime('today midnight')) сегодня
                    @elseif( $ad->date > strtotime('yesterday midnight')) вчера
                    @else{{ gmdate("d.m.Y", ($ad->date + 3 * 60 * 60)) }}@endif
                    </p>
                </div>
            </div>
            <div class="info-more">
                @if($ad->owner_id == 1)
                    <img class="info-more-our" width="50px" height="50px" src="/img/shield.svg" title="Создано на нашем сайте" alt="Создано на нашем сайте">
                @else
                    <svg class="three-dots" width="24" height="24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><g id="more_horizontal_24__Page-2" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><g id="more_horizontal_24__more_horizontal_24"><path id="more_horizontal_24__Bounds" d="M24 0H0v24h24z"></path><path d="M18 10a2 2 0 0 1 2 2 2 2 0 0 1-2 2 2 2 0 0 1-2-2c0-1.1.9-2 2-2Zm-6 4a2 2 0 0 1-2-2c0-1.1.9-2 2-2a2 2 0 0 1 2 2 2 2 0 0 1-2 2Zm-6 0a2 2 0 0 1-2-2c0-1.1.9-2 2-2a2 2 0 0 1 2 2 2 2 0 0 1-2 2Z" id="more_horizontal_24__Mask" fill="currentColor"></path></g></g></svg>
                    <div class="info-hidden">
                        <a class="go_to_vk" data-id="{{ $ad->vk_id }}" href="{{ $ad->link }}" target="_blank" rel="nofollow noopener noreferrer">
                            Открыть оригинал
                        </a>
                    </div>
                @endif
            </div>
        </header>
        
        <div class="post-content">{!! $ad->content_changed !!}</div>
        {{-- <span class="">{!! $ad->content !!}</span> --}}
    </article>
@empty
    <article class="post no-ads">
        <img class="no-ads-img" src="/img/no-ads.svg" title="Объявлений не найдено" alt="Объявлений не найдено">
        <h2 class="">Объявлений не найдено</h2>
    </article>
@endforelse
@endif