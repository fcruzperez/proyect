@extends('layouts.designer')

@section('content')

    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="text-align:center;">
                        <span class="card-title">Withdraw Detail</span>
                    </div>
                    @if($withdraw->status === 'paid')
                        <span class="badge-success badge-pill">Paid</span>
                    @else
                        <span class="badge-warning badge-pill">Pending</span>
                    @endif
                    <div class="card-body pt-3">
                        <table id="detail_table" class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Fee</th>
                                <th>Payment</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($offers as $offer)
                            <tr>
                                <td>{{$offer->id}}</td>
                                <td>{{$offer->request->name}}</td>
                                <td>{{$offer->price}}</td>
                                <td>{{$offer->fee}}</td>
                                <td>{{$offer->paid}}</td>
                            </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <td></td>
                                <td></td>
                                <td>{{$withdraw->total}}</td>
                                <td>{{$withdraw->fee}}</td>
                                <td>{{$withdraw->paid}}</td>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <div class="card-footer text-center">
                        <a class="btn btn-primary" href="{{route('designer.withdraw.list')}}">Back</a>
                    </div>
                </div>
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
            $('#detail_table').DataTable();
        } );
    </script>
@endsection
