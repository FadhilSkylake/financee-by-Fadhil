<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRemindersTable extends Migration
{
    public function up()
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('title');
            $table->decimal('amount', 12, 2);
            $table->date('due_date');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
            $table->index(['user_id', 'due_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('reminders');
    }
}
