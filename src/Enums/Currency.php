<?php

declare(strict_types=1);

namespace AndyDefer\PhpVo\Enums;

/**
 * Enum representing world currencies with their ISO 4217 codes and symbols.
 *
 * @example
 * // Get currency by code
 * $currency = Currency::EUR;
 *
 * // Get ISO code
 * echo $currency->getIsoCode(); // "EUR"
 *
 * // Get symbol
 * echo $currency->getSymbol(); // "€"
 *
 * // Get display name
 * echo $currency->getDisplayName(); // "Euro"
 *
 * // From ISO code
 * $currency = Currency::fromIsoCode('USD'); // Currency::USD
 */
enum Currency: string
{
    // Devises majeures
    case AED = 'AED'; // Dirham des Émirats arabes unis
    case AFN = 'AFN'; // Afghani afghan
    case ALL = 'ALL'; // Lek albanais
    case AMD = 'AMD'; // Dram arménien
    case ANG = 'ANG'; // Florin des Antilles néerlandaises (historique)
    case AOA = 'AOA'; // Kwanza angolais
    case ARS = 'ARS'; // Peso argentin
    case AUD = 'AUD'; // Dollar australien
    case AWG = 'AWG'; // Florin arubais
    case AZN = 'AZN'; // Manat azerbaïdjanais

    // Devises européennes
    case BAM = 'BAM'; // Mark convertible de Bosnie-Herzégovine
    case BBD = 'BBD'; // Dollar barbadien
    case BDT = 'BDT'; // Taka bangladais
    case BGN = 'BGN'; // Lev bulgare (historique - remplacé par EUR au 01/01/2026)
    case BHD = 'BHD'; // Dinar bahreïni
    case BIF = 'BIF'; // Franc burundais
    case BMD = 'BMD'; // Dollar bermudien
    case BND = 'BND'; // Dollar brunéien
    case BOB = 'BOB'; // Boliviano bolivien
    case BRL = 'BRL'; // Réal brésilien
    case BSD = 'BSD'; // Dollar bahaméen
    case BTN = 'BTN'; // Ngultrum bhoutanais
    case BWP = 'BWP'; // Pula botswanais
    case BYN = 'BYN'; // Rouble biélorusse
    case BZD = 'BZD'; // Dollar bélizien

    case CAD = 'CAD'; // Dollar canadien
    case CDF = 'CDF'; // Franc congolais
    case CHF = 'CHF'; // Franc suisse
    case CLF = 'CLF'; // Unidad de Fomento (Chili)
    case CLP = 'CLP'; // Peso chilien
    case CNY = 'CNY'; // Yuan chinois
    case COP = 'COP'; // Peso colombien
    case COU = 'COU'; // Unidad de Valor Real (Colombie)
    case CRC = 'CRC'; // Colón costaricain
    case CUP = 'CUP'; // Peso cubain
    case CVE = 'CVE'; // Escudo cap-verdien
    case CZK = 'CZK'; // Couronne tchèque

    case DJF = 'DJF'; // Franc djiboutien
    case DKK = 'DKK'; // Couronne danoise
    case DOP = 'DOP'; // Peso dominicain
    case DZD = 'DZD'; // Dinar algérien

    case EGP = 'EGP'; // Livre égyptienne
    case ERN = 'ERN'; // Nakfa érythréen
    case ETB = 'ETB'; // Birr éthiopien
    case EUR = 'EUR'; // Euro (inclut Bulgarie depuis 2026)

    case FJD = 'FJD'; // Dollar fidjien
    case FKP = 'FKP'; // Livre des îles Malouines

    case GBP = 'GBP'; // Livre sterling
    case GEL = 'GEL'; // Lari géorgien
    case GHS = 'GHS'; // Cedi ghanéen
    case GIP = 'GIP'; // Livre de Gibraltar
    case GMD = 'GMD'; // Dalasi gambien
    case GNF = 'GNF'; // Franc guinéen
    case GTQ = 'GTQ'; // Quetzal guatémaltèque
    case GYD = 'GYD'; // Dollar guyanais

    case HKD = 'HKD'; // Dollar de Hong Kong
    case HNL = 'HNL'; // Lempira hondurien
    case HTG = 'HTG'; // Gourde haïtienne
    case HUF = 'HUF'; // Forint hongrois

