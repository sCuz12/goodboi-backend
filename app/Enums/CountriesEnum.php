<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class CountriesEnum extends Enum
{
    const COUNTRIES = [
        [
            "id" => 1,
            "name" => "Cyprus",
            "code" => "CY",
        ],
        [
            "id" => 2,
            "name" => "Greece",
            "code" => "GR",
        ]
    ];
}
