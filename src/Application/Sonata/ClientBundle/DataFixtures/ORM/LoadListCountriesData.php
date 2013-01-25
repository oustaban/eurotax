<?php

namespace Application\Sonata\ClientBundle\DataFixtures\ORM;

use Application\Sonata\ClientBundle\DataFixtures\AbstractLoadListData;
use Application\Sonata\ClientBundle\Entity\ListCountries;


class LoadListCountriesData extends AbstractLoadListData
{
    /**
     * @var string
     */
    protected $_className = 'ListCountries';

    /**
     * @var array
     */
    protected $_lists = array(
        "AF" => "Afghanistan",
        "ZA" => "Afrique du Sud",
        "AL" => "Albanie",
        "DZ" => "Algérie",
        "DE" => "Allemagne",
        "AD" => "Andorre",
        "AO" => "Angola",
        "AI" => "Anguilla",
        "AG" => "Antigua-et-Barbuda",
        "AN" => "Antilles néerlandaises",
        "SA" => "Arabie saoudite",
        "AR" => "Argentine",
        "AM" => "Arménie",
        "AW" => "Aruba",
        "AU" => "Australie",
        "AT" => "Autriche",
        "AZ" => "Azerbaïdjan",
        "BS" => "Bahamas",
        "BH" => "Bahreïn",
        "BD" => "Bangladesh",
        "BB" => "Barbade",
        "BY" => "Bélarus",
        "BE" => "Belgique",
        "BZ" => "Belize",
        "BJ" => "Bénin",
        "BM" => "Bermudes",
        "BT" => "Bhoutan",
        "BO" => "Bolivie",
        "BA" => "Bosnie-Herzégovine",
        "BW" => "Botswana",
        "BR" => "Brésil",
        "BN" => "Brunéi Darussalam",
        "BG" => "Bulgarie",
        "BF" => "Burkina Faso",
        "BI" => "Burundi",
        "CM" => "Cameroun",
        "CA" => "Canada",
        "CV" => "Cap-Vert",
        "CL" => "Chili",
        "CN" => "Chine",
        "CY" => "Chypre",
        "CO" => "Colombie",
        "KM" => "Comores",
        "CG" => "Congo-Brazzaville",
        "KP" => "Corée du Nord",
        "KR" => "Corée du Sud",
        "CR" => "Costa Rica",
        "CI" => "Côte d’Ivoire",
        "HR" => "Croatie",
        "CU" => "Cuba",
        "DK" => "Danemark",
        "DJ" => "Djibouti",
        "DM" => "Dominique",
        "EG" => "Égypte",
        "SV" => "El Salvador",
        "AE" => "Émirats arabes unis",
        "EC" => "Équateur",
        "ER" => "Érythrée",
        "ES" => "Espagne",
        "EE" => "Estonie",
        "VA" => "État de la Cité du Vatican",
        "FM" => "États fédérés de Micronésie",
        "US" => "États-Unis",
        "ET" => "Éthiopie",
        "FJ" => "Fidji",
        "FI" => "Finlande",
        "FR" => "France",
        "GA" => "Gabon",
        "GM" => "Gambie",
        "GE" => "Géorgie",
        "GH" => "Ghana",
        "GI" => "Gibraltar",
        "GR" => "Grèce",
        "GD" => "Grenade",
        "GL" => "Groenland",
        "GP" => "Guadeloupe",
        "GT" => "Guatemala",
        "GN" => "Guinée",
        "GQ" => "Guinée équatoriale",
        "GW" => "Guinée-Bissau",
        "GY" => "Guyana",
        "GF" => "Guyane française",
        "HT" => "Haïti",
        "HN" => "Honduras",
        "HU" => "Hongrie",
        "KY" => "Îles Caïmans",
        "FO" => "Îles Féroé",
        "FK" => "Îles Malouines",
        "MP" => "Îles Mariannes du Nord",
        "MH" => "Îles Marshall",
        "SB" => "Îles Salomon",
        "TC" => "Îles Turks et Caïques",
        "VG" => "Îles Vierges britanniques",
        "VI" => "Îles Vierges des États-Unis",
        "IN" => "Inde",
        "ID" => "Indonésie",
        "IQ" => "Irak",
        "IR" => "Iran",
        "IE" => "Irlande",
        "IS" => "Islande",
        "IL" => "Israël",
        "IT" => "Italie",
        "JM" => "Jamaïque",
        "JP" => "Japon",
        "JO" => "Jordanie",
        "KZ" => "Kazakhstan",
        "KE" => "Kenya",
        "KG" => "Kirghizistan",
        "KI" => "Kiribati",
        "KW" => "Koweït",
        "LA" => "Laos",
        "LS" => "Lesotho",
        "LV" => "Lettonie",
        "LB" => "Liban",
        "LR" => "Libéria",
        "LY" => "Libye",
        "LI" => "Liechtenstein",
        "LT" => "Lituanie",
        "LU" => "Luxembourg",
        "MG" => "Madagascar",
        "MY" => "Malaisie",
        "MW" => "Malawi",
        "MV" => "Maldives",
        "ML" => "Mali",
        "MT" => "Malte",
        "MA" => "Maroc",
        "MQ" => "Martinique",
        "MU" => "Maurice",
        "MR" => "Mauritanie",
        "YT" => "Mayotte",
        "MX" => "Mexique",
        "MD" => "Moldavie",
        "MC" => "Monaco",
        "MN" => "Mongolie",
        "ME" => "Monténégro",
        "MS" => "Montserrat",
        "MZ" => "Mozambique",
        "MM" => "Myanmar",
        "NA" => "Namibie",
        "NR" => "Nauru",
        "NP" => "Népal",
        "NI" => "Nicaragua",
        "NE" => "Niger",
        "NG" => "Nigéria",
        "NO" => "Norvège",
        "NC" => "Nouvelle-Calédonie",
        "NZ" => "Nouvelle-Zélande",
        "OM" => "Oman",
        "UG" => "Ouganda",
        "UZ" => "Ouzbékistan",
        "PK" => "Pakistan",
        "PW" => "Palaos",
        "PA" => "Panama",
        "PG" => "Papouasie-Nouvelle-Guinée",
        "PY" => "Paraguay",
        "NL" => "Pays-Bas",
        "PE" => "Pérou",
        "PH" => "Philippines",
        "PN" => "Pitcairn",
        "PL" => "Pologne",
        "PF" => "Polynésie française",
        "PT" => "Portugal",
        "QA" => "Qatar",
        "HK" => "R.A.S. chinoise de Hong Kong",
        "MO" => "R.A.S. chinoise de Macao",
        "CF" => "République centrafricaine",
        "CD" => "République démocratique du Congo",
        "DO" => "République dominicaine",
        "CZ" => "République tchèque",
        "RE" => "Réunion",
        "RO" => "Roumanie",
        "GB" => "Royaume-Uni",
        "RU" => "Russie",
        "RW" => "Rwanda",
        "KN" => "Saint-Kitts-et-Nevis",
        "SM" => "Saint-Marin",
        "PM" => "Saint-Pierre-et-Miquelon",
        "VC" => "Saint-Vincent-et-les Grenadines",
        "SH" => "Sainte-Hélène",
        "LC" => "Sainte-Lucie",
        "WS" => "Samoa",
        "ST" => "Sao Tomé-et-Principe",
        "SN" => "Sénégal",
        "SC" => "Seychelles",
        "SL" => "Sierra Leone",
        "SG" => "Singapour",
        "SK" => "Slovaquie",
        "SI" => "Slovénie",
        "SO" => "Somalie",
        "SD" => "Soudan",
        "LK" => "Sri Lanka",
        "SE" => "Suède",
        "CH" => "Suisse",
        "SR" => "Suriname",
        "SZ" => "Swaziland",
        "SY" => "Syrie",
        "TJ" => "Tadjikistan",
        "TW" => "Taïwan",
        "TZ" => "Tanzanie",
        "TD" => "Tchad",
        "IO" => "Territoire britannique de l'océan Indien",
        "TH" => "Thaïlande",
        "TG" => "Togo",
        "TO" => "Tonga",
        "TT" => "Trinité-et-Tobago",
        "TN" => "Tunisie",
        "TM" => "Turkménistan",
        "TR" => "Turquie",
        "TV" => "Tuvalu",
        "UA" => "Ukraine",
        "UY" => "Uruguay",
        "VU" => "Vanuatu",
        "VE" => "Venezuela",
        "VN" => "Viêt Nam",
        "WF" => "Wallis-et-Futuna",
        "YE" => "Yémen",
        "ZM" => "Zambie",
        "ZW" => "Zimbabwe",
        "CB" => "Cambodge",
        "XA" => "Océanie américaine",
        "XC" => "Ceuta",
        "XC" => "Iles Canaries",
        "XL" => "Melilla",
        "XO" => "Océanie australienne",
        "XP" => "Cisjordanie / Bande de Gaza",
        "XR" => "Régions polaires",
        "XZ" => "Océanie néo-zélandaise",
        "YU" => "Rép. Fédérale de Yougoslavie",
    );

