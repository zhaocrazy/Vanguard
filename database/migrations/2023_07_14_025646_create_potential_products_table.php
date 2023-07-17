<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePotentialProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('potential_products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rank', 191)->comment('产品排序');
            $table->string('category', 50)->nullable()->comment('产品描述');
            $table->string('thumbnail', 191)->comment('产品主缩略图');
            $table->text('url')->comment('产品链接');
            $table->string('original_title', 250)->comment('产品源标题');
            $table->text('original_description')->comment('产品源描述');
            $table->string('price', 20)->comment('产品价格');
            $table->string('english_title', 191)->nullable()->comment('产品英文标题');
            $table->text('english_description')->nullable()->comment('产品英文描述');
            $table->string('chinese_title', 191)->comment('产品中文标题');
            $table->text('chinese_description')->comment('产品中文描述');
            $table->string('image', 191)->comment('产品图片');
            $table->text('extra_images')->comment('产品额外图片');
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
        Schema::dropIfExists('potential_products');
    }
}
