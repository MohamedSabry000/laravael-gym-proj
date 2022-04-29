@extends('adminlte::page')

@section('title', 'Traning Package')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper pb-4">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1>New Package</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Create New Package</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">
        <form method="post" action="{{ route('traningPackage.store') }}" class="w-75 m-auto">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Create</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="form-group">
                                <label class="form-label" for="name">Name</label>
                                <input autofocus required minlength="4" maxlength="100" type="text" id="name"
                                    name="name" class=" form-control @error('name') is-invalid @enderror">
                                @error('name')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="price">Price</label>
                                <input autofocus required minlength="1" maxlength="100" type="number" id="price"
                                    name="price" class=" form-control @error('price') is-invalid @enderror">
                                @error('price')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="price">Sessions Number</label>
                                <input autofocus required minlength="1" maxlength="100" type="number" id="sessions_number"
                                    name="sessions_number" class=" form-control @error('sessions_number') is-invalid @enderror">
                                @error('sessions_number')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="gym_id">Gym Name</label>
                                <label style="color: #aeb1b6; ">(optional)</label>
                                <select class=" form-control @error('gym_id') is-invalid @enderror"
                                    name="gym_id" id="gym_id">
                                    <optgroup label="Available Gyms">
                                        <option hidden>optional</option>
                                        @foreach ($gyms as $gym)
                                        <option value={{ $gym->id }}>{{ $gym->name }}</option>
                                        @endforeach
                                    </optgroup>
                                </select>
                                @error('gym_id')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="col-12">
                                <a href="{{ url()->previous() }}" class="btn btn-secondary">Cancel</a>
                                <input type="submit" value="Add New Pacage" class="btn btn-success float-right">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
</div>
</section>
</div>
@endsection