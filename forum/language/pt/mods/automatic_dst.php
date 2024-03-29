<?php

/**
*
* automatic_dst.php [English]
*
* @package - "Automatic Daylight Savings Time 2"
* @version $Id: automatic_dst.php 3 2009-03-28 MartectX $
* @copyright (C)2008-2009, MartectX ( http://mods.martectx.de/ )
* @author 2009-11-16 - Translated by Nuno Nina ( http://www.multiracingfsr.com/ ) 
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/**
* DO NOT CHANGE
*/
if (!defined('IN_PHPBB'))
{
	exit;
}

if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

// DEVELOPERS PLEASE NOTE
//
// All language files should use UTF-8 as their encoding and the files must not contain a BOM.
//
// Placeholders can now contain order information, e.g. instead of
// 'Page %s of %s' you can (and should) write 'Page %1$s of %2$s', this allows
// translators to re-order the output of data while ensuring it remains correct
//
// You do not need this where single placeholders are used, e.g. 'Message %d' is fine
// equally where a string contains only two placeholders which are used to wrap text
// in a url you again do not need to specify an order e.g., 'Click %sHERE%s' is fine
//
// Some characters you may want to copy&paste:
// ’ » “ ” …
//

$lang = array_merge($lang, array(
	'AUTOMATIC_DST_DISPLAY'		=> 'Fuso horário: %1$s %2$s',
	'AUTOMATIC_DST_SETUP'		=> 'Primeiro vá às Configurações do Fórum e escolha um novo fuso horário válido (se já estiver correcto aprove clicando em "Submeter").<br /><br /><strong>Os fusos horários não foram convertidos!</strong>',
	'AUTOMATIC_DST_INSTALLED'	=> 'A conversão dos fusos horários foi concluída.<br /><br /><strong>Por favor remova este ficheiro do seu servidor!</strong>',

	'automatic_dst_timezones'	=> array(

		'Africa/' => 'África/',
		// 'Abidjan' => 'Abidjan',
		'Accra' => 'Acra',
		'Addis_Ababa' => 'Addis_Abeba',
		'Algiers' => 'Argel',
		// 'Asmara' => 'Asmara',
		// 'Asmera' => 'Asmera',
		// 'Bamako' => 'Bamako',
		// 'Bangui' => 'Bangui',
		// 'Banjul' => 'Banjul',
		// 'Bissau' => 'Bissau',
		// 'Blantyre' => 'Blantyre',
		// 'Brazzaville' => 'Brazzaville',
		// 'Cairo' => 'Cairo',
		// 'Casablanca' => 'Casablanca',
		// 'Ceuta' => 'Ceuta',
		'Conakry' => 'Conacri',
		// 'Dakar' => 'Dakar',
		// 'Dar_es_Salaam' => 'Dar_es_Salaam',
		'Djibouti' => 'Djibuti',
		// 'Douala' => 'Douala',
		// 'El_Aaiun' => 'El_Aaiun',
		// 'Freetown' => 'Freetown',
		// 'Gaborone' => 'Gaborone',
		// 'Harare' => 'Harare',
		'Johannesburg' => 'Joanesburgo',
		// 'Kampala' => 'Kampala',
		'Khartoum' => 'Cartum',
		// 'Kigali' => 'Kigali',
		// 'Lagos' => 'Lagos',
		// 'Libreville' => 'Libreville',
		// 'Luanda' => 'Luanda',
		// 'Lusaka' => 'Lusaka',
		// 'Malabo' => 'Malabo',
		// 'Maputo' => 'Maputo',
		// 'Maseru' => 'Maseru',
		// 'Mbabane' => 'Mbabane',
		'Mogadishu' => 'Mogadíscio',
		'Monrovia' => 'Monróvia',
		// 'Nairobi' => 'Nairobi',
		// 'Ndjamena' => 'Ndjamena',
		// 'Niamey' => 'Niamey',
		// 'Nouakchott' => 'Nouakchott',
		// 'Ouagadougou' => 'Ouagadougou',
		'Porto-Novo' => 'Porto',
		'Sao_Tome' => 'São_Tomé',
		// 'Timbuktu' => 'Timbuktu',
		'Tripoli' => 'Trípoli',
		'Tunis' => 'Tunes',
		// 'Windhoek' => 'Windhoek',
		
		'America/' => 'América/',
		// 'Adak' => 'Adak',
		// 'Anchorage' => 'Anchorage',
		// 'Anguilla' => 'Anguilla',
		'Antigua' => 'Antígua',
		'Araguaina' => 'Araguaína',
		// 'Argentina/Buenos_Aires' => 'Argentina/Buenos_Aires',
		// 'Argentina/Catamarca' => 'Argentina/Catamarca',
		// 'Argentina/ComodRivadavia' => 'Argentina/ComodRivadavia',
		// 'Argentina/Cordoba' => 'Argentina/Cordoba',
		// 'Argentina/Jujuy' => 'Argentina/Jujuy',
		// 'Argentina/La_Rioja' => 'Argentina/La_Rioja',
		// 'Argentina/Mendoza' => 'Argentina/Mendoza',
		// 'Argentina/Rio_Gallegos' => 'Argentina/Rio_Gallegos',
		// 'Argentina/San_Juan' => 'Argentina/San_Juan',
		// 'Argentina/Tucuman' => 'Argentina/Tucuman',
		// 'Argentina/Ushuaia' => 'Argentina/Ushuaia',
		// 'Aruba' => 'Aruba',
		'Asuncion' => 'Assunção',
		// 'Atikokan' => 'Atikokan',
		// 'Atka' => 'Atka',
		// 'Bahia' => 'Bahia',
		// 'Barbados' => 'Barbados',
		'Belem' => 'Belém',
		// 'Belize' => 'Belize',
		'Blanc-Sablon' => 'Blanc',
		// 'Boa_Vista' => 'Boa_Vista',
		'Bogota' => 'Bogotá',
		// 'Boise' => 'Boise',
		// 'Buenos_Aires' => 'Buenos_Aires',
		// 'Cambridge_Bay' => 'Cambridge_Bay',
		// 'Campo_Grande' => 'Campo_Grande',
		'Cancun' => 'Cancún',
		// 'Caracas' => 'Caracas',
		// 'Catamarca' => 'Catamarca',
		// 'Cayenne' => 'Cayenne',
		// 'Cayman' => 'Cayman',
		// 'Chicago' => 'Chicago',
		// 'Chihuahua' => 'Chihuahua',
		// 'Coral_Harbour' => 'Coral_Harbour',
		// 'Cordoba' => 'Cordoba',
		// 'Costa_Rica' => 'Costa_Rica',
		'Cuiaba' => 'Cuiabá',
		// 'Curacao' => 'Curacao',
		// 'Danmarkshavn' => 'Danmarkshavn',
		// 'Dawson' => 'Dawson',
		// 'Dawson_Creek' => 'Dawson_Creek',
		// 'Denver' => 'Denver',
		// 'Detroit' => 'Detroit',
		// 'Dominica' => 'Dominica',
		// 'Edmonton' => 'Edmonton',
		// 'Eirunepe' => 'Eirunepe',
		// 'El_Salvador' => 'El_Salvador',
		// 'Ensenada' => 'Ensenada',
		// 'Fort_Wayne' => 'Fort_Wayne',
		// 'Fortaleza' => 'Fortaleza',
		// 'Glace_Bay' => 'Glace_Bay',
		// 'Godthab' => 'Godthab',
		// 'Goose_Bay' => 'Goose_Bay',
		// 'Grand_Turk' => 'Grand_Turk',
		'Grenada' => 'Granada',
		'Guadeloupe' => 'Guadalupe',
		// 'Guatemala' => 'Guatemala',
		// 'Guayaquil' => 'Guayaquil',
		'Guyana' => 'Guiana',
		// 'Halifax' => 'Halifax',
		// 'Havana' => 'Havana',
		// 'Hermosillo' => 'Hermosillo',
		// 'Indiana/Indianapolis' => 'Indiana/Indianapolis',
		// 'Indiana/Knox' => 'Indiana/Knox',
		// 'Indiana/Marengo' => 'Indiana/Marengo',
		// 'Indiana/Petersburg' => 'Indiana/Petersburg',
		// 'Indiana/Vevay' => 'Indiana/Vevay',
		// 'Indiana/Vincennes' => 'Indiana/Vincennes',
		// 'Indiana/Winamac' => 'Indiana/Winamac',
		'Indianapolis' => 'Indianápolis',
		// 'Inuvik' => 'Inuvik',
		// 'Iqaluit' => 'Iqaluit',
		// 'Jamaica' => 'Jamaica',
		// 'Jujuy' => 'Jujuy',
		// 'Juneau' => 'Juneau',
		// 'Kentucky/Louisville' => 'Kentucky/Louisville',
		// 'Kentucky/Monticello' => 'Kentucky/Monticello',
		// 'Knox_IN' => 'Knox_IN',
		// 'La_Paz' => 'La_Paz',
		// 'Lima' => 'Lima',
		// 'Los_Angeles' => 'Los_Angeles',
		// 'Louisville' => 'Louisville',
		'Maceio' => 'Maceió',
		'Managua' => 'Manágua',
		// 'Manaus' => 'Manaus',
		'Martinique' => 'Martinica',
		// 'Mazatlan' => 'Mazatlan',
		// 'Mendoza' => 'Mendoza',
		// 'Menominee' => 'Menominee',
		'Merida' => 'Mérida',
		'Mexico_City' => 'Cidade_do_México',
		// 'Miquelon' => 'Miquelon',
		// 'Moncton' => 'Moncton',
		// 'Monterrey' => 'Monterrey',
		'Montevideo' => 'Montevidéu',
		// 'Montreal' => 'Montreal',
		// 'Montserrat' => 'Montserrat',
		// 'Nassau' => 'Nassau',
		'New_York' => 'Nova_Iorque',
		// 'Nipigon' => 'Nipigon',
		// 'Nome' => 'Nome',
		// 'Noronha' => 'Noronha',
		'North_Dakota/Center' => 'Dakota_do_Norte/Centro',
		'North_Dakota/New_Salem' => 'Dakota_do_Norte/New_Salem',
		'Panama' => 'Panamá',
		// 'Pangnirtung' => 'Pangnirtung',
		// 'Paramaribo' => 'Paramaribo',
		// 'Phoenix' => 'Phoenix',
		'Port-au-Prince' => 'Porto',
		// 'Port_of_Spain' => 'Port_of_Spain',
		// 'Porto_Acre' => 'Porto_Acre',
		// 'Porto_Velho' => 'Porto_Velho',
		'Puerto_Rico' => 'Porto_Rico',
		// 'Rainy_River' => 'Rainy_River',
		// 'Rankin_Inlet' => 'Rankin_Inlet',
		// 'Recife' => 'Recife',
		// 'Regina' => 'Regina',
		// 'Rio_Branco' => 'Rio_Branco',
		// 'Rosario' => 'Rosario',
		// 'Santiago' => 'Santiago',
		// 'Santo_Domingo' => 'Santo_Domingo',
		'Sao_Paulo' => 'São_Paulo',
		// 'Scoresbysund' => 'Scoresbysund',
		// 'Shiprock' => 'Shiprock',
		// 'St_Johns' => 'St_Johns',
		'St_Kitts' => 'São_Cristóvão',
		'St_Lucia' => 'Santa_Lúcia',
		// 'St_Thomas' => 'St_Thomas',
		'St_Vincent' => 'São_Vicente',
		// 'Swift_Current' => 'Swift_Current',
		// 'Tegucigalpa' => 'Tegucigalpa',
		// 'Thule' => 'Thule',
		// 'Thunder_Bay' => 'Thunder_Bay',
		// 'Tijuana' => 'Tijuana',
		// 'Toronto' => 'Toronto',
		// 'Tortola' => 'Tortola',
		'Vancouver' => 'Vancôver',
		'Virgin' => 'Virgem',
		// 'Whitehorse' => 'Whitehorse',
		'Winnipeg' => 'Vinipegue',
		// 'Yakutat' => 'Yakutat',
		// 'Yellowknife' => 'Yellowknife',
		
		'Antarctica/' => 'Antártica/',
		// 'Casey' => 'Casey',
		// 'Davis' => 'Davis',
		// 'DumontDUrville' => 'DumontDUrville',
		// 'Mawson' => 'Mawson',
		// 'McMurdo' => 'McMurdo',
		// 'Palmer' => 'Palmer',
		// 'Rothera' => 'Rothera',
		// 'South_Pole' => 'South_Pole',
		// 'Syowa' => 'Syowa',
		// 'Vostok' => 'Vostok',
		
		'Asia/' => 'Ásia/',
		// 'Aden' => 'Aden',
		// 'Almaty' => 'Almaty',
		'Amman' => 'Amã',
		// 'Anadyr' => 'Anadyr',
		// 'Aqtau' => 'Aqtau',
		// 'Aqtobe' => 'Aqtobe',
		// 'Ashgabat' => 'Ashgabat',
		// 'Ashkhabad' => 'Ashkhabad',
		'Baghdad' => 'Bagdad',
		'Bahrain' => 'Bahrein',
		// 'Baku' => 'Baku',
		'Bangkok' => 'Banguecoque',
		'Beirut' => 'Beirute',
		// 'Bishkek' => 'Bishkek',
		// 'Brunei' => 'Brunei',
		'Calcutta' => 'Calcutá',
		// 'Choibalsan' => 'Choibalsan',
		// 'Chongqing' => 'Chongqing',
		// 'Chungking' => 'Chungking',
		// 'Colombo' => 'Colombo',
		'Dacca' => 'Daca',
		'Damascus' => 'Damasco',
		'Dhaka' => 'Daca',
		'Dili' => 'Díli',
		// 'Dubai' => 'Dubai',
		// 'Dushanbe' => 'Dushanbe',
		// 'Gaza' => 'Gaza',
		// 'Harbin' => 'Harbin',
		// 'Hong_Kong' => 'Hong_Kong',
		// 'Hovd' => 'Hovd',
		// 'Irkutsk' => 'Irkutsk',
		'Istanbul' => 'Istambul',
		'Jakarta' => 'Jacarta',
		// 'Jayapura' => 'Jayapura',
		'Jerusalem' => 'Jerusalém',
		'Kabul' => 'Cabul',
		// 'Kamchatka' => 'Kamchatka',
		// 'Karachi' => 'Karachi',
		// 'Kashgar' => 'Kashgar',
		'Katmandu' => 'Catmandu',
		// 'Krasnoyarsk' => 'Krasnoyarsk',
		// 'Kuala_Lumpur' => 'Kuala_Lumpur',
		// 'Kuching' => 'Kuching',
		// 'Kuwait' => 'Kuwait',
		'Macao' => 'Macau',
		// 'Macau' => 'Macau',
		// 'Magadan' => 'Magadan',
		// 'Makassar' => 'Makassar',
		// 'Manila' => 'Manila',
		'Muscat' => 'Mascate',
		'Nicosia' => 'Nicósia',
		// 'Novosibirsk' => 'Novosibirsk',
		// 'Omsk' => 'Omsk',
		// 'Oral' => 'Oral',
		// 'Phnom_Penh' => 'Phnom_Penh',
		// 'Pontianak' => 'Pontianak',
		// 'Pyongyang' => 'Pyongyang',
		'Qatar' => 'Catar',
		// 'Qyzylorda' => 'Qyzylorda',
		'Rangoon' => 'Rangun',
		'Riyadh' => 'Riade',
		// 'Saigon' => 'Saigon',
		// 'Sakhalin' => 'Sakhalin',
		'Samarkand' => 'Samarcanda',
		'Seoul' => 'Seul',
		'Shanghai' => 'Xangai',
		'Singapore' => 'Cingapura',
		'Taipei' => 'Taipé',
		'Tashkent' => 'Tachkent',
		// 'Tbilisi' => 'Tbilisi',
		'Tehran' => 'Teerão',
		// 'Tel_Aviv' => 'Tel_Aviv',
		// 'Thimbu' => 'Thimbu',
		// 'Thimphu' => 'Thimphu',
		'Tokyo' => 'Tóquio',
		// 'Ujung_Pandang' => 'Ujung_Pandang',
		// 'Ulaanbaatar' => 'Ulaanbaatar',
		// 'Ulan_Bator' => 'Ulan_Bator',
		// 'Urumqi' => 'Urumqi',
		'Vientiane' => 'Viantiane',
		// 'Vladivostok' => 'Vladivostok',
		// 'Yakutsk' => 'Yakutsk',
		'Yekaterinburg' => 'Yekaterinburgo',
		// 'Yerevan' => 'Yerevan',
		
		'Atlantic/' => 'Atlântico/',
		'Azores' => 'Açores',
		'Bermuda' => 'Bermudas',
		'Canary' => 'Canárias',
		'Cape_Verde' => 'Cabo_Verde',
		'Faeroe' => 'Faroé',
		'Faroe' => 'Faroé',
		// 'Jan_Mayen' => 'Jan_Mayen',
		// 'Madeira' => 'Madeira',
		'Reykjavik' => 'Reiquiavique',
		'St_Helena' => 'Santa_Helena',
		// 'Stanley' => 'Stanley',
		
		'Australia/' => 'Austrália/',
		// 'ACT' => 'ACT',
		// 'Adelaide' => 'Adelaide',
		// 'Brisbane' => 'Brisbane',
		// 'Broken_Hill' => 'Broken_Hill',
		'Canberra' => 'Camberra',
		// 'Currie' => 'Currie',
		// 'Darwin' => 'Darwin',
		// 'Eucla' => 'Eucla',
		// 'Hobart' => 'Hobart',
		// 'LHI' => 'LHI',
		// 'Lindeman' => 'Lindeman',
		// 'Lord_Howe' => 'Lord_Howe',
		// 'Melbourne' => 'Melbourne',
		// 'NSW' => 'NSW',
		'North' => 'Norte',
		// 'Perth' => 'Perth',
		// 'Queensland' => 'Queensland',
		'South' => 'Sul',
		'Sydney' => 'Sidnei',
		'Tasmania' => 'Tasmânia',
		// 'Victoria' => 'Victoria',
		// 'West' => 'West',
		// 'Yancowinna' => 'Yancowinna',
		
		'Europe/' => 'Europa/',
		'Amsterdam' => 'Amesterdão',
		// 'Andorra' => 'Andorra',
		'Athens' => 'Atenas',
		// 'Belfast' => 'Belfast',
		'Belgrade' => 'Belgrado',
		'Berlin' => 'Berlim',
		// 'Bratislava' => 'Bratislava',
		'Brussels' => 'Bruxelas',
		'Bucharest' => 'Bucareste',
		'Budapest' => 'Budapeste',
		// 'Chisinau' => 'Chisinau',
		'Copenhagen' => 'Copenhague',
		// 'Dublin' => 'Dublin',
		// 'Gibraltar' => 'Gibraltar',
		// 'Guernsey' => 'Guernsey',
		'Helsinki' => 'Helsínquia',
		'Isle_of_Man' => 'Ilha_de_Man',
		'Istanbul' => 'Istambul',
		// 'Jersey' => 'Jersey',
		'Kaliningrad' => 'Kaliningrado',
		// 'Kiev' => 'Kiev',
		'Lisbon' => 'Lisboa',
		'Ljubljana' => 'Liubliana',
		'London' => 'Londres',
		'Luxembourg' => 'Luxemburgo',
		'Madrid' => 'Comunidade_de_Madrid',
		// 'Malta' => 'Malta',
		// 'Mariehamn' => 'Mariehamn',
		// 'Minsk' => 'Minsk',
		'Monaco' => 'Mónaco',
		'Moscow' => 'Moscovo',
		'Nicosia' => 'Nicósia',
		// 'Oslo' => 'Oslo',
		// 'Paris' => 'Paris',
		// 'Podgorica' => 'Podgorica',
		'Prague' => 'Praga',
		// 'Riga' => 'Riga',
		'Rome' => 'Roma',
		// 'Samara' => 'Samara',
		// 'San_Marino' => 'San_Marino',
		// 'Sarajevo' => 'Sarajevo',
		// 'Simferopol' => 'Simferopol',
		// 'Skopje' => 'Skopje',
		// 'Sofia' => 'Sofia',
		'Stockholm' => 'Estocolmo',
		// 'Tallinn' => 'Tallinn',
		// 'Tirane' => 'Tirane',
		// 'Tiraspol' => 'Tiraspol',
		// 'Uzhgorod' => 'Uzhgorod',
		// 'Vaduz' => 'Vaduz',
		'Vatican' => 'Vaticano',
		'Vienna' => 'Viena',
		// 'Vilnius' => 'Vilnius',
		'Volgograd' => 'Volgogrado',
		'Warsaw' => 'Varsóvia',
		// 'Zagreb' => 'Zagreb',
		// 'Zaporozhye' => 'Zaporozhye',
		'Zurich' => 'Zurique',
		
		'Indian/' => 'Índico/',
		// 'Antananarivo' => 'Antananarivo',
		// 'Chagos' => 'Chagos',
		// 'Comoro' => 'Comoro',
		// 'Kerguelen' => 'Kerguelen',
		// 'Mahe' => 'Mahe',
		'Maldives' => 'Maldivas',
		'Mauritius' => 'Maurício',
		// 'Mayotte' => 'Mayotte',
		// 'Reunion' => 'Reunion',
		
		'Pacific/' => 'Pacífico/',
		// 'Apia' => 'Apia',
		// 'Auckland' => 'Auckland',
		// 'Chatham' => 'Chatham',
		'Easter' => 'Páscoa',
		// 'Efate' => 'Efate',
		// 'Enderbury' => 'Enderbury',
		// 'Fiji' => 'Fiji',
		'Galapagos' => 'Galápagos',
		// 'Gambier' => 'Gambier',
		// 'Guadalcanal' => 'Guadalcanal',
		// 'Guam' => 'Guam',
		// 'Honolulu' => 'Honolulu',
		// 'Kiritimati' => 'Kiritimati',
		// 'Kosrae' => 'Kosrae',
		// 'Kwajalein' => 'Kwajalein',
		// 'Majuro' => 'Majuro',
		// 'Marquesas' => 'Marquesas',
		// 'Midway' => 'Midway',
		'Nauru' => 'Nauruano',
		// 'Niue' => 'Niue',
		// 'Norfolk' => 'Norfolk',
		// 'Noumea' => 'Noumea',
		// 'Pago_Pago' => 'Pago_Pago',
		// 'Pitcairn' => 'Pitcairn',
		// 'Rarotonga' => 'Rarotonga',
		// 'Saipan' => 'Saipan',
		// 'Samoa' => 'Samoa',
		// 'Tahiti' => 'Tahiti',
		// 'Tongatapu' => 'Tongatapu',

	)
));

?>