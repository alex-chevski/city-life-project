<?php

declare(strict_types=1);

namespace App\Http\Requests\Admin\Users;

use App\Models\User\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * @property User $user
 */
class UpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => "required|string|email|max:255|unique:users,email,{$this->user->id},id",
            'role' => ['required', 'string', Rule::in(array_keys(User::rolesList()))],
        ];
    }
}
