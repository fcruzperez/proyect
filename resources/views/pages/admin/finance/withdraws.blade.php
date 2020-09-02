@extends('layouts.admin')

@section('content')

    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="row">
            <div class="col-12">
                <table id="withdraws_table" class="table table-striped table-bordered text-center">

                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>User_Id</th>
                            <th>User Name</th>
                            <th>Amount</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($withdraws as $withdraw)
                        <tr>
                            <td> {{$withdraw['created_at']}}</td>
                            <td> {{$withdraw['user_id']}}</td>
                            <td> {{$withdraw['user_name']}}</td>
                            <td> {{$withdraw['amount']}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('stylesheet')
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
@endsection


@section('js')

    <script src = https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js></script>
    <script src = https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js></script>

    <script>
        $(document).ready(function() {
            $('#withdraws_table').DataTable();
        } );
    </script>
@endsection
