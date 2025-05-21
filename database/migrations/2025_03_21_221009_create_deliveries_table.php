<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('deliveries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('transaction_code')->nullable(); // adjust type if needed
            $table->string('beneficiary'); // اسم المستفيد
            $table->string('transaction_type'); // استلام أو تسليم
            $table->string('currency_name'); // العملة
            $table->decimal('amount', 15, 2); // المبلغ
            $table->decimal('total', 15, 2)->nullable(); // القيمة الإجمالية
          $table->string('note')->nullable(); // اسم المستفيد يمكن أن يكون فارغًا

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('deliveries');
    }
};
