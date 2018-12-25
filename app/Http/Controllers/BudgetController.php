<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use \Khill\Lavacharts\Lavacharts as Lava;
use App\Budget;
use App\Balance;
use DB;

class BudgetController extends Controller
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

    }

    /**
     * Show the form for creating a new resource.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = \Auth::user();

        $wf_date = $request->input('fdate');
        $wt_date = $request->input('tdate');
        if(empty($wf_date)) $wf_date = '2019-01-01';
        if(empty($wt_date)) $wt_date = '2019-12-31';

        $page_name = "【予算】予算入力画面【 $wf_date 〜 $wt_date 】";


        $budget_list_tmp = DB::table('budgets')
            ->select('category_1','category_2','amount')
            ->where('user_id','=',1)
            ->where('year','=',2019)
            ->where('month','=',0)
            ->get();
        $budget_list = array();
        foreach($budget_list_tmp as $key => $val){
            $budget_list[$val->category_1][$val->category_2] = $val->amount;
        }
        //var_dump($budget_list);exit;


        //DB::enableQueryLog();
        $category_list_tmp = DB::table('balance_list')
            ->select(DB::raw('category_1,category_2,sum(amount) as sum'))
            ->where('user_id','=',1)
            ->where('is_confirmed','=',1)
            ->where('is_transfer','=',0)
            ->where('amount','<',0)
            ->where('use_date','>=',date('Y-m-d', strtotime($wf_date.' -1 year')))
            ->where('use_date','<=',date('Y-m-d', strtotime($wt_date.' -1 year')))
            ->groupBy('category_1','category_2')
            ->orderby('sum')
            ->get();
        //dd(DB::getQueryLog());

        $category_list = array();
        foreach($category_list_tmp as $key => $val){
            $amount=0;
            if(!empty($budget_list[$val->category_1][$val->category_2]))$amount = $budget_list[$val->category_1][$val->category_2];
            $category_list[$val->category_1][$key]['name']=$val->category_2;
            $category_list[$val->category_1][$key]['budget'] = $amount;
            $category_list[$val->category_1][$key]['sum']=number_format($val->sum * -1);
        }
        //var_dump($category_list);exit;
        return view('budget.create', compact(
            'page_name',
            'category_list'
        ));

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
        $user = \Auth::user();
        $save_record = array(
            'user_id' => $user->id,
            'category_1' => $request->category_1,
            'category_2' => $request->category_2,
            'amount' => $request->budget,
            'status' => 1,
            'year' => 2019,
            'month' => 0,
            'memo' => ''
        );
        Budget::updateOrCreate([
            'user_id' => $user->id,
            'category_1' => $request->category_1,
            'category_2' => $request->category_2,
            ],$save_record);
        exit;
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
        var_dump($request);exit;
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
