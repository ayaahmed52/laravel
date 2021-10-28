<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Vendor extends Model
{
    use Notifiable;
    use HasFactory;

    protected $fillable = [
      'name',
        'logo','active','email','address','password','mobile','category_id', 'created_at',
        'updated_at',
    ];
    protected $hidden =['category_id'];

    public function scopeActive($query){
        return $query -> where('active',1);
    }//active

    public function getLogoAttribute($val)
    {
        return ($val !== null) ? asset('' . $val) : "";
    }//logo

    public function scopeSelection($query){

        return $query->select('id','name','logo','active','email','address','mobile','category_id');

    } //end select

    public function category(){

        return $this ->belongsTo('App\Models\MainCategory','category_id','id');

    }//end relation

    public function getActive(){

        return   $this -> active == 1 ? 'مفعل'  : 'غير مفعل';
    }//GetActive

    public function setPasswordAttribute($password)
    {
        if (!empty($password)) {
            $this->attributes['password'] = bcrypt($password);
        }
    }//end bcrypt4

}//end model
