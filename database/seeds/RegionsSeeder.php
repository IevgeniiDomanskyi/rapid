<?php

use Illuminate\Database\Seeder;

class RegionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('regions')->truncate();

        $regions = [
            'Lake District National Park',
            'North West and Forest of Bowland',
            'Yorkshire Dales and West Yorkshire',
            'North Yorkshire Moors & Wolds',
            'Peak District & North Staffordshire',
            'Lincolnshire Wolds',
            'Snowdonia National Park',
            'Shropshire Hills',
            'East Midlands and Rutland TT',
            'Cotswolds & Oxfordshire',
            'North Wessex Downs',
            'Dorset',
            'Cranborne Chase',
            'South Downs National Park/Surrey Hills',
            'Kent & Kent Downs',
            'Bishop Stortford & Epping Forest',
        ];
        
        foreach ($regions as $region) {
            DB::table('regions')->insert([
                'title' => $region,
            ]);
        }
    }
}
