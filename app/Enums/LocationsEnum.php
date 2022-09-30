<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class LocationsEnum extends Enum
{
    /* Notes Cities Ids - see CitiesEnum
    * 1 = Nicosia
    * 2 = Limassol 
    * 3 = Paphos
    * 4 = Larnaca 
    * 5 = Farmacousta

    */
    const Locations = [
        [
            "id" => 1,
            "name" => "Strovolos",
            "city_id" => 1,
        ],
        [
            "id" => 2,
            "name" => "Lakatamia",
            "city_id" => 1,
        ],
        [
            "id" => 3,
            "name" => "Aglatzia",
            "city_id" => 1,
        ],
        [
            "id" => 4,
            "name" => "Engomi",
            "city_id" => 1,
        ],
        [
            "id" => 5,
            "name" => "Agios Dometios",
            "city_id" => 1,
        ],
        [
            "id" => 6,
            "name" => "Kaimakli",
            "city_id" => 1,
        ],
        [
            "id" => 7,
            "name" => "Latsia",
            "city_id" => 1,
        ],
        [
            "id" => 9,
            "name" => "Dali",
            "city_id" => 1,
        ],
        [
            "id" => 10,
            "name" => "Downtown",
            "city_id" => 1,
        ],

        //Limassol 
        [
            "id" => 11,
            "name" => "Agios Antonios",
            "city_id" => 2,
        ],
        [
            "id" => 12,
            "name" => "Agia Triada",
            "city_id" => 2,
        ],
        [
            "id" => 13,
            "name" => "Agios Georgios",
            "city_id" => 2,
        ],
        [
            "id" => 14,
            "name" => "Agia Fyla",
            "city_id" => 2,
        ],
        [
            "id" => 15,
            "name" => "Agios Nektarios",
            "city_id" => 2,
        ],
        [
            "id" => 16,
            "name" => "Agios Nicolaos",
            "city_id" => 2,
        ],
        [
            "id" => 16,
            "name" => "Agios Spyridon",
            "city_id" => 2,
        ],
        [
            "id" => 17,
            "name" => "Ekali",
            "city_id" => 2,
        ],
        //TODO: Add more cities for limassol

        //Paphos 
        [
            "name" => "Agios Theodoros ",
            "city_id" => 3
        ],
        [
            "name" => "Agios Pavlos",
            "city_id" => 3
        ],

        //TODO: add more cities for Paphos

        //Larnaca
        [
            "name" => "Kamares",
            "city_id" => 4
        ],
        [
            "name" => "Kokkines",
            "city_id" => 4
        ],
        //TODO: add more cities for Larnaca

        //Famagusta
        [
            "name" => "Agia napa",
            "city_id" => 5
        ],
        [
            "name" => "Paralimni",
            "city_id" => 5
        ],
        [
            "name" => "Protaras",
            "city_id" => 5
        ],
        //TODO: add more cities for Famagusta
        [
            "name" => "Avgorou",
            "city_id" => 5
        ]


    ];
}
