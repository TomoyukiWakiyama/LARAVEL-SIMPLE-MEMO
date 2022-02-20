<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemoTagsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memo_tags', function (Blueprint $table) {
            // 中間テーブルの作成。一つのメモに複数のタグを紐付けるために必要なテーブル。
            // このテーブル自体に固有のカラムは存在しない
            $table->unsignedBigInteger('memo_id');
            $table->unsignedBigInteger('tag_id');

            $table->foreign('memo_id')->references('id')->on('memos');
            $table->foreign('tag_id') ->references('id')->on('tags');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memo_tags');
    }
}
