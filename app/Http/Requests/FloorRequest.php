<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FloorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'project_id'      => 'required|exists:projects,project_id',
            'floor_name'      => 'required|max:255',
            'member_id'       => 'required|exists:users,id',
            'category_id'     => 'required|exists:categories,category_id',
            'floor_status_id' => 'required|exists:statuses,status_id',
            'address'         => 'nullable|max:255',
            'file'            => 'required|file|mimes:dwg',
        ];

        return $rules;
    }

    public function getFloorData()
    {
        $data = [
            'project_id'      => $this->get('project_id'),
            'floor_name'      => $this->get('floor_name'),
            'member_id'       => $this->get('member_id'),
            'category_id'     => $this->get('category_id'),
            'floor_status_id' => $this->get('floor_status_id'),
            'address'         => $this->get('address'),
        ];
        return $data;
    }
}
