<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('errorlog_events', function (Blueprint $table) {
            $table->id();
            $table->string('fingerprint', 32)->index();
            $table->string('exception_class');
            $table->text('message')->nullable();
            $table->string('file')->nullable();
            $table->unsignedInteger('line')->nullable();
            $table->json('payload');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('environment')->nullable();
            $table->timestamp('occurred_at')->nullable();
            $table->timestamps();

            $table->index('occurred_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('errorlog_events');
    }
};
