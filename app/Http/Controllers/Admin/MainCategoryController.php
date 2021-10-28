<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoryRequest;
use App\Models\MainCategory;
use GuzzleHttp\Psr7\UploadedFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MainCategoryController extends Controller
{
    public function index()
    {
        $default_lang = get_default_lang();
        $categories = MainCategory::where('translation_lang', $default_lang)
            ->selection()
            ->get();

        return view('admin.maincategory.index', compact('categories'));
    }//end index

    public function create(){
        return view('admin.maincategory.create');
    }//end create

    public function store(MainCategoryRequest $request){
//             return $request ;
        try {
            //return $request;

            $main_categories = collect($request->category);

            $filter = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] == get_default_lang();
            });

            $default_category = array_values($filter->all()) [0];

            if (!$request->has('active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            $filePath = "";
            if ($request->has('photo')) {

                $filePath = uploadImage('maincategories', $request->photo);
            }

            DB::beginTransaction();

            $default_category_id = MainCategory::insertGetId([
                'translation_lang' => $default_category['abbr'],
                'translation_of' => 0,
                'name' => $default_category['name'],
                'slug' => $default_category['name'],
                'photo' => $filePath,
                'active' => $request->active,
            ]);

            $categories = $main_categories->filter(function ($value, $key) {
                return $value['abbr'] != get_default_lang();
            });


            if (isset($categories) && $categories->count()) {

                $categories_arr = [];
                foreach ($categories as $category) {
                    $categories_arr[] = [
                        'translation_lang' => $category['abbr'],
                        'translation_of' => $default_category_id,
                        'name' => $category['name'],
                        'slug' => $category['name'],
                        'photo' => $filePath,
                        'active' => $request->active,
                    ];
                }

                MainCategory::insert($categories_arr);
            }

            DB::commit();

            return redirect()->route('admin.MainCategory')->with(['success' => 'تم الحفظ بنجاح']);

        } catch (\Exception $ex) {
            DB::rollback();
            return redirect()->route('admin.MainCategory')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }//end catch

    }//end store


    public function edit($mainCat_id)
    {
        //get specific categories and its translations
        $mainCategory = MainCategory::with('categories')
            ->selection()
            ->find($mainCat_id);

        if (!$mainCategory)
            return redirect()->route('admin.MainCategory')->with(['error' => 'هذا القسم غير موجود ']);

        return view('admin.maincategory.edit', compact('mainCategory'));
    }//end edit

    public function update($mainCat_id , MainCategoryRequest $request){

     try{
      $main_category = MainCategory::find($mainCat_id);

      if(!$main_category)
        return redirect()->route('admin.MainCategory')->with(['error' => 'هذا القسم غير موجود ']);
        $category = array_values($request -> category)[0] ;

        if (!$request ->has('category.0.active'))
             $request->request->add(['active'=> 0 ]);
        else
                $request->request->add(['active' => 1]);
             //save img
         $file_path=$main_category->photo;

         if($request->has('photo'))
             $file_path = uploadImage('maincategories',$request->photo);
       MainCategory::where('id' , $mainCat_id) -> update([
          'name' => $category['name'],
           'active' => $request->active,
           'photo'=>$file_path,
      ]) ;
        return redirect()->route('admin.MainCategory')->with(['success' => 'تم الحفظ بنجاح']);
       } catch (\Exception $ex) {

        return redirect()->route('admin.MainCategory')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
}//end catch

}//end update

    public function delete($id){

        try {
            $maincategory = MainCategory::find($id);
            if (!$maincategory)
                return redirect()->route('admin.MainCategory')->with(['error' => 'هذا القسم غير موجود ']);

            $vendors = $maincategory->vendors();
            if (isset($vendors) && $vendors->count() > 0) {
                return redirect()->route('admin.MainCategory')->with(['error' => 'لأ يمكن حذف هذا القسم  ']);
            }
           $image = Str::after($maincategory->photo , 'images/');
            $image = base_path('public/images/' . $image);
            unlink($image);
            $maincategory->categories()->delete();
            $maincategory->delete();
            return redirect()->route('admin.MainCategory')->with(['success' => 'تم حذف القسم بنجاح']);
        } catch (\Exception $ex) {
            return redirect()->route('admin.MainCategory')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }//end delete

    public function changestatus($id){

        try {
            $maincategory = MainCategory::find($id);
            if (!$maincategory)
                return redirect()->route('admin.MainCategory')->with(['error' => 'هذا القسم غير موجود ']);
            $status =  $maincategory -> active  == 0 ? 1 : 0;

            $maincategory -> update(['active' =>$status ]);

            return redirect()->route('admin.MainCategory')->with(['success' => ' تم تغيير الحالة بنجاح ']);
        } catch (\Exception $ex) {
            return redirect()->route('admin.MainCategory')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
        }

    }//end changestatus
}// end controller
