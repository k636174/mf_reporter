<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Balance;
use DB;

class BalanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $wf_date = $request->input('fdate');
        $wt_date = $request->input('tdate');
        $wc1_val = $request->input('category_1');
        if(empty($wf_date)) $wf_date = '2018-01-01';
        if(empty($wt_date)) $wt_date = '2018-12-31';
        if(empty($wc1_val)) $wc1_val = '%';

        $page_name = "【支出・収入】一覧【 $wf_date 〜 $wt_date 】";

        // ログインユーザー情報取得
        $user = \Auth::user();

        // 一般ユーザーは検索範囲を絞る
        // $balance_list = Balance::with('user_id' );
        // $balance_list->where('user_id',$user->id);

        // 支出トータル
        $expense_list = DB::table('balance_list')
            //->select(DB::raw('category_1 ,category_2 ,COUNT(*) AS cnt , sum(amount) AS sum'))
            ->select(DB::raw('category_1  ,COUNT(*) AS cnt , sum(amount) AS sum'))
            //->groupBy('category_1','category_2')
            ->groupBy('category_1')
            ->where('is_confirmed','=',1)
            ->where('is_transfer','=',0)
            ->where('amount','<',0)
            ->where('user_id','=',1)
            ->where('use_date','>=',$wf_date)
            ->where('use_date','<=',$wt_date)
            ->orderby('sum')
            ->get();

        // 収入トータル
        $income_list = DB::table('balance_list')
            ->select(DB::raw('category_1 ,category_2 ,COUNT(*) AS cnt , sum(amount) AS sum'))
            ->groupBy('category_1','category_2')
            ->where('is_confirmed','=',1)
            ->where('is_transfer','=',0)
            ->where('amount','>',0)
            ->where('user_id','=',1)
            ->where('use_date','>=',$wf_date)
            ->where('use_date','<=',$wt_date)
            ->orderby('sum')
            ->get();

        // 予算取得
        $budget_list = DB::table('budgets')
            ->select(DB::raw('balance_list.category_1 as category_1 ,balance_list.category_2 as category_2 ,sum(balance_list.amount) as balance_sum ,max(budgets.amount) as budgets_sum,max(budgets.amount) + sum(balance_list.amount) as remnant'))
            ->leftjoin('balance_list' ,function($join){
                $join->on('budgets.category_1','=', 'balance_list.category_1');
                $join->on('budgets.category_2','=', 'balance_list.category_2');
            })
            ->groupBy('category_1','category_2')
            ->where('budgets.user_id','=',1)
            ->where('is_confirmed','=',1)
            ->where('is_transfer','=',0)
            ->where('use_date','>=',$wf_date)
            ->where('use_date','<=',$wt_date)
            ->get();

        $balance_list = new Balance();
        $data = $balance_list
            ->where('category_1','LIKE',$wc1_val)
            ->where('use_date','>=',$wf_date)
            ->where('use_date','<=',$wt_date)
            ->paginate(100);
        return view('balance.index', compact(
            'page_name',
            'data',
            'expense_list',
            'income_list',
            'budget_list'
        ));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $user = \Auth::user();


        echo 'hogehoge';exit;

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
