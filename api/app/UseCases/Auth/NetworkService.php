<?php

declare(strict_types=1);

namespace App\UseCases\Auth;

use App\Models\User\User;
use DomainException;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Contracts\User as NetworkUser;

class NetworkService
{
    public function auth(string $network, NetworkUser $data): User
    {
        if ($user = User::byNetwork($network, $data->getId())->first()) {
            return $user;
        }

        if ($data->getEmail() && $user = User::where('email', $data->getEmail())->exists()) {
            throw new DomainException('User with this email is already registered.');
        }

        $user = DB::transaction(static fn () => User::registerByNetwork($network, $data->getId()));

        event(new Registered($user));

        return $user;
    }
}
