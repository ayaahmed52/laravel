<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
define('PAGINATION_COUNT',10);


Route::group([ 'namespace'=> 'Admin', 'middleware'=>'auth:admin'],function (){
    //dashboard
 Route::get('/', 'DashboardController@index')->name('admin.dashboard');

      //Language
Route::group(['prefix'=>'language'],function(){
 Route::get('/' , 'LanguagesController@index')->name('admin.languages');
 Route::get('/create' , 'LanguagesController@create')->name('admin.languages.create');
 Route::post('/store' , 'LanguagesController@store')->name('admin.languages.store');
 Route::get('/edit/{id}' , 'LanguagesController@edit')->name('admin.languages.edit');
 Route::post('/update/{id}' , 'LanguagesController@update')->name('admin.languages.update');
 Route::get('/delete/{id}' , 'LanguagesController@delete')->name('admin.languages.delete');

});//prefix language

    // prefix Main Category

    Route::group(['prefix'=>'MainCategory'],function() {
        Route::get('/', 'MainCategoryController@index')->name('admin.MainCategory');
        Route::get('/create', 'MainCategoryController@create')->name('admin.MainCategory.create');
        Route::post('/store', 'MainCategoryController@store')->name('admin.MainCategory.store');
        Route::get('/edit/{id}', 'MainCategoryController@edit')->name('admin.MainCategory.edit');
        Route::post('/update/{id}', 'MainCategoryController@update')->name('admin.MainCategory.update');
        Route::get('/delete/{id}', 'MainCategoryController@delete')->name('admin.MainCategory.delete');
        Route::get('/changestatus/{id}', 'MainCategoryController@changestatus')->name('admin.MainCategory.changestatus');

    });// prefix main_category

 //prefix Vendors
    Route::group(['prefix'=>'Vendors'],function() {
        Route::get('/', 'VendorsController@index')->name('admin.Vendors');
        Route::get('/create', 'VendorsController@create')->name('admin.Vendors.create');
        Route::post('/store', 'VendorsController@store')->name('admin.Vendors.store');
        Route::get('/edit/{id}', 'VendorsController@edit')->name('admin.Vendors.edit');
        Route::post('/update/{id}', 'VendorsController@update')->name('admin.Vendors.update');
        Route::get('/delete/{id}', 'VendorsController@delete')->name('admin.Vendors.delete');

    });// prefix Vendors

});//middleware auth


Route::group([ 'namespace'=> 'Admin', 'middleware'=>'guest:admin'],function (){
    //login
    Route::get('login','LoginController@getlogin')->name('admin.login');
    Route::post('login','LoginController@login')->name('admin.login');
});//middleware guest
