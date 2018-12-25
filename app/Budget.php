<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    //
    protected $table = 'budgets';
    protected $fillable = [
        'user_id',
        'category_1',
        'category_2',
        'amount' ,
        'status' ,
        'year' ,
        'month',
        'memo'];

}
