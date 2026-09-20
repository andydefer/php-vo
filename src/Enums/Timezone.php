<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Enums;

enum Timezone: string
{
    case AFRICA_ABIDJAN = 'Africa/Abidjan';
    case AFRICA_ACCRA = 'Africa/Accra';
    case AFRICA_ADDIS_ABABA = 'Africa/Addis_Ababa';
    case AFRICA_ALGIERS = 'Africa/Algiers';
    case AFRICA_BAMAKO = 'Africa/Bamako';
    case AFRICA_BANGUI = 'Africa/Bangui';
    case AFRICA_BANJUL = 'Africa/Banjul';
    case AFRICA_BISSAU = 'Africa/Bissau';
    case AFRICA_BLANTYRE = 'Africa/Blantyre';
    case AFRICA_BRAZZAVILLE = 'Africa/Brazzaville';
    case AFRICA_BUJUMBURA = 'Africa/Bujumbura';
    case AFRICA_CAIRO = 'Africa/Cairo';
    case AFRICA_CASABLANCA = 'Africa/Casablanca';
    case AFRICA_CEUTA = 'Africa/Ceuta';
    case AFRICA_CONAKRY = 'Africa/Conakry';
    case AFRICA_DAKAR = 'Africa/Dakar';
    case AFRICA_DAR_ES_SALAAM = 'Africa/Dar_es_Salaam';
    case AFRICA_DJIBOUTI = 'Africa/Djibouti';
    case AFRICA_DOUALA = 'Africa/Douala';
    case AFRICA_EL_AAIUN = 'Africa/El_Aaiun';
    case AFRICA_FREETOWN = 'Africa/Freetown';
    case AFRICA_GABORONE = 'Africa/Gaborone';
    case AFRICA_HARARE = 'Africa/Harare';
    case AFRICA_JOHANNESBURG = 'Africa/Johannesburg';
    case AFRICA_JUBA = 'Africa/Juba';
    case AFRICA_KAMPALA = 'Africa/Kampala';
    case AFRICA_KHARTOUM = 'Africa/Khartoum';
    case AFRICA_KIGALI = 'Africa/Kigali';
    case AFRICA_KINSHASA = 'Africa/Kinshasa';
    case AFRICA_LAGOS = 'Africa/Lagos';
    case AFRICA_LIBREVILLE = 'Africa/Libreville';
    case AFRICA_LOME = 'Africa/Lome';
    case AFRICA_LUANDA = 'Africa/Luanda';
    case AFRICA_LUBUMBASHI = 'Africa/Lubumbashi';
    case AFRICA_LUSAKA = 'Africa/Lusaka';
    case AFRICA_MALABO = 'Africa/Malabo';
    case AFRICA_MAPUTO = 'Africa/Maputo';
    case AFRICA_MASERU = 'Africa/Maseru';
    case AFRICA_MBABANE = 'Africa/Mbabane';
    case AFRICA_MOGADISHU = 'Africa/Mogadishu';
    case AFRICA_MONROVIA = 'Africa/Monrovia';
    case AFRICA_NAIROBI = 'Africa/Nairobi';
    case AFRICA_NDJAMENA = 'Africa/Ndjamena';
    case AFRICA_NIAMEY = 'Africa/Niamey';
    case AFRICA_NOUAKCHOTT = 'Africa/Nouakchott';
    case AFRICA_OUAGADOUGOU = 'Africa/Ouagadougou';
    case AFRICA_PORTO_NOVO = 'Africa/Porto-Novo';
    case AFRICA_SAO_TOME = 'Africa/Sao_Tome';
    case AFRICA_TRIPOLI = 'Africa/Tripoli';
    case AFRICA_TUNIS = 'Africa/Tunis';
    case AFRICA_WINDHOEK = 'Africa/Windhoek';

