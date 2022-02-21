@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        メモ編集
        {{-- 削除用のpostを書く --}}
        <form class="card-body" action="{{route('destroy')}}" method="POST">
            {{-- @csrf：なりすまし（他人と偽って）送信を防止 LaravelでPOSTする際は必ずつける --}}
            @csrf
            <input type="hidden" name="memo_id" value="{{$edit_memo[0]['id']}}">
            <button type="submit">削除</button>
        </form>
    </div>
    
    <form class="card-body" action="{{ route('update') }}" method="POST">
        {{-- @csrf：なりすまし（他人と偽って）送信を防止 LaravelでPOSTする際は必ずつける --}}
        @csrf
        {{-- 更新用にidを見えない場所で保存する --}}
        <input type="hidden" name="memo_id" value="{{$edit_memo[0]['id']}}" >
        <div class="form-group">
            <textarea class="form-control" name="content" rows="3" placeholder="ここにメモを入力">{{$edit_memo[0]['content']}}</textarea>
        </div>

    @foreach ($tags as $t)
        <div class="form-check form-check-inline mb-3">
            <input class="form-check-input" type="checkbox" name="tags[]" id="{{$t['id']}}" value="{{$t['id']}}"
                    {{in_array($t['id'], $include_tags) ? 'checked' : ''}}>
            <label class="form-check-label" for="{{$t['id']}}" {{$t['name']}} >{{$t['name']}}</label>
            </div>
    @endforeach
    
        <input type="text" class="form-control w-50 mb-3" name="new_tag" placeholder="新しいタグを追加する" />
        <button type="submit" class="btn btn-primary">更新</button>
    </form>
</div>
@endsection
