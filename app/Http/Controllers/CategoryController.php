<?php

namespace App\Http\Controllers;

use App\Category;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function addCategory(Request $request){
        if($request->isMethod('post')){
            $data=$request->all();
            //echo"<pre>";print_r($data); die;
            $category = new category;
            $category->name = $data['category_name'];
            $category->discription = $data['discription'];
            $category->url = $data['url'];
            $category->save();

            return redirect('/admin/view-categories')->with('success', 'Category Added Successfully');
        }
        return view('admin.categories.add_category');
    }

    public function viewCategories(){
        //$categories = new categories;
        $categories = Category::get();
        return view('admin.categories.view_categories')->with(compact('categories'));
    }

}
