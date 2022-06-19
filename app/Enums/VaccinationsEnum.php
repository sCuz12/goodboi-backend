<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class VaccinationsEnum extends Enum
{
    const VACCINATIONS = [
        [
            "id" => 1,
            "name" => "Rabies",
            "code" => "RA",
        ],
        [
            "id" => 2,
            "name" => "Parvovirus",
            "code" => "PA",
        ],
        [
            "id" => 3,
            "name" => "Distemper",
            "code" => "DI",
        ],
        [
            "id" => 4,
            "name" => "Canine Hepatitis",
            "code" => "CH",
        ]
    ];
}
