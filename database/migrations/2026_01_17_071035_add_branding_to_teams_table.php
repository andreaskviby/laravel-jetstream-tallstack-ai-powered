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
        Schema::table('teams', function (Blueprint $table) {
            $table->string('logo_path', 2048)->nullable()->after('personal_team');
            $table->string('primary_color', 7)->nullable()->after('logo_path');
            $table->string('secondary_color', 7)->nullable()->after('primary_color');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {
            $table->dropColumn(['logo_path', 'primary_color', 'secondary_color']);
        });
    }
};
