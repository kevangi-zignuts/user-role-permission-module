<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = ['role_name', 'description', 'is_active', 'created_by', 'updated_by'];

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

  public function permission()
  {
    return $this->belongsToMany(Permission::class, 'role_permissions', 'role_id', 'permission_id');
  }

  public function user()
  {
    return $this->belongsToMany(Permission::class, 'user_roles', 'user_id', 'role_id');
  }
}
