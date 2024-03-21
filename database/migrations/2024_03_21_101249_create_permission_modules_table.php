<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  /**
   * Run the migrations.
   */
  public function up(): void
  {
    Schema::create('permission_modules', function (Blueprint $table) {
      $table->unsignedBigInteger('permission_id');
      $table->string('module_code', 8);
      $table->tinyInteger('add_access');
      $table->tinyInteger('view_access');
      $table->tinyInteger('edit_access');
      $table->tinyInteger('delete_access');

      $table
        ->foreign('module_code')
        ->references('code')
        ->on('modules')
        ->onDelete('cascade')
        ->nullable();
      $table
        ->foreign('permission_id')
        ->references('id')
        ->on('permissions')
        ->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('permission_modules');
  }
};
