<?php

namespace App\Http\Requests\Configuration\Operational;

use App\Models\Configuration\Operational\OperationalProfile;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class UpdateOperationalProfileRequest extends FormRequest
{
    /**
     * Determina se o usuário está autorizado a realizar a requisição.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Regras de validação.
     */
    public function rules(): array
    {
        $operationalProfile = $this->route('operationalProfile');

        return [
            'name' => [
                'required',
                'string',
                'max:255',

                Rule::unique('operational_profiles', 'name')
                    ->where(
                        'unit_type_id',
                        $operationalProfile->unit_type_id
                    )
                    ->ignore($operationalProfile),
            ],

            'active' => [
                'boolean',
            ],

            'categories' => [
                'nullable',
                'array',
            ],

            'categories.*.category_id' => [
                'required',
                'integer',
                'distinct',
                'exists:categories,id',

                Rule::exists('category_unit_type', 'category_id')
                    ->where(
                        'unit_type_id',
                        $operationalProfile->unit_type_id
                    ),
            ],

            'categories.*.quantity' => [
                'required',
                'integer',
                'min:0',
            ],
        ];
    }

    /**
     * Validações adicionais após as regras básicas.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            $operationalProfile = $this->route('operationalProfile');

            $categories = $this->input('categories', []);

            /*
             * Normaliza a composição recebida.
             *
             * A ordem das categorias não importa.
             * O que importa é:
             *
             * categoria + quantidade
             */
            $currentComposition = collect($categories)
                ->map(function ($category) {
                    return [
                        'category_id' => (int) $category['category_id'],
                        'quantity' => (int) $category['quantity'],
                    ];
                })
                ->sortBy('category_id')
                ->values()
                ->toJson();

            /*
             * Busca os outros perfis do mesmo tipo de unidade.
             *
             * O próprio perfil que está sendo editado é ignorado.
             */
            $otherProfiles = OperationalProfile::query()
                ->where('unit_type_id', $operationalProfile->unit_type_id)
                ->whereKeyNot($operationalProfile->id)
                ->with('categories')
                ->get();

            /*
             * Compara a composição atual com as composições
             * dos outros perfis.
             */
            foreach ($otherProfiles as $otherProfile) {
                $otherComposition = $otherProfile->categories
                    ->map(function ($category) {
                        return [
                            'category_id' => (int) $category->category_id,
                            'quantity' => (int) $category->quantity,
                        ];
                    })
                    ->sortBy('category_id')
                    ->values()
                    ->toJson();

                if ($currentComposition === $otherComposition) {
                    $validator->errors()->add(
                        'categories',
                        "A composição informada já existe no perfil \"{$otherProfile->name}\"."
                    );

                    break;
                }
            }
        });
    }

    /**
     * Mensagens de validação.
     */
    public function messages(): array
    {
        return [
            'name.required' =>
                'O nome do perfil é obrigatório.',

            'name.max' =>
                'O nome do perfil não pode ultrapassar 255 caracteres.',

            'name.unique' =>
                'Já existe um perfil com este nome para o tipo de unidade selecionado.',

            'active.boolean' =>
                'O status informado é inválido.',

            'categories.array' =>
                'A composição do perfil é inválida.',

            'categories.*.category_id.required' =>
                'A categoria da composição é obrigatória.',

            'categories.*.category_id.exists' =>
                'A categoria selecionada não existe ou não pode ser utilizada neste tipo de unidade.',

            'categories.*.category_id.distinct' =>
                'Uma categoria não pode ser adicionada mais de uma vez.',

            'categories.*.quantity.required' =>
                'A quantidade da categoria é obrigatória.',

            'categories.*.quantity.integer' =>
                'A quantidade deve ser um número inteiro.',

            'categories.*.quantity.min' =>
                'A quantidade não pode ser negativa.',
        ];
    }
}
