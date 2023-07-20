<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePdflogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pdflog', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('product_id')->nullable()->comment('产品id');
            $table->unsignedInteger('quantity')->nullable()->comment('产品数量');
            $table->string('stock_location', 191)->nullable()->comment('库存位置');
            $table->string('ean_number', 191)->nullable()->comment('国际商品编码');
            $table->string('name', 191)->comment('pdf文件名');
            $table->tinyInteger('type')->comment('操作pdf 1 upload, 2 edit');
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
        Schema::dropIfExists('pdflog');
    }
}
