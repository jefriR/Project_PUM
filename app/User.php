<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'emp_num', 'password', 'pin'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token','pin',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function registerPin($emp_num, $password, $pin){
        $cekUser    = DB::connection('api_sys')->table('sys_user')->select("*")->where('user_name', $emp_num)->get();
        $cekPswrd   = DB::connection('api_sys')->table('sys_user')->select("*")->where('user_name', $emp_num)->where('pswd', $password)->get();

        if ($cekUser == null){
            return 1;
        } elseif ($cekPswrd == null){
            return 2;
        } else {
            DB::connection('api_sys')->table('sys_user')->where('user_name', $emp_num)->insert(["PIN" => $pin]);
            return 3;
        }
    }
}
