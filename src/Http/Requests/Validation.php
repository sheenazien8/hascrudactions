<?php

namespace Sheenazien8\Hascrudactions\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Class Validation
 * @author sheenazien8
 */
class Validation extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'slug' => ['required'],
            'req.strategy' => ['required', Rule::in(['array', 'single_class', 'full_url'])],
            'req.type' => ['required', Rule::in(['same', 'unique'])],
            'hascrud_rows.*.type' => ['required'],
            'hascrud_rows.*.collumn' => ['required'],
            'hascrud_rows.*.store_validation' => ['string'],
            'hascrud_rows.*.update_validation' => ['exclude_if:req.type,same'],
            'hascrud_rows.*.update_validation' => [
                Rule::requiredIf(request()->req['type'] == 'unique'),
                'string'
            ],
        ];
    }
}
