<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Balance;
use App\ErrorRow;
use App\CsvFile;
use DB;


class ImportCSV extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'csv:import {path? : インポートするCSVファイルを指定}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import from csv(MF)';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        // カラム用
        $col_arr = array(
            0 => 'is_confirmed' ,
            1 => 'use_date',
            2 => 'body',
            3 => 'amount' ,
            4 => 'financial_company' ,
            5 => 'category_1',
            6 => 'category_2',
            7 => 'memo',
            8 => 'is_transfer',
            9 => 'mf_id'
        );

        // 処理待ちCSVファイルを取得
        $csv_record = DB::table('csv_files')
            ->where('is_working','=',0)
            ->where('is_complete','=',0)
            ->orderby('created_at')
            ->first();

        // 処理対象レコードがない場合は未登録ファイルをDBへ登録
        if(empty($csv_record)){
            // ディレクトリ内のファイルをDBへ登録
            foreach(glob('/var/www/html/mf_reporter/storage/mf_csv/*') as $filepath){
                CsvFile::updateOrCreate(['file_path' => $filepath."1"],array(
                    'file_path'=>$filepath,
                    'user_id'=>1
                ));
            }
            exit;
        }

        CsvFile::where('id' ,$csv_record->id)->update(['is_working' => 1]);

        if(is_file($csv_record->file_path)){



            $row_cnt = 0;
            $fpr = fopen($csv_record->file_path, 'r');
            while ($row = fgetcsv($fpr)) {

                // ヘッダ行はスルー
                if($row_cnt != 0) {

                    // 日本語圏なので辛い。。。
                    mb_convert_variables('UTF-8', 'SJIS-win', $row);

                    // カラム名とデータの紐づけ
                    $save_record = array();
                    foreach ($row as $val_key => $val) {
                        $save_record[$col_arr[$val_key]] = $val;
                    }


                    $save_record['user_id'] = 1; // #Todo 後でいい感じに調整

                    // たまに変なCSV入ってくるからそいつを除外
                    if(count($save_record)==11) {
                        // 正常時はbalance_listテーブルへ保存
                        Balance::updateOrCreate(['mf_id' => $save_record['mf_id']],$save_record);
                    }else{
                        // エラー時はerror_rowsテーブルへ保存
                        $error_row = implode(',',$save_record);
                        unset($save_record);
                        $save_record['user_id'] = 1; // #Todo 後でいい感じに調整
                        $save_record['memo'] = 'カラム数が多い／少ないです';
                        $save_record['error_row' ] = $error_row;
                        ErrorRow::updateOrCreate(['error_row' => $error_row],$save_record);
                    }
                }

                $row_cnt++;
            }
            fclose($fpr);

            // 処理済みファイルは消去
            unlink($csv_record->file_path);

            CsvFile::where('id' ,$csv_record->id)->update(['is_complete' => 1]);

        }
    }
}
