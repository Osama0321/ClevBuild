<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateRequest extends FormRequest
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
            'project_name'  => 'required|max:255',
            'member_id'     => 'required',
            'category_id'   => 'required',
            'followers'     => 'required',
            'followers.*'   => 'required',
            'country_id'    => 'required',
            'city_id'       => 'required'
        ];

        // Check if the logged-in user is an admin
        if (auth()->user()->user_type == 1) {
            $rules['created_by'] = 'required';
        }
        return $rules;
    }

    public function getProjectData()
    {
       $data = [
            'project_name'      => $this->get('project_name'),
            'category_id'       => $this->get('category_id'),
            'member_id'         => $this->get('member_id'),
            'country_id'        => $this->get('country_id'),
            'city_id'           => $this->get('city_id'),
            'address'           => $this->get('address'),
            'followers'         => $this->get('followers'),
            'created_by'        => $this->get('created_by'),
            'project_status_id' => $this->get('project_status_id'),
        ];
        return $data;
    }
}
