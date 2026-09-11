<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->string('hr_signature_path')->nullable()->after('logo');
            $table->string('hr_signer_name')->nullable()->after('hr_signature_path');
            $table->string('hr_signer_title')->nullable()->after('hr_signer_name');
        });
    }

    public function down(): void
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn(['hr_signature_path', 'hr_signer_name', 'hr_signer_title']);
        });
    }
};
