<?php

namespace App\Enum;

enum DureeJDS: string
{
    case TRES_COURT = 'moins_10';
    case COURT = '10_20';
    case MOYEN = '20_60';
    case LONG = '60_120';
    case TRES_LONG = 'plus_120';
}