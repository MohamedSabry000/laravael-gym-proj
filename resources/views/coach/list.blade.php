@extends('adminlte::page')

@section('content')
<!-- Content Wrapper. Contains page content -->
<div >
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h4>All Coaches</h4>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
                        <li class="breadcrumb-item active">Coaches</li>
                    </ol>
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>
    <!-- Main content -->
    <section class="content">

        <!-- Default box -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Coaches</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                        <i class="fas fa-minus"></i>
                    </button>
                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body p-0">
                <table class="table table-bordered data-table">
                    <thead>
                        <tr>
                            <th class="project-state"> id</th>
                            <th class="project-state">Coach Name</th>
                            <th class="project-state">Coach Email</th>
                            <th class="project-state">City</th>
                            <th class="project-state">Coach Image</th>
                            <th class="project-state" style="width: 202px">Action</th>

                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
        <!-- /.card -->

    </section>
    @section('css')
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <!-- <link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet"> -->
    <!-- <link href="https://cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css" rel="stylesheet"> -->
    <link href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css" rel="stylesheet">

    <!-- <style>
        .content-wrapper{
            width: 90% !important;
            margin: auto !important;
        }
    </style> -->
    @stop

    @section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <!-- <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script> -->
    <!-- <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script> -->
    <!-- <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js"></script> -->
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>


    <script type="text/javascript">
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
    $(function() {

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('showCoaches') }}",
            columns: [{
                    data: 'id',
                    name: 'id'
                },
                {
                    data: 'name',
                    name: 'name'
                },
                {
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'city_id',
                    name: 'city_id'
                },
                {

                    data: 'avatar',
                    name: 'profile_image',
                    overable: false,
                    searchable: false

                },

                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false
                },
            ]
        });

    });
    </script>
    @stop
</div>
<!-- /.content-wrapper -->
<script>
function banUser(id) {
    if (confirm("Do you want to ban this user?")) {
        $.ajax({
            url: '/banUser/' + id,
            type: 'get',
            data: {
                _token: $("input[name=_token]").val()
            },
            success: function(response) {
                $("#cid" + id).remove();
            }
        });
    }
}

function deleteCoach(id) {
    if (confirm("Do you want to delete this record?")) {
        $.ajax({
            url: '/coach/' + id,
            type: 'DELETE',
            data: {
                _token: $("input[name=_token]").val()
            },
            success: function(response) {
                $("#cid" + id).remove();
            }
        });
    }
}
</script>
@endsection