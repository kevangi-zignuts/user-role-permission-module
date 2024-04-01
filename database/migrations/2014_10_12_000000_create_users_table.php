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
    Schema::create('users', function (Blueprint $table) {
      $table->id();
      $table->string('first_name', 64);
      $table->string('last_name', 64)->nullable();
      $table->string('email', 128);
      $table->timestamp('email_verified_at')->nullable();
      $table->string('password', 64);
      $table->string('contact_no', 16)->nullable();
      $table->string('address', 256)->nullable();
      $table->tinyInteger('is_active')->default(1);
      $table->string('invitation_token', 128)->nullable();
      $table->enum('status', ['I', 'A', 'R']); // I=Invited, A=Accepted, R=Rejected
      $table->string('remember_token', 256)->nullable();
      $table->timestamps();
      $table->softDeletes();
    });
  }

  /**
   * Reverse the migrations.
   */
  public function down(): void
  {
    Schema::dropIfExists('users');
  }
};
