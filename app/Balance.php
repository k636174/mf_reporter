<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Balance extends Model
{
    protected $table = 'balance_list';
    protected $fillable = [
        'user_id',
        'is_confirmed' ,
        'use_date',
        'body',
        'amount' ,
        'financial_company' ,
        'category_1',
        'category_2',
        'memo',
        'is_transfer',
        'mf_id'];

    public function user()
    {
        return $this->hasOne('User' ,'id', 'user_id');
    }

}
