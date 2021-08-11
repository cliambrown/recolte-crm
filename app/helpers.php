<?php

/**
 * Parse a url string and return the domain.
 *
 * @param  String  $str
 * @return String
 */
function get_domain($str) {
    if (gettype($str) !== 'string') return '';
    $parsed = parse_url($str);
    if (gettype($parsed) !== 'array' || !isset($parsed['host'])) return '';
    $domain = $parsed['host'];
    if (substr($domain, 0, 4) === 'www.') $domain = substr($domain, 4);
    return $domain;
}

/**
 * Get an array of all country names.
 *
 * @return Array
 */
function get_all_countries() {
    return ["Afrique du Sud","Afghanistan","Albanie","Algérie","Allemagne","Andorre","Angola","Antigua-et-Barbuda","Arabie Saoudite","Argentine","Arménie","Australie","Autriche","Azerbaïdjan","Bahamas","Bahreïn","Bangladesh","Barbade","Belgique","Belize","Bénin","Bhoutan","Biélorussie","Birmanie","Bolivie","Bosnie-Herzégovine","Botswana","Brésil","Brunei","Bulgarie","Burkina Faso","Burundi","Cambodge","Cameroun","Canada","Cap-Vert","Chili","Chine","Chypre","Colombie","Comores","Corée du Nord","Corée du Sud","Costa Rica","Côte d’Ivoire","Croatie","Cuba","Danemark","Djibouti","Dominique","Égypte","Émirats arabes unis","Équateur","Érythrée","Espagne","Eswatini","Estonie","États-Unis","Éthiopie","Fidji","Finlande","France","Gabon","Gambie","Géorgie","Ghana","Grèce","Grenade","Guatemala","Guinée","Guinée équatoriale","Guinée-Bissau","Guyana","Haïti","Honduras","Hongrie","Îles Cook","Îles Marshall","Inde","Indonésie","Irak","Iran","Irlande","Islande","Israël","Italie","Jamaïque","Japon","Jordanie","Kazakhstan","Kenya","Kirghizistan","Kiribati","Koweït","Laos","Lesotho","Lettonie","Liban","Liberia","Libye","Liechtenstein","Lituanie","Luxembourg","Macédoine","Madagascar","Malaisie","Malawi","Maldives","Mali","Malte","Maroc","Maurice","Mauritanie","Mexique","Micronésie","Moldavie","Monaco","Mongolie","Monténégro","Mozambique","Namibie","Nauru","Népal","Nicaragua","Niger","Nigeria","Niue","Norvège","Nouvelle-Zélande","Oman","Ouganda","Ouzbékistan","Pakistan","Palaos","Palestine","Panama","Papouasie-Nouvelle-Guinée","Paraguay","Pays-Bas","Pérou","Philippines","Pologne","Portugal","Qatar","République centrafricaine","République démocratique du Congo","République Dominicaine","République du Congo","République tchèque","Roumanie","Royaume-Uni","Russie","Rwanda","Saint-Kitts-et-Nevis","Saint-Vincent-et-les-Grenadines","Sainte-Lucie","Saint-Marin","Salomon","Salvador","Samoa","São Tomé-et-Principe","Sénégal","Serbie","Seychelles","Sierra Leone","Singapour","Slovaquie","Slovénie","Somalie","Soudan","Soudan du Sud","Sri Lanka","Suède","Suisse","Suriname","Syrie","Tadjikistan","Tanzanie","Tchad","Thaïlande","Timor oriental","Togo","Tonga","Trinité-et-Tobago","Tunisie","Turkménistan","Turquie","Tuvalu","Ukraine","Uruguay","Vanuatu","Vatican","Venezuela","Viêt Nam","Yémen","Zambie","Zimbabwe",];
}

/**
 * Get an array of Canadian cities.
 *
 * @return Array
 */