    case IDR = 'IDR'; // Rupiah indonésienne
    case ILS = 'ILS'; // Nouveau shekel israélien
    case INR = 'INR'; // Roupie indienne
    case IQD = 'IQD'; // Dinar irakien
    case IRR = 'IRR'; // Rial iranien
    case ISK = 'ISK'; // Couronne islandaise

    case JMD = 'JMD'; // Dollar jamaïcain
    case JOD = 'JOD'; // Dinar jordanien
    case JPY = 'JPY'; // Yen japonais

    case KES = 'KES'; // Shilling kényan
    case KGS = 'KGS'; // Som kirghize
    case KHR = 'KHR'; // Riel cambodgien
    case KMF = 'KMF'; // Franc comorien
    case KPW = 'KPW'; // Won nord-coréen
    case KRW = 'KRW'; // Won sud-coréen
    case KWD = 'KWD'; // Dinar koweïtien
    case KYD = 'KYD'; // Dollar des îles Caïmans
    case KZT = 'KZT'; // Tenge kazakh

    case LAK = 'LAK'; // Kip laotien
    case LBP = 'LBP'; // Livre libanaise
    case LKR = 'LKR'; // Roupie srilankaise
    case LRD = 'LRD'; // Dollar libérien
    case LSL = 'LSL'; // Loti lesothan
    case LYD = 'LYD'; // Dinar libyen

    case MAD = 'MAD'; // Dirham marocain
    case MDL = 'MDL'; // Leu moldave
    case MGA = 'MGA'; // Ariary malgache
    case MKD = 'MKD'; // Denar macédonien
    case MMK = 'MMK'; // Kyat birman
    case MNT = 'MNT'; // Tugrik mongol
    case MOP = 'MOP'; // Pataca macanaise
    case MRU = 'MRU'; // Ouguiya mauritanien
    case MUR = 'MUR'; // Roupie mauricienne
    case MVR = 'MVR'; // Rufiyaa maldivienne
    case MWK = 'MWK'; // Kwacha malawite
    case MXN = 'MXN'; // Peso mexicain
    case MXV = 'MXV'; // Unidad de Inversion (Mexique)
    case MYR = 'MYR'; // Ringgit malaisien
    case MZN = 'MZN'; // Metical mozambicain

    case NAD = 'NAD'; // Dollar namibien
    case NGN = 'NGN'; // Naira nigérian
    case NIO = 'NIO'; // Córdoba oro nicaraguayen
    case NOK = 'NOK'; // Couronne norvégienne
    case NPR = 'NPR'; // Roupie népalaise
    case NZD = 'NZD'; // Dollar néo-zélandais

    case OMR = 'OMR'; // Rial omanais

    case PAB = 'PAB'; // Balboa panaméen
    case PEN = 'PEN'; // Sol péruvien
    case PGK = 'PGK'; // Kina papouan-néo-guinéenne
    case PHP = 'PHP'; // Peso philippin
    case PKR = 'PKR'; // Roupie pakistanaise
    case PLN = 'PLN'; // Złoty polonais
    case PYG = 'PYG'; // Guaraní paraguayen

    case QAR = 'QAR'; // Rial qatari

    case RON = 'RON'; // Leu roumain
    case RSD = 'RSD'; // Dinar serbe
    case RUB = 'RUB'; // Rouble russe
    case RWF = 'RWF'; // Franc rwandais

    case SAR = 'SAR'; // Riyal saoudien
    case SBD = 'SBD'; // Dollar des îles Salomon
    case SCR = 'SCR'; // Roupie seychelloise
    case SDG = 'SDG'; // Livre soudanaise
    case SEK = 'SEK'; // Couronne suédoise
    case SGD = 'SGD'; // Dollar de Singapour
    case SHP = 'SHP'; // Livre de Sainte-Hélène
    case SLE = 'SLE'; // Leone sierra-léonais
    case SOS = 'SOS'; // Shilling somalien
    case SRD = 'SRD'; // Dollar surinamais
    case SSP = 'SSP'; // Livre sud-soudanaise
    case STN = 'STN'; // Dobra santoméen
    case SVC = 'SVC'; // Colón salvadorien (historique)
    case SYP = 'SYP'; // Livre syrienne
    case SZL = 'SZL'; // Lilangeni swazilandais

