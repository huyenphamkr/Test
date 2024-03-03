<?php

namespace App\Http\Requests;

use App\Common\CommonConst;
use Illuminate\Foundation\Http\FormRequest;

class U074Request extends FormRequest
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
        $validate = [];
        if($this->has('txtRetire_date')){
            $validate['txtRetire_date'] = 'required|max:10';
            $validate['txtPost_code'] = 'required|max:8';
            $validate['txtAddress'] = 'required|max:50';
            $validate['txtRetire_contact_info'] = 'required|max:20';
            $validate['cmbSeparation_form'] = 'required';
        }
        return $validate;
    }

    public function messages()
    {
        return CommonConst::RULE_COMMON;
    }

    public function attributes()
    {
        return [
            'txtRetire_date' => '退職年月日',
            'txtPost_code' => '退職後〒',
            'txtAddress' => '退職後住所',
            'txtRetire_contact_info' => '退職後連絡先',
            'cmbSeparation_form' => '離職票',
        ];
    }
}
