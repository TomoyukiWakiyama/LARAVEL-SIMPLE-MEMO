<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo; // Memoモデルを呼び出せるようにする
use App\Models\Tag;
use App\Models\MemoTag;

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
        // タグを全件取得する。また、論理削除されていないもののみを取得する
        $tags = Tag::where('user_id', '=', \Auth::id())
                ->whereNull('deleted_at')
                ->orderBy('id', 'DESC')
                ->get();

        
        // compactの中に上で取得した$memosを入れることでbladeでmemosの変数が使えるようになる！
        return view('create', compact('tags'));
    }

    // POSTのアクションを記載。Request $requestをつけると様々なメソッドが使えて便利になる
    public function store(Request $request)
    {
        $posts = $request->all(); // 全てを取得する
        
        // トランザクション_開始
        DB::transaction(function() use($posts) {
          // DBへ送られた値を入れる。\Auth::id()でユーザIDを取得する
          // insetGetIdはデータを入れるだけではなく、入れた先のIDを取得する
          $memo_id = Memo::insertGetId(['content' => $posts['content'], 'user_id' => \Auth::id()]);
          // 新規タグに"同じ"タグが存在していないかチェックする
          $tag_exists = Tag::where('user_id', '=', \Auth::id())->where('name', '=', $posts['new_tag'])-> exists();

          // タグが入っているか確認する処理
          if( !empty($posts['new_tag']) && !$tag_exists ){
            $tag_id = Tag::insertGetId(['user_id' => \Auth::id(), 'name' => $posts['new_tag']]);
            MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag_id]);
          }

          // タグがひとつもついていない場合はスキップさせる
          if(!empty($posts['tags'][0])){
              // 既存タグが紐づけられた場合->memo_tagsにインサートされる
            foreach($posts['tags'] as $tag){
              MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag]);
            }
          }
        });
        // トランザクション_終了

        return redirect( route('home') );
    }

    public function edit($id)
    {
        

        // 編集するメモ取得する
        $edit_memo = Memo::select('memos.*', 'tags.id AS tag_id')
                      ->leftJoin('memo_tags', 'memo_tags.memo_id', '=', 'memos.id')
                      ->leftJoin('tags', 'memo_tags.tag_id', '=', 'tags.id')
                      ->where('memos.user_id', '=', \Auth::id())
                      ->where('memos.id', '=', $id)
                      ->whereNull('memos.deleted_at')
                      ->get();

        $include_tags = [];
        foreach($edit_memo as $memo){
          array_push($include_tags, $memo['tag_id']);
        }

        // タグを全件取得する。また、論理削除されていないもののみを取得する
        $tags = Tag::where('user_id', '=', \Auth::id())
                ->whereNull('deleted_at')
                ->orderBy('id', 'DESC')
                ->get();
        
        return view('edit', compact('edit_memo', 'include_tags', 'tags'));

    }

    // 更新するアクションを記載。Request $requestをつけると様々なメソッドが使えて便利になる
    public function update(Request $request)
    {
        $posts = $request->all(); // 送り先のpostデータ全てを取得する=>nameで利用することができる

        DB::transaction(function() use($posts){
            // editで送られたidからメモを探して更新する
            Memo::where('id', $posts['memo_id']) -> update(['content' => $posts['content']]);
            // 一度紐付けられているタグを全て削除する
            Memotag::where('memo_id', '=', $posts['memo_id'])
                    ->delete();
            foreach($posts['tags'] as $tag){
                Memotag::insert(['memo_id' => $posts['memo_id'], 'tag_id' => $tag]);
            }

            // 新規タグに"同じ"タグが存在していないかチェックする
            $tag_exists = Tag::where('user_id', '=', \Auth::id())->where('name', '=', $posts['new_tag'])
                        -> exists();

            // タグが入っているか確認する処理
            if( !empty($posts['new_tag']) && !$tag_exists ){
                $tag_id = Tag::insertGetId(['user_id' => \Auth::id(), 'name' => $posts['new_tag']]);
                MemoTag::insert(['memo_id' => $posts['memo_id'], 'tag_id' => $tag_id]);
          }
        });
        
        

        return redirect( route('home') );
        // return back();

    }

    // 削除アクションを記載
    public function destroy(Request $request)
    {
        $posts = $request->all(); // 送り先のpostデータ全てを取得する=>nameで利用することができる

        // editで送られたidからメモを探して更新する
        Memo::where('id', $posts['memo_id'])->update(['deleted_at' => date("Y-m-d H:i:s", time())]);
        

        return redirect( route('home') );
    }

    // 演習用ページ作成。ただレンダリングする
    public function fromphp()
    {
        return view('fromphp');
    }
}
