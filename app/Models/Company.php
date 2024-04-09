<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = ['company_name', 'owner_name', 'industry', 'user_id', 'created_by', 'updated_by'];

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
}
