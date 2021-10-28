<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\LanguageRequest;
use App\Models\Language;
use Illuminate\Http\Request;

class LanguagesController extends Controller
{
  public function index(){
      $languages = Language::select()->paginate(PAGINATION_COUNT);
      return view('admin.language.index', compact('languages'));
  }//end index

      public function create(){

       return view('admin.language.create');
  }//end create

  public function store(LanguageRequest $request){

      try {

          Language::create($request->except(['_token']));
          return redirect()->route('admin.languages')->with(['success' => 'تم حفظ اللغة بنجاح']);
      } catch (\Exception $ex) {
          return redirect()->route('admin.languages')->with(['error' => 'هناك خطا ما يرجي المحاوله فيما بعد']);
      }


  }//end store
    public function edit($id){

        $language =Language::select()->find($id);
        if(!$language){
            return redirect()->route('admin.languages')->with(['error'=>'هذه اللغه غير موجوده']);
        }
        return  view('admin.language.edit', compact('language')) ;
    }//end edit

    public function update($id,LanguageRequest $request){
        try{
            $language =Language::find($id);
            if(!$language){
                return redirect()->route('admin.languages.edit',$id)->with(['error'=>'هذه اللغه غير موجوده']);
            }
            $language ->update($request->except(['_token']));
            return redirect()->route('admin.languages')->with(['success' => 'تم تحديث اللغة بنجاح']);
        }catch (\Exception $ex) {
            return redirect()->route('admin.languages.edit',$id)->with(['error' => 'هناك خطا ما يرجي المحاوله مره اخرى ']);
        }

    }//end update

    public function delete($id){
        try{
            $language =Language::find($id);
            if(!$language){
                return redirect()->route('admin.languages')->with(['error'=>'هذه اللغه غير موجوده']);
            }
            $language->delete();
            return redirect()->route('admin.languages')->with(['success' => 'تم حذف اللغة بنجاح']);
        }catch (\Exception $ex) {
            return redirect()->route('admin.languages')->with(['error' => 'هناك خطا ما يرجي المحاوله مره اخرى ']);
        }




    }// end delete


}//end controller
