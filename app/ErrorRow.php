<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ErrorRow extends Model
{
    //
    protected $table = 'error_rows';
    protected $fillable = [
        'user_id',
        'is_acknowledged' ,
        'error_row',
        'memo'];

    public function user()
    {
        return $this->hasOne('User' ,'id', 'user_id');
    }
}
