<div class="location modal-wrapper">
    <span class="modal-open location-modal-open">{{$locale['title']}}</span>
    <div class="popup-bg modal-hidden">
        <div class="modal-inner location-popup">
            <span class="modal-close close_modal"></span>
            <p class="location-title">Выберите город</p>
            <ul class="location-list">
                @foreach($locales as $city)
                    @if($city['name'] == $locale['name'])
                        <li class="location-city location_current-city">
                            <span>{{$city['title']}}</span>
                    @else
                        <li class="location-city">
                            <a href="//@if($city['name'] != 'donetsk'){{$city['name']}}.@endif{{$city['domain']}}.{{env('ENV_DOMAIN')}}">
                                {{$city['title']}}
                            </a>
                    @endif
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>