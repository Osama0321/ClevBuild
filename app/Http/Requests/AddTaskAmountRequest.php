<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddTaskAmountRequest extends FormRequest
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
        return [
            'Project_id'    => 'required',
            'task_ids.*' => 'required',
            'task_amounts.*' => 'required|numeric'
        ];
    }

    public function RequestData()
    {
       $date = new \Datetime("now");
       return [
            'Project_id'        => $this->get('Project_id'),
            'task_ids'             => $this->get('task_ids',[]),
            'task_amounts'             => $this->get('task_amounts',[]),
       ];
    }

}
