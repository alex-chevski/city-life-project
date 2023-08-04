<?php

namespace App\Console\Commands\User;

use Illuminate\Console\Command;
use App\Models\User\User;

class RoleCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:role {email} {role}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set role for user';

    /**
     * Execute the console command.
     */
    public function handle():bool
    {
        $email = $this->argument('email');
        $role = $this->argument('role');
        if(!$user = User::where('email', $email)->first()){
            $this->error('Undefined user with email' . $email);
            return false;
        }

        try{
            $user->changeRole($role);
        }catch(\DomainException $e){
            $this->error($e->getMessage());
            return false;
        }catch(\InvalidArgumentException $e){
            $this->error($e->getMessage());
            return false;
        }

        $this->info('Role is successfully changed');
        return true;
    }
}
