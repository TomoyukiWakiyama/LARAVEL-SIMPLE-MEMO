@extends('layouts.app')

@section('content')
<div class="card">
  <div class="card-header">
    メモ編集
    {{-- 削除用のpostを書く --}}
    <form class="card-body" action="{{route('destroy')}}" method="POST">
      {{-- @csrf：なりすまし（他人と偽って）送信を防止 LaravelでPOSTする際は必ずつける --}}
      @csrf
      <input type="hidden" name="memo_id" value="{{$edit_memo['id']}}">
      <button type="submit">削除</button>
    </form>
  </div>
  <form class="card-body" action="{{ route('update') }}" method="POST">
    {{-- @csrf：なりすまし（他人と偽って）送信を防止 LaravelでPOSTする際は必ずつける --}}
    @csrf
    {{-- 更新用にidを見えない場所で保存する --}}
    <input type="hidden" name="memo_id" value="{{$edit_memo['id']}}" >
    <div class="form-group">
      <textarea class="form-control" name="content" rows="3" placeholder="ここにメモを入力">{{$edit_memo['content']}}</textarea>
    </div>
    <button type="submit" class="btn btn-primary">更新</button>
  </form>
</div>
@endsection