    case THB = 'THB'; // Baht thaïlandais
    case TJS = 'TJS'; // Somoni tadjik
    case TMT = 'TMT'; // Manat turkmène
    case TND = 'TND'; // Dinar tunisien
    case TOP = 'TOP'; // Pa'anga tongien
    case TRY = 'TRY'; // Livre turque
    case TTD = 'TTD'; // Dollar trinidadien
    case TWD = 'TWD'; // Nouveau dollar taïwanais
    case TZS = 'TZS'; // Shilling tanzanien

    case UAH = 'UAH'; // Hryvnia ukrainienne
    case UGX = 'UGX'; // Shilling ougandais
    case USD = 'USD'; // Dollar américain
    case USN = 'USN'; // Dollar américain (jour suivant)
    case UYI = 'UYI'; // Unidades Indexadas (Uruguay)
    case UYU = 'UYU'; // Peso uruguayen
    case UYW = 'UYW'; // Unité prévisionnelle uruguayenne
    case UZS = 'UZS'; // Sum ouzbek

    case VED = 'VED'; // Bolívar souverain vénézuélien
    case VES = 'VES'; // Bolívar souverain vénézuélien
    case VND = 'VND'; // Dong vietnamien
    case VUV = 'VUV'; // Vatu vanuatais

    case WST = 'WST'; // Tala samoan

    case XAF = 'XAF'; // Franc CFA (CEMAC)
    case XAG = 'XAG'; // Argent (once troy)
    case XAU = 'XAU'; // Or (once troy)
    case XBA = 'XBA'; // Unité européenne composée (EURCO)
    case XBB = 'XBB'; // Unité monétaire européenne (EMU-6)
    case XBC = 'XBC'; // Unité de compte européenne 9 (EUA-9)
    case XBD = 'XBD'; // Unité de compte européenne 17 (EUA-17)
    case XCD = 'XCD'; // Dollar des Caraïbes orientales
    case XCG = 'XCG'; // Florin caribéen (Curaçao, Sint Maarten - 2025)
    case XDR = 'XDR'; // Droit de tirage spécial (FMI)
    case XOF = 'XOF'; // Franc CFA (UEMOA)
    case XPD = 'XPD'; // Palladium (once troy)
    case XPF = 'XPF'; // Franc CFP
    case XPT = 'XPT'; // Platine (once troy)
    case XSU = 'XSU'; // Sucre (Système Unitaire)
    case XUA = 'XUA'; // Unité de compte (ADB)
    case XXX = 'XXX'; // Aucune devise

    case YER = 'YER'; // Rial yéménite

    case ZAR = 'ZAR'; // Rand sud-africain
    case ZMW = 'ZMW'; // Kwacha zambien
    case ZWL = 'ZWL'; // Dollar zimbabwéen

