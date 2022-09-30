<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class DogListingStatuses extends Enum
{
    const INACTIVE  = 0;
    const ACTIVE    = 1;
    const DELETED   = 2;
}
