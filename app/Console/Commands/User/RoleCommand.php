<?php

namespace App\Console\Commands\User;

use App\Entity\User;
use Illuminate\Console\Command;

class RoleCommand extends Command
{
    protected $signature = 'user:role {email} {role}';

    protected $description = 'Change role for user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle(): int
    {
        $email = $this->argument('email');
        $role = $this->argument('role');

        if (!$user = User::where('email', $email)->first()) {
            $this->error('Undefined user with email ' . $email);
            return 1;
        }

        try {
            $user->changeRole($role);
        } catch (\DomainException $e) {
            $this->error($e->getMessage());
            return 1;
        }

        $this->info('Role is successfully changed!');
        return 0;
    }
}
