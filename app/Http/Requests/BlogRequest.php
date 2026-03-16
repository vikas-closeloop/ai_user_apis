<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BlogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        $isCreate = $this->isMethod('post');

        return [
            'title' => $isCreate
                ? ['required', 'string', 'max:255']
                : ['sometimes', 'required', 'string', 'max:255'],
            'content' => $isCreate
                ? ['required', 'string']
                : ['sometimes', 'required', 'string'],
            'status' => $isCreate
                ? ['required', 'in:draft,published']
                : ['sometimes', 'required', 'in:draft,published'],
            'featured_image' => [
                'sometimes',
                'file',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:2048',
            ],
        ];
    }
}