    /**
     * Get the currency symbol.
     */
    public function getSymbol(): string
    {
        return match ($this) {
            // Devises majeures
            self::AED => 'د.إ',     // Dirham Émirats arabes unis
            self::AFN => '؋',       // Afghani afghan
            self::ALL => 'L',       // Lek albanais
            self::AMD => '֏',       // Dram arménien
            self::ANG => 'ƒ',       // Florin des Antilles néerlandaises
            self::AOA => 'Kz',      // Kwanza angolais
            self::ARS => '$',       // Peso argentin
            self::AUD => 'A$',      // Dollar australien
            self::AWG => 'ƒ',       // Florin arubais
            self::AZN => '₼',       // Manat azerbaïdjanais

            // Devises européennes
            self::BAM => 'KM',      // Mark convertible
            self::BBD => '$',       // Dollar barbadien
            self::BDT => '৳',       // Taka bangladais
            self::BGN => 'лв',      // Lev bulgare
            self::BHD => '.د.ب',    // Dinar bahreïni
            self::BIF => 'FBu',     // Franc burundais
            self::BMD => '$',       // Dollar bermudien
            self::BND => '$',       // Dollar brunéien
            self::BOB => 'Bs',      // Boliviano bolivien
            self::BRL => 'R$',      // Réal brésilien
            self::BSD => '$',       // Dollar bahaméen
            self::BTN => 'Nu.',     // Ngultrum bhoutanais
            self::BWP => 'P',       // Pula botswanais
            self::BYN => 'Br',      // Rouble biélorusse
            self::BZD => '$',       // Dollar bélizien

            self::CAD => 'C$',      // Dollar canadien
            self::CDF => 'FC',      // Franc congolais
            self::CHF => 'CHF',     // Franc suisse
            self::CLF => 'UF',      // Unidad de Fomento
            self::CLP => '$',       // Peso chilien
            self::CNY => '¥',       // Yuan chinois
            self::COP => '$',       // Peso colombien
            self::COU => 'COU',     // Unidad de Valor Real
            self::CRC => '₡',       // Colón costaricain
            self::CUP => '$',       // Peso cubain
            self::CVE => '$',       // Escudo cap-verdien
            self::CZK => 'Kč',      // Couronne tchèque

            self::DJF => 'Fdj',     // Franc djiboutien
            self::DKK => 'kr',      // Couronne danoise
            self::DOP => '$',       // Peso dominicain
            self::DZD => 'د.ج',     // Dinar algérien

            self::EGP => 'E£',      // Livre égyptienne
            self::ERN => 'Nfk',     // Nakfa érythréen
            self::ETB => 'Br',      // Birr éthiopien
            self::EUR => '€',       // Euro

            self::FJD => '$',       // Dollar fidjien
            self::FKP => '£',       // Livre des îles Malouines

            self::GBP => '£',       // Livre sterling
            self::GEL => '₾',       // Lari géorgien
            self::GHS => '₵',       // Cedi ghanéen
            self::GIP => '£',       // Livre de Gibraltar
            self::GMD => 'D',       // Dalasi gambien
            self::GNF => 'FG',      // Franc guinéen
            self::GTQ => 'Q',       // Quetzal guatémaltèque
            self::GYD => '$',       // Dollar guyanais

            self::HKD => 'HK$',     // Dollar de Hong Kong
            self::HNL => 'L',       // Lempira hondurien
            self::HTG => 'G',       // Gourde haïtienne
            self::HUF => 'Ft',      // Forint hongrois

            self::IDR => 'Rp',      // Rupiah indonésienne
            self::ILS => '₪',       // Nouveau shekel israélien
            self::INR => '₹',       // Roupie indienne
            self::IQD => 'ع.د',     // Dinar irakien
            self::IRR => '﷼',       // Rial iranien
            self::ISK => 'kr',      // Couronne islandaise

            self::JMD => '$',       // Dollar jamaïcain
            self::JOD => 'د.ا',     // Dinar jordanien
            self::JPY => '¥',       // Yen japonais

            self::KES => 'KSh',     // Shilling kényan
            self::KGS => 'с',       // Som kirghize
            self::KHR => '៛',       // Riel cambodgien
            self::KMF => 'CF',      // Franc comorien
            self::KPW => '₩',       // Won nord-coréen
            self::KRW => '₩',       // Won sud-coréen
            self::KWD => 'د.ك',     // Dinar koweïtien
            self::KYD => '$',       // Dollar des îles Caïmans
            self::KZT => '₸',       // Tenge kazakh

            self::LAK => '₭',       // Kip laotien
            self::LBP => 'ل.ل',     // Livre libanaise
            self::LKR => 'Rs',      // Roupie srilankaise
            self::LRD => '$',       // Dollar libérien
            self::LSL => 'L',       // Loti lesothan
            self::LYD => 'ل.د',     // Dinar libyen

            self::MAD => 'د.م.',    // Dirham marocain
            self::MDL => 'L',       // Leu moldave
            self::MGA => 'Ar',      // Ariary malgache
            self::MKD => 'ден',     // Denar macédonien
            self::MMK => 'K',       // Kyat birman
            self::MNT => '₮',       // Tugrik mongol
            self::MOP => 'MOP$',    // Pataca macanaise
            self::MRU => 'UM',      // Ouguiya mauritanien
            self::MUR => 'Rs',      // Roupie mauricienne
            self::MVR => 'Rf',      // Rufiyaa maldivienne
            self::MWK => 'MK',      // Kwacha malawite
            self::MXN => '$',       // Peso mexicain
            self::MXV => 'UDI',     // Unidad de Inversion
            self::MYR => 'RM',      // Ringgit malaisien
            self::MZN => 'MT',      // Metical mozambicain

            self::NAD => '$',       // Dollar namibien
            self::NGN => '₦',       // Naira nigérian
            self::NIO => 'C$',      // Córdoba oro nicaraguayen
            self::NOK => 'kr',      // Couronne norvégienne
            self::NPR => 'Rs',      // Roupie népalaise
            self::NZD => 'NZ$',     // Dollar néo-zélandais

            self::OMR => 'ر.ع.',    // Rial omanais

            self::PAB => 'B/.',     // Balboa panaméen
            self::PEN => 'S/',      // Sol péruvien
            self::PGK => 'K',       // Kina papouan-néo-guinéenne
            self::PHP => '₱',       // Peso philippin
            self::PKR => 'Rs',      // Roupie pakistanaise
            self::PLN => 'zł',      // Złoty polonais
            self::PYG => '₲',       // Guaraní paraguayen

            self::QAR => 'ر.ق',     // Rial qatari

            self::RON => 'lei',     // Leu roumain
            self::RSD => 'дин.',    // Dinar serbe
            self::RUB => '₽',       // Rouble russe
            self::RWF => 'FRw',     // Franc rwandais

            self::SAR => '﷼',       // Riyal saoudien
            self::SBD => '$',       // Dollar des îles Salomon
            self::SCR => 'Rs',      // Roupie seychelloise
            self::SDG => 'ج.س.',    // Livre soudanaise
            self::SEK => 'kr',      // Couronne suédoise
            self::SGD => 'S$',      // Dollar de Singapour
            self::SHP => '£',       // Livre de Sainte-Hélène
            self::SLE => 'Le',      // Leone sierra-léonais
            self::SOS => 'Sh',      // Shilling somalien
            self::SRD => '$',       // Dollar surinamais
            self::SSP => '£',       // Livre sud-soudanaise
            self::STN => 'Db',      // Dobra santoméen
            self::SVC => '₡',       // Colón salvadorien
            self::SYP => '£S',      // Livre syrienne
            self::SZL => 'L',       // Lilangeni swazilandais

            self::THB => '฿',       // Baht thaïlandais
            self::TJS => 'SM',      // Somoni tadjik
            self::TMT => 'm',       // Manat turkmène
            self::TND => 'د.ت',     // Dinar tunisien
            self::TOP => 'T$',      // Pa'anga tongien
            self::TRY => '₺',       // Livre turque
            self::TTD => '$',       // Dollar trinidadien
            self::TWD => 'NT$',     // Nouveau dollar taïwanais
            self::TZS => 'TSh',     // Shilling tanzanien

            self::UAH => '₴',       // Hryvnia ukrainienne
            self::UGX => 'USh',     // Shilling ougandais
            self::USD => '$',       // Dollar américain
            self::USN => '$',       // Dollar américain (jour suivant)
            self::UYI => 'UYI',     // Unidades Indexadas
            self::UYU => '$U',      // Peso uruguayen
            self::UYW => 'UYW',     // Unité prévisionnelle uruguayenne
            self::UZS => 'so\'m',   // Sum ouzbek

            self::VED => 'Bs.S',    // Bolívar souverain
            self::VES => 'Bs.S',    // Bolívar souverain
            self::VND => '₫',       // Dong vietnamien
            self::VUV => 'VT',      // Vatu vanuatais

            self::WST => 'WS$',     // Tala samoan

            self::XAF => 'FCFA',    // Franc CFA (CEMAC)
            self::XAG => 'XAG',     // Argent
            self::XAU => 'XAU',     // Or
            self::XBA => 'XBA',     // Unité européenne composée
            self::XBB => 'XBB',     // Unité monétaire européenne
            self::XBC => 'XBC',     // Unité de compte européenne 9
            self::XBD => 'XBD',     // Unité de compte européenne 17
            self::XCD => '$',       // Dollar des Caraïbes orientales
            self::XCG => 'ƒ',       // Florin caribéen
            self::XDR => 'XDR',     // Droit de tirage spécial
            self::XOF => 'CFA',     // Franc CFA (UEMOA)
            self::XPD => 'XPD',     // Palladium
            self::XPF => 'FCFP',    // Franc CFP
            self::XPT => 'XPT',     // Platine
            self::XSU => 'XSU',     // Sucre
            self::XUA => 'XUA',     // Unité de compte ADB
            self::XXX => 'XXX',     // Aucune devise

            self::YER => '﷼',       // Rial yéménite

            self::ZAR => 'R',       // Rand sud-africain
            self::ZMW => 'ZK',      // Kwacha zambien
            self::ZWL => '$',       // Dollar zimbabwéen
        };
    }

