@extends('layouts.designer')

@section('content')

    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="row">
            <div class="col-12">
                <table id="mediates_table" class="table table-striped table-bordered text-center">
                    <thead>
                    <tr>
                        <th>Time</th>
                        <th>Publish Name</th>
                        <th>Hours</th>
                        <th>Client</th>
                        <th>Offer Hours</th>
                        <th>Offer Price</th>
                        <th>Status</th>
                        <th>Title</th>
                        <th>Detail</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($mediates as $mediate)
                        <?php
                        $offer = $mediate->offer;
                        $publish = $offer->request;
                        $mstatus = $mediate->status;
                        ?>
                        <tr>
                            <td>{{$mediate->created_at}}</td>
                            <td>{{$publish->name}}</td>
                            <td>{{$publish->hours}}</td>
                            <td>{{$publish->client->name}}</td>
                            <td>{{$offer->hours}}</td>
                            <td>{{$offer->price}}</td>
                            <td >
                                <span class="p-1 text-light @if($mstatus==='issued') bg-danger @else bg-success @endif">
                                    {{$mediate->status_label}}
                                </span>
                            </td>
                            <td>{{$mediate->title}}</td>
                            <td>
                                <a class="btn btn-info" href="{{url("designer/mediate-detail/{$mediate->id}")}}">Detail</a>
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
            $('#mediates_table').DataTable();
        } );
    </script>
@endsection
