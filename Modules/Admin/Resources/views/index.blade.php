@extends('admin::layouts.admin')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="ibox">
                <div class="ibox-content">
                    <div class="row">
                        <h1>Hello World</h1>

                        <p>
                            This view is loaded from module: {!! config('admin.name') !!}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
