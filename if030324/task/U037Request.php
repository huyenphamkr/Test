<?php

namespace App\Http\Requests;

use App\Common\CommonConst;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;

class U037Request extends FormRequest
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
    public function rules(Request $request)
    {
        $data = $request->input();
        $validate = [];
        if($this->has('cmbContract_class')){
            $validate['cmbContract_class'] = 'required';
            $validate['cmbGender'] = 'required';
            $validate['txtTel_number1'] = 'required|max:5';
            $validate['txtTel_number2'] = 'required|max:5';
            $validate['txtTel_number3'] = 'required|max:5';
            $validate['txtPost_code'] = 'required|max:8';
            $validate['txtAdress1'] = 'required|max:30';
            $validate['txtAdress_furigana1'] = 'required|max:30|regex:'.CommonConst::REGEX_FURIGANA;
            $validate['txtAdress_furigana2'] = 'max:30|regex:'.CommonConst::REGEX_FURIGANA;
            $validate['txtAdress_furigana3'] = 'max:30|regex:'.CommonConst::REGEX_FURIGANA;
            $validate['txtBirthday'] = 'required|max:10';
            $validate['txtPrev_job'] = 'required|max:50';
            $validate['txtMy_number'] = 'required|max:12';
            $validate['txtBank_code'] = 'required|max:4';
            $validate['txtBank_name'] = 'required|max:20';
            $validate['txtBranch_bank_code'] = 'required|max:3';
            $validate['txtBranch_bank_name'] = 'required|max:20';
            $validate['txtBank_user_name'] = 'required|max:30';
            $validate['txtBank_user_furigana'] = 'required|max:30|regex:'.CommonConst::REGEX_FURIGANA;
            $validate['cmbDeposit_type'] = 'required';
            $validate['txtBank_number'] = 'required|max:7';
            if($this->request->get('curRouteMap') == 0) $validate['linkRouteMap'] = 'required';
            if ($this->request->has('txtInsurance_date') && $this->input('cmbJion_insurance')  == '2') {
                $validate['txtInsurance_date'] = 'required';
            }
            if ($this->request->has('txtInsurance_social_date') && $this->input('cmbInsurance_social')  == '2') {
                $validate['txtInsurance_social_date'] = 'required';
            }
        }
        if($this->has('txtDepName')){
            $validate['txtDepName.*'] = 'required|max:30';
            $validate['txtDepFurigana.*'] = 'required|max:30';
            $validate['cmbDepGender.*'] = 'required';
            $validate['txtDepRelationship.*'] = 'required|max:10';
            $validate['txtDepBirthday.*'] = 'required|max:10';
            $validate['txtDepCareer.*'] = 'required|max:50';
            $validate['txtDepMyNumber.*'] = 'required|max:12';
        }
        if($this->has('txtDepNameIns')){
            $validate['txtDepNameIns.*'] = 'required|max:30';
            $validate['txtDepFuriganaIns.*'] = 'required|max:30';
            $validate['cmbDepGenderIns.*'] = 'required';
            $validate['txtDepRelationshipIns.*'] = 'required|max:10';
            $validate['txtDepBirthdayIns.*'] = 'required|max:10';
            $validate['txtDepCareerIns.*'] = 'required|max:50';
            $validate['txtDepMyNumberIns.*'] = 'required|max:12';
        }
        if($this->has('txtDepNameUp')){
            $validate['txtDepNameUp.*'] = 'required|max:30';
            $validate['txtDepFuriganaUp.*'] = 'required|max:30';
            $validate['cmbDepGenderUp.*'] = 'required';
            $validate['txtDepRelationshipUp.*'] = 'required|max:10';
            $validate['txtDepBirthdayUp.*'] = 'required|max:10';
            $validate['txtDepCareerUp.*'] = 'required|max:50';
            $validate['txtDepMyNumberUp.*'] = 'required|max:12';
        }
        
        if($this->has('txtPrivate_car')){
            if($this->has('txtStartPoint')){
                $flgTrans = false;
                if((empty($data['txtPrivate_car']) && empty($data['cmbTransport_type'])) ||
                !empty($data['txtPrivate_car']) || !empty($data['cmbTransport_type'])){
                    for($i=0; $i < count($data['txtStartPoint']); $i++){
                        if($data['txtStartPoint'][$i] != "" || $data['txtEndPoint'][$i] != ""){
                            $flgTrans = true;
                            break;
                        }
                        if(!empty($data['txtAmount'][$i]) || $data['txtAmount'][$i] != ""){
                            $flgTrans = true;
                            break;
                        }
                    }
                }
                if($flgTrans){
                    $validate['txtStartPoint.*'] = 'required|max:10';
                    $validate['txtEndPoint.*'] = 'required|max:10';
                    $validate['txtAmount.*'] = 'required|max:11';
                }
            }
            if($this->has('txtStartPointIns')){
                $flgTrans = false;
                if((empty($data['txtPrivate_car']) && empty($data['cmbTransport_type'])) ||
                !empty($data['txtPrivate_car']) || !empty($data['cmbTransport_type'])){
                    for($i=0; $i < count($data['txtStartPointIns']); $i++){
                        if($data['txtStartPointIns'][$i] != "" || $data['txtEndPointIns'][$i] != ""){
                            $flgTrans = true;
                            break;
                        }
                        if(!empty($data['txtAmountIns'][$i]) || $data['txtAmountIns'][$i] != ""){
                            $flgTrans = true;
                            break;
                        }
                    }
                }
                if($flgTrans){
                    $validate['txtStartPointIns.*'] = 'required|max:10';
                    $validate['txtEndPointIns.*'] = 'required|max:10';
                    $validate['txtAmountIns.*'] = 'required|max:11';
                }
            }
            if($this->has('txtStartPointUp')){
                $flgTrans = false;
                if((empty($data['txtPrivate_car']) && empty($data['cmbTransport_type'])) ||
                !empty($data['txtPrivate_car']) || !empty($data['cmbTransport_type'])){
                    for($i=0; $i < count($data['txtStartPointUp']); $i++){
                        if($data['txtStartPointUp'][$i] != "" || $data['txtEndPointUp'][$i] != ""){
                            $flgTrans = true;
                            break;
                        }
                        if(!empty($data['txtAmountUp'][$i]) || $data['txtAmountUp'][$i] != ""){
                            $flgTrans = true;
                            break;
                        }
                    }
                }
                if($flgTrans){
                    $validate['txtStartPointUp.*'] = 'required|max:10';
                    $validate['txtEndPointUp.*'] = 'required|max:10';
                    $validate['txtAmountUp.*'] = 'required|max:11';
                }
            }
        }
        return $validate;
    }
    
    public function messages()
    {
        $rule = CommonConst::RULE_COMMON;
        $rule['txtAdress_furigana1.regex'] = $rule['txtAdress_furigana2.regex'] = 
        $rule['txtAdress_furigana3.regex'] = $rule['txtBank_user_furigana.regex'] = CommonConst::RULE_REGEX_FURIGANA;
        return $rule;
    }

    public function attributes()
    {
        return [
            'cmbContract_class' => '契約区分',
            'cmbGender' => '性別',
            'txtTel_number1' => '電話番号',
            'txtTel_number2' => '電話番号',
            'txtTel_number3' => '電話番号',
            'txtPost_code' => '〒',
            'txtAdress1' => '住所1',
            'txtAdress_furigana1' => '住所1（ﾌﾘｶﾞﾅ）',
            'txtAdress_furigana2' => '住所2（ﾌﾘｶﾞﾅ）',
            'txtAdress_furigana3' => '住所3（ﾌﾘｶﾞﾅ）',
            'txtBirthday' => '生年月日',
            'txtPrev_job' => '前職（会社名）',
            'txtMy_number' => 'マイナンバー',
            'txtBank_code' => '銀行コード',
            'txtBank_name' => '銀行名・振興金庫名・組合名',
            'txtBranch_bank_code' => '支店コード',
            'txtBranch_bank_name' => '支店名・出張所/代理店名',
            'txtBank_user_name' => '口座氏名',
            'txtBank_user_furigana' => 'ﾌﾘｶﾞﾅ',
            'cmbDeposit_type' => '預金種類',
            'txtBank_number' => '口座番号',
            'imgRouteMap' => '通勤経路マップ',
            'txtDepName.*' => '氏名',
            'txtDepNameIns.*' => '氏名',
            'txtDepNameUp.*' => '氏名',
            'txtDepFurigana.*' => 'フリガナ',
            'txtDepFuriganaIns.*' => 'フリガナ',
            'txtDepFuriganaUp.*' => 'フリガナ',
            'cmbDepGender.*' => '性別',
            'cmbDepGenderIns.*' => '性別',
            'cmbDepGenderUp.*' => '性別',

            'txtDepRelationship.*' => '続柄',
            'txtDepRelationshipIns.*' => '続柄',
            'txtDepRelationshipUp.*' => '続柄',
            'txtDepBirthday.*' => '生年月日',
            'txtDepBirthdayIns.*' => '生年月日',
            'txtDepBirthdayUp.*' => '生年月日',
            'txtDepCareer.*' => '職業',
            'txtDepCareerIns.*' => '職業',
            'txtDepCareerUp.*' => '職業',
            'txtDepMyNumber.*' => 'マイナンバー',
            'txtDepMyNumberIns.*' => 'マイナンバー',
            'txtDepMyNumberUp.*' => 'マイナンバー',

            'txtStartPoint.*' => '公共交通機関',
            'txtStartPointIns.*' => '公共交通機関',
            'txtStartPointUp.*' => '公共交通機関',
            'txtEndPoint.*' => '公共交通機関',
            'txtEndPointIns.*' => '公共交通機関',
            'txtEndPointUp.*' => '公共交通機関',
            'txtAmount.*' => '金額',
            'txtAmountIns.*' => '金額',
            'txtAmountUp.*' => '金額',

            'linkRouteMap' =>'通勤経路マップ',

            'txtInsurance_date' => '社会保険加入日',
            'txtInsurance_social_date' => '雇用保険加入日',
        ];
    }
}
