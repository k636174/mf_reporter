@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{$page_name}}</div>
                    <div class="card-body">
                        <!-- 現在のステータス -->
                        <div class="card-body row">
                            <div class="col">
                                <label for="title" class="col-form-label text-md-right">現在のステータス：</label>
                                <div class="">
                                    <input id="name" type="text" class="form-control" name="name" value="新規追加画面未保存／$car_status[0]}}" disabled>
                                </div>
                            </div>

                            <div class="col">
                                <label for="title" class="col-form-label text-md-right">シリアル番号：</label>
                                <div class="">
                                    <input id="name" type="text" class="form-control" name="name" value="未発行 or C-1234-5678" disabled>
                                </div>
                            </div>

                        </div>

                    </div>

                    <form method="POST" enctype="multipart/form-data" action="{{ route('budget.store') }}">
                        @csrf

                        <div class="card-body row">
                            <div class="col-6">
                                <!-- 予算項目 -->
                                @foreach($category_list as $cate_1 => $cate2_arr)
                                    {{$cate_1}}<br/>
                                    @foreach($cate2_arr as $key => $cate2)
                                        <div class="form-group row">
                                            <label for="title" class="col-4 col-form-label text-md-right"> {{$cate2['name']}}</label>
                                            <div class="">
                                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{$cate2['sum']}}" disabled>
                                                @if ($errors->has('name'))
                                                    <span class="invalid-feedback">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    <hr/>
                                @endforeach
                            </div>


                            <div class="col-6">

                                <!-- 予算項目 -->
                                @foreach($category_list as $cate_1 => $cate2_arr)
                                    {{$cate_1}}<br/>
                                    @foreach($cate2_arr as $key => $cate2)
                                        <div class="form-group row">
                                            <label for="title" class="col-4 col-form-label text-md-right"> {{$cate2['name']}}</label>
                                            <div class="" >
                                                <input id="{{md5($cate_1.$cate2['name'])}}" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="val" value="{{$cate2['budget']}}" required autofocus>
                                                <input type="hidden" class="{{md5($cate_1.$cate2['name'])}}" name="category_1" value="{{$cate_1}}"/>
                                                <input type="hidden" class="{{md5($cate_1.$cate2['name'])}}" name="category_2" value="{{$cate2['name']}}"/>
                                                @if ($errors->has('name'))
                                                    <span class="invalid-feedback">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    <hr/>
                                @endforeach

                            </div>

                        </div>

                    </form>


                </div>
            </div>
        </div>
    </div>


    <script type="text/javascript">
        window.onload = (function () {

            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

            $("input").focus(function(){
                $(this).css("background","red");
            }).blur(function(){
                $(this).css("background","#fff");
                var TARGET_ID = $(this).attr('id');
                var BUDGET = $(this).val();
                var CATEGORY_1 = $('input.' + TARGET_ID + '[name="category_1"]').val();
                var CATEGORY_2 = $('input.' + TARGET_ID + '[name="category_2"]').val();

                $.ajax({
                    url: '/budget',
                    type: 'POST',
                    data: {
                        _token: CSRF_TOKEN,
                        category_1: CATEGORY_1,
                        category_2: CATEGORY_2,
                        budget: BUDGET
                    },
                    success: function (data) {
                        console.log(data);
                    }
                });
            });
        });
    </script>


@endsection