    public function getDisplayName(): string
    {
        return match ($this) {
            // Devises majeures
            self::AED => 'Dirham des Émirats arabes unis',
            self::AFN => 'Afghani afghan',
            self::ALL => 'Lek albanais',
            self::AMD => 'Dram arménien',
            self::ANG => 'Florin des Antilles néerlandaises',
            self::AOA => 'Kwanza angolais',
            self::ARS => 'Peso argentin',
            self::AUD => 'Dollar australien',
            self::AWG => 'Florin arubais',
            self::AZN => 'Manat azerbaïdjanais',

            // Devises européennes
            self::BAM => 'Mark convertible de Bosnie-Herzégovine',
            self::BBD => 'Dollar barbadien',
            self::BDT => 'Taka bangladais',
            self::BGN => 'Lev bulgare',
            self::BHD => 'Dinar bahreïni',
            self::BIF => 'Franc burundais',
            self::BMD => 'Dollar bermudien',
            self::BND => 'Dollar brunéien',
            self::BOB => 'Boliviano bolivien',
            self::BRL => 'Réal brésilien',
            self::BSD => 'Dollar bahaméen',
            self::BTN => 'Ngultrum bhoutanais',
            self::BWP => 'Pula botswanais',
            self::BYN => 'Rouble biélorusse',
            self::BZD => 'Dollar bélizien',

            self::CAD => 'Dollar canadien',
            self::CDF => 'Franc congolais',
            self::CHF => 'Franc suisse',
            self::CLF => 'Unidad de Fomento (Chili)',
            self::CLP => 'Peso chilien',
            self::CNY => 'Yuan chinois',
            self::COP => 'Peso colombien',
            self::COU => 'Unidad de Valor Real (Colombie)',
            self::CRC => 'Colón costaricain',
            self::CUP => 'Peso cubain',
            self::CVE => 'Escudo cap-verdien',
            self::CZK => 'Couronne tchèque',

            self::DJF => 'Franc djiboutien',
            self::DKK => 'Couronne danoise',
            self::DOP => 'Peso dominicain',
            self::DZD => 'Dinar algérien',

            self::EGP => 'Livre égyptienne',
            self::ERN => 'Nakfa érythréen',
            self::ETB => 'Birr éthiopien',
            self::EUR => 'Euro',

            self::FJD => 'Dollar fidjien',
            self::FKP => 'Livre des îles Malouines',

            self::GBP => 'Livre sterling',
            self::GEL => 'Lari géorgien',
            self::GHS => 'Cedi ghanéen',
            self::GIP => 'Livre de Gibraltar',
            self::GMD => 'Dalasi gambien',
            self::GNF => 'Franc guinéen',
            self::GTQ => 'Quetzal guatémaltèque',
            self::GYD => 'Dollar guyanais',

            self::HKD => 'Dollar de Hong Kong',
            self::HNL => 'Lempira hondurien',
            self::HTG => 'Gourde haïtienne',
            self::HUF => 'Forint hongrois',

            self::IDR => 'Rupiah indonésienne',
            self::ILS => 'Nouveau shekel israélien',
            self::INR => 'Roupie indienne',
            self::IQD => 'Dinar irakien',
            self::IRR => 'Rial iranien',
            self::ISK => 'Couronne islandaise',

            self::JMD => 'Dollar jamaïcain',
            self::JOD => 'Dinar jordanien',
            self::JPY => 'Yen japonais',

            self::KES => 'Shilling kényan',
            self::KGS => 'Som kirghize',
            self::KHR => 'Riel cambodgien',
            self::KMF => 'Franc comorien',
            self::KPW => 'Won nord-coréen',
            self::KRW => 'Won sud-coréen',
            self::KWD => 'Dinar koweïtien',
            self::KYD => 'Dollar des îles Caïmans',
            self::KZT => 'Tenge kazakh',

            self::LAK => 'Kip laotien',
            self::LBP => 'Livre libanaise',
            self::LKR => 'Roupie srilankaise',
            self::LRD => 'Dollar libérien',
            self::LSL => 'Loti lesothan',
            self::LYD => 'Dinar libyen',

            self::MAD => 'Dirham marocain',
            self::MDL => 'Leu moldave',
            self::MGA => 'Ariary malgache',
            self::MKD => 'Denar macédonien',
            self::MMK => 'Kyat birman',
            self::MNT => 'Tugrik mongol',
            self::MOP => 'Pataca macanaise',
            self::MRU => 'Ouguiya mauritanien',
            self::MUR => 'Roupie mauricienne',
            self::MVR => 'Rufiyaa maldivienne',
            self::MWK => 'Kwacha malawite',
            self::MXN => 'Peso mexicain',
            self::MXV => 'Unidad de Inversion (Mexique)',
            self::MYR => 'Ringgit malaisien',
            self::MZN => 'Metical mozambicain',

            self::NAD => 'Dollar namibien',
            self::NGN => 'Naira nigérian',
            self::NIO => 'Córdoba oro nicaraguayen',
            self::NOK => 'Couronne norvégienne',
            self::NPR => 'Roupie népalaise',
            self::NZD => 'Dollar néo-zélandais',

            self::OMR => 'Rial omanais',

            self::PAB => 'Balboa panaméen',
            self::PEN => 'Sol péruvien',
            self::PGK => 'Kina papouan-néo-guinéenne',
            self::PHP => 'Peso philippin',
            self::PKR => 'Roupie pakistanaise',
            self::PLN => 'Złoty polonais',
            self::PYG => 'Guaraní paraguayen',

            self::QAR => 'Rial qatari',

            self::RON => 'Leu roumain',
            self::RSD => 'Dinar serbe',
            self::RUB => 'Rouble russe',
            self::RWF => 'Franc rwandais',

            self::SAR => 'Riyal saoudien',
            self::SBD => 'Dollar des îles Salomon',
            self::SCR => 'Roupie seychelloise',
            self::SDG => 'Livre soudanaise',
            self::SEK => 'Couronne suédoise',
            self::SGD => 'Dollar de Singapour',
            self::SHP => 'Livre de Sainte-Hélène',
            self::SLE => 'Leone sierra-léonais',
            self::SOS => 'Shilling somalien',
            self::SRD => 'Dollar surinamais',
            self::SSP => 'Livre sud-soudanaise',
            self::STN => 'Dobra santoméen',
            self::SVC => 'Colón salvadorien',
            self::SYP => 'Livre syrienne',
            self::SZL => 'Lilangeni swazilandais',

            self::THB => 'Baht thaïlandais',
            self::TJS => 'Somoni tadjik',
            self::TMT => 'Manat turkmène',
            self::TND => 'Dinar tunisien',
            self::TOP => 'Pa\'anga tongien',
            self::TRY => 'Livre turque',
            self::TTD => 'Dollar trinidadien',
            self::TWD => 'Nouveau dollar taïwanais',
            self::TZS => 'Shilling tanzanien',

            self::UAH => 'Hryvnia ukrainienne',
            self::UGX => 'Shilling ougandais',
            self::USD => 'Dollar américain',
            self::USN => 'Dollar américain (jour suivant)',
            self::UYI => 'Unidades Indexadas (Uruguay)',
            self::UYU => 'Peso uruguayen',
            self::UYW => 'Unité prévisionnelle uruguayenne',
            self::UZS => 'Sum ouzbek',

            self::VED => 'Bolívar souverain vénézuélien',
            self::VES => 'Bolívar souverain vénézuélien',
            self::VND => 'Dong vietnamien',
            self::VUV => 'Vatu vanuatais',

            self::WST => 'Tala samoan',

            self::XAF => 'Franc CFA (CEMAC - Afrique centrale)',
            self::XAG => 'Argent (once troy)',
            self::XAU => 'Or (once troy)',
            self::XBA => 'Unité européenne composée (EURCO)',
            self::XBB => 'Unité monétaire européenne (EMU-6)',
            self::XBC => 'Unité de compte européenne 9 (EUA-9)',
            self::XBD => 'Unité de compte européenne 17 (EUA-17)',
            self::XCD => 'Dollar des Caraïbes orientales',
            self::XCG => 'Florin caribéen (Curaçao, Sint Maarten)',
            self::XDR => 'Droit de tirage spécial (FMI)',
            self::XOF => 'Franc CFA (UEMOA - Afrique de l\'Ouest)',
            self::XPD => 'Palladium (once troy)',
            self::XPF => 'Franc CFP (Polynésie française, Nouvelle-Calédonie)',
            self::XPT => 'Platine (once troy)',
            self::XSU => 'Sucre (Système Unitaire de Compensation Régionale)',
            self::XUA => 'Unité de compte (ADB - Banque africaine de développement)',
            self::XXX => 'Aucune devise',

            self::YER => 'Rial yéménite',

            self::ZAR => 'Rand sud-africain',
            self::ZMW => 'Kwacha zambien',
            self::ZWL => 'Dollar zimbabwéen',
        };
    }
}
