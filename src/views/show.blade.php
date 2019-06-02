@extends('layouts.app')
@section('content')
<section class="content-header">
      <h1>
        Product
        <small>Show Product.</small>
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
                <h2> </h2>
            </div>
            <div class="pull-right">
                <a class="btn btn-primary" href="{{ route('products.index') }}"> Back</a>
            </div>
        </div>
    </div>
   
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12">	
<div class="box box-danger">
            <div class="box-header with-border">
              <i class="fa fa-text-width"></i>

              <h3 class="box-title">Show</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
              <dl>
                <dt>Name:</dt>
                <dd>{{ $product->name }}</dd>
                <dt>Details:</dt>
                <dd>{{ $product->detail }}</dd>
              </dl>
            </div>
            <!-- /.box-body -->
          </div>
        </div>

    </div>
	
	
	
    </section>
@endsection