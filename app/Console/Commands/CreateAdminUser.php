<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateAdminUser extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create {email : Email to register} {--admin : An admin user will be created if set}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a user';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userData = [];
        $userData['email'] = $this->argument('email');
        $userData['name'] = $this->ask('Enter name for new user');
        $userData['role'] = $this->option('admin') ? 'admin' : 'user';
        $userData['password'] = $this->secret('Enter password for new user');
        $userData['password_confirmation'] = $this->secret('Confirm password for new user');
        if ($this->validateInputs($userData)) {
            $user = new User();
            $user->email = $userData['email'];
            $user->name = $userData['name'];
            $user->role = $userData['role'];
            $user->password = Hash::make($userData['password']);
            $user->email_verified_at = new \DateTime();
            $user->save();
            $this->info('User created successfully.');
        }
    }

    /**
     * Get arguments validator
     *
     * @return void
     */
    private function validateInputs(array $input)
    {
        $validator = Validator::make($input, [
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', 'min:8']
        ]);
        if ($validator->fails()) {
            $this->info('Cannot create user with following errors:');
        
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }
            return false;
        }
        return true;
    }
}
