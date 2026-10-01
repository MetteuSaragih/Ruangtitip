<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('biteship_status')->nullable()->after('biteship_tracking_id');
            $table->unsignedTinyInteger('biteship_attempts')->default(0)->after('biteship_status');
            $table->text('biteship_last_error')->nullable()->after('biteship_attempts');
            $table->boolean('needs_admin_attention')->default(false)->after('biteship_last_error');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['biteship_status', 'biteship_attempts', 'biteship_last_error', 'needs_admin_attention']);
        });
    }
};
