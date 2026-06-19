<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('preloved_orders')) {
            Schema::table('preloved_orders', function (Blueprint $table) {
                if (! Schema::hasColumn('preloved_orders', 'user_id')) {
                    $table->foreignId('user_id')->nullable()->after('order_code')->constrained()->nullOnDelete();
                }

                if (! Schema::hasColumn('preloved_orders', 'preloved_product_id')) {
                    $table->foreignId('preloved_product_id')->nullable()->after('user_id')->constrained()->nullOnDelete();
                }

                if (! Schema::hasColumn('preloved_orders', 'quantity')) {
                    $table->integer('quantity')->default(1)->after('preloved_product_id');
                }

                if (! Schema::hasColumn('preloved_orders', 'product_subtotal')) {
                    $table->decimal('product_subtotal', 12, 2)->nullable()->after('quantity');
                }

                if (! Schema::hasColumn('preloved_orders', 'service_fee')) {
                    $table->decimal('service_fee', 12, 2)->default(1000)->after('product_subtotal');
                }

                if (! Schema::hasColumn('preloved_orders', 'shipping_fee')) {
                    $table->decimal('shipping_fee', 12, 2)->default(0)->after('service_fee');
                }

                if (! Schema::hasColumn('preloved_orders', 'total_amount')) {
                    $table->decimal('total_amount', 12, 2)->nullable()->after('shipping_fee');
                }

                if (! Schema::hasColumn('preloved_orders', 'delivery_method')) {
                    $table->enum('delivery_method', ['pickup', 'biteship'])->default('pickup')->after('total_amount');
                }

                if (! Schema::hasColumn('preloved_orders', 'courier_code')) {
                    $table->string('courier_code')->nullable()->after('delivery_method');
                }

                if (! Schema::hasColumn('preloved_orders', 'courier_name')) {
                    $table->string('courier_name')->nullable()->after('courier_code');
                }

                if (! Schema::hasColumn('preloved_orders', 'delivery_address')) {
                    $table->string('delivery_address')->nullable()->after('courier_name');
                }

                if (! Schema::hasColumn('preloved_orders', 'payment_method')) {
                    $table->enum('payment_method', ['qris', 'virtual_account', 'ewallet'])->nullable()->after('delivery_address');
                }

                if (! Schema::hasColumn('preloved_orders', 'payment_gateway')) {
                    $table->string('payment_gateway')->default('midtrans')->after('payment_method');
                }

                if (! Schema::hasColumn('preloved_orders', 'midtrans_order_id')) {
                    $table->string('midtrans_order_id')->nullable()->after('payment_gateway');
                }

                if (! Schema::hasColumn('preloved_orders', 'midtrans_transaction_id')) {
                    $table->string('midtrans_transaction_id')->nullable()->after('midtrans_order_id');
                }

                if (! Schema::hasColumn('preloved_orders', 'midtrans_token')) {
                    $table->string('midtrans_token')->nullable()->after('midtrans_transaction_id');
                }

                if (! Schema::hasColumn('preloved_orders', 'midtrans_redirect_url')) {
                    $table->string('midtrans_redirect_url')->nullable()->after('midtrans_token');
                }

                if (! Schema::hasColumn('preloved_orders', 'payment_status')) {
                    $table->enum('payment_status', ['pending', 'paid', 'failed', 'expired'])->default('pending')->after('midtrans_redirect_url');
                }

                if (! Schema::hasColumn('preloved_orders', 'order_status')) {
                    $table->enum('order_status', ['pending', 'confirmed', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending')->after('payment_status');
                }

                if (! Schema::hasColumn('preloved_orders', 'biteship_order_id')) {
                    $table->string('biteship_order_id')->nullable()->after('order_status');
                }

                if (! Schema::hasColumn('preloved_orders', 'tracking_number')) {
                    $table->string('tracking_number')->nullable()->after('biteship_order_id');
                }

                if (! Schema::hasColumn('preloved_orders', 'paid_at')) {
                    $table->timestamp('paid_at')->nullable()->after('tracking_number');
                }
            });

            return;
        }

        Schema::create('preloved_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_code')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('preloved_product_id')->nullable()->constrained()->nullOnDelete();
            $table->integer('quantity')->default(1);
            $table->decimal('product_subtotal', 12, 2);
            $table->decimal('service_fee', 12, 2)->default(1000);
            $table->decimal('shipping_fee', 12, 2)->default(0);
            $table->decimal('total_amount', 12, 2);
            $table->enum('delivery_method', ['pickup', 'biteship'])->default('pickup');
            $table->string('courier_code')->nullable();
            $table->string('courier_name')->nullable();
            $table->string('delivery_address')->nullable();
            $table->enum('payment_method', ['qris', 'virtual_account', 'ewallet'])->nullable();
            $table->string('payment_gateway')->default('midtrans');
            $table->string('midtrans_order_id')->nullable();
            $table->string('midtrans_transaction_id')->nullable();
            $table->string('midtrans_token')->nullable();
            $table->string('midtrans_redirect_url')->nullable();
            $table->enum('payment_status', ['pending', 'paid', 'failed', 'expired'])->default('pending');
            $table->enum('order_status', ['pending', 'confirmed', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending');
            $table->string('biteship_order_id')->nullable();
            $table->string('tracking_number')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preloved_orders');
    }
};
