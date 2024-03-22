<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Permission extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = ['permission_name', 'description', 'is_active', 'created_by', 'updated_by'];

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

  public function module()
  {
    return $this->belongsToMany(Module::class, 'permission_modules', 'permission_id', 'module_code')->withPivot(
      'add_access',
      'view_access',
      'edit_access',
      'delete_access'
    );
  }

  public function role()
  {
    return $this->belongsToMany(Role::class, 'role_permissions', 'role_id', 'permission_id');
  }

  public function hasAccess($module, $permission)
  {
    // Check if the permission exists for the given module
    if ($this->module->contains($module)) {
      return (bool) $this->module->find($module)->pivot->{$permission . '_access'};
    }

    return false;
  }
}
