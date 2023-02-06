<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostcodesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('postcodes')->truncate();

        DB::table('postcodes')->insert([
            'code' => 'AB',
            'area' => 'Aberdeen',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'AL',
            'area' => 'St. Albans',
        ]);
        
        DB::table('postcodes')->insert([
            'code' => 'B',
            'area' => 'Birmingham',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'BA',
            'area' => 'Bath',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'BB',
            'area' => 'Blackburn',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'BD',
            'area' => 'Bradford',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'BF',
            'area' => 'British Forces',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'BH',
            'area' => 'Bournemouth',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'BL',
            'area' => 'Bolton',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'BN',
            'area' => 'Brighton',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'BR',
            'area' => 'Bromley',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'BS',
            'area' => 'Bristol',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'BT',
            'area' => 'Northern Ireland',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'CA',
            'area' => 'Carlisle',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'CB',
            'area' => 'Cambridge',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'CF',
            'area' => 'Cardiff',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'CH',
            'area' => 'Chester',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'CM',
            'area' => 'Chemlsford',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'CO',
            'area' => 'Colchester',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'CR',
            'area' => 'Croydon',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'CT',
            'area' => 'Canterbury',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'CV',
            'area' => 'Coventry',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'CW',
            'area' => 'Crewe',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'DA',
            'area' => 'Dartford',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'DD',
            'area' => 'Dundee',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'DE',
            'area' => 'Derby',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'DG',
            'area' => 'Dumfries and Galloway',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'DH',
            'area' => 'Durham',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'DL',
            'area' => 'Darlington',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'DN',
            'area' => 'Doncaster',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'DT',
            'area' => 'Dorchester',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'DY',
            'area' => 'Dudley',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'E',
            'area' => 'East London',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'EC',
            'area' => 'Central London',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'EH',
            'area' => 'Edinburgh',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'EN',
            'area' => 'Enfield',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'EX',
            'area' => 'Exeter',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'FK',
            'area' => 'Falkirk and Stirling',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'FY',
            'area' => 'Blackpool',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'G',
            'area' => 'Clasfow',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'GL',
            'area' => 'Gloucester',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'GU',
            'area' => 'Guildford',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'HA',
            'area' => 'Harrow',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'HD',
            'area' => 'Huddersfield',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'HG',
            'area' => 'Harrogate',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'HP',
            'area' => 'Hemel Hempstead',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'HR',
            'area' => 'Hereford',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'HS',
            'area' => 'Outer Hebrides',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'HU',
            'area' => 'Hull',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'HX',
            'area' => 'Halifax',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'IG',
            'area' => 'Ilford',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'IP',
            'area' => 'Ipswitch',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'IV',
            'area' => 'Inverness',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'KA',
            'area' => 'Kilmarnock',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'KT',
            'area' => 'Kingston upon Thames',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'KW',
            'area' => 'Kirkwall',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'KY',
            'area' => 'Kirkcaldy',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'L',
            'area' => 'Liverpool',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'LA',
            'area' => 'Lancaster',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'LD',
            'area' => 'Llandrindod Wells',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'LE',
            'area' => 'Leicester',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'LL',
            'area' => 'Llandudno',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'LN',
            'area' => 'Lincoln',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'LS',
            'area' => 'Leeds',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'LU',
            'area' => 'Luton',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'M',
            'area' => 'Manchester',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'ME',
            'area' => 'Rochester',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'MK',
            'area' => 'Milton Keynes',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'ML',
            'area' => 'Motherwell',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'N',
            'area' => 'North London',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'NE',
            'area' => 'Newcastle upon Tyne',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'NG',
            'area' => 'Nottingham',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'NN',
            'area' => 'Northampton',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'NP',
            'area' => 'Newport',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'NR',
            'area' => 'Norwich',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'NW',
            'area' => 'North West London',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'OL',
            'area' => 'Oldham',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'OX',
            'area' => 'Oxford',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'PA',
            'area' => 'Paisley',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'PE',
            'area' => 'Peterborough',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'PH',
            'area' => 'Perth',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'PL',
            'area' => 'Plymouth',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'PO',
            'area' => 'Portsmouth',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'PR',
            'area' => 'Preston',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'RG',
            'area' => 'Reading',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'RH',
            'area' => 'Redhill',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'RM',
            'area' => 'Romford',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'S',
            'area' => 'Sheffield',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'SA',
            'area' => 'Swansea',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'SE',
            'area' => 'South East London',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'SG',
            'area' => 'Stevenage',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'SK',
            'area' => 'Stockport',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'SL',
            'area' => 'Slough',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'SM',
            'area' => 'Sutton',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'SN',
            'area' => 'Swindon',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'SO',
            'area' => 'Southampton',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'SP',
            'area' => 'Salisbury',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'SR',
            'area' => 'Sunderland',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'SS',
            'area' => 'Southend-on-Sea',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'ST',
            'area' => 'Stoke-on-Trent',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'SW',
            'area' => 'South West London',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'SY',
            'area' => 'Shrewsbury',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'TA',
            'area' => 'Taunton',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'TD',
            'area' => 'Galashiels',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'TF',
            'area' => 'Telford',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'TN',
            'area' => 'Tonbridge',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'TQ',
            'area' => 'Torquay',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'TR',
            'area' => 'Truro',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'TS',
            'area' => 'Cleveland',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'TW',
            'area' => 'Twickenham',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'UB',
            'area' => 'Southall',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'W',
            'area' => 'West London',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'WA',
            'area' => 'Warrington',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'WC',
            'area' => 'Central London',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'WD',
            'area' => 'Watford',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'WF',
            'area' => 'Wakefield',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'WN',
            'area' => 'Wigan',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'WR',
            'area' => 'Worcester',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'WS',
            'area' => 'Walsall',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'WV',
            'area' => 'Wolverhampton',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'YO',
            'area' => 'York',
        ]);

        DB::table('postcodes')->insert([
            'code' => 'ZE',
            'area' => 'Lerwick',
        ]);
    }
}
