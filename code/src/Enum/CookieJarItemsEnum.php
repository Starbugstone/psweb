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
    case MATCHES = 'Base.Matches';
    case BOILED_EGG = 'Base.EggBoiled';
    case DOG_FOOD = 'Base.Dogfood';
    case RADISH = 'farming.RedRadish';
    case BEER = 'Base.BeerCan';
    case JUICEBOX = 'Base.JuiceBox';
    case CHIPS = 'Base.Crisps';
    case PLONKIES = 'Base.Plonkies';
    case JELLYBEANS = 'Base.JellyBeans';
    case HOTDOG = 'Base.Hotdog';
    case STERILLIZED_RAG = 'Base.AlcoholRippedSheets';
    case BOURBON = 'Base.WhiskeyFull';
    case COMIC_BOOK = 'Base.ComicBook';
    case SPECIAL_RUBBER_DUCK = 'Base.Rubberducky2';

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