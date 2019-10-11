<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class trx_all extends Model
{
    protected $table = 'pum_trx_all';

    protected $fillable = [
        'trx_num','trx_date', 'emp_id','dept_id','po_number', 'use_date','resp_estimate_date','pum_status', 'resp_status', 'files_data', 'upload_data',
    ];
}
