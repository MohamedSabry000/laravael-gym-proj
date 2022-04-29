@extends('adminlte::page')

@section('title', 'Show Package Details')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Show</h1>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Default box -->
        <div class="card card-solid">
            <div class="card-body">
                <div class="row">
                    <div class="col-12 col-sm-12 d-flex  align-items-center">
                        <div>
                            <p class="my-3">ID: {{$package->id}}</p>
                            <p class="my-3">Name: {{$package->name}}</p>
                            <p class="my-3">Sessions Number: {{$package->sessions_number}}</p>
                            <p class="my-3">User Id: {{$package->user_id}}</p>

                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
    <!-- /.content -->
</div>
    
@endsection
