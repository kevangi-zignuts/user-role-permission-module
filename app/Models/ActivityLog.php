<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ActivityLog extends Model
{
  use HasFactory, SoftDeletes;

  protected $fillable = ['name', 'type', 'log', 'is_active', 'user_id', 'created_by', 'updated_by'];

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
