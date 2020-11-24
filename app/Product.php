<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function attributes(){
        return $this->hasMany('App\ProductAttributes','product_id');
    }
}
