<?php

namespace App\Http\Requests;

use App\Common\CommonConst;
use Illuminate\Foundation\Http\FormRequest;

class U03Request extends FormRequest
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
        if($this->has('txtTitle_invitation')){
            $validate['txtTitle_invitation'] = 'required|max:30';
            $validate['txtIntro_invitation'] = 'required|max:500';
            $validate['txtBody_invitation'] = 'required|max:1000';
        }
//u03-1
        if($this->has('txtDate_join') && $this->has('txtLastName')){
            $validate['txtNote'] = 'max:500';
            $validate['txtDate_join'] = 'required';
            $validate['txtFirstName'] = 'required|max:30';
            $validate['txtLastName'] = 'required|max:30';
            $validate['txtFirstFurigana'] = 'required|max:30|regex:'.CommonConst::REGEX_FURIGANA;
            $validate['txtLastFurigana'] = 'required|max:30|regex:'.CommonConst::REGEX_FURIGANA;
            $validate['cmbWorkStatus'] = 'required';
            $validate['cmbOffice_cd'] = 'required';
            $validate['cmbBelong_cd'] = 'required';
            $validate['txtPosition'] = 'max:30';
            $validate['cmbWork_time_flag'] = 'required';
            if($this->input('id') == '')
                $validate['users_number'] = 'nullable|max:7|unique:info_users,users_number,' . $this->id;
            else
                $validate['users_number'] = 'required|max:7|unique:info_users,users_number,' . $this->id;
            $validate['txtCompany_mobile_number'] = 'nullable|max:20';
            $validate['txtResume'] = 'required';
            $validate['email'] = 'required|email|required_with:confirm_email|same:confirm_email|max:100|unique:users,email';
            $validate['confirm_email'] = 'required|email|max:100';
            if($this->input('hidCheck') != '')
            {
                $validate['txtConditions'] = 'required';
                $validate['txtCollectionFees'] = 'required';
                $validate['txtContract'] = 'required';
            }
       }
        return $validate;
    }
    
    public function messages()
    {
        $rule = CommonConst::RULE_COMMON;
        $rule['txtFirstFurigana.regex'] = $rule['txtLastFurigana.regex'] = CommonConst::RULE_REGEX_FURIGANA;
        return $rule;
    }

    public function attributes()
    {
        return [
            'txtTitle_invitation' => 'タイトル',
            'txtIntro_invitation' => '前書き',
            'txtBody_invitation' => '本文',
            'txtRental'          => '賃貸借契約書',
            'txtEvidence'        => '郵便物',
            'chkLive'            => '条件1',
            'chkEmpFultime'     => '条件2',
            'txtNote' => 'コメント',
            'txtDate_join' => '入社予定日',
            'txtFirstName' => '氏名（姓）',
            'txtLastName' => '氏名（名）',
            'txtFirstFurigana' => 'ﾌﾘｶﾞﾅ（姓）',
            'txtLastFurigana' => 'ﾌﾘｶﾞﾅ（名）',
            'cmbWorkStatus' => 'ステータス',
            'cmbOffice_cd' => '事業所',
            'cmbBelong_cd' => '所属',
            'txtPosition' => '役職',
            'cmbWork_time_flag' => '正社員・パート',
            'users_number' => '社員番号',
            'txtCompany_mobile_number' => '会社携帯番号',
            'txtResume' => '履歴書',
            'email' => 'メールアドレス',
            'confirm_email' => 'メールアドレス_再確認',
            'txtConditions' =>  '（外国人雇用）雇用条件書',
            'txtCollectionFees' => '（外国人雇用）徴収費用の説明書',
            'txtContract' => '（外国人雇用）雇用契約書',
        ];
    }
}
