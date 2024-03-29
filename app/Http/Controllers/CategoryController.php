<?php

namespace App\Http\Controllers;

use App\Category;

use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function addCategory(Request $request){
        if($request->isMethod('post')){
            $data=$request->all();
            //dd($data);
            if(empty($data['status'])){
                $status = 0;
            }else{
                $status = 1;
            }
            $category = new category;
            $category->name = $data['category_name'];
            $category->parent_id = $data['parent_id'];
            $category->discription = $data['discription'];
            $category->url = $data['url'];
            $category->status = $status;
            $category->save();

            return redirect('/admin/view-categories')->with('success', 'Category Added Successfully');
        }
        $levels = Category::where(['parent_id'=>0])->get();
        return view('admin.categories.add_category')->with(compact('levels'));
    }

    public function editCategory(Request $request, $id = null){
        if($request->isMethod('post')){
            $data=$request->all();
            //echo "<pre>";print_r($data);die;
            if(empty($data['status'])){
                $status = 0;
            }else{
                $status = 1;
            }
            Category::where(['id'=>$id])->update(['name' => $data['category_name'],'discription' => $data['discription'],'url' => $data['url'],'status'=>$status]);
            return redirect('/admin/view-categories')->with('success', 'Category Updated Successfully');
        }
        $categoryDetails = Category::where(['id'=>$id])->first();
        $levels = Category::where(['parent_id'=>0])->get();
        return view('admin.categories.edit_category')->with(compact('categoryDetails','levels'));
    }

    public function deleteCategory($id = null){
        if(!empty($id)){
            Category::where(['id' => $id])->delete();
            return redirect()->back()->with('success', 'Category Deleted Successfully' );
        }
    }

    public function viewCategories(){
        //$categories = new categories;
        $categories = Category::get();
        return view('admin.categories.view_categories')->with(compact('categories'));
    }

}