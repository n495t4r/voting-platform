<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateElectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()->can('update', $this->route('election'));
    }

    public function rules(): array
    {
        $election = $this->route('election');
        
        return [
            'organization_id' => 'required|exists:organizations,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:elections,slug,' . $election->id,
            'description' => 'nullable|string',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
        ];
    }
}
