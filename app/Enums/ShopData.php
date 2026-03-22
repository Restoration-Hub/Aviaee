<?php

namespace App\Enums;

// Enum for shop data, including ID, label, and address. 
// This is used for testing - later will be replaced with dynamic data from the db
enum ShopData: string
{
    case SHOP1 = '123 Main St, City, State 12345';
    case SHOP2 = '456 Oak Ave, City, State 12345';
    case SHOP3 = '789 Pine Rd, City, State 12345';
    case SHOP4 = '321 Elm St, City, State 12345';

    public function label(): string
    {
        return match ($this) {
            self::SHOP1 => 'Shop 1',
            self::SHOP2 => 'Shop 2',
            self::SHOP3 => 'Shop 3',
            self::SHOP4 => 'Shop 4',
        };
    }

    public function id(): int
    {
        return match ($this) {
            self::SHOP1 => 1,
            self::SHOP2 => 2,
            self::SHOP3 => 3,
            self::SHOP4 => 4,
        };
    }

    public function address(): string
    {
        return match ($this) {
            self::SHOP1 => '123 Main St, City, State 12345',
            self::SHOP2 => '456 Oak Ave, City, State 12345',
            self::SHOP3 => '789 Pine Rd, City, State 12345',
            self::SHOP4 => '321 Elm St, City, State 12345',
        };
    }
}
