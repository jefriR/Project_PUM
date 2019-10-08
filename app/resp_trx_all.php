<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class resp_trx_all extends Model
{
    protected $table = 'pum_resp_trx_all';

    protected $fillable = [
        'pum_resp_trx_id','pum_resp_trx_num', 'pum_trx_id', 'resp_date', 'resp_status','approval_emp_id1', 'approval_date1','approval_emp_id2', 'approval_date2', 'approval_emp_id3', 'approval_date3',
    ];

}