    case AMERICA_ANCHORAGE = 'America/Anchorage';
    case AMERICA_ARGENTINA_BUENOS_AIRES = 'America/Argentina/Buenos_Aires';
    case AMERICA_BOGOTA = 'America/Bogota';
    case AMERICA_CARACAS = 'America/Caracas';
    case AMERICA_CHICAGO = 'America/Chicago';
    case AMERICA_DENVER = 'America/Denver';
    case AMERICA_HALIFAX = 'America/Halifax';
    case AMERICA_LIMA = 'America/Lima';
    case AMERICA_LOS_ANGELES = 'America/Los_Angeles';
    case AMERICA_MEXICO_CITY = 'America/Mexico_City';
    case AMERICA_MONTERREY = 'America/Monterrey';
    case AMERICA_NEW_YORK = 'America/New_York';
    case AMERICA_PANAMA = 'America/Panama';
    case AMERICA_PHOENIX = 'America/Phoenix';
    case AMERICA_SANTIAGO = 'America/Santiago';
    case AMERICA_SAO_PAULO = 'America/Sao_Paulo';
    case AMERICA_TORONTO = 'America/Toronto';
    case AMERICA_VANCOUVER = 'America/Vancouver';

    case ASIA_BAGHDAD = 'Asia/Baghdad';
    case ASIA_BANGKOK = 'Asia/Bangkok';
    case ASIA_BEIRUT = 'Asia/Beirut';
    case ASIA_DHAKA = 'Asia/Dhaka';
    case ASIA_DUBAI = 'Asia/Dubai';
    case ASIA_HONG_KONG = 'Asia/Hong_Kong';
    case ASIA_JAKARTA = 'Asia/Jakarta';
    case ASIA_JERUSALEM = 'Asia/Jerusalem';
    case ASIA_KARACHI = 'Asia/Karachi';
    case ASIA_KATHMANDU = 'Asia/Kathmandu';
    case ASIA_KOLKATA = 'Asia/Kolkata';
    case ASIA_KUALA_LUMPUR = 'Asia/Kuala_Lumpur';
    case ASIA_MANILA = 'Asia/Manila';
    case ASIA_RIYADH = 'Asia/Riyadh';
    case ASIA_SEOUL = 'Asia/Seoul';
    case ASIA_SHANGHAI = 'Asia/Shanghai';
    case ASIA_SINGAPORE = 'Asia/Singapore';
    case ASIA_TAIPEI = 'Asia/Taipei';
    case ASIA_TEHRAN = 'Asia/Tehran';
    case ASIA_TOKYO = 'Asia/Tokyo';

    case ATLANTIC_AZORES = 'Atlantic/Azores';
    case ATLANTIC_CANARY = 'Atlantic/Canary';
    case ATLANTIC_REYKJAVIK = 'Atlantic/Reykjavik';

    case AUSTRALIA_ADELAIDE = 'Australia/Adelaide';
    case AUSTRALIA_BRISBANE = 'Australia/Brisbane';
    case AUSTRALIA_DARWIN = 'Australia/Darwin';
    case AUSTRALIA_HOBART = 'Australia/Hobart';
    case AUSTRALIA_MELBOURNE = 'Australia/Melbourne';
    case AUSTRALIA_PERTH = 'Australia/Perth';
    case AUSTRALIA_SYDNEY = 'Australia/Sydney';

    case EUROPE_AMSTERDAM = 'Europe/Amsterdam';
    case EUROPE_ATHENS = 'Europe/Athens';
    case EUROPE_BERLIN = 'Europe/Berlin';
    case EUROPE_BRUSSELS = 'Europe/Brussels';
    case EUROPE_BUCHAREST = 'Europe/Bucharest';
    case EUROPE_BUDAPEST = 'Europe/Budapest';
    case EUROPE_COPENHAGEN = 'Europe/Copenhagen';
    case EUROPE_DUBLIN = 'Europe/Dublin';
    case EUROPE_HELSINKI = 'Europe/Helsinki';
    case EUROPE_ISTANBUL = 'Europe/Istanbul';
    case EUROPE_LISBON = 'Europe/Lisbon';
    case EUROPE_LONDON = 'Europe/London';
    case EUROPE_MADRID = 'Europe/Madrid';
    case EUROPE_MOSCOW = 'Europe/Moscow';
    case EUROPE_OSLO = 'Europe/Oslo';
    case EUROPE_PARIS = 'Europe/Paris';
    case EUROPE_PRAGUE = 'Europe/Prague';
    case EUROPE_ROME = 'Europe/Rome';
    case EUROPE_STOCKHOLM = 'Europe/Stockholm';
    case EUROPE_VIENNA = 'Europe/Vienna';
    case EUROPE_WARSAW = 'Europe/Warsaw';
    case EUROPE_ZURICH = 'Europe/Zurich';

