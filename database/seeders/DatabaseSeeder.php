<?php

namespace Database\Seeders;

use App\Models\Org;
use App\Models\Person;
use App\Models\Position;
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
                
        User::create([
            'name' => 'Liam',
            'email' => 'liam@recolte.ca',
            'password' => Hash::make(env('LIAM_PASSWORD')),
            'is_admin' => true,
        ]);
        
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
        
        Person::factory(10)->create();
        Org::factory(10)->create();
        
        $testOrg1 = Org::factory()->make();
        $testOrg1->name = 'Test Org 1';
        $testOrg1->short_name = 'TO1';
        $testOrg1->save();
        
        $testOrg2 = Org::factory()->make();
        $testOrg2->name = 'Test Org 2';
        $testOrg2->short_name = 'TO2';
        $testOrg2->save();
        
        $position = new Position;
        $position->org_id = 1;
        $position->person_id = 1;
        $position->title = 'Manager';
        $position->start_year = 2015;
        $position->start_month = 2;
        $position->start_day = 14;
        $position->end_year = 2020;
        $position->end_month = 3;
        $position->end_day = 15;
        $position->save();
        
        $position = new Position;
        $position->org_id = 2;
        $position->person_id = 1;
        $position->title = 'Senior Manager';
        $position->start_year = 2020;
        $position->start_month = 4;
        $position->start_day = 16;
        $position->is_current = true;
        $position->save();
    }
}
