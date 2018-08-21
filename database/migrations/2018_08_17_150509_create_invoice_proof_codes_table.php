<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoiceProofCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_proof_codes', function (Blueprint $table) {
            $table->increments('EDocTypeId');
            $table->string('EDocTypeCode')->nullable();
            $table->string('description')->nullable();
            $table->string('EDocTypeSri')->nullable();
            $table->integer('status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_proof_codes');
    }
}
