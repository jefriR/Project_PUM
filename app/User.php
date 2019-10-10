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
        $cekUser    = DB::connection('api_sys')->table('sys_user')->select("*")->where('user_name', $emp_num)->get()->toArray();
        $cekPswrd   = DB::connection('api_sys')->table('sys_user')->select("*")->where('user_name', $emp_num)->where('pswd', $password)->get()->toArray();

        if ($cekUser == null){
            return 1;
        } elseif ($cekPswrd == null){
            return 2;
        } elseif ($cekUser[0]->PIN != null) {
            return 3;
        } else {
            DB::connection('api_sys')->table('sys_user')->where('user_name', $emp_num)->UPDATE(["PIN" => bcrypt($pin)]);
            return 4;
        }

    }

    public function login($email, $password){
        $getEmpNum  = DB::connection('api_hr')->table('hr_employees')->select('EMP_NUM')->where('email', $email)->get()->toArray();

        if ($getEmpNum == null){
            return 1;
        } else {
            $cekAuth    = DB::connection('api_sys')->table('sys_user')->select('*')->where('USER_NAME', $getEmpNum)->where('password', $password);
            dd($cekAuth);
            return 2;
        }

    }

    /**
     * @return array
     */
    public function getCasts(): array
    {
        return $this->casts;
    }
}