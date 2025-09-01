<?php

namespace Omnipay\Powertranz\Traits;

use Omnipay\Common\Exception\InvalidRequestException;

trait CurrencyConversionTrait
{
    public function getCurrencyNumeric()
    {
        $currencies = [
            // Major currencies
            'AED' => '784', // UAE Dirham
            'AFN' => '971', // Afghani
            'ALL' => '008', // Lek
            'AMD' => '051', // Armenian Dram
            'ANG' => '532', // Netherlands Antillean Guilder
            'AOA' => '973', // Kwanza
            'ARS' => '032', // Argentine Peso
            'AUD' => '036', // Australian Dollar
            'AWG' => '533', // Aruban Florin
            'AZN' => '944', // Azerbaijan Manat
            'BAM' => '977', // Convertible Mark
            'BBD' => '052', // Barbados Dollar
            'BDT' => '050', // Taka
            'BGN' => '975', // Bulgarian Lev
            'BHD' => '048', // Bahraini Dinar
            'BIF' => '108', // Burundi Franc
            'BMD' => '060', // Bermudian Dollar
            'BND' => '096', // Brunei Dollar
            'BOB' => '068', // Boliviano
            'BOV' => '984', // Mvdol
            'BRL' => '986', // Brazilian Real
            'BSD' => '044', // Bahamian Dollar
            'BTN' => '064', // Ngultrum
            'BWP' => '072', // Pula
            'BYN' => '933', // Belarusian Ruble
            'BZD' => '084', // Belize Dollar
            'CAD' => '124', // Canadian Dollar
            'CDF' => '976', // Congolese Franc
            'CHE' => '947', // WIR Euro
            'CHF' => '756', // Swiss Franc
            'CHW' => '948', // WIR Franc
            'CLF' => '990', // Unidad de Fomento
            'CLP' => '152', // Chilean Peso
            'CNY' => '156', // Yuan Renminbi
            'COP' => '170', // Colombian Peso
            'COU' => '970', // Unidad de Valor Real
            'CRC' => '188', // Costa Rican Colon
            'CUC' => '931', // Peso Convertible
            'CUP' => '192', // Cuban Peso
            'CVE' => '132', // Cabo Verde Escudo
            'CZK' => '203', // Czech Koruna
            'DJF' => '262', // Djibouti Franc
            'DKK' => '208', // Danish Krone
            'DOP' => '214', // Dominican Peso
            'DZD' => '012', // Algerian Dinar
            'EGP' => '818', // Egyptian Pound
            'ERN' => '232', // Nakfa
            'ETB' => '230', // Ethiopian Birr
            'EUR' => '978', // Euro
            'FJD' => '242', // Fiji Dollar
            'FKP' => '238', // Falkland Islands Pound
            'GBP' => '826', // Pound Sterling
            'GEL' => '981', // Lari
            'GHS' => '936', // Ghana Cedi
            'GIP' => '292', // Gibraltar Pound
            'GMD' => '270', // Dalasi
            'GNF' => '324', // Guinean Franc
            'GTQ' => '320', // Quetzal
            'GYD' => '328', // Guyana Dollar
            'HKD' => '344', // Hong Kong Dollar
            'HNL' => '340', // Lempira
            'HRK' => '191', // Kuna
            'HTG' => '332', // Gourde
            'HUF' => '348', // Forint
            'IDR' => '360', // Rupiah
            'ILS' => '376', // New Israeli Sheqel
            'INR' => '356', // Indian Rupee
            'IQD' => '368', // Iraqi Dinar
            'IRR' => '364', // Iranian Rial
            'ISK' => '352', // Iceland Krona
            'JMD' => '388', // Jamaican Dollar
            'JOD' => '400', // Jordanian Dinar
            'JPY' => '392', // Yen
            'KES' => '404', // Kenyan Shilling
            'KGS' => '417', // Som
            'KHR' => '116', // Riel
            'KMF' => '174', // Comorian Franc
            'KPW' => '408', // North Korean Won
            'KRW' => '410', // Won
            'KWD' => '414', // Kuwaiti Dinar
            'KYD' => '136', // Cayman Islands Dollar
            'KZT' => '398', // Tenge
            'LAK' => '418', // Lao Kip
            'LBP' => '422', // Lebanese Pound
            'LKR' => '144', // Sri Lanka Rupee
            'LRD' => '430', // Liberian Dollar
            'LSL' => '426', // Loti
            'LYD' => '434', // Libyan Dinar
            'MAD' => '504', // Moroccan Dirham
            'MDL' => '498', // Moldovan Leu
            'MGA' => '969', // Malagasy Ariary
            'MKD' => '807', // Denar
            'MMK' => '104', // Kyat
            'MNT' => '496', // Tugrik
            'MOP' => '446', // Pataca
            'MRU' => '929', // Ouguiya
            'MUR' => '480', // Mauritius Rupee
            'MVR' => '462', // Rufiyaa
            'MWK' => '454', // Malawi Kwacha
            'MXN' => '484', // Mexican Peso
            'MXV' => '979', // Mexican Unidad de Inversion (UDI)
            'MYR' => '458', // Malaysian Ringgit
            'MZN' => '943', // Mozambique Metical
            'NAD' => '516', // Namibia Dollar
            'NGN' => '566', // Naira
            'NIO' => '558', // Cordoba Oro
            'NOK' => '578', // Norwegian Krone
            'NPR' => '524', // Nepalese Rupee
            'NZD' => '554', // New Zealand Dollar
            'OMR' => '512', // Rial Omani
            'PAB' => '590', // Balboa
            'PEN' => '604', // Sol
            'PGK' => '598', // Kina
            'PHP' => '608', // Philippine Peso
            'PKR' => '586', // Pakistan Rupee
            'PLN' => '985', // Zloty
            'PYG' => '600', // Guarani
            'QAR' => '634', // Qatari Rial
            'RON' => '946', // Romanian Leu
            'RSD' => '941', // Serbian Dinar
            'RUB' => '643', // Russian Ruble
            'RWF' => '646', // Rwanda Franc
            'SAR' => '682', // Saudi Riyal
            'SBD' => '090', // Solomon Islands Dollar
            'SCR' => '690', // Seychelles Rupee
            'SDG' => '938', // Sudanese Pound
            'SEK' => '752', // Swedish Krona
            'SGD' => '702', // Singapore Dollar
            'SHP' => '654', // Saint Helena Pound
            'SLE' => '925', // Leone
            'SLL' => '694', // Leone (old)
            'SOS' => '706', // Somali Shilling
            'SRD' => '968', // Surinam Dollar
            'SSP' => '728', // South Sudanese Pound
            'STN' => '930', // Dobra
            'SVC' => '222', // El Salvador Colon
            'SYP' => '760', // Syrian Pound
            'SZL' => '748', // Lilangeni
            'THB' => '764', // Baht
            'TJS' => '972', // Somoni
            'TMT' => '934', // Turkmenistan New Manat
            'TND' => '788', // Tunisian Dinar
            'TOP' => '776', // Pa'anga
            'TRY' => '949', // Turkish Lira
            'TTD' => '780', // Trinidad and Tobago Dollar
            'TVD' => '036', // Tuvalu Dollar (uses AUD code)
            'TWD' => '901', // New Taiwan Dollar
            'TZS' => '834', // Tanzanian Shilling
            'UAH' => '980', // Hryvnia
            'UGX' => '800', // Uganda Shilling
            'USD' => '840', // US Dollar
            'USN' => '997', // US Dollar (Next day)
            'UYI' => '940', // Uruguay Peso en Unidades Indexadas (UI)
            'UYU' => '858', // Uruguayan Peso
            'UYW' => '927', // Unidad Previsional
            'UZS' => '860', // Uzbekistan Sum
            'VED' => '926', // Bolívar Digital
            'VES' => '928', // Bolívar Soberano
            'VND' => '704', // Dong
            'VUV' => '548', // Vatu
            'WST' => '882', // Tala
            'XAF' => '950', // CFA Franc BEAC
            'XAG' => '961', // Silver
            'XAU' => '959', // Gold
            'XBA' => '955', // Bond Markets Unit European Composite Unit (EURCO)
            'XBB' => '956', // Bond Markets Unit European Monetary Unit (E.M.U.-6)
            'XBC' => '957', // Bond Markets Unit European Unit of Account 9 (E.U.A.-9)
            'XBD' => '958', // Bond Markets Unit European Unit of Account 17 (E.U.A.-17)
            'XCD' => '951', // East Caribbean Dollar
            'XDR' => '960', // SDR (Special Drawing Right)
            'XOF' => '952', // CFA Franc BCEAO
            'XPD' => '964', // Palladium
            'XPF' => '953', // CFP Franc
            'XPT' => '962', // Platinum
            'XSU' => '994', // Sucre
            'XTS' => '963', // Codes specifically reserved for testing purposes
            'XUA' => '965', // ADB Unit of Account
            'XXX' => '999', // The codes assigned for transactions where no currency is involved
            'YER' => '886', // Yemeni Rial
            'ZAR' => '710', // Rand
            'ZMW' => '967', // Zambian Kwacha
            'ZWL' => '932', // Zimbabwe Dollar
        ];

        $currency = strtoupper($this->getCurrency());
        
        if (isset($currencies[$currency])) {
            return $currencies[$currency];
        }

        // If not in our map, check if it's already numeric
        if (is_numeric($currency) && strlen($currency) === 3) {
            return $currency;
        }

        throw new InvalidRequestException('Unsupported or invalid currency code: ' . $currency);
    }
}