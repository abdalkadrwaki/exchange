<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransactionCodeAndNoteToExchangeAndDeliveryTables extends Migration
{
    public function up()
    {
        // تعديل جدول Exchange
        Schema::table('exchanges', function (Blueprint $table) {
            $table->string('transaction_code')->nullable()->after('total');
            $table->text('note')->nullable()->after('transaction_code');
        });

        // تعديل جدول Delivery
        Schema::table('deliveries', function (Blueprint $table) {
            $table->string('transaction_code')->nullable()->after('amount');
            $table->text('note')->nullable()->after('transaction_code');
        });
    }

    public function down()
    {
        // التراجع عن التعديلات في جدول Exchange
        Schema::table('exchanges', function (Blueprint $table) {
            $table->dropColumn(['transaction_code', 'note']);
        });

        // التراجع عن التعديلات في جدول Delivery
        Schema::table('deliveries', function (Blueprint $table) {
            $table->dropColumn(['transaction_code', 'note']);
        });
    }
}
