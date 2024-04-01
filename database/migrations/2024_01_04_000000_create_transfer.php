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
        Schema::create('transfer', function (Blueprint $table) {
            $table->id();
            $table->string('ut_id');
            $table->string('status');
            $table->string('amount');
            $table->string('currency');

            $table->jsonb('json_blob');
            $table->jsonb('sign_json_blob')->default(null);
            $table->jsonb('call_back_reserved_at')->default(null);
            $table->timestamp('updated_at')->useCurrent();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transfer');
    }
};
