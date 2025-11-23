<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('financial_request_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('financial_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained('financial_request_categories');
            $table->decimal('amount', 10, 2);
            $table->text('description');
            $table->string('attachment_path')->nullable();
            $table->string('status')->default('pending'); // pending, approved_manager, approved_finance, paid, rejected
            $table->unsignedBigInteger('approved_by_manager')->nullable();
            $table->unsignedBigInteger('approved_by_finance')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('financial_requests');
        Schema::dropIfExists('financial_request_categories');
    }
};