function get_all_cities() {
    return ["Montréal","Québec","Toronto","Ottawa","Calgary","Edmonton","Mississauga","Winnipeg","Vancouver","Brampton","Hamilton","Surrey","Laval","Halifax","London","Markham","Vaughan","Gatineau","Saskatoon","Longueuil","Kitchener","Burnaby","Windsor","Regina","Richmond","Richmond Hill","Oakville","Burlington","Sudbury","Sherbrooke","Oshawa","Saguenay","Lévis","Barrie","Abbotsford","Coquitlam","Trois-Rivières","St. Catharines","Guelph","Cambridge","Whitby","Kelowna","Kingston","Ajax","Langley","Saanich","Terrebonne","Milton","St. John's","Thunder Bay","Waterloo","Delta","Chatham-Kent","Red Deer","Strathcona County","Brantford","Saint-Jean-sur-Richelieu","Cape Breton","Lethbridge","Clarington","Pickering","Nanaimo","Kamloops","Niagara Falls","North Vancouver","Victoria","Brossard","Repentigny","Newmarket","Chilliwack","Maple Ridge","Peterborough","Kawartha Lakes","Drummondville","Saint-Jérôme","Prince George","Sault Ste. Marie","Moncton","Sarnia","Wood Buffalo","New Westminster","Saint John","Caledon","Granby","St. Albert","Norfolk County","Medicine Hat","Grande Prairie","Airdrie","Halton Hills","Port Coquitlam","Fredericton","Blainville","Saint-Hyacinthe","Aurora","North Vancouver","Welland","North Bay","Belleville","Mirabel"];
}

/**
 * Get an array of Canadian provinces & territories and American states.
 *
 * @return Array
 */
function get_all_provinces() {
    return [
        "Québec","Alberta","Colombie-Britannique","Île-du-Prince-Édouard","Manitoba","Nouveau-Brunswick","Nouvelle-Écosse","Ontario","Saskatchewan","Terre-Neuve-et-Labrador","Nunavut","Territoires du Nord-Ouest","Yukon",
        "Alabama","Alaska","Arizona","Arkansas","California","Colorado","Connecticut","Delaware","Florida","Georgia","Hawaii","Idaho","Illinois","Indiana","Iowa","Kansas","Kentucky","Louisiana","Maine","Maryland","Massachusetts","Michigan","Minnesota","Mississippi","Missouri","Montana","Nebraska","Nevada","New Hampshire","New Jersey","New Mexico","New York","North Carolina","North Dakota","Ohio","Oklahoma","Oregon","Pennsylvania","Rhode Island","South Carolina","South Dakota","Tennessee","Texas","Utah","Vermont","Virginia","Washington","West Virginia","Wisconsin","Wyoming",
    ];
}

/**
 * libphonenumber's parse(), but returns null instead of throwing error
 *
 * @param  String  $value
 * @param  \libphonenumber\PhoneNumberUtil $phoneUtil (optional) false
 * @return \libphonenumber\PhoneNumber
 */
function parse_phone_or_null($value, $phoneUtil = false) {
    if ($phoneUtil === false) {
        $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
    }
    try {
        $phoneObj = $phoneUtil->parse($value, 'CA');
    } catch (\libphonenumber\NumberParseException $e) {
        return null;
    }
    return $phoneObj;
}

/**
 * Takes a presumed phone number in any format, tries to return a libphonenumber object with isValidNumber() = true, or else null
 *
 * @param  String  $value
 * @return \libphonenumber\PhoneNumber
 */
function get_valid_phone_obj($value) {
    $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
    // Try with unedited input value
    $phoneObj = parse_phone_or_null($value, $phoneUtil);
    if (!$phoneObj) return null;
    try {
        $isValid = $phoneUtil->isValidNumber($phoneObj);
    } catch (\Throwable $e) {
        return null;
    }
    if ($isValid) return $phoneObj;
    // If not valid and missing '+', try with '+'
    if (substr(trim($value), 0, 1) === '+') return null;
    $phoneObj = parse_phone_or_null('+'.$value, $phoneUtil);
    if (!$phoneObj) return null;
    try {
        $isValid = $phoneUtil->isValidNumber($phoneObj);
    } catch (\Throwable $e) {
        return null;
    }
    if ($isValid) return $phoneObj;
    return null;
}

/**
 * Returns a human-readable string from a libphonenumber object (omits +1 for Canadian numbers)
 *
 * @param  \libphonenumber\PhoneNumber  $phoneObj
 * @return String
 */
function get_readable_phone($phoneObj) {
    if (empty($phoneObj)) return null;
    $phoneUtil = \libphonenumber\PhoneNumberUtil::getInstance();
    try {
        $r = $phoneUtil->format($phoneObj, \libphonenumber\PhoneNumberFormat::INTERNATIONAL);
        if ($phoneUtil->isValidNumberForRegion($phoneObj, 'CA')) {
            // Remove "+1 " from beginning of string
            $r = substr($r, 3);
        }
    } catch (\libphonenumber\NumberParseException $e) {
        return null;
    }
    // Replace "ext. " with "x"
    $r = str_replace('ext. ', 'x', $r);
    return $r;
}