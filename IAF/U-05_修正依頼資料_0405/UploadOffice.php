<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UploadOffice extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'upload_type',
        'office_cd',
        'folder_path',
        'folder_name',
        'file_name',
        'created_id',
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

    public function scopeUploadType($query, $type)
    {
        return $query->where('upload_type', $type);
    }

    public function scopeOfficeCd($query, $officeCd)
    {
        return $query->where('office_cd', $officeCd);
    }
}
