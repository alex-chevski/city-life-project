<?php

declare(strict_types=1);

namespace App\Http\Requests\Auth\ResetPassword;

use Illuminate\Foundation\Http\FormRequest;

final class PasswordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'password' => 'required|string|min:8|confirmed',
            'token' => 'required|string|max:255',
        ];
    }
}
