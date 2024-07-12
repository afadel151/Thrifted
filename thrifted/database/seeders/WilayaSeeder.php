<?php

namespace Database\Seeders;

use App\Models\Wilaya;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class WilayaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $wilayas = [
            'Adrar',
             'Chlef',
             'Laghouat',
             'Oum El Bouaghi',
             'Batna',
             'Bejaïa',
             'Biskra',
             'Béchar',
             'Blida',
             'Bouira',
             'Tamanrasset',
             'Tebessa',
             'Tlemce',
             'Tiaret',
             'Tizi Ouzou',
             'Alger',
             'Djelfa',
             'Djijel',
             'Sétif',
             'Saïda',
             'Skikda',
             'Sidi Bel Abbè',
             'Annab',
             'Guelma',
             'Constantine',
             'Médéa',
             'Mostaganem',
             'M\'Sila',
             'Mascara',
             'Ouargla',
             'Oran',
             'El Bayadh',
             'Illizi',
             'Bordj Bou Arreridj',
             'Boumerdè',
             'El Tarf',
             'Tindouf',
             'Tissemsilt',
             'El Oued',
             'Khenchela',
             'Souk Ahras',
             'Tipaza',
             'Mila',
             'Aïn Defla',
             'Naâma',
             'Aïn Témouchent',
             'Ghardaia',
             'Relizane',
             'Timimou',
             'Bordj Badji Mokhta',
             'Ouled Djella',
             'Beni Abbe',
             'In Sala',
             'In Guezza',
             'Touggour',
             'Djane',
             'El M\'Ghai',
            ' El Meniaa'
        ];
        for ($i=1; $i < 59; $i++) { 
            Wilaya::create([
                'matricule' => $i,
                'name' => $wilayas[$i - 1]
            ]);
        }
    }
}
