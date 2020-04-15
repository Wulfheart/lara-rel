<?php

use Illuminate\Database\Seeder;

class DeadlineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (\App\documentation::all() as $doc) {
            factory(App\deadline::class, rand(5,9))->create([
                'documentation_id' => $doc->id
            ]);
        }
    }
}
