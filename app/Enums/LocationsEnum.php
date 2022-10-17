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
            "name" => "Strovolos",
            "city_id" => 1,
        ],
        [
            "name" => "Lakatamia",
            "city_id" => 1,
        ],
        [
            "name" => "Aglatzia",
            "city_id" => 1,
        ],
        [
            "name" => "Engomi",
            "city_id" => 1,
        ],
        [
            "name" => "Ayios Dometios",
            "city_id" => 1,
        ],
        [
            "name" => "Kaimakli",
            "city_id" => 1,
        ],
        [
            "name" => "Latsia",
            "city_id" => 1,
        ],
        [
            "name" => "Dali",
            "city_id" => 1,
        ],
        [
            "name" => "City Center",
            "city_id" => 1,
        ],
        [
            "name" => "Ayioi Omoloyites",
            "city_id" => 1,
        ],
        [
            "name" => "Nisou",
            "city_id" => 1,
        ],
        [
            "name" => "Tseri",
            "city_id" => 1,
        ],
        [
            "name" => "Geri",
            "city_id" => 1,
        ],
        [
            "name" => "Kalo Chorio",
            "city_id" => 1,
        ],
        [
            "name" => "Lympia",
            "city_id" => 1,
        ],
        [
            "name" => "Lythrodontas",
            "city_id" => 1,
        ],
        [
            "name" => "Agioi trimithias",
            "city_id" => 1,
        ],
        [
            "name" => "Kokkinotrimithia",
            "city_id" => 1,
        ],
        [
            "name" => "Mammari",
            "city_id" => 1,
        ],
        [
            "name" => "Deftera",
            "city_id" => 1,
        ],
        [
            "name" => "Astromeritis",
            "city_id" => 1,
        ],


        //Limassol 
        [
            "name" => "Ayios Antonios",
            "city_id" => 2,
        ],
        [
            "name" => "Ayios Athanasios",
            "city_id" => 2,
        ],
        [
            "name" => "Ayia Triada",
            "city_id" => 2,
        ],
        [
            "name" => "Ayios Theodoros",
            "city_id" => 2,
        ],
        [
            "name" => "Ayios Georgios",
            "city_id" => 2,
        ],
        [
            "name" => "Ayia Fyla",
            "city_id" => 2,
        ],
        [
            "name" => "Ayios Nektarios",
            "city_id" => 2,
        ],
        [
            "name" => "Ayios Nicolaos",
            "city_id" => 2,
        ],
        [
            "name" => "Ayios Tychonas",
            "city_id" => 2,
        ],
        [
            "name" => "Agios Spyridon",
            "city_id" => 2,
        ],
        [
            "name" => "Armenochori",
            "city_id" => 2,
        ],
        [
            "name" => "Akrotiri",
            "city_id" => 2,
        ],
        [
            "name" => "Ekali",
            "city_id" => 2,
        ],
        [
            "name" => "Trachoni",
            "city_id" => 2,
        ],
        [
            "name" => "Mesa Geitonia",
            "city_id" => 2,
        ],
        [
            "name" => "Germasogia",
            "city_id" => 2,
        ],
        [
            "name" => "Kolossi",
            "city_id" => 2,
        ],
        [
            "name" => "Kato Polemidia",
            "city_id" => 2,
        ],
        [
            "name" => "Ypsonas",
            "city_id" => 2,
        ],
        [
            "name" => "Platres",
            "city_id" => 2,
        ],
        [
            "name" => "Agros",
            "city_id" => 2,
        ],
        [
            "name" => "Molos (City Center)",
            "city_id" => 2,
        ],

        //Paphos 
        [
            "name" => "Ayios Theodoros ",
            "city_id" => 3
        ],
        [
            "name" => "Ayios Pavlos",
            "city_id" => 3
        ],
        [
            "name" => "Ayios Nikolaos",
            "city_id" => 3
        ],
        [
            "name" => "Argaka",
            "city_id" => 3
        ],
        [
            "name" => "Anarita",
            "city_id" => 3
        ],
        [
            "name" => "Axylou",
            "city_id" => 3
        ],
        [
            "name" => "Empa",
            "city_id" => 3
        ],
        [
            "name" => "Fasoula",
            "city_id" => 3
        ],
        [
            "name" => "Geroskipou",
            "city_id" => 3
        ],
        [
            "name" => "Konia",
            "city_id" => 3
        ],
        [
            "name" => "Kouklia",
            "city_id" => 3
        ],
        [
            "name" => "Pegeia",
            "city_id" => 3
        ],
        [
            "name" => "Polis Chrysochous",
            "city_id" => 3
        ],
        [
            "name" => "Tala",
            "city_id" => 3
        ],

        [
            "name" => "Polemi",
            "city_id" => 3
        ],



        //TODO: add more cities for Paphos

        //Larnaca
        [
            "name" => "City Center",
            "city_id" => 4
        ],
        [
            "name" => "Ayia Anna",
            "city_id" => 4
        ],
        [
            "name" => "Ayioi Vavatsinias",
            "city_id" => 4
        ],
        [
            "name" => "Ayios Theodoros",
            "city_id" => 4
        ],
        [
            "name" => "Alaminos",
            "city_id" => 4
        ],
        [
            "name" => "Alethriko",
            "city_id" => 4
        ],
        [
            "name" => "Aradippou",
            "city_id" => 4
        ],
        [
            "name" => "Athienou",
            "city_id" => 4
        ],
        [
            "name" => "Avdellero",
            "city_id" => 4
        ],
        [
            "name" => "Choirokoitia",
            "city_id" => 4
        ],
        [
            "name" => "Dromolaxia",
            "city_id" => 4
        ],
        [
            "name" => "Meneou",
            "city_id" => 4
        ],
        [
            "name" => "Kalavasos",
            "city_id" => 4
        ],
        [
            "name" => "Kato Drys",
            "city_id" => 4
        ],

        [
            "name" => "Kelia",
            "city_id" => 4
        ],
        [
            "name" => "Kamares",
            "city_id" => 4
        ],
        [
            "name" => "Kokkines",
            "city_id" => 4
        ],
        [
            "name" => "Lefkara",
            "city_id" => 4
        ],
        [
            "name" => "Livadia",
            "city_id" => 4
        ],
        [
            "name" => "Dromolaxia",
            "city_id" => 4
        ],
        [
            "name" => "Oroklini",
            "city_id" => 4
        ],
        [
            "name" => "Ormidia",
            "city_id" => 4
        ],
        [
            "name" => "Pyla",
            "city_id" => 4
        ],
        [
            "name" => "Skarinou",
            "city_id" => 4
        ],
        [
            "name" => "Xylofagou",
            "city_id" => 4
        ],
        [
            "name" => "Xylotymbou",
            "city_id" => 4
        ],
        [
            "name" => "Zygi",
            "city_id" => 4
        ],
        [
            "name" => "Vavatsinia",
            "city_id" => 4
        ],
        [
            "name" => "Pervolia",
            "city_id" => 4
        ],


        //Famagusta
        [
            "name" => "Derynia",
            "city_id" => 5
        ],
        [
            "name" => "Akanthou",
            "city_id" => 5
        ],
        [
            "name" => "Ayia napa",
            "city_id" => 5
        ],
        [
            "name" => "Paralimni",
            "city_id" => 5
        ],
        [
            "name" => "Sotira",
            "city_id" => 5
        ],
        [
            "name" => "Avgorou",
            "city_id" => 5
        ],
        [
            "name" => "Peristerona",
            "city_id" => 5
        ],
        [
            "name" => "Liopetri",
            "city_id" => 5
        ]

    ];
}
