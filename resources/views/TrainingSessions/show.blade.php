@extends('adminlte::page')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h1>Show Session</h1>
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
                            <p class="my-3">ID: {{$trainingSession->id}}</p>
                            <p class="my-3">Name: {{$trainingSession->name}}</p>
                            <p class="my-3">Day: {{$trainingSession->day}}</p>
                            <p class="my-3">Starts At: {{$trainingSession->starts_at}}</p>
                            <p class="my-3">Finishes At: {{$trainingSession->finishes_at}}</p>
                            <p class="my-3">Training Package: {{$package->name}}</p>


                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
</div>
<!-- /.content-wrapper -->
@endsection