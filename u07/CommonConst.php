<?php


namespace App\Common;


class CommonConst
{
    const AUTH_USER = 'web';


    const MAIL_FROM = 'メールアドレス(I&f)';
    const MAIL_SUBJ = 'i&fホールディングス◆労務管理システム　パスワード再設定受付完了のお知らせ';


    const RULE_COMMON = [
        'required' => ':attributeを入力してください。',
        'max' => ':max桁まで入力してください。',
        'min' => ':min桁まで入力してください。',
        'email' => 'メールアドレスが不正です。',
        'exists' => '入力されたメールアドレスは存在しません。',
        'confirmed' => '入力されたパスワードが一致しません',
        'distinct' => ':attributeが重複しています。',
        'required_if' => ':otherのファイルアップロードを入力してください。',
        'accepted' =>'他の構成マスタの期間と重複です。',
        'unique' => '所属cd=:inputが重複しています。',
        'before' => ':attributeの範囲指定に矛盾があります。',
        'after' => ':attributeは:dateより後の時間が必要です。',
        'same' => ':attributeが一致しません。',
        'required_with' => ':attributeのファイルアップロードを入力してください。',
    ];


    const ATTR_COMMON = [
        'email' => 'メールアドレス',
        'password' => 'パスワード',
        'nameProcedure.*' => '氏名',
        'office_cd' => '事業所cd',
        'office_name' => '事業所名',
        'belong_cd' => '所属cd',
        'belong_name' => '所属名',
        'belong_address' => '所属住所',
        'staff_name_1.*' => '1次承認名',
        'staff_name_2.*' => '2次承認名',
        'start_date' => '開始日時',
        'end_date' => '終了日時',
        'trStaffType1_.*' => '1次承認',
        'nameStaffType1.*' => '1次承認',
        'nameStaffType1Ins.*' => '1次承認',
        'nameStaffType1Up.*' => '1次承認',
        'nameStaffType2.*' => '2次承認',
        'nameStaffType2Ins.*' => '2次承認',
        'nameStaffType2Up.*' => '2次承認',
        'trStaffType2_.*' => '2次承認',
        'tbStaffType1' => '1次承認',
        'tbStaffType2' => '2次承認',
    ];


    const DEL_FLAG = '1';
    const UNDEL_FLAG = '0';


    const MAX_ROW = 40;
    const DATE_MIN = '2020-01-01';
    const DATE_MAX = '2100-12-31';
    const DATE_FORMAT_1 = "Y/m/d";
    const DATE_FORMAT_2 = "Y-m-d";
    const DATE_FORMAT_3 = "Y.m.d";
    const DATETIME_FORMAT = "Y/m/d H:i";
    const DATETIME_FORMAT_2 = "Y.m.d H:i";
    const POST_CODE_LINK = "https://www.post.japanpost.jp/zipcode/";


    const ENCODING_U = 'utf8';
    const ENCODING_CSV = 'UTF-8';
    const PATH_MAX_LENGTH = 200;
    const VER_HIS = 0;


    //Folder Create Upload
    const UPLOAD_FOLDER_PATH = 'storage/app/public/';
    const UPLOAD_USERS = 'staff/';
    const UPLOAD_OFFICES = 'offices/';
    const UPLOAD_INFO_USERS = 'info_users/';
    const UPLOAD_W_INFO_USERS = 'w_info_users/';
    const UPLOAD_RULES = 'rules/';
    const UPLOAD_NOTICE_CARD = 'notice_cars/';
    const UPLOAD_DRIVER_LICENSE = 'driver_license/';
    const UPLOAD_DEPENDENT_PERSON = 'dependent_person/';
    const UPLOAD_INSURANCE_NO = 'insurance_no/';
    const UPLOAD_RESIDENT_TAX = 'resident_tax/';
    const UPLOAD_CHILDCARE_FEE = 'childcare_fee/';
    const UPLOAD_DISABLE_PERSON = 'disable_person/';
    const UPLOAD_RESIDENT_PERSON_CARD = 'resident_person_card/';
    const UPLOAD_HEALTH_CERTIFICATE = 'health_certificate/';
    const UPLOAD_OTHERS = 'others/';
    const UPLOAD_PASSPORT_FOREIGN = 'passport_foreign/';
    const UPLOAD_DISABLE_DEPEND = 'disable_depend/';
    const UPLOAD_RESIDENT_DEPEND = 'resident_depend/';
    const UPLOAD_CERTIFICATE = 'certificate/';


