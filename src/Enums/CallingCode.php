<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Enums;

/**
 * Enum representing telephone country codes (ITU-T E.164) with ISO 3166-1 alpha-2 keys.
 *
 * @example
 * // Get telephone code by country
 * $code = CallingCode::FR;
 *
 * // Get telephone code value
 * echo $code->getCallingCode(); // "33"
 *
 * // Get formatted telephone code
 * echo '+' . $code->getCallingCode(); // "+33"
 *
 * // Get display name
 * echo $code->getDisplayName(); // "France (+33)"
 *
 * // From alpha-2 key
 * $code = CallingCode::tryFromName('CD'); // CallingCode::CD
 *
 * // Get all countries sharing the same calling code
 * $countries = CallingCode::getCountriesByCallingCode('672'); // [CallingCode::AQ, CallingCode::HM, CallingCode::NF]
 */
enum CallingCode: string
{
    case AF = 'AF';
    case AX = 'AX';
    case AL = 'AL';
    case DZ = 'DZ';
    case AS = 'AS';
    case AD = 'AD';
    case AO = 'AO';
    case AI = 'AI';
    case AQ = 'AQ';
    case AG = 'AG';
    case AR = 'AR';
    case AM = 'AM';
    case AW = 'AW';
    case AU = 'AU';
    case AT = 'AT';
    case AZ = 'AZ';
    case BS = 'BS';
    case BH = 'BH';
    case BD = 'BD';
    case BB = 'BB';
    case BY = 'BY';
    case BE = 'BE';
    case BZ = 'BZ';
    case BJ = 'BJ';
    case BM = 'BM';
    case BT = 'BT';
    case BO = 'BO';
    case BQ = 'BQ';
    case BA = 'BA';
    case BW = 'BW';
    case BV = 'BV';
    case BR = 'BR';
    case IO = 'IO';
    case BN = 'BN';
    case BG = 'BG';
    case BF = 'BF';
    case BI = 'BI';
    case CV = 'CV';
    case KH = 'KH';
    case CM = 'CM';
    case CA = 'CA';
    case KY = 'KY';
    case CF = 'CF';
    case TD = 'TD';
    case CL = 'CL';
    case CN = 'CN';
    case CX = 'CX';
    case CC = 'CC';
    case CO = 'CO';
    case KM = 'KM';
    case CG = 'CG';
    case CD = 'CD';
    case CK = 'CK';
    case CR = 'CR';
    case CI = 'CI';
    case HR = 'HR';
    case CU = 'CU';
    case CW = 'CW';
    case CY = 'CY';
    case CZ = 'CZ';
    case DK = 'DK';
    case DJ = 'DJ';
    case DM = 'DM';
    case DO = 'DO';
    case EC = 'EC';
    case EG = 'EG';
    case SV = 'SV';
    case GQ = 'GQ';
    case ER = 'ER';
    case EE = 'EE';
    case SZ = 'SZ';
    case ET = 'ET';
    case FK = 'FK';
    case FO = 'FO';
    case FJ = 'FJ';
    case FI = 'FI';
    case FR = 'FR';
    case GF = 'GF';
    case PF = 'PF';
    case TF = 'TF';
    case GA = 'GA';
    case GM = 'GM';
    case GE = 'GE';
    case DE = 'DE';
    case GH = 'GH';
    case GI = 'GI';
    case GR = 'GR';
    case GL = 'GL';
    case GD = 'GD';
    case GP = 'GP';
    case GU = 'GU';
    case GT = 'GT';
    case GG = 'GG';
    case GN = 'GN';
    case GW = 'GW';
    case GY = 'GY';
    case HT = 'HT';
    case HM = 'HM';
    case VA = 'VA';
    case HN = 'HN';
    case HK = 'HK';
    case HU = 'HU';
    case IS = 'IS';
    case IN = 'IN';
    case ID = 'ID';
    case IR = 'IR';
    case IQ = 'IQ';
    case IE = 'IE';
    case IM = 'IM';
    case IL = 'IL';
    case IT = 'IT';
    case JM = 'JM';
    case JP = 'JP';
    case JE = 'JE';
    case JO = 'JO';
    case KZ = 'KZ';
    case KE = 'KE';
    case KI = 'KI';
    case KP = 'KP';
    case KR = 'KR';
    case KW = 'KW';
    case KG = 'KG';
    case LA = 'LA';
    case LV = 'LV';
    case LB = 'LB';
    case LS = 'LS';
    case LR = 'LR';
    case LY = 'LY';
    case LI = 'LI';
    case LT = 'LT';
    case LU = 'LU';
    case MO = 'MO';
    case MG = 'MG';
    case MW = 'MW';
    case MY = 'MY';
    case MV = 'MV';
    case ML = 'ML';
    case MT = 'MT';
    case MH = 'MH';
    case MQ = 'MQ';
    case MR = 'MR';
    case MU = 'MU';
    case YT = 'YT';
    case MX = 'MX';
    case FM = 'FM';
    case MD = 'MD';
    case MC = 'MC';
    case MN = 'MN';
    case ME = 'ME';
    case MS = 'MS';
    case MA = 'MA';
    case MZ = 'MZ';
    case MM = 'MM';
    case NA = 'NA';
    case NR = 'NR';
    case NP = 'NP';
    case NL = 'NL';
    case NC = 'NC';
    case NZ = 'NZ';
    case NI = 'NI';
    case NE = 'NE';
    case NG = 'NG';
    case NU = 'NU';
    case NF = 'NF';
    case MK = 'MK';
    case MP = 'MP';
    case NO = 'NO';
    case OM = 'OM';
    case PK = 'PK';
    case PW = 'PW';
    case PS = 'PS';
    case PA = 'PA';
    case PG = 'PG';
    case PY = 'PY';
    case PE = 'PE';
    case PH = 'PH';
    case PN = 'PN';
    case PL = 'PL';
    case PT = 'PT';
    case PR = 'PR';
    case QA = 'QA';
    case RE = 'RE';
    case RO = 'RO';
    case RU = 'RU';
    case RW = 'RW';
    case BL = 'BL';
    case SH = 'SH';
    case KN = 'KN';
    case LC = 'LC';
    case MF = 'MF';
    case PM = 'PM';
    case VC = 'VC';
    case WS = 'WS';
    case SM = 'SM';
    case ST = 'ST';
    case SA = 'SA';
    case SN = 'SN';
    case RS = 'RS';
    case SC = 'SC';
    case SL = 'SL';
    case SG = 'SG';
    case SX = 'SX';
    case SK = 'SK';
    case SI = 'SI';
    case SB = 'SB';
    case SO = 'SO';
    case ZA = 'ZA';
    case GS = 'GS';
    case SS = 'SS';
    case ES = 'ES';
    case LK = 'LK';
    case SD = 'SD';
    case SR = 'SR';
    case SJ = 'SJ';
    case SE = 'SE';
    case CH = 'CH';
    case SY = 'SY';
    case TW = 'TW';
    case TJ = 'TJ';
    case TZ = 'TZ';
    case TH = 'TH';
    case TL = 'TL';
    case TG = 'TG';
    case TK = 'TK';
    case TO = 'TO';
    case TT = 'TT';
    case TN = 'TN';
    case TR = 'TR';
    case TM = 'TM';
    case TC = 'TC';
    case TV = 'TV';
    case UG = 'UG';
    case UA = 'UA';
    case AE = 'AE';
    case GB = 'GB';
    case US = 'US';
    case UM = 'UM';
    case UY = 'UY';
    case UZ = 'UZ';
    case VU = 'VU';
    case VE = 'VE';
    case VN = 'VN';
    case VG = 'VG';
    case VI = 'VI';
    case WF = 'WF';
    case EH = 'EH';
    case YE = 'YE';
    case ZM = 'ZM';
    case ZW = 'ZW';

