<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hash;
use Auth;

class ManagerRequest extends FormRequest
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
            'first_name' => 'required|max:255',
            'last_name'  => 'nullable|max:255',
            'email'      => 'email|unique:users|required|max:255',
            'company'    => 'nullable|exists:users,id'
        ];
    }

    public function getUserData()
    {
       $date = new \Datetime("now");
       return [
            'first_name'        => $this->get('first_name'),
            'last_name'         => $this->get('last_name'),
            'email'             => $this->get('email'),
            'address'           => $this->get('address'),
            'phone_no'          => $this->get('phone_no'),
            'company'           => $this->get('company'),
            'email_verified_at' => ($this->get('email_verified_at') == 'verified') ?  $date->format('U') : null,
       ];
    }
}
