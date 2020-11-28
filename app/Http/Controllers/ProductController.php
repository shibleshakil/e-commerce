<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Input;

use Auth;
use Session;
use Image;
use App\Category;
use App\Product;
use App\ProductAttributes;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function addProduct(Request $request){
        if($request->isMethod('post')){
            $data = $request->all();
            //dd($data);
            if(empty($data['category_id'])){
                return redirect()->back()->with('error','Category Id Missing');
            }
            $product = new Product;
            $product->category_id=$data['category_id'];
            $product->product_name=$data['product_name'];
            $product->product_code=$data['product_code'];
            $product->product_color=$data['product_color'];
            
            if(!empty($data['discription'])){
                $product->discription=$data['discription'];
            }else{
                $product->discription='';
            }
            $product->price=$data['price'];
            //upload image
            if($request->hasfile('image')){
                $image_tmp = Input::file('image');
                if($image_tmp->isValid()){
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $original_image_path = 'img/backend_images/products/original/'.$filename;
                    $large_image_path = 'img/backend_images/products/large/'.$filename;
                    $medium_image_path = 'img/backend_images/products/medium/'.$filename;
                    $small_image_path = 'img/backend_images/products/small/'.$filename;

                    //image resize
                    Image::make($image_tmp)->save($original_image_path);
                    Image::make($image_tmp)->resize(1200,1200)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);

                    //image store in database
                    $product->image = $filename;
                }
            }

            $product->save();
           // return redirect()->back()->with('success','Product Has Been Added Succesfully');
            $product->save();
            return redirect('/admin/view-products')->with('success','Product Has Been Added Succesfully');
        }
        //categories drop down start
        $categories = Category::where(['parent_id'=>0])->get();
        $categories_dropdown = "<option value='' selected disabled>Select</option>";
        foreach($categories as $cat){
            $categories_dropdown .= "<option value='".$cat->id."'>".$cat->name."</option>"; 
            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
            foreach($sub_categories as $sub_cat){
                $categories_dropdown .= "<option value = '".$sub_cat->id."'>&nbsp;--&nbsp;".$sub_cat->name."</option>";
            }
        }
        //categories drop down end
        return view('admin.products.add_product')->with(compact('categories_dropdown'));
    }

    public function editProduct(Request $request, $id=null){
        if($request->isMethod('post')){
            $data = $request->all();
            //dd($data);
            //upload image
            if($request->hasfile('image')){
                $image_tmp = Input::file('image');
                if($image_tmp->isValid()){
                    $extension = $image_tmp->getClientOriginalExtension();
                    $filename = rand(111,99999).'.'.$extension;
                    $original_image_path = 'img/backend_images/products/original/'.$filename;
                    $large_image_path = 'img/backend_images/products/large/'.$filename;
                    $medium_image_path = 'img/backend_images/products/medium/'.$filename;
                    $small_image_path = 'img/backend_images/products/small/'.$filename;

                    //image resize
                    Image::make($image_tmp)->save($original_image_path);
                    Image::make($image_tmp)->resize(1200,1200)->save($large_image_path);
                    Image::make($image_tmp)->resize(600,600)->save($medium_image_path);
                    Image::make($image_tmp)->resize(300,300)->save($small_image_path);
                }
            }
            else{
                $filename = $data['current_image'];
            }
            if(empty($data['discription'])){
                $data['discription'] = '';
            }
            Product::where(['id'=>$id])->update(['category_id'=>$data['category_id'],'product_name'=>$data['product_name'],
            'product_code'=>$data['product_code'],'product_color'=>$data['product_color'],'discription'=>$data['discription'],
            'price'=>$data['price'],'image'=>$filename]);
            
            return redirect()->back()->with('success','product update successfully');
        }
        //geting product details
        $productDetails = Product::where(['id'=>$id])->first();
        //categories drop down start
        $categories = Category::where(['parent_id'=>0])->get();
        $categories_dropdown = "<option value='' selected disabled>Select</option>";
        foreach($categories as $cat){
            if($cat->id == $productDetails->category_id){
                $selected="selected";
            }else{
                $selected="";
            }
            $categories_dropdown .= "<option value='".$cat->id."' ".$selected.">".$cat->name."</option>"; 
            $sub_categories = Category::where(['parent_id'=>$cat->id])->get();
            foreach($sub_categories as $sub_cat){
                if($sub_cat->id == $productDetails->category_id){
                    $selected="selected";
                }else{
                    $selected="";
                }
                $categories_dropdown .= "<option value = '".$sub_cat->id."' ".$selected.">
                &nbsp;--&nbsp;".$sub_cat->name."</option>";
            }
        }
        //categories drop down end
        return view('admin.products.edit_product')->with(compact('productDetails','categories_dropdown'));
    }

    public function viewProducts(){
        $products = Product::get();
        $products = json_decode(json_encode($products));
        foreach($products as $key =>$val){
            $category_name = Category::where(['id' => $val->category_id])->first();
            $products[$key]->category_name = $category_name->name;

        }
        //dd($products);
        return view('admin.products.view_products')->with(compact('products'));
    }

    public function deleteProductImage($id=null){
        Product::where(['id'=>$id])->update(['image'=>'']);
        return redirect()->back()->with('success','Image Deleted Successfully');
    }

    public function deleteProduct($id = null){
        Product::where(['id' => $id])->delete();
        return redirect()->back()->with('success', 'Product Deleted Successfully' );
    }

    public function addAttributes(Request $request, $id=null){
        $productDetails = Product::with('attributes')->where(['id' => $id])->first();
        // $productDetails = json_decode(json_encode($productDetails));
        // dd($productDetails);
        if($request->isMethod('post')){
            $data = $request->all();
            //dd($data);
            foreach($data['sku'] as $key => $val){
                if(!empty($val)){
                    $attributes = new ProductAttributes;
                    $attributes->product_id = $id;
                    $attributes->sku = $val;
                    $attributes->size = $data['size'][$key];
                    $attributes->price = $data['price'][$key];
                    $attributes->stock = $data['stock'][$key];
                    $attributes->save();
                }
            }
            return redirect('/admin/add-attributes/'.$id)->with('success', 'Product Attributes Added Successfully');
        }
        return view('admin.products.add_attributes')->with(compact('productDetails'));
    }

    public function deleteattribute(Request $request, $id=null){
        ProductAttributes::where(['id'=>$id])->delete();
        return redirect()->back()->with('success','Attribute deleted Successfully!');
    }

    //front 

    public function products($url=null){
        $categories = Category::with('categories')->where(['parent_id' =>'0'])->get();
        $categoryDetails = Category::where(['url'=>$url])->first();
       // dd($categoryDetails->id);
        if($categoryDetails->parent_id==0){
            //if url is main category url
            $sub_categories = Category::where(['parent_id'=>$categoryDetails->id])->get('id')->toarray();
            //dd($sub_categories);
            $productsAll = Product::whereIn('category_id',$sub_categories)->get();
            //dd($productsAll);
        }else{
            //if url is sub category url
            $productsAll = Product::where(['category_id'=>$categoryDetails->id])->get();
        }

        
        return view('products.listing')->with(compact('categories','categoryDetails','productsAll'));
    }

}
