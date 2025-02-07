<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Config as Conf; // здесь свои конфиги, поэтому класс конфликтует

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // remove columns about populariy, add json column for AI response
        foreach ( Conf::get('locales') as $subdomain => $locale ){
            Schema::table($locale['name'], function (Blueprint $table) {
                $table->dropColumn(['phone_showed', 'popularity', 'link_followed']);
                $table->mediumText('json');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
