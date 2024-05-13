<?php

namespace App\Enum;

enum CookieJarItemsEnum: string
{
    case PIZZA = 'Base.Pizza';
    case POP = 'Base.Pop';
    case BAGUETTE = 'Base.Baguette';
    case CROISSANT = 'Base.Croissant';
    case COOKIE1 = 'Base.CookiesChocolate';
    case COOKIE2 = 'Base.CookieChocolateChip';
    case DOUGHNUT = 'Base.DoughnutPlain';

    // Returns all cases of the enum
    public static function listItems(): array
    {
        return array_column(self::cases(), 'name');
    }

    // Returns a random case of the enum
    public static function randomItem(): string
    {
        $cases = self::cases();
        return $cases[array_rand($cases)]->value;
    }
}