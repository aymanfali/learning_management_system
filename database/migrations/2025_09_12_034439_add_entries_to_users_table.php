<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->date('birthdate')->after('name')->nullable();
            $table->text('image')->after('birthdate')->nullable();
            $table->enum('role', ['admin', 'instructor', 'student'])->after('image')->default('student');
            $table->string('major')->after('role')->nullable();
            $table->text('cv')->after('major')->nullable();
            $table->text('bio')->after('cv')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['slug', 'birthdate', 'image', 'role', 'major', 'cv', 'bio']);
        });
    }
};
