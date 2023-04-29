@extends('test::layouts.master')

@section('content')
    <h1>TEST 本地化多【语言】</h1>

    <p>
        此视图是从【{!! config('test.name') !!}】模块加载的
    </p>

    <p>
        测试语言本地化 lang: {{ __('hello') }}
    </p>

    <p>
        测试语言本地化 lang: {{ __('test::test.hello', ['name' => 'dayle'])  }}
    </p>
@endsection
