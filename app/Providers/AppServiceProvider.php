<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Memo; // Memoモデルを呼び出せるようにする


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // 全てのメソッドが呼ばれる前に呼ばれる
        view()->composer('*', function($view){
            // ここでメモデータを取得する
            // indexと同じく、編集画面でも表示する必要があるため残す
            $memos = Memo::select('memos.*')
            ->where('user_id', '=', \Auth::id())
            // deleted_atがnullのもの->削除日時が入っていない=削除されていないもの
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'DESC')
            ->get();
            $view->with('memos', $memos);
        });
    }
}
