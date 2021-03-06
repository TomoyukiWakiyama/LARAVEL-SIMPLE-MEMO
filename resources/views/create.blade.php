@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        新規メモ作成
    </div>
    <form class="card-body my-card-body" action="{{ route('store') }}" method="POST">
        {{-- @csrf：なりすまし（他人と偽って）送信を防止 LaravelでPOSTする際は必ずつける --}}
        @csrf
        <div class="form-group">
            <textarea class="form-control mb-3" name="content" rows="3" placeholder="ここにメモを入力"></textarea>
        </div>
    @foreach ($tags as $t)
        <div class="form-check form-check-inline mb-3">
            <input class="form-check-input" type="checkbox" name="tags[]" id="{{$t['id']}}" value="{{$t['id']}}">
            <label class="form-check-label" for="{{$t['id']}}">{{$t['name']}}</label>
        </div>
    @endforeach
        <input type="text" class="form-control w-50 mb-3" name="new_tag" placeholder="新しいタグを追加する" />
        <button type="submit" class="btn btn-primary mb-3">保存</button>
    </form>

</div>
@endsection
