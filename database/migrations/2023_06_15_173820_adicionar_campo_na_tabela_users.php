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
        Schema::table('users', function (Blueprint $table) {
            $table->string('image')->nullable();
            $table->string("relationship_status")->nullable();
            $table->date("birthday")->nullable();
            $table->integer("age")->nullable();
            $table->string("interests")->nullable();
            $table->string("about_me")->nullable();
            $table->string("children")->nullable();
            $table->string("ethnicity")->nullable();
            $table->string("humor")->nullable();
            $table->string("sexual_orientation")->nullable();
            $table->string("style")->nullable();
            $table->string("smoking")->nullable();
            $table->string("drinking")->nullable();
            $table->string("pets")->nullable();
            $table->string("location")->nullable();
            $table->string("hometown")->nullable();
            $table->string("website")->nullable();
            $table->string("passions")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            Schema::dropIfExists('events');
        });
    }
};
