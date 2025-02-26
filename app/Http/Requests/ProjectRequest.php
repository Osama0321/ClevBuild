<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectRequest extends FormRequest
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
            'project_name'      => 'required|max:255',
            'manager_id'        => 'required|exists:users,id',
            'category_id'       => 'required|exists:categories,category_id',
            'project_status_id' => 'required|exists:project_statuses,project_status_id',
            'address'           => 'nullable|max:255',
            'start_date'        => 'required|date',
            'end_date'          => 'required|date',
        ];

        return $rules;
    }

    public function getProjectData()
    {
        $data = [
            'project_name'      => $this->get('project_name'),
            'manager_id'        => $this->get('manager_id'),
            'category_id'       => $this->get('category_id'),
            'project_status_id' => $this->get('project_status_id'),
            'address'           => $this->get('address'),
            'start_date'        => $this->get('start_date'),
            'end_date'          => $this->get('end_date'),
        ];
        return $data;
    }
}
