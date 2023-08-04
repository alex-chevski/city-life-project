<?php

declare(strict_types=1);

namespace App\Console\Commands\User;

use App\Models\User\User;
use Illuminate\Console\Command;
use App\UseCases\Auth\RegisterService;

class VerifyCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:verify {email}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'verify user email';

    private $service;

    /**
     * @paramRegisterService $service
     */
    public function __construct(RegisterService $service)
    {
        parent::__construct();
        $this->service = $service;
    }


    /**
     * Execute the console command.
     */
    public function handle()
    {
        $email = $this->argument('email');

        if (!$user = User::where('email', $email)->first()) {
            $this->error('Undefined user with email ' . $email);
            return false;
        }

        $this->service->verify($user->id);

        $this->info('success');
        return true;
    }
}
