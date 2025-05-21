<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('exchanges', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('transaction_code')->nullable(); // adjust type if needed
            $table->string('transaction_type'); // شراء أو بيع
            $table->string('currency_name'); // العملة
            $table->string('currency_name3'); // العملة
            $table->decimal('amount', 15, 2); // المبلغ بالدولار
            $table->decimal('rate', 15, 2)->default(10000); // سعر الصرف
            $table->decimal('total', 15, 2); // القيمة الإجمالية
         $table->string('note')->nullable(); // اسم المستفيد يمكن أن يكون فارغًا

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('exchanges');
    }
};
