<?php

declare(strict_types=1);

namespace App\Enums;

enum DistrictRegion: string
{
    case DHAKA = 'Dhaka Division';
    case CHITTAGONG = 'Chittagong Division';
    case RAJSHAHI = 'Rajshahi Division';
    case KHULNA = 'Khulna Division';
    case BARISHAL = 'Barishal Division';
    case SYLHET = 'Sylhet Division';
    case RANGPUR = 'Rangpur Division';
    case MYMENSINGH = 'Mymensingh Division';
}