    const NOTICE_CARD_BEFORE_NAME = '通知カード(表)';
    const NOTICE_CARD_AFTER_NAME = '通知カード(裏)';
    const DRIVER_LICENSE_BEFORE_NAME = '運転免許証(表)';
    const DRIVER_LICENSE_AFTER_NAME = '運転免許証(裏)';
    const DEPENDENT_PERSON_NAME = '扶養控除申告書';
    const INSURANCE_NO_NAME = '保険番号';
    const RESIDENT_TAX_NAME = '住民税';
    const CHILDCARE_FEE_NAME = '保育料助成金';
    const DISABLE_PERSON_NAME = '障害者手帳(本人分)';
    const RESIDENT_PERSON_CARD_BEFORE_NAME = '在留カード(本人分_表)';
    const RESIDENT_PERSON_CARD_AFTER_NAME = '在留カード(本人分_裏)';
    const HEALTH_CERTIFICATE_NAME = '健康診断書';
    const OTHERS_NAME = 'その他';
    const PASSPORT_FOREIGN_NAME = '通帳';
    const DISABLE_DEPEND_NAME = '障害者';
    const RESIDENT_DEPEND_BEFORE_NAME = '在留カード(表)';
    const RESIDENT_DEPEND_AFTER_NAME = '在留カード(裏)';
    const CERTIFICATE_NAME = '資格者証';


    const NOTICE_BEFORE = 'NoticeCardBefore';
    const NOTICE_AFTER = 'NoticeCardAfter';
    const LICENSE_BEFORE = 'DriverLicenseBefore';
    const LICENSE_AFTER = 'DriverLicenseAfter';
    const DEPENDENT_PERSON = 'DependentPerson';
    const INSURANCE_NO = 'InsuranceNo';
    const RESIDENT_TAX = 'ResidentTax';
    const CHILDCARE_FEE = 'ChildcareFee';
    const DISABLE_PERSON = 'DisablePerson';
    const RESIDENT_PERSON_CARD_BEFORE = 'ResidentCardBefore';
    const RESIDENT_PERSON_CARD_AFTER = 'ResidentCardAfter';
    const HEALTH_CERTIFICATE = 'HealthCertificate';
    const OTHERS = 'Others';
    const PASSPORT_FOREIGN = 'PassportForeign';
    const ROUTE_MAP = 'RouteMap';


    //クラスマスタ
    const APPLY_OBJECT = 'APPLY_OBJECT';
    const YES_NO = 'YES_NO';
    const WORK_TIME = 'WORK_TIME';
    const WORK_STATUS = 'WORK_STATUS';
    const PAYMENT_METHOD = 'PAYMENT_METHOD';
    const GENDER = 'GENDER';
    const CONTRACT_CLASS = 'CONTRACT_CLASS';
    const OBJECT_PERSON = 'OBJECT_PERSON';
    const TRANSPORT_TYPE = 'TRANSPORT_TYPE';
    const DEPOSIT_TYPE = 'DEPOSIT_TYPE';
    const SEPARATE_FORM = 'SEPARATE_FORM';
    const UPLOAD_TYPE = 'UPLOAD_TYPE';
    const UPLOAD_TYPE_1 = 1; //会社ロゴ
    const UPLOAD_TYPE_2 = 2; //サイン書類
    const UPLOAD_TYPE_3 = 3; //労務経理担当者誓約書
    const UPLOAD_TYPE_4 = 4; //社章
    const UPLOAD_TYPE_5 = 5; //通勤経路マップ
    const AUTHORITY_ADMIN = '1';  //管理者権限
    const AUTHORITY_HD = '2';     //HD労務担当
    const AUTHORITY_1STUSER = '3';//一版ユーザ


    const STORAGE_TEMP = 'public/temp/';
    const STORAGE_PUBLIC = 'public/';


    const CURRENCY_UNIT = '円';
    const STATUS_CREATOR = '入職者申請待ち';
    const STATUS_APPROVAL_1 = '1次承認待ち';
    const STATUS_APPROVAL_2 = '2次承認待ち';
    const STATUS_APPROVAL_HD = 'HD承認待ち';
    const STATUS_REJECT = '上長入力中';
    const STATUS_COMPLETED = '完了';
    const STATUS_INFO_GUARANTOR_WAIT = '身元保証人申請待ち';
    const PROD_NAME_PHONE = '会社携帯';
    const OFFICE_NAME = 'ライフケア';
    const OFFICE_CD_HD = '00004';
    const USERNUM_LIMIT_START = '0000001';
    const USERNUM_LIMIT_END = '0100000';
    const BTN_APPROVE = '承認';
    const BTN_REJECT = '否認';


    //STATUS U07
    const U07_STATUS_保存 = '上長入力中';
    const U07_STATUS_作成者 = '退職者申請待ち';
    const U07_STATUS_1次= '1次承認待ち';
    const U07_STATUS_2次= '2次承認待ち';
    const U07_STATUS_HD= 'HD承認待ち';
    //
    const C_作成者 = '作成者';
    const C_申請者 = '申請者';
    const C_1次承認 = '1次承認';
    const C_1次否認= '1次否認';
    const C_2次承認= '2次承認';
    const C_2次否認= '2次否認';
    const C_HD承認= 'HD承認';
    const C_HD否認= 'HD否認';
    //EXPORT
    const MODE_EXPORT_BASIC = 'exportBasic';
    const MODE_EXPORT_SALARY = 'exportSalary';
    //NOTICES VISIBLE
    const NOTICE_NO_INDICATION = '0';
    const NOTICE_INDICATION = '1';
}
