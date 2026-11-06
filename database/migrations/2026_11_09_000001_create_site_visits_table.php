<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Statistik kunjungan anonim: satu baris per pengunjung per hari.
     * visitor_id berisi kode acak dari cookie, bukan identitas. user_id terisi bila pengunjung sudah login pada hari itu.
     */
    public function up(): void
    {
        if (Schema::hasTable('site_visits')) {
            return;
        }

        Schema::create('site_visits', function (Blueprint $table) {
            $table->id();
            $table->string('visitor_id', 40);
            $table->date('visit_date');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedInteger('landing_views')->default(0); // membuka halaman depan (landing)
            $table->unsignedInteger('other_views')->default(0);   // membuka halaman lain (login, katalog, aplikasi, dst.)
            $table->timestamp('first_seen_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();

            $table->unique(['visitor_id', 'visit_date']);
            $table->index('visit_date');
            $table->index('last_seen_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_visits');
    }
};
