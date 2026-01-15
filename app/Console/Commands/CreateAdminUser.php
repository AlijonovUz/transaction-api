<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new admin user interactively';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $name = $this->ask("Enter full name");

        if (!preg_match('/^[a-zA-Z]+( [a-zA-Z]+)*$/', $name)) {
            $this->error('Name must contain only letters and spaces.');
            return Command::FAILURE;
        }

        $email = $this->ask("Enter your email: ");

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->error("The email address is not valid.");
            return Command::FAILURE;
        }

        if (User::where('email', $email)->exists()) {
            $this->error("A user with this email already exists.");
            return Command::FAILURE;
        }

        $password = $this->secret("Enter password");

        if (!$password) {
            $this->error("Password cannot be empty.");
            return Command::FAILURE;
        }

        $confirm = $this->secret("Confirm password");

        if ($password != $confirm) {
            $this->error("Passwords do not match.");
            return Command::FAILURE;
        }

        DB::transaction(function () use ($name, $email, $password) {
            (new User())->forceFill([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'role' => "admin"
            ])->save();
        });

        $this->info("Admin user created successfully.");
        return Command::SUCCESS;
    }
}
