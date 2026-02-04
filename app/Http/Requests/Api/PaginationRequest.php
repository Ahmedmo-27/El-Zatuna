<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class PaginationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'page' => 'sometimes|integer|min:1',
            'per_page' => 'sometimes|integer|min:1|max:100',
            'sort_by' => 'sometimes|string',
            'sort_order' => 'sometimes|in:asc,desc',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'page' => 'page number',
            'per_page' => 'items per page',
            'sort_by' => 'sort field',
            'sort_order' => 'sort order',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'page.integer' => 'The page number must be an integer.',
            'page.min' => 'The page number must be at least 1.',
            'per_page.integer' => 'The per_page value must be an integer.',
            'per_page.min' => 'The per_page value must be at least 1.',
            'per_page.max' => 'The per_page value must not exceed 100.',
            'sort_order.in' => 'The sort order must be either "asc" or "desc".',
        ];
    }

    /**
     * Get validated pagination parameters with defaults
     *
     * @return array
     */
    public function getPaginationParams()
    {
        return [
            'page' => max(1, (int)$this->input('page', 1)),
            'per_page' => min(100, max(1, (int)$this->input('per_page', 10))),
            'sort_by' => $this->input('sort_by', 'created_at'),
            'sort_order' => in_array(strtolower($this->input('sort_order', 'desc')), ['asc', 'desc']) 
                ? strtolower($this->input('sort_order', 'desc')) 
                : 'desc',
        ];
    }
}
