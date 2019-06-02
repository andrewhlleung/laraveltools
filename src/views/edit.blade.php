@extends('layouts.app')

   
@section('content')
<section class="content-header">
      <h1>
        Product
        <small>Edit Product.</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a href="#">Tables</a></li>
        <li class="active">Simple</li>
      </ol>
    </section>
<section class="content">
    <div class="row">
        <div class="col-lg-12 margin-tb">
            <div class="pull-left">
                <h2></h2>
            </div>
            <div class="pull-right">
                
            </div>
        </div>
    </div>
   
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops!</strong> There were some problems with your input.<br><br>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
  
	
<div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Edit</h3>
            </div>
            <!-- /.box-header -->
            <!-- form start -->
            <form class="form-horizontal" action="{{ route('products.update',$product->id) }}" method="POST">
			    @csrf
        @method('PUT')
              <div class="box-body">
                <div class="form-group">
                  <label for="name" class="col-sm-2 control-label">Name:</label>

                  <div class="col-sm-10">
                <input type="text" name="name" value="{{ $product->name }}" class="form-control" placeholder="Name">
                  </div>
                </div>
                <div class="form-group">
                  <label for="detail" class="col-sm-2 control-label">Detail</label>

                  <div class="col-sm-10">
                <textarea class="form-control" style="height:150px" name="detail" placeholder="Detail">{{ $product->detail }}</textarea>
                  </div>
                </div>

              </div>
              <!-- /.box-body -->
              <div class="box-footer">
				<a class="btn btn-default pull-right" href="{{ route('products.index') }}"> Back</a>
                <button type="submit" class="btn btn-info pull-right">Submit</button>
              </div>
              <!-- /.box-footer -->
            </form>
          </div>	
    </section>
@endsection