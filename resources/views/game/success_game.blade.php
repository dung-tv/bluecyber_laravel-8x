@extends('master')

@section('content')
<h2>Chúc mừng</h2>
<a href="{{ url('/') }}" class="btn btn-primary" style="margin-bottom: 20px;">Trang chủ</a>
<div class="row">
    <div class="col-12" style="color: #217ff3">
        {{ $reply }}
    </div>
</div>
@endsection