@extends('layouts.designer')

@section('content')

    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        @isset($new_success)
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>Your withdraw request successfully saved!</strong>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endisset

        @if($errors->any())
        <div class="alert alert-warning alert-dismissible fade show" role="alert">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>

            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        @endif

        <div class="row">
            <div class="col-12 text-center">
                <h3>Withdraw List</h3>
            </div>
            <div class="col-12 text-right">
                <a class="btn btn-success" href="{{route('designer.withdraw.new')}}">New</a>
            </div>
            <div class="col-12 mt-2">
                <table id="withdraw_table" class="table table-striped table-bordered text-center">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Total</th>
                        <th>Fee</th>
                        <th>Paid</th>
                        <th>Status</th>
                        <th>Request Time</th>
                        <th>Paid Time</th>
                        <th>Action</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($withdraws as $wd)
                        <tr>
                            <td>{{$wd->id}}</td>
                            <td>{{$wd->total}}</td>
                            <td>{{$wd->fee}}</td>
                            <td>{{$wd->paid}}</td>
                            <td>
                                <span class="p-2 @if($wd->status === 'pending') bg-warning @else bg-success @endif">
                                {{$wd->status_label}}
                                </span>
                            </td>
                            <td>{{$wd->created_at}}</td>
                            <td>
                                @if($wd->status === 'paid')
                                    {{$wd->paid_at}}
                                @else
                                    ---
                                @endif
                            </td>
                            <td >
                                <a class="btn btn-info" href="{{url("designer/withdraw-detail/{$wd->id}")}}">
                                    Detail
                                </a>
                            </td>
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
@endsection


@section('js')
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#withdraw_table').DataTable();
        } );
    </script>
@endsection