    /**
     * Map of country codes to their calling codes and country names
     */
    private const CALLING_CODE_MAP = [
        'AF' => ['93', 'Afghanistan'],
        'AX' => ['358', 'Åland Islands'],
        'AL' => ['355', 'Albania'],
        'DZ' => ['213', 'Algeria'],
        'AS' => ['1684', 'American Samoa'],
        'AD' => ['376', 'Andorra'],
        'AO' => ['244', 'Angola'],
        'AI' => ['1264', 'Anguilla'],
        'AQ' => ['672', 'Antarctica'],
        'AG' => ['1268', 'Antigua and Barbuda'],
        'AR' => ['54', 'Argentina'],
        'AM' => ['374', 'Armenia'],
        'AW' => ['297', 'Aruba'],
        'AU' => ['61', 'Australia'],
        'AT' => ['43', 'Austria'],
        'AZ' => ['994', 'Azerbaijan'],
        'BS' => ['1242', 'Bahamas'],
        'BH' => ['973', 'Bahrain'],
        'BD' => ['880', 'Bangladesh'],
        'BB' => ['1246', 'Barbados'],
        'BY' => ['375', 'Belarus'],
        'BE' => ['32', 'Belgium'],
        'BZ' => ['501', 'Belize'],
        'BJ' => ['229', 'Benin'],
        'BM' => ['1441', 'Bermuda'],
        'BT' => ['975', 'Bhutan'],
        'BO' => ['591', 'Bolivia'],
        'BQ' => ['599', 'Bonaire, Sint Eustatius and Saba'],
        'BA' => ['387', 'Bosnia and Herzegovina'],
        'BW' => ['267', 'Botswana'],
        'BV' => ['47', 'Bouvet Island'],
        'BR' => ['55', 'Brazil'],
        'IO' => ['246', 'British Indian Ocean Territory'],
        'BN' => ['673', 'Brunei Darussalam'],
        'BG' => ['359', 'Bulgaria'],
        'BF' => ['226', 'Burkina Faso'],
        'BI' => ['257', 'Burundi'],
        'CV' => ['238', 'Cabo Verde'],
        'KH' => ['855', 'Cambodia'],
        'CM' => ['237', 'Cameroon'],
        'CA' => ['1', 'Canada'],
        'KY' => ['1345', 'Cayman Islands'],
        'CF' => ['236', 'Central African Republic'],
        'TD' => ['235', 'Chad'],
        'CL' => ['56', 'Chile'],
        'CN' => ['86', 'China'],
        'CX' => ['61', 'Christmas Island'],
        'CC' => ['61', 'Cocos (Keeling) Islands'],
        'CO' => ['57', 'Colombia'],
        'KM' => ['269', 'Comoros'],
        'CG' => ['242', 'Congo'],
        'CD' => ['243', 'Congo (Democratic Republic of the)'],
        'CK' => ['682', 'Cook Islands'],
        'CR' => ['506', 'Costa Rica'],
        'CI' => ['225', 'Côte d\'Ivoire'],
        'HR' => ['385', 'Croatia'],
        'CU' => ['53', 'Cuba'],
        'CW' => ['599', 'Curaçao'],
        'CY' => ['357', 'Cyprus'],
        'CZ' => ['420', 'Czechia'],
        'DK' => ['45', 'Denmark'],
        'DJ' => ['253', 'Djibouti'],
        'DM' => ['1767', 'Dominica'],
        'DO' => ['1809', 'Dominican Republic'],
        'EC' => ['593', 'Ecuador'],
        'EG' => ['20', 'Egypt'],
        'SV' => ['503', 'El Salvador'],
        'GQ' => ['240', 'Equatorial Guinea'],
        'ER' => ['291', 'Eritrea'],
        'EE' => ['372', 'Estonia'],
        'SZ' => ['268', 'Eswatini'],
        'ET' => ['251', 'Ethiopia'],
        'FK' => ['500', 'Falkland Islands'],
        'FO' => ['298', 'Faroe Islands'],
        'FJ' => ['679', 'Fiji'],
        'FI' => ['358', 'Finland'],
        'FR' => ['33', 'France'],
        'GF' => ['594', 'French Guiana'],
        'PF' => ['689', 'French Polynesia'],
        'TF' => ['262', 'French Southern Territories'],
        'GA' => ['241', 'Gabon'],
        'GM' => ['220', 'Gambia'],
        'GE' => ['995', 'Georgia'],
        'DE' => ['49', 'Germany'],
        'GH' => ['233', 'Ghana'],
        'GI' => ['350', 'Gibraltar'],
        'GR' => ['30', 'Greece'],
        'GL' => ['299', 'Greenland'],
        'GD' => ['1473', 'Grenada'],
        'GP' => ['590', 'Guadeloupe'],
        'GU' => ['1671', 'Guam'],
        'GT' => ['502', 'Guatemala'],
        'GG' => ['44', 'Guernsey'],
        'GN' => ['224', 'Guinea'],
        'GW' => ['245', 'Guinea-Bissau'],
        'GY' => ['592', 'Guyana'],
        'HT' => ['509', 'Haiti'],
        'HM' => ['672', 'Heard Island and McDonald Islands'],
        'VA' => ['39', 'Holy See'],
        'HN' => ['504', 'Honduras'],
        'HK' => ['852', 'Hong Kong'],
        'HU' => ['36', 'Hungary'],
        'IS' => ['354', 'Iceland'],
        'IN' => ['91', 'India'],
        'ID' => ['62', 'Indonesia'],
        'IR' => ['98', 'Iran'],
        'IQ' => ['964', 'Iraq'],
        'IE' => ['353', 'Ireland'],
        'IM' => ['44', 'Isle of Man'],
        'IL' => ['972', 'Israel'],
        'IT' => ['39', 'Italy'],
        'JM' => ['1876', 'Jamaica'],
        'JP' => ['81', 'Japan'],
        'JE' => ['44', 'Jersey'],
        'JO' => ['962', 'Jordan'],
        'KZ' => ['7', 'Kazakhstan'],
        'KE' => ['254', 'Kenya'],
        'KI' => ['686', 'Kiribati'],
        'KP' => ['850', 'North Korea'],
        'KR' => ['82', 'South Korea'],
        'KW' => ['965', 'Kuwait'],
        'KG' => ['996', 'Kyrgyzstan'],
        'LA' => ['856', 'Laos'],
        'LV' => ['371', 'Latvia'],
        'LB' => ['961', 'Lebanon'],
        'LS' => ['266', 'Lesotho'],
        'LR' => ['231', 'Liberia'],
        'LY' => ['218', 'Libya'],
        'LI' => ['423', 'Liechtenstein'],
        'LT' => ['370', 'Lithuania'],
        'LU' => ['352', 'Luxembourg'],
        'MO' => ['853', 'Macao'],
        'MG' => ['261', 'Madagascar'],
        'MW' => ['265', 'Malawi'],
        'MY' => ['60', 'Malaysia'],
        'MV' => ['960', 'Maldives'],
        'ML' => ['223', 'Mali'],
        'MT' => ['356', 'Malta'],
        'MH' => ['692', 'Marshall Islands'],
        'MQ' => ['596', 'Martinique'],
        'MR' => ['222', 'Mauritania'],
        'MU' => ['230', 'Mauritius'],
        'YT' => ['262', 'Mayotte'],
        'MX' => ['52', 'Mexico'],
        'FM' => ['691', 'Micronesia'],
        'MD' => ['373', 'Moldova'],
        'MC' => ['377', 'Monaco'],
        'MN' => ['976', 'Mongolia'],
        'ME' => ['382', 'Montenegro'],
        'MS' => ['1664', 'Montserrat'],
        'MA' => ['212', 'Morocco'],
        'MZ' => ['258', 'Mozambique'],
        'MM' => ['95', 'Myanmar'],
        'NA' => ['264', 'Namibia'],
        'NR' => ['674', 'Nauru'],
        'NP' => ['977', 'Nepal'],
        'NL' => ['31', 'Netherlands'],
        'NC' => ['687', 'New Caledonia'],
        'NZ' => ['64', 'New Zealand'],
        'NI' => ['505', 'Nicaragua'],
        'NE' => ['227', 'Niger'],
        'NG' => ['234', 'Nigeria'],
        'NU' => ['683', 'Niue'],
        'NF' => ['672', 'Norfolk Island'],
        'MK' => ['389', 'North Macedonia'],
        'MP' => ['1670', 'Northern Mariana Islands'],
        'NO' => ['47', 'Norway'],
        'OM' => ['968', 'Oman'],
        'PK' => ['92', 'Pakistan'],
        'PW' => ['680', 'Palau'],
        'PS' => ['970', 'Palestine'],
        'PA' => ['507', 'Panama'],
        'PG' => ['675', 'Papua New Guinea'],
        'PY' => ['595', 'Paraguay'],
        'PE' => ['51', 'Peru'],
        'PH' => ['63', 'Philippines'],
        'PN' => ['870', 'Pitcairn'],
        'PL' => ['48', 'Poland'],
        'PT' => ['351', 'Portugal'],
        'PR' => ['1787', 'Puerto Rico'],
        'QA' => ['974', 'Qatar'],
        'RE' => ['262', 'Réunion'],
        'RO' => ['40', 'Romania'],
        'RU' => ['7', 'Russia'],
        'RW' => ['250', 'Rwanda'],
        'BL' => ['590', 'Saint Barthélemy'],
        'SH' => ['290', 'Saint Helena'],
        'KN' => ['1869', 'Saint Kitts and Nevis'],
        'LC' => ['1758', 'Saint Lucia'],
        'MF' => ['590', 'Saint Martin'],
        'PM' => ['508', 'Saint Pierre and Miquelon'],
        'VC' => ['1784', 'Saint Vincent and the Grenadines'],
        'WS' => ['685', 'Samoa'],
        'SM' => ['378', 'San Marino'],
        'ST' => ['239', 'Sao Tome and Principe'],
        'SA' => ['966', 'Saudi Arabia'],
        'SN' => ['221', 'Senegal'],
        'RS' => ['381', 'Serbia'],
        'SC' => ['248', 'Seychelles'],
        'SL' => ['232', 'Sierra Leone'],
        'SG' => ['65', 'Singapore'],
        'SX' => ['1721', 'Sint Maarten'],
        'SK' => ['421', 'Slovakia'],
        'SI' => ['386', 'Slovenia'],
        'SB' => ['677', 'Solomon Islands'],
        'SO' => ['252', 'Somalia'],
        'ZA' => ['27', 'South Africa'],
        'GS' => ['500', 'South Georgia'],
        'SS' => ['211', 'South Sudan'],
        'ES' => ['34', 'Spain'],
        'LK' => ['94', 'Sri Lanka'],
        'SD' => ['249', 'Sudan'],
        'SR' => ['597', 'Suriname'],
        'SJ' => ['47', 'Svalbard and Jan Mayen'],
        'SE' => ['46', 'Sweden'],
        'CH' => ['41', 'Switzerland'],
        'SY' => ['963', 'Syria'],
        'TW' => ['886', 'Taiwan'],
        'TJ' => ['992', 'Tajikistan'],
        'TZ' => ['255', 'Tanzania'],
        'TH' => ['66', 'Thailand'],
        'TL' => ['670', 'Timor-Leste'],
        'TG' => ['228', 'Togo'],
        'TK' => ['690', 'Tokelau'],
        'TO' => ['676', 'Tonga'],
        'TT' => ['1868', 'Trinidad and Tobago'],
        'TN' => ['216', 'Tunisia'],
        'TR' => ['90', 'Turkey'],
        'TM' => ['993', 'Turkmenistan'],
        'TC' => ['1649', 'Turks and Caicos Islands'],
        'TV' => ['688', 'Tuvalu'],
        'UG' => ['256', 'Uganda'],
        'UA' => ['380', 'Ukraine'],
        'AE' => ['971', 'United Arab Emirates'],
        'GB' => ['44', 'United Kingdom'],
        'US' => ['1', 'United States'],
        'UM' => ['1', 'United States Minor Outlying Islands'],
        'UY' => ['598', 'Uruguay'],
        'UZ' => ['998', 'Uzbekistan'],
        'VU' => ['678', 'Vanuatu'],
        'VE' => ['58', 'Venezuela'],
        'VN' => ['84', 'Viet Nam'],
        'VG' => ['1284', 'Virgin Islands (British)'],
        'VI' => ['1340', 'Virgin Islands (U.S.)'],
        'WF' => ['681', 'Wallis and Futuna'],
        'EH' => ['212', 'Western Sahara'],
        'YE' => ['967', 'Yemen'],
        'ZM' => ['260', 'Zambia'],
        'ZW' => ['263', 'Zimbabwe'],
    ];

