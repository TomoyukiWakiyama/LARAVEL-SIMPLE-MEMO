<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMemosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memos', function (Blueprint $table) {
            // メモのIDを作成。unsignedはプラスマイナスといった符号がなくなる
            $table->unsignedBigInteger('id', true);
            $table->longText('content');
            $table->unsignedBigInteger('user_id');
            // 論理削除を定義->deleted_atを自動生成してくれる
            // DB上から消えることはないが、deleted_atに削除時間が入ることにより"内部的に"削除されたということにする->倫理削除
            // DBに残っているため最悪復活させることができる
            $table->softDeletes();

            $table->timestamp('updated_at')->default(\DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            $table->timestamp('created_at')->default(\DB::raw('CURRENT_TIMESTAMP'));

            // foreign>外部キー制約。user_idに入る値はusersテーブルにidとして存在している値のみ
            // このforeignを使うためにはunsignedで取得する値を制限しておく必要がある
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memos');
    }
}
