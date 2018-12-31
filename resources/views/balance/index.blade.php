@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">{{$page_name}}</div>
                    <div class="card-body">

                        <!-- ボタン等設置エリア -->
                        <div  class="card mb-2">
                            <div class="col-12">
                                <canvas id="myChart"></canvas>
                            </div>

                        </div>

                        <!-- メッセージエリア -->
                        <div  class="card mb-2">
                            <div class="card-body row">

                                <div class="col-6">

                                    <canvas id="myChart"></canvas>

                                    <!-- TODO: 検索画面を実装-->
                                    支出一覧
                                    <table border="1">

                                        <thead>
                                        <tr>
                                            <td>レコード件数</td>
                                            <td>大カテゴリ</td>
                                            <!--<td>中カテゴリ</td>-->
                                            <td>金額</td>
                                        </tr>
                                        </thead>
                                        <!-- データ表示エリア -->
                                        <tbody>
                                        @foreach($expense_list as $item)
                                            <tr>
                                                <td>{{$item->cnt}}</td>
                                                <td><a href="/balance/?category_1={{$item->category_1}}">{{$item->category_1}}</a></td>
                                                <!--<td>$item->category_2}}</td>-->
                                                <td>{{$item->sum * -1}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="col-6">
                                    <!-- TODO: 検索画面を実装-->
                                    予算一覧
                                    <table border="1">

                                        <thead>
                                        <tr>
                                            <td>大カテゴリ</td>
                                            <td>中カテゴリ</td>
                                            <td>予算金額</td>
                                            <td>利用金額</td>
                                            <td>残り予算</td>
                                        </tr>
                                        </thead>
                                        <!-- データ表示エリア -->
                                        <tbody>
                                        @foreach($budget_list as $item)
                                            <tr>
                                                <td>{{$item->category_1}}</td>
                                                <td>{{$item->category_2}}</td>
                                                <td>{{$item->budgets_sum}}</td>
                                                <td>{{$item->balance_sum}}</td>
                                                <td>{{$item->remnant}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>



                            </div>
                        </div>

                        <!-- ボタン等設置エリア -->
                        <div  class="card mb-2">
                            <div class="col-4">
                                収入一覧4
                                <table border="1">

                                    <thead>
                                    <tr>
                                        <td>レコード件数</td>
                                        <td>大カテゴリ</td>
                                        <td>中カテゴリ</td>
                                        <td>金額</td>
                                    </tr>
                                    </thead>
                                    <!-- データ表示エリア -->
                                    <tbody>
                                    @foreach($income_list as $item)
                                        <tr>
                                            <td>{{$item->cnt}}</td>
                                            <td>{{$item->category_1}}</td>
                                            <td>{{$item->category_2}}</td>
                                            <td>{{$item->sum}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="card-body col-4">
                                <a href="#hogehoge" class="btn btn-primary">ボタンエリア</a>
                            </div>
                        </div>

                        <hr/>

                        <!-- ページネーション設置エリア -->
                        <div  class="flex-center">
                            {{ $data->links() }}
                        </div>

                        <table border="1">

                            <thead>
                            <tr>
                                <td>ID</td>
                                <td>ユーザーID</td>
                                <td>計算対象</td>
                                <td>使用日</td>
                                <td>内容</td>
                                <td>金額（円）</td>
                                <td>保有金融機関</td>
                                <td>大項目</td>
                                <td>中項目</td>
                                <td>メモ</td>
                                <td>振替フラグ</td>
                                <td>MF_ID</td>
                                <td>作成日</td>
                                <td>変更日</td>
                            </tr>
                            </thead>
                            <!-- データ表示エリア -->
                            <tbody>
                                @foreach($data as $item)
                                    <tr>
                                        <td>{{$item->id}}</td>
                                        <td>{{$item->user_id}}</td>
                                        <td>{{$item->is_confirmed}}</td>
                                        <td>{{$item->use_date}}</td>
                                        <td>{{$item->body}}</td>
                                        <td>{{$item->amount}}</td>
                                        <td>{{$item->financial_company}}</td>
                                        <td>{{$item->category_1}}</td>
                                        <td>{{$item->category_2}}</td>
                                        <td>{{$item->memo}}</td>
                                        <td>{{$item->is_transfer}}</td>
                                        <td>{{$item->mf_id}}</td>
                                        <td>{{$item->created_at}}</td>
                                        <td>{{$item->updated_at}}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    <!-- ページネーション設置エリア -->
                        <hr/>
                        <div  class="flex-center">
                            {{ $data->links() }}
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- // 追加で使うJavaScript-->
    <script>
        function deleteNotification(e) {
            'use strict';
            if (confirm('削除しますがよろしいでしょうか？')) {
                document.getElementById('form_' + e.dataset.id).submit();
            }
        }

        window.onload = function(){
            // グラフ描画
            var ctx = document.getElementById("myChart").getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: [
                        @foreach($expense_list as $item)
                        "{{$item->category_1}}",
                        @endforeach
                    ],
                    datasets: [{
                        backgroundColor: [
                            @foreach($expense_list as $item)
                            "{{sprintf("#%06x",rand(0x000000, 0xFFFFFF))}}",
                            @endforeach
                        ],
                        data: [
                            @foreach($expense_list as $item)
                            {{$item->sum * -1}},
                            @endforeach
                        ]
                    }]
                }
            });
        };
    </script>
@endsection
