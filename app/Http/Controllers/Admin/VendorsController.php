<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Models\MainCategory;
use App\Models\Vendor;
use App\Notifications\VendorCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class VendorsController extends Controller
{

    public function index()
    {
        $vendors = Vendor::selection()->paginate(PAGINATION_COUNT);
        return view('admin.vendors.index', compact('vendors'));
    }//end index


    public function create()
    {
        $categories = MainCategory::active()->get();
        return view('admin.vendors.create', compact('categories'));
    }//end create

    public function store(VendorRequest $request)
    {
        try {

            if (!$request->has('active'))
                $request->request->add(['active' => 0]);
            else
                $request->request->add(['active' => 1]);

            $filePath = "";
            if ($request->has('logo')) {
                $filePath = uploadImage('vendors', $request->logo);
            }
            $vendor = Vendor::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'active' => $request->active,
                'address' => $request->address,
                'logo' => $filePath,
                'password' => $request->password,
                'category_id' => $request->category_id,
//                'latitude' => $request->latitude,
//                'longitude' => $request->longitude,
            ]);

            Notification::send($vendor, new VendorCreated($vendor));

            return redirect()->route('admin.Vendors')->with(['success' => 'تم الحفظ بنجاح']);

        } catch (\Exception $ex) {

            return redirect()->route('admin.Vendors')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);

        }
    }//end store

    public function edit($id)
    {
        //get specific categories and its translations
        $vendor = Vendor::with('category')
            ->selection()
            ->find($id);

        if (!$vendor)
            return redirect()->route('admin.Vendors')->with(['error' => 'هذا القسم غير موجود ']);
        $categories = MainCategory::active()->get();
        return view('admin.vendors.edit', compact('vendor', 'categories'));
    }//end edit

    public function update()
    {

//        try{
//            $main_category = MainCategory::find($mainCat_id);
//
//            if(!$main_category)
//                return redirect()->route('admin.MainCategory')->with(['error' => 'هذا القسم غير موجود ']);
//            $category = array_values($request -> category)[0] ;
//
//            if (!$request ->has('category.0.active'))
//                $request->request->add(['active'=> 0 ]);
//            else
//                $request->request->add(['active' => 1]);
//            //save img
//            $file_path=$main_category->photo;
//
//            if($request->has('photo'))
//                $file_path = uploadImage('maincategories',$request->photo);
//            MainCategory::where('id' , $mainCat_id) -> update([
//                'name' => $category['name'],
//                'active' => $request->active,
//                'photo'=>$file_path,
//            ]) ;
//            return redirect()->route('admin.MainCategory')->with(['success' => 'تم الحفظ بنجاح']);
//        } catch (\Exception $ex) {
//
//            return redirect()->route('admin.MainCategory')->with(['error' => 'حدث خطا ما برجاء المحاوله لاحقا']);
   // }//end catch

    }//end update


//    public function delete(){}//end delete
}
