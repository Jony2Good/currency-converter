<?php

namespace App\HTTP\Service;

enum CountryCentralBank
{
    case Russia;
    case Thailand;

    public function getCountryCentralBank(): string
    {
        return match ($this) {
            CountryCentralBank::Russia => 'http://www.cbr.ru/scripts/XML_daily.asp',
            CountryCentralBank::Thailand => 'https://apigw1.bot.or.th/bot/public/Stat-ExchangeRate/v2/DAILY_AVG_EXG_RATE/?start_period='. date("Y-m-d") . '&end_period=' . date("Y-m-d"),
        };
    }

}
