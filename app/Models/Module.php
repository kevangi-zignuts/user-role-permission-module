<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Module extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'code';

    protected $keyType = 'string';

    protected $fillable = ['code', 'module_name', 'description', 'is_active', 'parent_code', 'created_by', 'updated_by'];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($module) {
            $user = auth()->user();
            if ($user) {
                $module->created_by = $user->id;
            }
        });

        static::updating(function ($module) {
            $user = auth()->user();
            if ($user) {
                $module->updated_by = $user->id;
            }
        });
    }

    public function submodules()
    {
        return $this->hasMany(Module::class, 'parent_code', 'code');
    }

    public function parentModule()
    {
        return $this->belongsTo(Module::class, 'parent_code', 'code');
    }

    public function permission()
    {
        return $this->belongsToMany(Permission::class, 'permission_modules', 'permission_id', 'module_code')->withPivot(
            'add_access',
            'view_access',
            'edit_access',
            'delete_access'
        );
    }
}
