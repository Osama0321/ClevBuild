<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class CountriesTableSeeder extends Seeder
{
    public function run()
    {
        $client = new Client();
        $response = $client->get('https://geodata.phplift.net/api/index.php?type=getCountries');
        $countries = json_decode($response->getBody(), true);

        $countries = $countries['result'];

        // Generate ISO3 and ISO2 codes
        $isoCodes = $this->generateIsoCodes($countries);

        $countriesData = [];
        foreach ($countries as $country) {
            $countryName = $country['name'];
            $iso3 = $isoCodes[$countryName]['iso3'];
            $iso2 = $isoCodes[$countryName]['iso2'];

            $countriesData[] = [
                'country_id' => $country['id'],
                'country_name' => $countryName,
                'iso3' => $iso3,
                'iso2' => $iso2,
            ];
        }

        DB::table('countries')->insert($countriesData);
    }


    private function generateIsoCodes($countries)
    {
        $isoCodes = [];

        // Generate ISO codes based on country names
        foreach ($countries as $country) {
            $countryName = $country['name'];
            $isoCodes[$countryName] = [
                'iso3' => substr(strtoupper($countryName), 0, 3),
                'iso2' => substr(strtoupper($countryName), 0, 2),
            ];
        }

        return $isoCodes;
    }
}