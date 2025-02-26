<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Hash;

class CompanyUpdateRequest extends FormRequest
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

    protected function prepareForValidation()
    {
        if($this->get('old_email') != $this->get('email')){
            $this->merge(['email_verified_at' => 'unverified']);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'first_name'    => 'required|max:255',
            // 'last_name'     => 'required|max:255',
            'email'         => 'unique:users,email,'.$this->get('id').'|email|required|max:255'
        ];
    }
    
    public function getUserData()
    {
       $date = new \Datetime("now");
       $data = [
            'id'                => $this->get('id'),
            'first_name'        => $this->get('first_name'),
            'last_name'         => $this->get('last_name'),
            'email'             => $this->get('email'),
            'address'           => $this->get('address'),
            'phone_no'          => $this->get('phone_no')
        ];

        if ($this->has('password')) {        
            $data['password'] =  Hash::make($this->get('password'));
        }
        return $data;
    }
}
