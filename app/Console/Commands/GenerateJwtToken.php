<?php

namespace App\Console\Commands;

use App\Models\User;
use Faker\Factory as Faker;
use Illuminate\Console\Command;

class GenerateJwtToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jwt:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate JWT token';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $user = [
            'name' => 'John Doe',
            'email' => Faker::create()->email(),
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];


        $token = User::create($user)->createToken('token')->plainTextToken;


        $this->info("Your token: $token");
    }
}
