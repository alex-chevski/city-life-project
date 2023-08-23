<?php

declare(strict_types=1);

namespace App\Http\Requests\Adverts;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Class RejectRequest.
 * @author yourname
 */
class RejectRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'reason'=>'required|string',
        ];
    }
}
