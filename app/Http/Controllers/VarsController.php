<?php
 
namespace App\Http\Controllers;

class VarsController extends Controller
{
    public $publics = [
        "obmenvalut_donetsk"    => ["id" => "-87785879",  "time" => "everyFiveMinutes", "domain" => "vk"],    // 5
        "obmen_valut_donetsk"   => ["id" => "-92215147",  "time" => "everyFiveMinutes", "domain" => "vk"],    // 5
        "obmenvalyut_dpr"       => ["id" => "-153734109", "time" => "everyThirtyMinutes", "domain" => "vk"],  // 30
        "club156050748"         => ["id" => "-156050748", "time" => "everyThirtyMinutes", "domain" => "vk"],  // 30
        "kursvalut_donetsk"     => ["id" => "-63859238",  "time" => "everyThirtyMinutes", "domain" => "vk"],  // 30
        "obmen_valut_dnr"       => ["id" => "-193547744", "time" => "hourly", "domain" => "vk"],              // 60
        "donetsk_obmen_valyuta" => ["id" => "-174075254", "time" => "hourly", "domain" => "vk"],              // 60
        "obmenvalut_dnr"        => ["id" => "-172375183", "time" => "hourly", "domain" => "vk"],              // 60
        "valutoobmen"           => ["id" => "-75586957",  "time" => "hourly", "domain" => "vk"],              // 60

        "1154050282" => ["id" => "obmenkadn",  "time" => "everyFiveMinutes", "domain" => "tg"],               // 5
        "1161871204" => ["id" => "obmenkadonetck",  "time" => "everyTenMinutes", "domain" => "tg"],           // 10
        "1345575332" => ["id" => "obmen_valut_donetsk_1",  "time" => "everyTenMinutes", "domain" => "tg"],    // 10
        "1265653325" => ["id" => "obmenvalutdon",  "time" => "everyFifteenMinutes", "domain" => "tg"],        // 15
        "1295018924" => ["id" => "obmen77market",  "time" => "everyFifteenMinutes", "domain" => "tg"],        // 15
        "1204646240" => ["id" => "valut_don",  "time" => "everyThirtyMinutes", "domain" => "tg"],             // 30
    ];

    public $api_keys = [
        'vk' => [
            'url_key'        => 'https://api.vk.com/method/wall.get?v=5.81&access_token=',
            'items_key'      => 'items',
            'text_key'       => 'text',
            'channel_id_key' => 'owner_id',
            'url_channel_key'=> 'owner_id', 
            'url_limit_key'  => 'count', 
            'error_key'      => 'error'
        ],
        'tg' => [
            'url_key'        => 'http://127.0.0.1:9503/api/getHistory/?',
            'items_key'      => 'messages',
            'text_key'       => 'message',
            'channel_id_key' => 'peer_id', 
            'channel_sub'    => 'channel_id', // ключ дочернего объекта
            'user_sub'       => 'user_id',    // ключ дочернего объекта
            'url_channel_key'=> 'data[peer]', 
            'url_limit_key'  => 'data[limit]', 
            'error_key'      => 'errors'
        ]
    ];
    
    public $currencies = [
        "dollar" => "Доллар",
        "euro" => "Евро",
        "hrn" => "Гривна",
        "cashless" => "Безнал руб."
     ];
     public $date_sort = [
        //  1   => "1 час",
         5   => "5 часов",
         24  => "24 часа",
         168 => "7 дней",
        //  720 => "30 дней"
     ];

    public $course_patterns = [
        "sell_dollar"      => '/(Прод|прод|ПРОД|[бо]мен[яи])(.*)(\$|Дол|ДОЛ|дол|бел[ыо][йг]|син|зел|💵)/', // старая маска [Пп]род.*(\$|дол|син|зел|💵)(.*?\d{2})
        "sell_euro"        => '/(Прод|прод|ПРОД|[бо]мен[яи])(.*)(\€|евро|Евро|ЕВРО)/',
        "sell_hrn"         => '/(Прод|прод|ПРОД|[бо]мен[яи]|Пополн|пополн)(.*)(Грив|грив|ГРИВ|Грн|ГРН|грн|\sгр\s|\sгр\.|укр|Укр|Приват|приват|ПРИВАТ|Ощад|ощад|ОЩАД|Моно|моно)/',
        "sell_cashless"    => '/(Прод|прод|ПРОД|[бо]мен[яи]|Пополн|пополн)(.*)(Сбер|сбер|СБЕР|[Тт]инько)/',
        
        "buy_dollar"       => '/(Куп|куп|КУП)(.*)(\$|Дол|ДОЛ|дол|бел[ыо][йг]|син|зел|💵)/',
        "buy_euro"         => '/(Куп|куп|КУП)(.*)(\€|евро|Евро|ЕВРО)/',
        "buy_hrn"          => '/(Куп|куп|КУП|Обналич|обналич)(.*)(Грив|грив|ГРИВ|Грн|ГРН|грн|\sгр\s|\sгр\.|укр|Укр|Приват|приват|ПРИВАТ|Ощад|ощад|ОЩАД|Моно|моно)/',
        "buy_cashless"     => '/(Куп|куп|КУП|Обналич|обналич)(.*)(Сбер|сбер|СБЕР|[Тт]инько)/'
    ];
    public $rate_patterns = [
        // маска захватывает символы до и после курса, чтобы убедиться, что мы не попали на часть номера телефона, суммы или других чисел
        "dollar"      => '/\D{2}[678](\d$|\d\D{2}|\d([\.\,]\d?)\d$|\d([\.\,]\d*)\D{2})|[678][0-9]([\.\,]\d{0,2})?-[678][0-9]([\.\,]\d{0,2})?/',
        // пока евро одинаковый с долларом
        "euro"        => '/\D{2}[678](\d$|\d\D{2}|\d([\.\,]\d?)\d$|\d([\.\,]\d*)\D{2})|[678][0-9]([\.\,]\d{0,2})?-[678][0-9]([\.\,]\d{0,2})?/', 
        "hrn"         => '/(\D[-\s\(\)][12]([\.\,]\d{0,2})(\d$|\D\D))|[12]([\.\,]\d{1,2})?\s?-\s?[12]([\.\,]\d{0,2})?/',
        "cashless"    => '/(1[\s]?[к\:х\*\/][\s]?1)|(\d+[\.\,])?\d+\s?\%/'
    ];

    public $rate_digit_pattern = '/\d*[\.\,]?\d+/';

}