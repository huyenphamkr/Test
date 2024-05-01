<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class InfoUsers extends Model
{
    use HasFactory;

    protected $fillable = [
        'ver_his',
        'ver_his_U05',
        'procedure_type',
        'date_join',
        'first_name',
        'last_name',
        'first_furigana',
        'last_furigana',
        'office_cd',
        'belong_cd',
        'position',
        'work_time_flag',
        'users_number',
        'procedure_items',
        'company_mobile_number',
        'manager',
        'manage_position',
        'flg1',
        'manage_business',
        'accountant',
        'company_car',
        'flg3',
        'flg4',
        'flg5',
        'flg6',
        'flg7',
        'flg8',
        'flg9',
        'sign_items',
        'email',
        'confirm_email',
        'note',
        'author_id',
        'author_date',
        'applicant_id',
        'applicant_date',
        'admin1_id',
        'admin1_date',
        'admin2_id',
        'admin2_date',
        'hd_id',
        'hd_date',
        'hd_confirm_date',
        'status_user',
        'contract_class',
        'gender',
        'tel_number',
        'post_code',
        'adress1',
        'adress2',
        'adress3',
        'adress_furigana1',
        'adress_furigana2',
        'adress_furigana3',
        'birthday',
        'prev_job',
        'my_number',
        'object_person',
        'jion_insurance',
        'insurance_date',
        'insurance_social',
        'insurance_social_date',
        'dependent_number',
        'bank_name',
        'branch_bank_name',
        'bank_user_name',
        'bank_user_furigana',
        'branch_bank_number',
        'bank_number',
        'private_car',
        'transport_type',
        'payment_date',
        'payment_amount',
        'rental_folder_path',
        'rental_folder_name',
        'rental_file_name',
        'evidence_folder_path',
        'evidence_folder_name',
        'evidence_file_name',
        'sign_folder_path',
        'sign_folder_name',
        'sign_file_name',
        'sign_date',
        'authority',
        'work_status',
        'bank_code',
        'branch_bank_code',
        'deposit_type',
        'token',
        'token_guarantee',
        'office_cd_old',
        'belong_cd_old',
        'updated_date',
        'created_at',
        'created_id',
        'updated_at',
        'updated_id',
        'revision',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                $model->created_id = Auth::user()->id;
                $model->updated_id = Auth::user()->id;
            }
        });
        static::updating(function ($model) {
            if (Auth::check()) {
                $model->updated_id = Auth::user()->id;
                $model->revision = $model->revision + 1;
            }
        });
    }

    public function scopeId($query, $id)
    {
        return $query->where('id', $id);
    }

    public function scopeVerHisU05($query, $verHisU05)
    {
        return $query->where('ver_his_U05', $verHisU05);
    }
}
