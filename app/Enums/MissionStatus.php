<?php

namespace App\Enums;

enum MissionStatus: string
{
    case ORDERED = 'ordered';
    case PACKED = 'packed';
    case IN_TRANSIT = 'inTransit';
    case DELIVERED = 'delivered';

    public function label(): string
    {
        return match ($this) {
            self::ORDERED => 'Ordered',
            self::PACKED => 'Packed',
            self::IN_TRANSIT => 'In Transit',
            self::DELIVERED => 'Delivered',
        };
    }

    public function color(): string
    {
        // Background colors based on our Figma designs
        return match ($this) {
            self::ORDERED => '#F7D579', // Oriole Yellow
            self::PACKED => '#F5A97B', // Sunset Peach 
            self::IN_TRANSIT => '#99D5E4', // Oceano Blue
            self::DELIVERED => '#C6E293', // Spring Lawn Green
        };
    }
}
