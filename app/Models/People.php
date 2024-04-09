<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class People extends Model
{
  use HasFactory, SoftDeletes;

  protected $table = 'peoples';

  protected $fillable = [
    'name',
    'email',
    'designation',
    'contact_no',
    'address',
    'is_active',
    'user_id',
    'created_by',
    'updated_by',
  ];

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