    /**
     * Get the calling code for this country
     */
    public function getCallingCode(): string
    {
        return self::CALLING_CODE_MAP[$this->value][0];
    }

    /**
     * Get the country name
     */
    public function getCountryName(): string
    {
        return self::CALLING_CODE_MAP[$this->value][1];
    }

    /**
     * Get the display name with country name and telephone code
     */
    public function getDisplayName(): string
    {
        return $this->getCountryName().' (+'.$this->getCallingCode().')';
    }

    /**
     * Try to get the enum case from a country code (ISO 3166-1 alpha-2)
     */
    public static function tryFromName(string $name): ?self
    {
        return self::tryFrom($name);
    }

    /**
     * Get all countries that share the same calling code
     *
     * @return array<self>
     */
    public static function getCountriesByCallingCode(string $callingCode): array
    {
        return array_values(array_filter(
            self::cases(),
            fn (self $case) => $case->getCallingCode() === $callingCode
        ));
    }

    /**
     * Get all countries grouped by calling code
     *
     * @return array<string, array<self>>
     */
    public static function getCountriesGroupedByCallingCode(): array
    {
        $groups = [];
        foreach (self::cases() as $case) {
            $code = $case->getCallingCode();
            if (! isset($groups[$code])) {
                $groups[$code] = [];
            }
            $groups[$code][] = $case;
        }

        return $groups;
    }

    /**
     * Check if a country code exists
     */
    public static function isValidCountryCode(string $code): bool
    {
        return isset(self::CALLING_CODE_MAP[$code]);
    }

    /**
     * Get all country codes
     *
     * @return array<string>
     */
    public static function getCountryCodes(): array
    {
        return array_keys(self::CALLING_CODE_MAP);
    }

    /**
     * Get all calling codes (unique values)
     *
     * @return array<string>
     */
    public static function getUniqueCallingCodes(): array
    {
        return array_values(array_unique(array_column(self::CALLING_CODE_MAP, 0)));
    }
}
