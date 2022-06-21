<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class CoverImagesPathEnum extends Enum
{
    const USERS = "/images/cover_images/profiles";
    const LISTINGS = "/images/cover_images/listings";

    const ALLOWED_TYPES = [
        "users", "listings"
    ];
}
