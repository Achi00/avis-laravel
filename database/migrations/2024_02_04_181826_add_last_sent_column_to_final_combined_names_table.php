<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastSentColumnToFinalCombinedNamesTable extends Migration
{


    public function up()
    {
        Schema::table('final_combined_names', function (Blueprint $table) {
            $table->timestamp('last_sent')->nullable()->after('value');
        });
    }
    
    public function down()
    {
        Schema::table('final_combined_names', function (Blueprint $table) {
            $table->dropColumn('last_sent');
        });
    }

}
