<?php

namespace App\Models;

use App\Observers\MaincategoryObserve;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MainCategory extends Model
{
    use HasFactory;

    protected $table = 'main_categoreis';

    protected static function boot()
    {
        parent::boot();
        MainCategory::observe(MaincategoryObserve::class);
    }
    protected $fillable = [
        'translation_lang','translation_of','name','slug','active','photo', 'created_at',
        'updated_at',
    ];
    public function scopeActive($query){
        return $query -> where('active',1);
   }//Active

  public function  scopeSelection($query){

    return $query -> select('id','translation_lang', 'name', 'slug', 'active','photo','translation_of');
  }//Selection
    public function getPhotoAttribute($val)
    {
        return ($val !== null) ? asset('' . $val) : "";

    }

   public function getActive(){

        return   $this -> active == 1 ? 'مفعل'  : 'غير مفعل';
   }//GetActive

    public function categories()
    {
        return $this->hasMany(self::class, 'translation_of');
    }      // get all translation categories

    public function vendors(){

        return $this ->hasMany('App\Models\Vendor','category_id','id');

    }//end relation

}//end model
