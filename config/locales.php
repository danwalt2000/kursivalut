<?php

use Illuminate\Support\Facades\Facade;
use Illuminate\Http\Request;

return [
    'donetsk'       => [
        'title'          => 'Донецк',
        'name'           => 'donetsk',
        'domain'         => 'valuta-dn',
        'h1_keyword'     => ' в Донецке и ДНР',
        'currencies'     => ['dollar', 'euro', 'hrn', 'cashless'],
        'rate_currencies'=> ['dollar', 'euro', 'hrn'],
        'show_rates'     => true,
        'metrika'        => '90961172',
        // 'yandex-ad'      => '2593677',
        'vk'             => [
            "obmenvalut_donetsk"    => ["id" => "-87785879",  "time" => "everyFiveMinutes"],    // 5
            "obmen_valut_donetsk"   => ["id" => "-92215147",  "time" => "everyFiveMinutes"],    // 5
            "obmenvalyut_dpr"       => ["id" => "-153734109", "time" => "everyThirtyMinutes"],  // 30
            "club156050748"         => ["id" => "-156050748", "time" => "everyThirtyMinutes"],  // 30
            "kursvalut_donetsk"     => ["id" => "-63859238",  "time" => "everyThirtyMinutes"],  // 30
            "obmen_valut_dnr"       => ["id" => "-193547744", "time" => "hourly"],              // 60
            "donetsk_obmen_valyuta" => ["id" => "-174075254", "time" => "hourly"],              // 60
            "obmenvalut_dnr"        => ["id" => "-172375183", "time" => "hourly"],              // 60
            "valutoobmen"           => ["id" => "-75586957",  "time" => "hourly"]               // 60
        ],
        'tg'             => [
            "1154050282" => ["id" => "obmenkadn",             "time" => "everyTenMinutes"],     // 10
            "1649134439" => ["id" => "obmenvDNR",             "time" => "everyTenMinutes"],     // 10
            "1161871204" => ["id" => "obmenkadonetck",        "time" => "everyTenMinutes"],     // 10
            "1345575332" => ["id" => "obmen_valut_donetsk_1", "time" => "everyTenMinutes"],     // 10
            "1265653325" => ["id" => "obmenvalutdon",         "time" => "everyFifteenMinutes"], // 15
            "1295018924" => ["id" => "obmen77market",         "time" => "everyFifteenMinutes"], // 15  
            "1204646240" => ["id" => "valut_don",             "time" => "everyThirtyMinutes"],  // 30
            "1684913683" => ["id" => "retrust_dnr",             "time" => "everyThirtyMinutes"],  // 30
        ]
    ],
    'lugansk'       => [
        'title'          => 'Луганск',
        'name'           => 'lugansk',
        'domain'         => 'valuta-dn',
        'h1_keyword'     => ' в Луганске и ЛНР',
        'currencies'     => ['dollar', 'euro', 'hrn', 'cashless'],
        'rate_currencies'=> ['dollar', 'hrn'],
        'show_rates'     => true,
        'metrika'        => '93171078',
        // 'yandex-ad'      => '2593860',
        'vk'             => [],
        'tg'             => [
            "1304227894" => ["id" => "obmenka_lugansk",       "time" => "everyTenMinutes"],     // 10
            "1510326304" => ["id" => "obmennik_lnr",       "time" => "everyTenMinutes"],        // 10
            "1776390569" => ["id" => "obmenLPRobnal",       "time" => "everyTenMinutes"],       // 10
            "1780966414" => ["id" => "obmenka_val",       "time" => "everyTenMinutes"],         // 10
            "1629996803" => ["id" => "obmen_lugansk_obmen",   "time" => "everyFifteenMinutes"], // 15  
            "1789001285" => ["id" => "valuta_lugansk",   "time" => "everyFifteenMinutes"],      // 15
            "1643215722" => ["id" => "obmenka_dnr_lnr",   "time" => "everyFifteenMinutes"]      // 15
            "1471055481" => ["id" => "ObmenValut_Lugansk",   "time" => "everyFifteenMinutes"]      // 15
            "1824762799" => ["id" => "obmenik_lugansk",   "time" => "everyFifteenMinutes"]      // 15
        ]
    ],
    'mariupol'       => [
        'title'          => 'Мариуполь',
        'name'           => 'mariupol',
        'domain'         => 'valuta-dn',
        'h1_keyword'     => ' в Мариуполе',
        'currencies'     => ['dollar', 'euro', 'hrn'],
        'rate_currencies'=> ['dollar', 'hrn'],
        'show_rates'     => false,
        'metrika'        => '93171171',
        // 'yandex-ad'      => '2671372',
        'vk'             => [
            "obmenvalut_mariupol"    => ["id" => "-212955319",  "time" => "everyThirtyMinutes"],  // 30
        ],
        'tg'             => [
            "1650543563" => ["id" => "obmenmrpl",             "time" => "everyTenMinutes"],       // 10
            "1784051014" => ["id" => "obmenmariupolya",       "time" => "everyThirtyMinutes"],    // 30
            "1689436376" => ["id" => "obmenvalut_mariupol",       "time" => "everyThirtyMinutes"],    // 30
            "1946011018" => ["id" => "obmenmar",       "time" => "everyThirtyMinutes"],    // 30
            "1765663345" => ["id" => "obmen_don",       "time" => "everyThirtyMinutes"],    // 30
        ]
    ],
    'melitopol'       => [
        'title'          => 'Мелитополь',
        'name'           => 'melitopol',
        'domain'         => 'valuta-dn',
        'h1_keyword'     => ' в Мелитополе и Запорожской области',
        'currencies'     => ['dollar', 'euro', 'hrn'],
        'rate_currencies'=> ['dollar', 'hrn'],
        'show_rates'     => true,
        'metrika'        => '93171182',
        // 'yandex-ad'      => '2671451',
        'vk'             => [],
        'tg'             => [
            "1699506983" => ["id" => "mlt_obnal",             "time" => "everyTenMinutes"],      // 10
            "1600113833" => ["id" => "obnal_mlt_money",        "time" => "everyFifteenMinutes"],  // 15    
            "1809540638" => ["id" => "obmengenichesk",        "time" => "everyFifteenMinutes"],  // 15    
        ]
    ],
    'berdyansk'       => [
        'title'          => 'Бердянск',
        'name'           => 'berdyansk',
        'domain'         => 'valuta-dn',
        'h1_keyword'     => ' в Бердянске',
        'currencies'     => ['dollar', 'euro', 'hrn'],
        'rate_currencies'=> ['dollar', 'hrn'],
        'show_rates'     => true,
        'metrika'        => '93170908',
        // 'yandex-ad'      => '2671495',
        'vk'             => [],
        'tg'             => [
            "1751133458" => ["id" => "valutaBrd",          "time" => "everyTenMinutes"],         // 10
            "1579972943" => ["id" => "obmen_berdyansk",    "time" => "everyTenMinutes"],         // 10 
        ],
        
    ],
    'minsk'       => [
        'title'          => 'Минск',
        'name'           => 'minsk',
        'domain'         => 'valuta-dn',
        'h1_keyword'     => ' в Минске и Республике Беларусь',
        'currencies'     => ['dollar', 'euro', 'hrn', 'cashless'],
        'rate_currencies'=> ['dollar', 'euro', 'hrn'],
        'show_rates'     => false,
        'metrika'        => '93171189',
        // 'yandex-ad'      => '2671514',
        'vk'             => [],
        'tg'             => [
            "1661599578" => ["id" => "obmen_rb", "time" => "everyThirtyMinutes"],                 // 15 
            "1656368659" => ["id" => "obmenvrb", "time" => "everyThirtyMinutes"],                 // 15
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
    'moscow'       => [
        'title'          => 'Москва',
        'name'           => 'moscow',
        'domain'         => 'kursivalut',
        'h1_keyword'     => ' в Москве',
        'currencies'     => ['dollar', 'euro'],
        'rate_currencies'=> ['dollar', 'euro'],
        'show_rates'     => true,
        'metrika'        => '93171361',
        // 'yandex-ad'      => '2671563',
        'vk'             => [],
        'tg'             => [
            "1422420951" => ["id" => "Obmen_moskvaa", "time" => "everyThirtyMinutes"],                 // 30
            "1406118239" => ["id" => "obmenvmsk", "time" => "everyThirtyMinutes"],                 // 30
            "1460221844" => ["id" => "currency_exchanges", "time" => "everyThirtyMinutes"],        // 30
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
        'show_rates'     => false,
        'metrika'        => '93171389',
        // 'yandex-ad'      => '2671579',
        'vk'             => [],
        'tg'             => [
            "1635308592" => ["id" => "obmen_valutROS", "time" => "everyThirtyMinutes"],                 // 30
            "1282408554" => ["id" => "Rostovobmen", "time" => "everyThirtyMinutes"],                 // 30
            "1635743092" => ["id" => "obmen_rostov", "time" => "everyThirtyMinutes"],                 // 30 
            "1741293290" => ["id" => "obmendonchat", "time" => "everyThirtyMinutes"],                 // 30
            "1604607517" => ["id" => "exchange_rostov", "time" => "everyThirtyMinutes"],              // 30
        ]
    ],
    'krym'       => [
        'title'          => 'Севастополь и Крым',
        'name'           => 'krym',
        'domain'         => 'kursivalut',
        'h1_keyword'     => ' в Севастополе и Крыму',
        'currencies'     => ['dollar', 'euro', 'hrn'],
        'rate_currencies'=> ['dollar', 'hrn'],
        'show_rates'     => true,
        'metrika'        => '93171377',
        // 'yandex-ad'      => '2671597',
        'vk'             => [],
        'tg'             => [
            "1520647319" => ["id" => "krymex", "time" => "everyThirtyMinutes"],                 // 30
            "1419501182" => ["id" => "obmenvkrym", "time" => "everyThirtyMinutes"],                 // 30
            "1695691631" => ["id" => "obmen_92", "time" => "everyThirtyMinutes"],                 // 30 
            // "1798727074" => ["id" => "Exchange_Crimea", "time" => "everyThirtyMinutes"],         // 30  
            // "1775797250" => ["id" => "obmen9282", "time" => "everyThirtyMinutes"],                 // 30
            // "1662030913" => ["id" => "Qrimex", "time" => "everyThirtyMinutes"],                 // 30
        ]
    ],
    'krasnodar'       => [
        'title'          => 'Краснодар',
        'name'           => 'krasnodar',
        'domain'         => 'kursivalut',
        'h1_keyword'     => ' в Краснодаре',
        'currencies'     => ['dollar', 'euro', 'hrn'],
        'rate_currencies'=> ['dollar', 'euro', 'hrn'],
        'show_rates'     => false,
        'metrika'        => '93171402',
        // 'yandex-ad'      => '2671600',
        'vk'             => [],
        'tg'             => [
            "1664166871" => ["id" => "obmennikkrasnodar", "time" => "everyThirtyMinutes"],                 // 30
            "1756305590" => ["id" => "exchange_krasnodar", "time" => "everyThirtyMinutes"],                 // 30
            // "1695691631" => ["id" => "obmen_92", "time" => "everyThirtyMinutes"],                 // 30
        ]
    ],
];
