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
        'publics'        => [
            "obmenvalut_donetsk"    => ["id" => "-87785879",  "time" => "everyFiveMinutes", "domain" => "vk"],    // 5
            "obmen_valut_donetsk"   => ["id" => "-92215147",  "time" => "everyFiveMinutes", "domain" => "vk"],    // 5
            "obmenvalyut_dpr"       => ["id" => "-153734109", "time" => "everyThirtyMinutes", "domain" => "vk"],  // 30
            "club156050748"         => ["id" => "-156050748", "time" => "everyThirtyMinutes", "domain" => "vk"],  // 30
            "kursvalut_donetsk"     => ["id" => "-63859238",  "time" => "everyThirtyMinutes", "domain" => "vk"],  // 30
            "obmen_valut_dnr"       => ["id" => "-193547744", "time" => "hourly", "domain" => "vk"],              // 60
            "donetsk_obmen_valyuta" => ["id" => "-174075254", "time" => "hourly", "domain" => "vk"],              // 60
            "obmenvalut_dnr"        => ["id" => "-172375183", "time" => "hourly", "domain" => "vk"],              // 60
            "valutoobmen"           => ["id" => "-75586957",  "time" => "hourly", "domain" => "vk"],              // 60

            "1154050282" => ["id" => "obmenkadn",             "time" => "everyFiveMinutes", "domain" => "tg"],    // 5
            "1161871204" => ["id" => "obmenkadonetck",        "time" => "everyTenMinutes", "domain" => "tg"],     // 10
            "1345575332" => ["id" => "obmen_valut_donetsk_1", "time" => "everyTenMinutes", "domain" => "tg"],     // 10
            "1265653325" => ["id" => "obmenvalutdon",         "time" => "everyFifteenMinutes", "domain" => "tg"], // 15
            "1295018924" => ["id" => "obmen77market",         "time" => "everyFifteenMinutes", "domain" => "tg"], // 15
            "1204646240" => ["id" => "valut_don",             "time" => "everyThirtyMinutes", "domain" => "tg"],  // 30
        ]
    ],
    'lugansk'       => [
        'title'          => 'Луганск',
        'name'           => 'lugansk',
        'domain'         => 'valuta-dn',
        'h1_keyword'     => ' в Луганске и ЛНР',
        'currencies'     => ['dollar', 'euro', 'hrn', 'cashless'],
        'publics'        => [
            "1304227894" => ["id" => "obmenka_lugansk",       "time" => "everyTenMinutes", "domain" => "tg"],    // 5
            "1629996803" => ["id" => "obmen_lugansk_obmen",   "time" => "everyTenMinutes", "domain" => "tg"]     // 10
        ]
    ],
    'mariupol'       => [
        'title'          => 'Мариуполь',
        'name'           => 'mariupol',
        'domain'         => 'valuta-dn',
        'h1_keyword'     => ' в Мариуполе и ДНР',
        'currencies'     => ['dollar', 'euro', 'hrn'],
        'publics'        => [
            "obmenvalut_mariupol"    => ["id" => "-212955319",  "time" => "everyThirtyMinutes", "domain" => "vk"],  // 30

            "1650543563" => ["id" => "obmenmrpl",             "time" => "everyTenMinutes", "domain" => "tg"],       // 10
            "1784051014" => ["id" => "obmenmariupolya",       "time" => "everyThirtyMinutes", "domain" => "tg"],    // 30
        ]
    ],
    'melitopol'       => [
        'title'          => 'Мелитополь',
        'name'           => 'melitopol',
        'domain'         => 'valuta-dn',
        'h1_keyword'     => ' в Мелитополе и Запорожской области',
        'currencies'     => ['dollar', 'euro', 'hrn'],
        'publics'        => [
            "1699506983" => ["id" => "mlt_obnal",             "time" => "everyFiveMinutes", "domain" => "tg"],    // 5
            "1600113833" => ["id" => "obnal_mlt_ua",          "time" => "everyTenMinutes", "domain" => "tg"],    // 10
            "1809540638" => ["id" => "obmengenichesk",        "time" => "everyFifteenMinutes", "domain" => "tg"],    // 10
        ]
    ],
    'berdyansk'       => [
        'title'          => 'Бердянск',
        'name'           => 'berdyansk',
        'domain'         => 'valuta-dn',
        'h1_keyword'     => ' в Бердянске и Запорожской области',
        'currencies'     => ['dollar', 'euro', 'hrn'],
        'publics'        => [
            "1751133458" => ["id" => "valutaBrd",          "time" => "everyFiveMinutes", "domain" => "tg"],    // 5
            "1579972943" => ["id" => "obmen_berdyansk",    "time" => "everyTenMinutes", "domain" => "tg"],     // 10
        ]
    ],
];
