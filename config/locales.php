<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Http\Request;

return [
    'donetsk'       => [
        'title'          => 'Донецк',
        'name'           => 'donetsk',
        'domain'         => 'kursivalut',
        'h1_keyword'     => ' в Донецке и ДНР',
        'currencies'     => ['dollar', 'euro', 'hrn'],
        'rate_currencies'=> ['dollar', 'euro', 'hrn'],
        'load_freq'      => '60',                                  // частота загрузки новых объявлений в сек - 1 минута
        'show_rates'     => true,
        'metrika'        => '99071950',
        // 'yandex-ad'      => '2593677',
        'vk'             => [
            "obmenvalut_donetsk"    => ["id" => "-87785879",  "time" => "everyFiveMinutes"],    // 5 BANNED
            // "obmen_valut_donetsk"   => ["id" => "-92215147",  "time" => "everyFiveMinutes"],    // 5
            "obmenvalyut_dpr"       => ["id" => "-153734109", "time" => "everyThirtyMinutes"],  // 30
            "club156050748"         => ["id" => "-156050748", "time" => "everyThirtyMinutes"],  // 30
            "kursvalut_donetsk"     => ["id" => "-63859238",  "time" => "everyThirtyMinutes"],  // 30 BANNED
            "obmen_valut_dnr"       => ["id" => "-193547744", "time" => "hourly"],              // 60 BANNED
            "donetsk_obmen_valyuta" => ["id" => "-174075254", "time" => "hourly"],              // 60 BANNED
            // "obmenvalut_dnr"        => ["id" => "-172375183", "time" => "hourly"],              // 60 BANNED
            
            "moneydonetsk"        => ["id" => "-24635912", "time" => "everyThirtyMinutes"],              // 60 BANNED
            "obmenvalytdon_43586" => ["id" => "-86752029", "time" => "hourly"],              // 60 BANNED
            // "obmenkaclub"        => ["id" => "-141203158", "time" => "hourly"],              // 60 BANNED
            // "obmen_valut_harcyzsk" => ["id" => "-111721499", "time" => "hourly"],              // 60 BANNED
            
            // "valutoobmen"           => ["id" => "-75586957",  "time" => "hourly"]               // 60 неактуально
        ],
        'tg'             => [
            "1154050282" => ["id" => "obmenkadn"],   
            "1161871204" => ["id" => "obmenkadonetck"], 
            "1345575332" => ["id" => "obmen_valut_donetsk_1"], 
            "1265653325" => ["id" => "obmenvalutdon"],
            "1295018924" => ["id" => "obmen77market"], 
            "1204646240" => ["id" => "valut_don"],
            "1684913683" => ["id" => "retrust_dnr"], 
            "1199000277" => ["id" => "obmen_valut_dnr2020"],
            "1824000800" => ["id" => "valutchat_donetsk_lnr"],
        ]
    ],
    'lugansk'       => [
        'title'          => 'Луганск',
        'name'           => 'lugansk',
        'domain'         => 'kursivalut',
        'h1_keyword'     => ' в Луганске и ЛНР',
        'currencies'     => ['dollar', 'euro', 'hrn'],
        'rate_currencies'=> ['dollar', 'hrn'],
        'load_freq'      => '60',
        'show_rates'     => true,
        'metrika'        => '93171078',
        // 'yandex-ad'      => '2593860',
        'vk'             => [],
        'tg'             => [
            "1304227894" => ["id" => "obmenka_lugansk"], 
            "1510326304" => ["id" => "obmennik_lnr"], 
            "1865624683" => ["id" => "obmenik_lnr"], 
            "1886512425" => ["id" => "luganskobmen"], 
            "1776390569" => ["id" => "obmenLPRobnal"], 
            "1780966414" => ["id" => "obmenka_val"], 
            "1629996803" => ["id" => "obmen_lugansk_obmen"], 
            "1789001285" => ["id" => "valuta_lugansk"], 
            "1643215722" => ["id" => "obmenka_dnr_lnr"], 
            "1824762799" => ["id" => "obmenik_lugansk"], 
            "1271652753" => ["id" => "obmen_lugansk_reserv"], 
        ]
    ],
    'mariupol'       => [
        'title'          => 'Мариуполь',
        'name'           => 'mariupol',
        'domain'         => 'kursivalut',
        'h1_keyword'     => ' в Мариуполе',
        'currencies'     => ['dollar', 'euro', 'hrn'],
        'rate_currencies'=> ['dollar', 'hrn'],
        'load_freq'      => '360',
        'show_rates'     => false,
        'metrika'        => '93171171',
        // 'yandex-ad'      => '2671372',
        'vk'             => [
            // "obmenvalut_mariupol"    => ["id" => "-212955319",  "time" => "everyThirtyMinutes"],  // 30
            // "club145372726"    => ["id" => "-",  "time" => "everyThirtyMinutes"],  // 30
        ],
        'tg'             => [
            "1650543563" => ["id" => "obmenmrpl"], 
            "1784051014" => ["id" => "obmenmariupolya"], 
            "1689436376" => ["id" => "obmenvalut_mariupol"], 
            "1946011018" => ["id" => "obmenmar"],
            "1765663345" => ["id" => "obmen_don"], 
        ]
    ],
    'melitopol'       => [
        'title'          => 'Мелитополь',
        'name'           => 'melitopol',
        'domain'         => 'kursivalut',
        'h1_keyword'     => ' в Мелитополе и Запорожской области',
        'currencies'     => ['dollar', 'euro', 'hrn'],
        'rate_currencies'=> ['dollar', 'hrn'],
        'load_freq'      => '360',
        'show_rates'     => true,
        'metrika'        => '93171182',
        // 'yandex-ad'      => '2671451',
        'vk'             => [],
        'tg'             => [
            "1699506983" => ["id" => "mlt_obnal"], 
            "1809540638" => ["id" => "obmengenichesk"], 
        ]
    ],
    'berdyansk'       => [
        'title'          => 'Бердянск',
        'name'           => 'berdyansk',
        'domain'         => 'kursivalut',
        'h1_keyword'     => ' в Бердянске',
        'currencies'     => ['dollar', 'euro', 'hrn'],
        'rate_currencies'=> ['dollar', 'hrn'],
        'load_freq'      => '360',
        'show_rates'     => true,
        'metrika'        => '93170908',
        // 'yandex-ad'      => '2671495',
        'vk'             => [],
        'tg'             => [
            "1579972943" => ["id" => "obmen_berdyansk"], 
            "1282923863" => ["id" => "helpchangefiat"], 
            "1718783064" => ["id" => "obmenvalutberdynsk"], 
        ],
        
    ],
    'moscow'       => [
        'title'          => 'Москва',
        'name'           => 'moscow',
        'domain'         => 'kursivalut',
        'h1_keyword'     => ' в Москве',
        'currencies'     => ['dollar', 'euro'],
        'rate_currencies'=> ['dollar', 'euro'],
        'load_freq'      => '60',
        'show_rates'     => true,
        'metrika'        => '93171361',
        // 'yandex-ad'      => '2671563',
        'vk'             => [],
        'tg'             => [
            "1406118239" => ["id" => "obmenvmsk"], 
            "1460221844" => ["id" => "currency_exchanges"],
            "1683359903" => ["id" => "a7337z"], 
            "1725670916" => ["id" => "sovcomrates_msk"],
            "1861025310" => ["id" => "valuta_R"], 
            "1513641809" => ["id" => "curency_exchange_rf"], 
            "2060147758" => ["id" => "exchangemoneymsk"], 
        ]
    ],
    // 'spb'       => [
    //     'title'          => 'Санкт-Петербург',
    //     'name'           => 'spb',
    //     'domain'         => 'kursivalut',
    //     'h1_keyword'     => ' в Санкт-Петербурге',
    //     'currencies'     => ['dollar', 'euro'],
    //     'publics'        => [
    //         "1482220060" => ["id" => "simkinividimki11", "time" => "everyThirtyMinutes"],           // 30
    //         "1614231511" => ["id" => "exchange_piter", "time" => "everyThirtyMinutes"],             // 30
    //     ]
    // ],
    'rostov'       => [
        'title'          => 'Ростов-на-Дону',
        'name'           => 'rostov',
        'domain'         => 'kursivalut',
        'h1_keyword'     => ' в Ростове-на-Дону и Ростовской области',
        'currencies'     => ['dollar', 'euro', 'hrn'],
        'rate_currencies'=> ['dollar', 'euro', 'hrn'],
        'load_freq'      => '360',
        'show_rates'     => false,
        'metrika'        => '93171389',
        // 'yandex-ad'      => '2671579',
        'vk'             => [],
        'tg'             => [
            "1635308592" => ["id" => "obmen_valutROS"], 
            "1282408554" => ["id" => "Rostovobmen"], 
            "1635743092" => ["id" => "obmen_rostov"],
            "1741293290" => ["id" => "obmendonchat"],
            "1604607517" => ["id" => "exchange_rostov"],
        ]
    ],
    'krym'       => [
        'title'          => 'Севастополь и Крым',
        'name'           => 'krym',
        'domain'         => 'kursivalut',
        'h1_keyword'     => ' в Севастополе и Крыму',
        'currencies'     => ['dollar', 'euro', 'hrn'],
        'rate_currencies'=> ['dollar', 'hrn'],
        'load_freq'      => '120',
        'show_rates'     => true,
        'metrika'        => '93171377',
        // 'yandex-ad'      => '2671597',
        'vk'             => [],
        'tg'             => [
            "1520647319" => ["id" => "krymex"],                 
            "1419501182" => ["id" => "obmenvkrym"],                 
            "1695691631" => ["id" => "obmen_92"],                 
            "1798727074" => ["id" => "Exchange_Crimea"],         
            "1775797250" => ["id" => "obmen9282"],               
            "1662030913" => ["id" => "Qrimex"],                 
        ]
    ],
    'krasnodar'       => [
        'title'          => 'Краснодар',
        'name'           => 'krasnodar',
        'domain'         => 'kursivalut',
        'h1_keyword'     => ' в Краснодаре',
        'currencies'     => ['dollar', 'euro', 'hrn'],
        'rate_currencies'=> ['dollar', 'euro', 'hrn'],
        'load_freq'      => '360',
        'show_rates'     => false,
        'metrika'        => '93171402',
        // 'yandex-ad'      => '2671600',
        'vk'             => [],
        'tg'             => [
            "1664166871" => ["id" => "obmennikkrasnodar"], 
            "1756305590" => ["id" => "exchange_krasnodar"], 
            // "1695691631" => ["id" => "obmen_92", "time" => "everyThirtyMinutes"],                 // 30
        ]
    ],
    'minsk'       => [
        'title'          => 'Минск',
        'name'           => 'minsk',
        'domain'         => 'kursivalut',
        'h1_keyword'     => ' в Минске и Республике Беларусь',
        'currencies'     => ['dollar', 'euro', 'hrn', 'cashless'],
        'rate_currencies'=> ['dollar', 'euro', 'hrn'],
        'load_freq'      => '360',
        'show_rates'     => false,
        'metrika'        => '93171189',
        // 'yandex-ad'      => '2671514',
        'vk'             => [],
        'tg'             => [
            "1661599578" => ["id" => "obmen_rb"],
            "1656368659" => ["id" => "obmenvrb"],
            // "1774846066" => ["id" => "exchange_minsk", "time" => "everyTenMinutes"],           // 10
            // "1537031622" => ["id" => "obmen_rb_minsk1", "time" => "everyTenMinutes"],          // 10
            // "1768008123" => ["id" => "EXCHANGE_BY", "time" => "everyTenMinutes"],              // 10
            // "1285340951" => ["id" => "obmen_minsk", "time" => "everyTenMinutes"],              // 10
            // "1677945470" => ["id" => "obmenbtcrb", "time" => "everyTenMinutes"],               // 10
            // "1765384254" => ["id" => "chatProkopovich", "time" => "everyTenMinutes"],          // 10
            // "1733494082" => ["id" => "obmen_belarus", "time" => "everyFifteenMinutes"],        // 15
            // "1393592627" => ["id" => "obmenrbrf", "time" => "everyFifteenMinutes"],            // 15
        ]
    ],
];
