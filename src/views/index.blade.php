@extends('layouts.app')

 
@section('content')
<section class="content-header">
      <h1>
        Product
        <small>Product List.</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Tables</a></li>
        <li class="active">Simple</li>
      </ol>
    </section>
<section class="content">
   
    @if ($message = Session::get('success'))
        <div class="alert alert-success">
            <p>{{ $message }}</p>
        </div>
    @endif
   

	
	
	
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2> </h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-success" href="{{ route('products.create') }}"> Create New Product</a>
            </div>
        </div>
    </div>
	
<div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">

			  </h3>

              <div class="box-tools">
                <div class="input-group input-group-sm" style="width: 150px;">
                  <input type="text" name="table_search" class="form-control pull-right" placeholder="Search">

                  <div class="input-group-btn">
                    <button type="submit" class="btn btn-default"><i class="fa fa-search"></i></button>
                  </div>
                </div>
              </div>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive no-padding">

              <table class="table table-hover">
                <tbody><tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Details</th>
                  <th>Action</th>
                </tr>

        @foreach ($products as $product)
                <tr>
                  <td>{{ $product->id }}</td>
                  <td>{{ $product->name }}</td>
                  <td>{{ $product->detail }}</td>
                  <td>
				  <form action="{{ route('products.destroy',$product->id) }}" method="POST">
				  <a href="{{ route('products.show',$product->id) }}" class="btn btn-info ">Show</a>
				  <a href="{{ route('products.edit',$product->id) }}" class="btn btn-primary ">Edit</a>
				  
				  @csrf
				@method('DELETE')
      
                    <button type="submit" class="btn btn-danger">Delete</button>
					</form>
					
				  </td>
                </tr>
        @endforeach
              </tbody></table>
    {!! $products->links() !!}
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
      </div>	
	
    </section>
	
	
@endsection