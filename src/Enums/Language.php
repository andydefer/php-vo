<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Enums;

/**
 * ISO 639-1 Language codes (two-letter).
 *
 * @see https://en.wikipedia.org/wiki/ISO_639-1
 */
enum Language: string
{
    case AF = 'af'; // Afrikaans
    case AM = 'am'; // Amharic
    case AR = 'ar'; // Arabic
    case AZ = 'az'; // Azerbaijani
    case BE = 'be'; // Belarusian
    case BG = 'bg'; // Bulgarian
    case BN = 'bn'; // Bengali
    case CA = 'ca'; // Catalan
    case CS = 'cs'; // Czech
    case CY = 'cy'; // Welsh
    case DA = 'da'; // Danish
    case DE = 'de'; // German
    case EL = 'el'; // Greek
    case EN = 'en'; // English
    case ES = 'es'; // Spanish
    case ET = 'et'; // Estonian
    case EU = 'eu'; // Basque
    case FA = 'fa'; // Persian
    case FI = 'fi'; // Finnish
    case FR = 'fr'; // French
    case GA = 'ga'; // Irish
    case GL = 'gl'; // Galician
    case HE = 'he'; // Hebrew
    case HI = 'hi'; // Hindi
    case HR = 'hr'; // Croatian
    case HU = 'hu'; // Hungarian
    case HY = 'hy'; // Armenian
    case ID = 'id'; // Indonesian
    case IS = 'is'; // Icelandic
    case IT = 'it'; // Italian
    case JA = 'ja'; // Japanese
    case KA = 'ka'; // Georgian
    case KK = 'kk'; // Kazakh
    case KO = 'ko'; // Korean
    case LT = 'lt'; // Lithuanian
    case LV = 'lv'; // Latvian
    case MK = 'mk'; // Macedonian
    case ML = 'ml'; // Malayalam
    case MN = 'mn'; // Mongolian
    case MS = 'ms'; // Malay
    case MT = 'mt'; // Maltese
    case MY = 'my'; // Burmese
    case NB = 'nb'; // Norwegian Bokmål
    case NL = 'nl'; // Dutch
    case NN = 'nn'; // Norwegian Nynorsk
    case NO = 'no'; // Norwegian
    case PL = 'pl'; // Polish
    case PT = 'pt'; // Portuguese
    case RO = 'ro'; // Romanian
    case RU = 'ru'; // Russian
    case SI = 'si'; // Sinhala
    case SK = 'sk'; // Slovak
    case SL = 'sl'; // Slovenian
    case SQ = 'sq'; // Albanian
    case SR = 'sr'; // Serbian
    case SV = 'sv'; // Swedish
    case SW = 'sw'; // Swahili
    case TA = 'ta'; // Tamil
    case TE = 'te'; // Telugu
    case TH = 'th'; // Thai
    case TL = 'tl'; // Tagalog
    case TR = 'tr'; // Turkish
    case UK = 'uk'; // Ukrainian
    case UR = 'ur'; // Urdu
    case UZ = 'uz'; // Uzbek
    case VI = 'vi'; // Vietnamese
    case ZH = 'zh'; // Chinese

