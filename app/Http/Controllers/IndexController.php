<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Product;

class IndexController extends Controller
{
    public function index(){
        $productsAll = Product::get();//for accending order
        $productsAll = Product::orderBy('id','DESC')->get();//for decending order
        $productsAll = Product::inRandomOrder()->get(); //for randomly
        return view('index')->with(compact('productsAll'));
    }
}
