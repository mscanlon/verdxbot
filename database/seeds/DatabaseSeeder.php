<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call('UserTableSeeder');

        DB::table('teams')->insert([
            'slack_team_id' => 'T0001',
            'token' => 'gIkuvaNzQIHg97ATvDxqgjtO',
        ]);

        DB::table('teams')->insert([
            'slack_team_id' => 'T0002',
            'token' => 'swn73aNzQIHg97ATvDxqgjtO',
        ]);
    }
}
