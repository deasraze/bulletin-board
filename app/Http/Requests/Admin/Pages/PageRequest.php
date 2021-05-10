<?php

namespace App\Http\Requests\Admin\Pages;

use Illuminate\Foundation\Http\FormRequest;

class PageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255',
            'menu_title' => 'nullable|string|max:50',
            'parent_id' => 'nullable|integer|exists:pages,id',
            'content' => 'required|string',
            'description' => 'nullable|string',
        ];
    }
}
