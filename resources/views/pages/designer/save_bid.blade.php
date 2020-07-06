@extends('layouts.designer')

@section('content')

    <table id="publishes_table" class="table table-striped table-bordered col-md-8">

        <thead>
        <tr>
            <th>Time</th>
            <th>Name</th>
            <th>Width</th>
            <th>Height</th>
            <th>Hours</th>
            <th>Deposit</th>
            <th>Status</th>
        </tr>
        </thead>

        <tbody>
        @foreach($publishes as $publsh)
            <tr>
                <td>{{$publsh['created_at']}}</td>
                <td>{{$publsh['name']}}</td>
                <td>{{$publsh['width']}}</td>
                <td>{{$publsh['height']}}</td>
                <td>{{$publsh['hours']}}</td>
                <td>{{$publsh['deposit']}}</td>
                <td>{{$publsh['status']}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection



@section('stylesheet')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet">
    <link  href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">

@endsection


@section('js')

    <script src = https://code.jquery.com/jquery-3.5.1.js></script>
    <script src = https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js></script>
    <script src = https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js></script>
    <script>
        $(document).ready(function() {
            $('#publishes_table').DataTable();
        } );
    </script>
@endsection
