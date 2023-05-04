@extends('docs::layouts.docs')

@section('content')
    <div class="wrapper wrapper-content">
        <div class="middle-box text-center animated fadeInRightBig">
            <i class="fa fa-check fa-4x text-info"></i>
            <h2 class="font-bold">{{ $info??'操作成功！' }}</h2>
            <div class="error-desc">
                {!! empty($desc)?'':$desc !!}

                @if (!empty($btn_text) && !empty($url))
                    <br/>
                    <a href="{{ $url }}" class="btn btn-primary m-t"><i class="fa fa-hand-o-right" aria-hidden="true"></i>&nbsp;&nbsp;{{$btn_text}}</a>
                @endif
            </div>
        </div>
    </div>
@endsection
