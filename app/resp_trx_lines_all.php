<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class resp_trx_lines_all extends Model
{
    protected $table = 'pum_resp_trx_lines_all';

    protected $fillable = [
        'pum_resp_trx_id', 'pum_trx_line_id', 'line_num', 'pum_resp_trx_type_id','description', 'amount','store_code',
    ];}
