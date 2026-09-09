<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class ReorderMenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->hasPrivilege('menus.update') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'items' => ['required', 'array','min:1'],

            'items.*.id' => ['required','integer', 'exists:menus,id'],
            'items.*.sort_order' => ['required','integer', 'min:0'],
            'items.*.parent_id' => ['nullable','integer', 'exists:menus,id'],
        ];
    }
}
