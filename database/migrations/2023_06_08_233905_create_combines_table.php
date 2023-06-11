<?php

use App\Models\Criterion;
use App\Models\Alternative;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('combines', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Alternative::class)->onDelete('cascade');
            $table->foreignIdFor(Criterion::class)->onDelete('cascade');
            $table->integer('value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('combines');
    }
};
