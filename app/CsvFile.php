<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CsvFile extends Model
{
    //
    protected $table = 'csv_files';
    protected $fillable = [
        'user_id',
        'file_path' ,
        'is_working',
        'is_complete',
        'memo'];

    public function user()
    {
        return $this->hasOne('User' ,'id', 'user_id');
    }
}