    case INDIAN_MAHE = 'Indian/Mahe';
    case INDIAN_MAURITIUS = 'Indian/Mauritius';
    case INDIAN_REUNION = 'Indian/Reunion';

    case PACIFIC_AUCKLAND = 'Pacific/Auckland';
    case PACIFIC_FIJI = 'Pacific/Fiji';
    case PACIFIC_HONOLULU = 'Pacific/Honolulu';

    case UTC = 'UTC';

    public function getLabel(): string
    {
        return match ($this) {
            self::AFRICA_ABIDJAN => 'Abidjan',
            self::AFRICA_ACCRA => 'Accra',
            self::AFRICA_ADDIS_ABABA => 'Addis-Abeba',
            self::AFRICA_ALGIERS => 'Alger',
            self::AFRICA_BAMAKO => 'Bamako',
            self::AFRICA_BANGUI => 'Bangui',
            self::AFRICA_BANJUL => 'Banjul',
            self::AFRICA_BISSAU => 'Bissau',
            self::AFRICA_BLANTYRE => 'Blantyre',
            self::AFRICA_BRAZZAVILLE => 'Brazzaville',
            self::AFRICA_BUJUMBURA => 'Bujumbura',
            self::AFRICA_CAIRO => 'Le Caire',
            self::AFRICA_CASABLANCA => 'Casablanca',
            self::AFRICA_CEUTA => 'Ceuta',
            self::AFRICA_CONAKRY => 'Conakry',
            self::AFRICA_DAKAR => 'Dakar',
            self::AFRICA_DAR_ES_SALAAM => 'Dar es Salam',
            self::AFRICA_DJIBOUTI => 'Djibouti',
            self::AFRICA_DOUALA => 'Douala',
            self::AFRICA_EL_AAIUN => 'Laâyoune',
            self::AFRICA_FREETOWN => 'Freetown',
            self::AFRICA_GABORONE => 'Gaborone',
            self::AFRICA_HARARE => 'Harare',
            self::AFRICA_JOHANNESBURG => 'Johannesburg',
            self::AFRICA_JUBA => 'Djouba',
            self::AFRICA_KAMPALA => 'Kampala',
            self::AFRICA_KHARTOUM => 'Khartoum',
            self::AFRICA_KIGALI => 'Kigali',
            self::AFRICA_KINSHASA => 'Kinshasa',
            self::AFRICA_LAGOS => 'Lagos',
            self::AFRICA_LIBREVILLE => 'Libreville',
            self::AFRICA_LOME => 'Lomé',
            self::AFRICA_LUANDA => 'Luanda',
            self::AFRICA_LUBUMBASHI => 'Lubumbashi',
            self::AFRICA_LUSAKA => 'Lusaka',
            self::AFRICA_MALABO => 'Malabo',
            self::AFRICA_MAPUTO => 'Maputo',
            self::AFRICA_MASERU => 'Maseru',
            self::AFRICA_MBABANE => 'Mbabane',
            self::AFRICA_MOGADISHU => 'Mogadiscio',
            self::AFRICA_MONROVIA => 'Monrovia',
            self::AFRICA_NAIROBI => 'Nairobi',
            self::AFRICA_NDJAMENA => 'N\'Djamena',
            self::AFRICA_NIAMEY => 'Niamey',
            self::AFRICA_NOUAKCHOTT => 'Nouakchott',
            self::AFRICA_OUAGADOUGOU => 'Ouagadougou',
            self::AFRICA_PORTO_NOVO => 'Porto-Novo',
            self::AFRICA_SAO_TOME => 'São Tomé',
            self::AFRICA_TRIPOLI => 'Tripoli',
            self::AFRICA_TUNIS => 'Tunis',
            self::AFRICA_WINDHOEK => 'Windhoek',
            self::AMERICA_ANCHORAGE => 'Anchorage',
            self::AMERICA_ARGENTINA_BUENOS_AIRES => 'Buenos Aires',
            self::AMERICA_BOGOTA => 'Bogota',
            self::AMERICA_CARACAS => 'Caracas',
            self::AMERICA_CHICAGO => 'Chicago',
            self::AMERICA_DENVER => 'Denver',
            self::AMERICA_HALIFAX => 'Halifax',
            self::AMERICA_LIMA => 'Lima',
            self::AMERICA_LOS_ANGELES => 'Los Angeles',
            self::AMERICA_MEXICO_CITY => 'Mexico',
            self::AMERICA_MONTERREY => 'Monterrey',
            self::AMERICA_NEW_YORK => 'New York',
            self::AMERICA_PANAMA => 'Panama',
            self::AMERICA_PHOENIX => 'Phoenix',
            self::AMERICA_SANTIAGO => 'Santiago',
            self::AMERICA_SAO_PAULO => 'São Paulo',
            self::AMERICA_TORONTO => 'Toronto',
            self::AMERICA_VANCOUVER => 'Vancouver',
            self::ASIA_BAGHDAD => 'Bagdad',
            self::ASIA_BANGKOK => 'Bangkok',
            self::ASIA_BEIRUT => 'Beyrouth',
            self::ASIA_DHAKA => 'Dhaka',
            self::ASIA_DUBAI => 'Dubaï',
            self::ASIA_HONG_KONG => 'Hong Kong',
            self::ASIA_JAKARTA => 'Jakarta',
            self::ASIA_JERUSALEM => 'Jérusalem',
            self::ASIA_KARACHI => 'Karachi',
            self::ASIA_KATHMANDU => 'Katmandou',
            self::ASIA_KOLKATA => 'Calcutta',
            self::ASIA_KUALA_LUMPUR => 'Kuala Lumpur',
            self::ASIA_MANILA => 'Manille',
            self::ASIA_RIYADH => 'Riyad',
            self::ASIA_SEOUL => 'Séoul',
            self::ASIA_SHANGHAI => 'Shanghai',
            self::ASIA_SINGAPORE => 'Singapour',
            self::ASIA_TAIPEI => 'Taipei',
            self::ASIA_TEHRAN => 'Téhéran',
            self::ASIA_TOKYO => 'Tokyo',
            self::ATLANTIC_AZORES => 'Açores',
            self::ATLANTIC_CANARY => 'Canaries',
            self::ATLANTIC_REYKJAVIK => 'Reykjavik',
            self::AUSTRALIA_ADELAIDE => 'Adélaïde',
            self::AUSTRALIA_BRISBANE => 'Brisbane',
            self::AUSTRALIA_DARWIN => 'Darwin',
            self::AUSTRALIA_HOBART => 'Hobart',
            self::AUSTRALIA_MELBOURNE => 'Melbourne',
            self::AUSTRALIA_PERTH => 'Perth',
            self::AUSTRALIA_SYDNEY => 'Sydney',
            self::EUROPE_AMSTERDAM => 'Amsterdam',
            self::EUROPE_ATHENS => 'Athènes',
            self::EUROPE_BERLIN => 'Berlin',
            self::EUROPE_BRUSSELS => 'Bruxelles',
            self::EUROPE_BUCHAREST => 'Bucarest',
            self::EUROPE_BUDAPEST => 'Budapest',
            self::EUROPE_COPENHAGEN => 'Copenhague',
            self::EUROPE_DUBLIN => 'Dublin',
            self::EUROPE_HELSINKI => 'Helsinki',
            self::EUROPE_ISTANBUL => 'Istanbul',
            self::EUROPE_LISBON => 'Lisbonne',
            self::EUROPE_LONDON => 'Londres',
            self::EUROPE_MADRID => 'Madrid',
            self::EUROPE_MOSCOW => 'Moscou',
            self::EUROPE_OSLO => 'Oslo',
            self::EUROPE_PARIS => 'Paris',
            self::EUROPE_PRAGUE => 'Prague',
            self::EUROPE_ROME => 'Rome',
            self::EUROPE_STOCKHOLM => 'Stockholm',
            self::EUROPE_VIENNA => 'Vienne',
            self::EUROPE_WARSAW => 'Varsovie',
            self::EUROPE_ZURICH => 'Zurich',
            self::INDIAN_MAHE => 'Mahé',
            self::INDIAN_MAURITIUS => 'Maurice',
            self::INDIAN_REUNION => 'La Réunion',
            self::PACIFIC_AUCKLAND => 'Auckland',
            self::PACIFIC_FIJI => 'Fidji',
            self::PACIFIC_HONOLULU => 'Honolulu',
            self::UTC => 'UTC',
        };
    }

