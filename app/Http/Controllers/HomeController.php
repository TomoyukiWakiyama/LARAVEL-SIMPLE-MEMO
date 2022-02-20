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

    public function edit($id)
    {
        // ここでメモデータを取得する
        // indexと同じく、編集画面でも表示する必要があるため残す
        $memos = Memo::select('memos.*')
                  ->where('user_id', '=', \Auth::id())
                  // deleted_atがnullのもの->削除日時が入っていない=削除されていないもの
                  ->whereNull('deleted_at')
                  ->orderBy('updated_at', 'DESC')
                  ->get();

        // 編集するメモ1件だけをidから取得する
        $edit_memo = Memo::find($id);

        return view('edit', compact('memos', 'edit_memo'));

    }

    // 更新するアクションを記載。Request $requestをつけると様々なメソッドが使えて便利になる
    public function update(Request $request)
    {
        $posts = $request->all(); // 送り先のpostデータ全てを取得する=>nameで利用することができる

        // editで送られたidからメモを探して更新する
        Memo::where('id', $posts['memo_id']) -> update(['content' => $posts['content']]);
        

        return redirect( route('home') );
    }

    // 削除アクションを記載
    public function destroy(Request $request)
    {
        $posts = $request->all(); // 送り先のpostデータ全てを取得する=>nameで利用することができる

        // editで送られたidからメモを探して更新する
        Memo::where('id', $posts['memo_id'])->update(['deleted_at' => date("Y-m-d H:i:s", time())]);
        

        return redirect( route('home') );
    }
}
