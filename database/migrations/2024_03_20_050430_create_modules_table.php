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
    Schema::create('modules', function (Blueprint $table) {
      $table->string('code', 8)->primary();
      $table->string('module_name', 64);
      $table->string('description', 256)->nullable();
      $table->tinyInteger('is_active')->default(1);
      $table->string('parent_code', 8)->nullable();
      $table->string('url', 128)->nullable();
      $table
        ->string('slug', 128)
        ->nullable()
        ->unique();
      $table->unsignedBigInteger('created_by')->nullable();
      $table->unsignedBigInteger('updated_by')->nullable();
      $table->timestamps();
      $table->softDeletes();

      $table
        ->foreign('parent_code')
        ->references('code')
        ->on('modules')
        ->onDelete('cascade')
        ->nullable();
      $table
        ->foreign('created_by')
        ->references('id')
        ->on('users')
        ->onDelete('cascade');
      $table
        ->foreign('updated_by')
        ->references('id')
        ->on('users')
        ->onDelete('cascade');
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('modules');
  }
};