    /**
     * Get the country associated with this timezone.
     */
    public function getCountry(): Country
    {
        return match ($this) {
            self::AFRICA_ABIDJAN => Country::CI,
            self::AFRICA_ACCRA => Country::GH,
            self::AFRICA_ADDIS_ABABA => Country::ET,
            self::AFRICA_ALGIERS => Country::DZ,
            self::AFRICA_BAMAKO => Country::ML,
            self::AFRICA_BANGUI => Country::CF,
            self::AFRICA_BANJUL => Country::GM,
            self::AFRICA_BISSAU => Country::GW,
            self::AFRICA_BLANTYRE => Country::MW,
            self::AFRICA_BRAZZAVILLE => Country::CG,
            self::AFRICA_BUJUMBURA => Country::BI,
            self::AFRICA_CAIRO => Country::EG,
            self::AFRICA_CASABLANCA => Country::MA,
            self::AFRICA_CEUTA => Country::ES,
            self::AFRICA_CONAKRY => Country::GN,
            self::AFRICA_DAKAR => Country::SN,
            self::AFRICA_DAR_ES_SALAAM => Country::TZ,
            self::AFRICA_DJIBOUTI => Country::DJ,
            self::AFRICA_DOUALA => Country::CM,
            self::AFRICA_EL_AAIUN => Country::EH,
            self::AFRICA_FREETOWN => Country::SL,
            self::AFRICA_GABORONE => Country::BW,
            self::AFRICA_HARARE => Country::ZW,
            self::AFRICA_JOHANNESBURG => Country::ZA,
            self::AFRICA_JUBA => Country::SS,
            self::AFRICA_KAMPALA => Country::UG,
            self::AFRICA_KHARTOUM => Country::SD,
            self::AFRICA_KIGALI => Country::RW,
            self::AFRICA_KINSHASA => Country::CD,
            self::AFRICA_LAGOS => Country::NG,
            self::AFRICA_LIBREVILLE => Country::GA,
            self::AFRICA_LOME => Country::TG,
            self::AFRICA_LUANDA => Country::AO,
            self::AFRICA_LUBUMBASHI => Country::CD,
            self::AFRICA_LUSAKA => Country::ZM,
            self::AFRICA_MALABO => Country::GQ,
            self::AFRICA_MAPUTO => Country::MZ,
            self::AFRICA_MASERU => Country::LS,
            self::AFRICA_MBABANE => Country::SZ,
            self::AFRICA_MOGADISHU => Country::SO,
            self::AFRICA_MONROVIA => Country::LR,
            self::AFRICA_NAIROBI => Country::KE,
            self::AFRICA_NDJAMENA => Country::TD,
            self::AFRICA_NIAMEY => Country::NE,
            self::AFRICA_NOUAKCHOTT => Country::MR,
            self::AFRICA_OUAGADOUGOU => Country::BF,
            self::AFRICA_PORTO_NOVO => Country::BJ,
            self::AFRICA_SAO_TOME => Country::ST,
            self::AFRICA_TRIPOLI => Country::LY,
            self::AFRICA_TUNIS => Country::TN,
            self::AFRICA_WINDHOEK => Country::NA,

            self::AMERICA_ANCHORAGE => Country::US,
            self::AMERICA_ARGENTINA_BUENOS_AIRES => Country::AR,
            self::AMERICA_BOGOTA => Country::CO,
            self::AMERICA_CARACAS => Country::VE,
            self::AMERICA_CHICAGO => Country::US,
            self::AMERICA_DENVER => Country::US,
            self::AMERICA_HALIFAX => Country::CA,
            self::AMERICA_LIMA => Country::PE,
            self::AMERICA_LOS_ANGELES => Country::US,
            self::AMERICA_MEXICO_CITY => Country::MX,
            self::AMERICA_MONTERREY => Country::MX,
            self::AMERICA_NEW_YORK => Country::US,
            self::AMERICA_PANAMA => Country::PA,
            self::AMERICA_PHOENIX => Country::US,
            self::AMERICA_SANTIAGO => Country::CL,
            self::AMERICA_SAO_PAULO => Country::BR,
            self::AMERICA_TORONTO => Country::CA,
            self::AMERICA_VANCOUVER => Country::CA,

            self::ASIA_BAGHDAD => Country::IQ,
            self::ASIA_BANGKOK => Country::TH,
            self::ASIA_BEIRUT => Country::LB,
            self::ASIA_DHAKA => Country::BD,
            self::ASIA_DUBAI => Country::AE,
            self::ASIA_HONG_KONG => Country::HK,
            self::ASIA_JAKARTA => Country::ID,
            self::ASIA_JERUSALEM => Country::IL,
            self::ASIA_KARACHI => Country::PK,
            self::ASIA_KATHMANDU => Country::NP,
            self::ASIA_KOLKATA => Country::IN,
            self::ASIA_KUALA_LUMPUR => Country::MY,
            self::ASIA_MANILA => Country::PH,
            self::ASIA_RIYADH => Country::SA,
            self::ASIA_SEOUL => Country::KR,
            self::ASIA_SHANGHAI => Country::CN,
            self::ASIA_SINGAPORE => Country::SG,
            self::ASIA_TAIPEI => Country::TW,
            self::ASIA_TEHRAN => Country::IR,
            self::ASIA_TOKYO => Country::JP,

            self::ATLANTIC_AZORES => Country::PT,
            self::ATLANTIC_CANARY => Country::ES,
            self::ATLANTIC_REYKJAVIK => Country::IS,

            self::AUSTRALIA_ADELAIDE => Country::AU,
            self::AUSTRALIA_BRISBANE => Country::AU,
            self::AUSTRALIA_DARWIN => Country::AU,
            self::AUSTRALIA_HOBART => Country::AU,
            self::AUSTRALIA_MELBOURNE => Country::AU,
            self::AUSTRALIA_PERTH => Country::AU,
            self::AUSTRALIA_SYDNEY => Country::AU,

            self::EUROPE_AMSTERDAM => Country::NL,
            self::EUROPE_ATHENS => Country::GR,
            self::EUROPE_BERLIN => Country::DE,
            self::EUROPE_BRUSSELS => Country::BE,
            self::EUROPE_BUCHAREST => Country::RO,
            self::EUROPE_BUDAPEST => Country::HU,
            self::EUROPE_COPENHAGEN => Country::DK,
            self::EUROPE_DUBLIN => Country::IE,
            self::EUROPE_HELSINKI => Country::FI,
            self::EUROPE_ISTANBUL => Country::TR,
            self::EUROPE_LISBON => Country::PT,
            self::EUROPE_LONDON => Country::GB,
            self::EUROPE_MADRID => Country::ES,
            self::EUROPE_MOSCOW => Country::RU,
            self::EUROPE_OSLO => Country::NO,
            self::EUROPE_PARIS => Country::FR,
            self::EUROPE_PRAGUE => Country::CZ,
            self::EUROPE_ROME => Country::IT,
            self::EUROPE_STOCKHOLM => Country::SE,
            self::EUROPE_VIENNA => Country::AT,
            self::EUROPE_WARSAW => Country::PL,
            self::EUROPE_ZURICH => Country::CH,

            self::INDIAN_MAHE => Country::SC,
            self::INDIAN_MAURITIUS => Country::MU,
            self::INDIAN_REUNION => Country::RE,

            self::PACIFIC_AUCKLAND => Country::NZ,
            self::PACIFIC_FIJI => Country::FJ,
            self::PACIFIC_HONOLULU => Country::US,

            self::UTC => Country::GB,
        };
    }

    /**
     * Get the numeric UTC offset for a given moment.
     */
    public function getOffset(\DateTimeInterface $at = new \DateTimeImmutable): string
    {
        $tz = new \DateTimeZone($this->value);
        $offset = $tz->getOffset($at);

        return $offset >= 0
            ? '+'.gmdate('H:i', $offset)
            : '-'.gmdate('H:i', -$offset);
    }

    /**
     * @return array<int, string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
