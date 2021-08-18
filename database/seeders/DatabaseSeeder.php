<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        
        DB::table('org_types')->insert([
            ['name' => 'Entreprise'],
            ['name' => 'Institution'],
            ['name' => 'Gouvernement'],
            ['name' => 'OBNL'],
            ['name' => 'Organisme communautaire'],
            ['name' => 'Banque alimentaire'],
            ['name' => 'Producteur'],
            ['name' => 'Ferme'],
        ]);
        
        \App\Models\Person::factory(10)->create();
        \App\Models\Org::factory(10)->create();
        
        User::create([
            'name' => 'Liam',
            'email' => 'liam@recolte.ca',
            'password' => Hash::make(env('LIAM_PASSWORD')),
            'is_admin' => true,
        ]);
    }
}
