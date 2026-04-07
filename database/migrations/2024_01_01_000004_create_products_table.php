<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('products.table', 'products'), function (Blueprint $table) {
            $table->id();
            $table->morphs('owner');
            $table->string('type')->default('product');
            $table->string('sku')->nullable();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('default_unit')->nullable();
            $table->bigInteger('default_price_cents')->default(0);
            $table->bigInteger('default_cost_cents')->nullable();
            $table->decimal('default_tax_rate', 6, 4)->nullable();
            $table->string('currency', 3)->default('USD');
            $table->string('category')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('metadata')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['owner_type', 'owner_id', 'type', 'is_active']);
            $table->unique(['owner_type', 'owner_id', 'sku']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('products.table', 'products'));
    }
};
