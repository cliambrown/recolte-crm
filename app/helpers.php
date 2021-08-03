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
    return ['Afghanistan','Albania','Algeria','Andorra','Angola','Antigua and Barbuda','Argentina','Armenia','Australia','Austria','Azerbaijan','Bahamas, The','Bahrain','Bangladesh','Barbados','Belarus','Belgium','Belize','Benin','Bhutan','Bolivia','Bosnia and Herzegovina','Botswana','Brazil','Brunei','Bulgaria','Burkina Faso','Burundi','Cambodia','Cameroon','Canada','Cape Verde','Central African Republic','Chad','Chile','China','Colombia','Comoros','Congo, Democratic Republic of the','Congo, Republic of the','Costa Rica','Croatia','Cuba','Cyprus','Czechia','Denmark','Djibouti','Dominica','Dominican Republic','East Timor','Ecuador','Egypt','El Salvador','Equatorial Guinea','Eritrea','Estonia','Eswatini','Ethiopia','Fiji','Finland','France','Gabon','Gambia, The','Georgia','Germany','Ghana','Greece','Grenada','Guatemala','Guinea','Guinea-Bissau','Guyana','Haiti','Honduras','Hungary','Iceland','India','Indonesia','Iran','Iraq','Ireland','Israel','Italy','Ivory Coast','Jamaica','Japan','Jordan','Kazakhstan','Kenya','Kiribati','Kuwait','Kyrgyzstan','Laos','Latvia','Lebanon','Lesotho','Liberia','Libya','Liechtenstein','Lithuania','Luxembourg','Madagascar','Malawi','Malaysia','Maldives','Mali','Malta','Marshall Islands','Mauritania','Mauritius','Mexico','Federated States of Micronesia','Moldova','Monaco','Mongolia','Montenegro','Morocco','Mozambique','Myanmar','Namibia','Nauru','Nepal','Netherlands','New Zealand','Nicaragua','Niger','Nigeria','North Korea','North Macedonia','Norway','Oman','Pakistan','Palau','Palestine','Panama','Papua New Guinea','Paraguay','Peru','Philippines','Poland','Portugal','Qatar','Romania','Russia','Rwanda','Saint Kitts and Nevis','Saint Lucia','Saint Vincent and the Grenadines','Samoa','San Marino','São Tomé and Príncipe','Saudi Arabia','Senegal','Serbia','Seychelles','Sierra Leone','Singapore','Slovakia','Slovenia','Solomon Islands','Somalia','South Africa','South Korea','South Sudan','Spain','Sri Lanka','Sudan','Suriname','Sweden','Switzerland','Syria','Tajikistan','Tanzania','Thailand','Togo','Tonga','Trinidad and Tobago','Tunisia','Turkey','Turkmenistan','Tuvalu','Uganda','Ukraine','United Arab Emirates','United Kingdom','United States','Uruguay','Uzbekistan','Vanuatu','Vatican City','Venezuela','Vietnam','Yemen','Zambia','Zimbabwe'];
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