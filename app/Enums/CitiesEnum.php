<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class CitiesEnum extends Enum
{
    const CITIES = [
        [
            "id" => 1,
            "name" => "Nicosia",
            "country_id" => 1,
        ],
        [
            "id" => 2,
            "name" => "Limassol",
            "country_id" => 1
        ],
        [
            "id" => 3,
            "name" => "Paphos",
            "country_id" => 1
        ],
        [
            "id" => 4,
            "name" => "Larnaca",
            "country_id" => 1
        ],
        [
            "id" => 5,
            "name" => "Famagusta",
            "country_id" => 1
        ],
        [
            "id" => 6,
            "name" => "Athens",
            "country_id" => 2
        ],
        [
            "id" => 7,
            "name" => "Salonica",
            "country_id" => 2
        ],
        [
            "id" => 8,
            "name" => "Nafplion",
            "country_id" => 2
        ],

    ];
}
