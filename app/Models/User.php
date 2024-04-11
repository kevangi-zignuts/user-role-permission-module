<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticableTrait;

class User extends Authenticatable
{
  use HasApiTokens, HasFactory, Notifiable, AuthenticableTrait, SoftDeletes;

  /**
   * The attributes that are mass assignable.
   *
   * @var array<int, string>
   */
  protected $fillable = [
    'first_name',
    'last_name',
    'email',
    'password',
    'contact_no',
    'address',
    'is_active',
    'invitation_token',
    'status',
  ];

  /**
   * The attributes that should be hidden for serialization.
   *
   * @var array<int, string>
   */
  protected $hidden = ['password', 'remember_token'];

  /**
   * The attributes that should be cast.
   *
   * @var array<string, string>
   */
  protected $casts = [
    'email_verified_at' => 'datetime',
  ];

  public function role()
  {
    return $this->belongsToMany(Role::class, 'user_roles', 'user_id', 'role_id');
  }

  // User.php
  public function getModulesWithPermissions()
  {
    $modules = collect();
    foreach ($this->role as $role) {
      foreach ($role->permission as $permission) {
        // dd($permission->module);
        // $modules = $modules->merge($permission->module);
        $modules = $modules->merge(
          $permission->module->filter(function ($module) {
            return $module->pivot->add_access || $module->pivot->view_access;
          })
        );
      }
    }
    // dd($modules);
    return $modules->unique('code');
  }
  public function getModuleWithPermissions()
  {
    $modules = collect();
    foreach ($this->role as $role) {
      foreach ($role->permission as $permission) {
        $modules = $modules->merge($permission->module);
        // dd($modules);
        $modules = $modules->merge(
          $permission->module->filter(function ($module) {
            return $module->pivot->add_access ||
              $module->pivot->view_access ||
              $module->pivot->edit_access ||
              $module->pivot->delete_access;
          })
        );
      }
    }
    // dd($modules, $modules->unique('code'));
    return $modules;
    // return $modules->unique('code');
  }

  // public function hasPermission($moduleCode, $accessType)
  // {
  //   $permission = $this->role()
  //     ->whereHas('permission.module', function ($query) use ($moduleCode, $accessType) {
  //       $query->where('module_code', $moduleCode)->where($accessType, true);
  //     })
  //     ->exists();

  //   // Check if any of the user's roles have permissions for the specified module and access type
  //   return $this->role()
  //     ->whereHas('permission.module', function ($query) use ($moduleCode, $accessType) {
  //       $query->where('module_code', $moduleCode)->where($accessType, true);
  //     })
  //     ->exists();
  // }

  public function hasPermission($moduleCode, $accessType)
  {
    // Get all roles of the user
    $roles = $this->role()
      ->with('permission.module')
      ->get();

    foreach ($roles as $role) {
      foreach ($role->permission as $permission) {
        foreach ($permission->module as $module) {
          if ($module->code === $moduleCode && $module->pivot->$accessType) {
            return true;
          }
        }
      }
    }

    return false; // User does not have permission for the specified module and access type
  }
}
