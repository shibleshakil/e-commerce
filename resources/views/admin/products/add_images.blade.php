@extends('layouts.adminLayout.admin_design')
@section('content')

<div id="content">
  <div id="content-header">
    <div id="breadcrumb"> <a href="index.html" title="Go to Home" class="tip-bottom"><i class="icon-home"></i> Home</a> 
    <a href="#">Products</a> <a href="#" class="current">Add Product Alternative Images</a> </div>
    <h1>Product Alternative Images</h1>
    @if ($message = Session::get('error'))
    <div class="alert alert-error alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>	
            <strong>{{ $message }}</strong>
    </div>
    @endif   
    @if ($message = Session::get('success'))
    <div class="alert alert-success alert-block">
        <button type="button" class="close" data-dismiss="alert">×</button>	
            <strong>{{ $message }}</strong>
    </div>
    @endif
  </div>
  <div class="container-fluid"><hr>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"> <i class="icon-info-sign"></i> </span>
            <h5>Add Product Attributes</h5>
          </div>
          <div class="widget-content nopadding">
          <form enctype="multipart/form-data" class="form-horizontal" method="post" action="{{ 
              url('/admin/add-images/'.$productDetails->id) }}" name="add_images" 
              id="add_images">@csrf
              
              <input type="hidden" name="product_id" value="{{ $productDetails->id }}"/>
              <div class="control-group">
                <label class="control-label">Product Name</label>
                <label class="control-label"><strong>{{ $productDetails->product_name }}</strong></label>
              </div>
              <div class="control-group">
                <label class="control-label">Product Code</label>
                <label class="control-label"><strong>{{ $productDetails->product_code }}</strong></label>
              </div>
              <div class="control-group">
                <label class="control-label">Alternative Image(s)</label>
                <div class="controls">
                    <input type="file" name="image[]" id="image" multiple="multiple">
                </div>
              </div>
              <div class="form-actions">
                <input type="submit" value="Add Images" class="btn btn-success">
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <div class="row-fluid">
      <div class="span12">
        <div class="widget-box">
          <div class="widget-title"> <span class="icon"><i class="icon-th"></i></span>
            <h5>View Products</h5>
          </div>
          <div class="widget-content nopadding">
            <table class="table table-bordered data-table">
              <thead>
                <tr>
                  <th>Image ID</th>
                  <th>Product ID</th>
                  <th>Image</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                  @foreach ($productImages as $productImage)
                <tr class="gradeX">
                  <td>{{$productImage->id}}</td>
                  <td>{{$productImage->product_id}}</td>
                  <td><img src="{{ asset('/img/backend_images/products/small/'.$productImage->image)}}" style="width:80px";></td>
                  <td class="center"> 
                    <a rel="{{ $productImage->id }}" rel1="delete-alt-image"
                     href="javascript:" class="btn btn-danger btn-mini deteteRecord">Delete</a>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@endsection