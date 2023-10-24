<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cabinet\ProfileEditRequest;
use App\Http\Resources\User\ProfileResource;
use App\Models\User\User;
use App\UseCases\Profile\ProfileService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    private $service;

    public function __construct(ProfileService $service)
    {
        $this->service = $service;
    }

    public function show(Request $request)
    {
        /**
         * @var User $user
         */
        $user = $request->user();
        return new ProfileResource($user);
    }

    public function update(ProfileEditRequest $request)
    {
        $this->service->edit($user = $request->user()->id, $request);

        $user = User::findOrFail($user->id);
        return new ProfileResource($user);
    }
}
