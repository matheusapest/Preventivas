<?php

namespace App\Http\Requests\Preventive;

use Illuminate\Foundation\Http\FormRequest;

class StorePreventiveRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Normaliza os dados recebidos do formulário.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'branch_id' => $this->branch_id !== null
                ? (int) $this->branch_id
                : null,

            'preventive_type_id' => $this->preventive_type_id !== null
                ? (int) $this->preventive_type_id
                : null,

            'preventive_profile_id' => $this->preventive_profile_id !== null
                ? (int) $this->preventive_profile_id
                : null,

            'assigned_user_id' => $this->assigned_user_id !== null
                ? (int) $this->assigned_user_id
                : null,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'branch_id' => [
                'required',
                'integer',
                'exists:branches,id',
            ],

            'preventive_type_id' => [
                'required',
                'integer',
                'exists:preventive_types,id',
            ],

            'preventive_profile_id' => [
                'required',
                'integer',
                'exists:preventive_profiles,id',
            ],

            'assigned_user_id' => [
                'required',
                'integer',
                'exists:users,id',
            ],

            'start_date' => [
                'required',
                'date',
            ],

            'due_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
            ],
        ];
    }
}
