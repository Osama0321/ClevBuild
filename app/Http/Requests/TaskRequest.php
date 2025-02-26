<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskRequest extends FormRequest
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
            'task_name'             => 'required',
            'priority_id'           => 'required|max:255',
            'project_status_id'     => 'required',
        ];
        return $rules;
    }

    public function getTaskData()
    {
        $data = [
            'task_name'         => $this->get('task_name'),
            'member_id'         => $this->get('member_id'),
            'project_id'        => $this->get('project_id'),
            'priority_id'       => $this->get('priority_id'),
            'project_status_id' => $this->get('project_status_id'),
            'task_images'       => $this->get('task_images'),
            'description'       => $this->get('description'),
        ];
        return $data;
    }
}
