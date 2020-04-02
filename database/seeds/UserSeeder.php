<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(User::class)->create([
            'first_name' => 'Jhon',
            'last_name' => 'Doe',
            'email' => 'jhon@gmail.com',
        ]);

        factory(User::class)->create([
            'first_name' => 'Oliver',
            'last_name' => 'Bernard',
            'email' => 'oli@gmail.com',
        ]);
    }
}
