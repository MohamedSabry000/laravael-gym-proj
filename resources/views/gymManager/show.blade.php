@extends('adminlte::page')

@section('title', 'Show Gym Manager')
@section('content')

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Show details</h1>
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
                        <div class="col-12 col-sm-6">
                            <h6 class="d-inline-block d-sm-none">GYM Manager</h6>
                            <img class="img-fluid" src="{{ asset($singleManager->profile_image) }}">
                        </div>
                        <div class="col-6">
                            <div class="small-box bg-warning">
                                <div class="inner">
                                    <h3>{{ $singleManager->name }}<sup style="font-size: 20px"></sup></h3>
                                    <p>Gym Managers</p>
                                </div>
                                <div class="icon">
                                    <i class="fas fa-user text-dark" style="font-size: 50px !important"></i>
                                </div>

                                <div class="inner">
                                    <!-- <h3>{{ $singleManager->name }}<sup style="font-size: 20px"></sup></h3> -->
                                    <p>ID :{{ $singleManager->id }}</p>
                                    <p>Email :{{ $singleManager->email }}</p>
                                    <p>National ID :{{ $singleManager->national_id }}</p>
                                    <p>Created At :{{ $singleManager->created_at }}</p>
                                    <p>Updated At :{{ $singleManager->updated_at }}</p>
                                </div>


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
    <!-- /.content-wrapper -->
@stop