    /**
     * Get the native name of the language.
     */
    public function nativeName(): string
    {
        return match ($this) {
            self::AF => 'Afrikaans',
            self::AM => 'አማርኛ',
            self::AR => 'العربية',
            self::AZ => 'Azərbaycan dili',
            self::BE => 'Беларуская мова',
            self::BG => 'Български език',
            self::BN => 'বাংলা',
            self::CA => 'Català',
            self::CS => 'Čeština',
            self::CY => 'Cymraeg',
            self::DA => 'Dansk',
            self::DE => 'Deutsch',
            self::EL => 'Ελληνικά',
            self::EN => 'English',
            self::ES => 'Español',
            self::ET => 'Eesti keel',
            self::EU => 'Euskara',
            self::FA => 'فارسی',
            self::FI => 'Suomi',
            self::FR => 'Français',
            self::GA => 'Gaeilge',
            self::GL => 'Galego',
            self::HE => 'עברית',
            self::HI => 'हिन्दी',
            self::HR => 'Hrvatski jezik',
            self::HU => 'Magyar nyelv',
            self::HY => 'Հայերեն',
            self::ID => 'Bahasa Indonesia',
            self::IS => 'Íslenska',
            self::IT => 'Italiano',
            self::JA => '日本語',
            self::KA => 'ქართული',
            self::KK => 'Қазақ тілі',
            self::KO => '한국어',
            self::LT => 'Lietuvių kalba',
            self::LV => 'Latviešu valoda',
            self::MK => 'Македонски јазик',
            self::ML => 'മലയാളം',
            self::MN => 'Монгол хэл',
            self::MS => 'Bahasa Melayu',
            self::MT => 'Malti',
            self::MY => 'မြန်မာစာ',
            self::NB => 'Norsk bokmål',
            self::NL => 'Nederlands',
            self::NN => 'Norsk nynorsk',
            self::NO => 'Norsk',
            self::PL => 'Polski',
            self::PT => 'Português',
            self::RO => 'Română',
            self::RU => 'Русский',
            self::SI => 'සිංහල',
            self::SK => 'Slovenčina',
            self::SL => 'Slovenščina',
            self::SQ => 'Shqip',
            self::SR => 'Српски језик',
            self::SV => 'Svenska',
            self::SW => 'Kiswahili',
            self::TA => 'தமிழ்',
            self::TE => 'తెలుగు',
            self::TH => 'ไทย',
            self::TL => 'Wikang Tagalog',
            self::TR => 'Türkçe',
            self::UK => 'Українська',
            self::UR => 'اردو',
            self::UZ => 'Oʻzbek tili',
            self::VI => 'Tiếng Việt',
            self::ZH => '中文',
        };
    }

    /**
     * Get the English name of the language.
     */
    public function englishName(): string
    {
        return match ($this) {
            self::AF => 'Afrikaans',
            self::AM => 'Amharic',
            self::AR => 'Arabic',
            self::AZ => 'Azerbaijani',
            self::BE => 'Belarusian',
            self::BG => 'Bulgarian',
            self::BN => 'Bengali',
            self::CA => 'Catalan',
            self::CS => 'Czech',
            self::CY => 'Welsh',
            self::DA => 'Danish',
            self::DE => 'German',
            self::EL => 'Greek',
            self::EN => 'English',
            self::ES => 'Spanish',
            self::ET => 'Estonian',
            self::EU => 'Basque',
            self::FA => 'Persian',
            self::FI => 'Finnish',
            self::FR => 'French',
            self::GA => 'Irish',
            self::GL => 'Galician',
            self::HE => 'Hebrew',
            self::HI => 'Hindi',
            self::HR => 'Croatian',
            self::HU => 'Hungarian',
            self::HY => 'Armenian',
            self::ID => 'Indonesian',
            self::IS => 'Icelandic',
            self::IT => 'Italian',
            self::JA => 'Japanese',
            self::KA => 'Georgian',
            self::KK => 'Kazakh',
            self::KO => 'Korean',
            self::LT => 'Lithuanian',
            self::LV => 'Latvian',
            self::MK => 'Macedonian',
            self::ML => 'Malayalam',
            self::MN => 'Mongolian',
            self::MS => 'Malay',
            self::MT => 'Maltese',
            self::MY => 'Burmese',
            self::NB => 'Norwegian Bokmål',
            self::NL => 'Dutch',
            self::NN => 'Norwegian Nynorsk',
            self::NO => 'Norwegian',
            self::PL => 'Polish',
            self::PT => 'Portuguese',
            self::RO => 'Romanian',
            self::RU => 'Russian',
            self::SI => 'Sinhala',
            self::SK => 'Slovak',
            self::SL => 'Slovenian',
            self::SQ => 'Albanian',
            self::SR => 'Serbian',
            self::SV => 'Swedish',
            self::SW => 'Swahili',
            self::TA => 'Tamil',
            self::TE => 'Telugu',
            self::TH => 'Thai',
            self::TL => 'Tagalog',
            self::TR => 'Turkish',
            self::UK => 'Ukrainian',
            self::UR => 'Urdu',
            self::UZ => 'Uzbek',
            self::VI => 'Vietnamese',
            self::ZH => 'Chinese',
        };
    }

