<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class trx_lines_all extends Model
{
    protected $table = 'pum_trx_lines_all';

    protected $fillable = [
        'pum_trx_id', 'line_num', 'pum_trx_type_id', 'description', 'curr_code', 'amount', 'amount_remaining',
    ];
}
