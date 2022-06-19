<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class DogListingStatusesEnum extends Enum
{
    const DOG_LISTING_STATUSES = [

        [
            "id" => 1,
            "name" => "active",
        ],
        [
            "id" => 2,
            "name" => "adopted",
        ],
        [
            "id" => 3,
            "name" => "deleted",

        ],
        [
            "id" => 4,
            "name" => "expired",
        ],
        [
            "id" => 5,
            "name" => "disabled",
        ],
    ];
}
