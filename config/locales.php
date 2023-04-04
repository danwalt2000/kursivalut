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

            "1154050282" => ["id" => "obmenkadn",             "time" => "everyTenMinutes", "domain" => "tg"],     // 10
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
            "1304227894" => ["id" => "obmenka_lugansk",       "time" => "everyTenMinutes", "domain" => "tg"],     // 10
            "1510326304" => ["id" => "obmennik_lnr",       "time" => "everyTenMinutes", "domain" => "tg"],        // 10
            "1776390569" => ["id" => "obmenLPRobnal",       "time" => "everyTenMinutes", "domain" => "tg"],       // 10
            "1780966414" => ["id" => "obmenka_val",       "time" => "everyTenMinutes", "domain" => "tg"],         // 10
            "1629996803" => ["id" => "obmen_lugansk_obmen",   "time" => "everyFifteenMinutes", "domain" => "tg"], // 15
            "1789001285" => ["id" => "valuta_lugansk",   "time" => "everyFifteenMinutes", "domain" => "tg"],      // 15
            "1643215722" => ["id" => "obmenka_dnr_lnr",   "time" => "everyFifteenMinutes", "domain" => "tg"]      // 15
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
            "1699506983" => ["id" => "mlt_obnal",             "time" => "everyTenMinutes", "domain" => "tg"],      // 10
            "1600113833" => ["id" => "obnal_mlt_ua",          "time" => "everyTenMinutes", "domain" => "tg"],      // 10
            "1809540638" => ["id" => "obmengenichesk",        "time" => "everyFifteenMinutes", "domain" => "tg"],  // 15
        ]
    ],
    'berdyansk'       => [
        'title'          => 'Бердянск',
        'name'           => 'berdyansk',
        'domain'         => 'valuta-dn',
        'h1_keyword'     => ' в Бердянске и Запорожской области',
        'currencies'     => ['dollar', 'euro', 'hrn'],
        'publics'        => [
            "1751133458" => ["id" => "valutaBrd",          "time" => "everyTenMinutes", "domain" => "tg"],         // 10
            "1579972943" => ["id" => "obmen_berdyansk",    "time" => "everyTenMinutes", "domain" => "tg"],         // 10
        ]
    ],
    'minsk'       => [
        'title'          => 'Минск',
        'name'           => 'minsk',
        'domain'         => 'valuta-dn',
        'h1_keyword'     => ' в Минске и Республике Беларусь',
        'currencies'     => ['dollar', 'euro', 'hrn', 'cashless'],
        'publics'        => [
            "1661599578" => ["id" => "obmen_rb", "time" => "everyTenMinutes", "domain" => "tg"],                 // 10
            "1656368659" => ["id" => "obmenvrb", "time" => "everyTenMinutes", "domain" => "tg"],                 // 10
            "1774846066" => ["id" => "exchange_minsk", "time" => "everyTenMinutes", "domain" => "tg"],           // 10
            "1537031622" => ["id" => "obmen_rb_minsk1", "time" => "everyTenMinutes", "domain" => "tg"],          // 10
            "1768008123" => ["id" => "EXCHANGE_BY", "time" => "everyTenMinutes", "domain" => "tg"],              // 10
            "1285340951" => ["id" => "obmen_minsk", "time" => "everyTenMinutes", "domain" => "tg"],              // 10
            "1677945470" => ["id" => "obmenbtcrb", "time" => "everyTenMinutes", "domain" => "tg"],               // 10
            "1765384254" => ["id" => "chatProkopovich", "time" => "everyTenMinutes", "domain" => "tg"],          // 10
            "1733494082" => ["id" => "obmen_belarus", "time" => "everyFifteenMinutes", "domain" => "tg"],        // 15
            "1393592627" => ["id" => "obmenrbrf", "time" => "everyFifteenMinutes", "domain" => "tg"],            // 15
        ]
    ],
];
