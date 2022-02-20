<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo; // Memoモデルを呼び出せるようにする
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // ここでメモデータを取得する
        // memosテーブル全件から、ログインしているID(user_id)とユーザIDが一致しているものだけを取得する
        $memos = Memo::select('memos.*')
                  ->where('user_id', '=', \Auth::id())
                  // deleted_atがnullのもの->削除日時が入っていない=削除されていないもの
                  ->whereNull('deleted_at')
                  ->orderBy('updated_at', 'DESC')
                  ->get();
        
        // compactの中に上で取得した$memosを入れることでbladeでmemosの変数が使えるようになる！
        return view('create', compact('memos'));
    }

    // POSTのアクションを記載。Request $requestをつけると様々なメソッドが使えて便利になる
    public function store(Request $request)
    {
        $posts = $request->all(); // 全てを取得する
        // "dump die"の略 -> メソッドの引数に取った値を展開して止める -> データの確認をする
        // dd(\Auth::id()); 

        // DBへ送られた値を入れる。\Auth::id()でユーザIDを取得する
        Memo::insert(['content' => $posts['content'], 'user_id' => \Auth::id()]);

        return redirect( route('home') );
    }
}
