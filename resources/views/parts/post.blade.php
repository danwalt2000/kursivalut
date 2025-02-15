<article class="post declaration">
    <header class="post-header">
        <div class="info">
            <img width="50px" height="50px" src="/img/groups/{!! str_replace('-', '', $ad->owner_id) !!}.webp" title="Группа" alt="Группа" loading="lazy">
            <div class="time-info">
                <p class="time-published">Опубликовано</p>
                <p class="time" data-time="{{$ad->date}}">в&nbsp;{{ gmdate("H:i", ($ad->date + 3 * 60 * 60)) }}
                @if( $ad->date > strtotime('today midnight')) сегодня
                @elseif( $ad->date > strtotime('yesterday midnight')) вчера
                @else{{ gmdate("d.m.Y", ($ad->date + 3 * 60 * 60)) }}@endif
                </p>
            </div>
        </div>
        <div class="info-more">
            @if($ad->owner_id == 1)
                <img class="info-more-our" width="50px" height="50px" src="/img/shield.svg" title="Создано на нашем сайте" alt="Создано на нашем сайте" loading="lazy">
            @else
                <a class="go_to_vk" data-id="{{ $ad->vk_id }}" 
                    href="{{ $ad->link }}" target="_blank" 
                    rel="nofollow noopener noreferrer"><span class="hidden_on_mobile">Открыть</span> источник</a>
            @endif
        </div>
    </header>
    
    <div class="post-content">{!! $ad->content_changed !!}</div>
    {{-- <span class="">{!! $ad->content !!}</span> --}}
</article>