    /**
     * Get the French name of the language.
     */
    public function frenchName(): string
    {
        return match ($this) {
            self::AF => 'Afrikaans',
            self::AM => 'Amharique',
            self::AR => 'Arabe',
            self::AZ => 'Azéri',
            self::BE => 'Biélorusse',
            self::BG => 'Bulgare',
            self::BN => 'Bengali',
            self::CA => 'Catalan',
            self::CS => 'Tchèque',
            self::CY => 'Gallois',
            self::DA => 'Danois',
            self::DE => 'Allemand',
            self::EL => 'Grec',
            self::EN => 'Anglais',
            self::ES => 'Espagnol',
            self::ET => 'Estonien',
            self::EU => 'Basque',
            self::FA => 'Persan',
            self::FI => 'Finnois',
            self::FR => 'Français',
            self::GA => 'Irlandais',
            self::GL => 'Galicien',
            self::HE => 'Hébreu',
            self::HI => 'Hindi',
            self::HR => 'Croate',
            self::HU => 'Hongrois',
            self::HY => 'Arménien',
            self::ID => 'Indonésien',
            self::IS => 'Islandais',
            self::IT => 'Italien',
            self::JA => 'Japonais',
            self::KA => 'Géorgien',
            self::KK => 'Kazakh',
            self::KO => 'Coréen',
            self::LT => 'Lituanien',
            self::LV => 'Letton',
            self::MK => 'Macédonien',
            self::ML => 'Malayalam',
            self::MN => 'Mongol',
            self::MS => 'Malais',
            self::MT => 'Maltais',
            self::MY => 'Birman',
            self::NB => 'Norvégien bokmål',
            self::NL => 'Néerlandais',
            self::NN => 'Norvégien nynorsk',
            self::NO => 'Norvégien',
            self::PL => 'Polonais',
            self::PT => 'Portugais',
            self::RO => 'Roumain',
            self::RU => 'Russe',
            self::SI => 'Cingalais',
            self::SK => 'Slovaque',
            self::SL => 'Slovène',
            self::SQ => 'Albanais',
            self::SR => 'Serbe',
            self::SV => 'Suédois',
            self::SW => 'Swahili',
            self::TA => 'Tamoul',
            self::TE => 'Télougou',
            self::TH => 'Thaï',
            self::TL => 'Tagalog',
            self::TR => 'Turc',
            self::UK => 'Ukrainien',
            self::UR => 'Ourdou',
            self::UZ => 'Ouzbek',
            self::VI => 'Vietnamien',
            self::ZH => 'Chinois',
        };
    }

    /**
     * Get the ISO 639-2/T three-letter code.
     */
    public function iso6392(): string
    {
        return match ($this) {
            self::AF => 'afr',
            self::AM => 'amh',
            self::AR => 'ara',
            self::AZ => 'aze',
            self::BE => 'bel',
            self::BG => 'bul',
            self::BN => 'ben',
            self::CA => 'cat',
            self::CS => 'ces',
            self::CY => 'cym',
            self::DA => 'dan',
            self::DE => 'deu',
            self::EL => 'ell',
            self::EN => 'eng',
            self::ES => 'spa',
            self::ET => 'est',
            self::EU => 'eus',
            self::FA => 'fas',
            self::FI => 'fin',
            self::FR => 'fra',
            self::GA => 'gle',
            self::GL => 'glg',
            self::HE => 'heb',
            self::HI => 'hin',
            self::HR => 'hrv',
            self::HU => 'hun',
            self::HY => 'hye',
            self::ID => 'ind',
            self::IS => 'isl',
            self::IT => 'ita',
            self::JA => 'jpn',
            self::KA => 'kat',
            self::KK => 'kaz',
            self::KO => 'kor',
            self::LT => 'lit',
            self::LV => 'lav',
            self::MK => 'mkd',
            self::ML => 'mal',
            self::MN => 'mon',
            self::MS => 'msa',
            self::MT => 'mlt',
            self::MY => 'mya',
            self::NB => 'nob',
            self::NL => 'nld',
            self::NN => 'nno',
            self::NO => 'nor',
            self::PL => 'pol',
            self::PT => 'por',
            self::RO => 'ron',
            self::RU => 'rus',
            self::SI => 'sin',
            self::SK => 'slk',
            self::SL => 'slv',
            self::SQ => 'sqi',
            self::SR => 'srp',
            self::SV => 'swe',
            self::SW => 'swa',
            self::TA => 'tam',
            self::TE => 'tel',
            self::TH => 'tha',
            self::TL => 'tgl',
            self::TR => 'tur',
            self::UK => 'ukr',
            self::UR => 'urd',
            self::UZ => 'uzb',
            self::VI => 'vie',
            self::ZH => 'zho',
        };
    }

    /**
     * Get all language codes as an array.
     *
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get the default language.
     */
    public static function default(): self
    {
        return self::EN;
    }
}