    /**
     * @var array
     */
    protected $_EU = array(
        'DE',
        'AT',
        'BE',
        'BG',
        'CY',
        'DK',
        'ES',
        'EE',
        'FI',
        'FR',
        'GR',
        'HU',
        'IE',
        'IT',
        'LV',
        'LT',
        'LU',
        'MT',
        'MC',
        'NL',
        'PL',
        'PT',
        'CZ',
        'RO',
        'GB',
        'SK',
        'SI',
        'SE',
    );

    /**
     * @var array
     */
    protected $_SEPA = array(
        //EU Zone
        'DE',
        'AT',
        'BE',
        'BG',
        'CY',
        'DK',
        'ES',
        'EE',
        'FI',
        'FR',
        'GR',
        'HU',
        'IE',
        'IT',
        'LV',
        'LT',
        'LU',
        'MT',
        'MC',
        'NL',
        'PL',
        'PT',
        'CZ',
        'RO',
        'GB',
        'SK',
        'SI',
        'SE',
        //non EU Zone
        'IS',
        'LI',
        'NO',
        'CH',
    );

    /**
     * @var array
     */
    protected $_destination = array(
        'DE',
        'AT',
        'BE',
        'BG',
        'CY',
        'DK',
        'ES',
        'EE',
        'FI',
        'GR',
        'HU',
        'IE',
        'IT',
        'LV',
        'LT',
        'LU',
        'MT',
        'MC',
        'NL',
        'PL',
        'PT',
        'CZ',
        'RO',
        'GB',
        'SK',
        'SI',
        'SE',
    );

    /**
     * @param $manager
     */
    public function saveFixtures($manager)
    {
        $class = $this->getClass($manager);

        if ($this->_lists) {

            foreach ($this->_lists as $code => $name) {
                /** @var $entity ListCountries */
                $entity = $manager->getRepository($this->getRepositoryName())->findOneByCode($code);

                if (!$entity) {
                    /** @var $entity ListCountries */
                    $entity = new $class();

                    $entity->setCode($this->upper($code));
                    $entity->setName($name);
                    $entity->setSepa(in_array($entity->getCode(), $this->_SEPA) ? 1 : 0);
                    $entity->setEU(in_array($entity->getCode(), $this->_EU) ? 1 : 0);
                    $entity->setDestination(in_array($entity->getCode(), $this->_destination) ? 1 : 0);

                    $manager->persist($entity);
                    $manager->flush();
                }
            }
        }
    }

    /**
     * @param $value
     * @return string
     */
    public function upper($value)
    {
        return strtoupper($value);
    }
}