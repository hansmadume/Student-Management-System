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
        Schema::table('departments', function (Blueprint $table) {
            if (! Schema::hasColumn('departments', 'department_head')) {
                $table->string('department_head')->nullable()->after('name');
            }

            if (! Schema::hasColumn('departments', 'description')) {
                $table->text('description')->nullable()->after('department_head');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('departments', function (Blueprint $table) {
            if (Schema::hasColumn('departments', 'description')) {
                $table->dropColumn('description');
            }

            if (Schema::hasColumn('departments', 'department_head')) {
                $table->dropColumn('department_head');
            }
        });
    }
};