<?php

namespace App\Console\Commands\User;

use App\Entity\User;
use App\UseCases\Auth\RegisterService;
use Illuminate\Console\Command;

class VerifyCommand extends Command
{
    protected $signature = 'user:verify {email}';

    protected $description = 'Verify user email';

    private RegisterService $service;

    public function __construct(RegisterService $service)
    {
        parent::__construct();
        $this->service = $service;
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email');

        if (!$user = User::where('email', $email)->first()) {
            $this->error('Undefined user with email ' . $email);
            return 1;
        }

        try {
            $this->service->verify($user->id);
        } catch (\DomainException $e) {
            $this->error($e->getMessage());
            return 1;
        }

        $this->info('User is successfully verified!');
        return 0;
    }
}
