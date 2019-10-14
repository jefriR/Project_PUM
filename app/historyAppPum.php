<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class historyAppPum extends Model
{
    protected $table = "history_app_pums";

    protected $fillable = [
        'emp_id', 'pum_trx_id', 'status'
    ];}
