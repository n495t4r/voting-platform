<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateElectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('create', \App\Models\Election::class);
    }

    public function rules(): array
    {
        return [
            'organization_id' => 'required|exists:organizations,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:elections,slug',
            'description' => 'nullable|string',
            'starts_at' => 'required|date|after:now',
            'ends_at' => 'required|date|after:starts_at',
        ];
    }
}
