@extends('layouts.client')

@section('content')

    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="row">
            <div class="col-12">
                <table id="mediates_table" class="table table-striped table-bordered text-center">
                    <thead>
                    <tr>
                        <th>Time</th>
                        <th>Publish Name</th>
                        <th>Offer Hours</th>
                        <th>Offer Price</th>
                        <th>Status</th>
                        <th>Title</th>
                        <th>Detail</th>
                        {{--                <th>Edit</th>--}}
                        {{--                <th>Cancel</th>--}}
                        <th>Complete</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($mediates as $mediate)
                        <?php
                            $offer_id = $mediate['offer_id'];
                            $offer = \App\Models\Offer::find($offer_id);
                            $mstatus = $mediate->status;
                            $publish = $offer->request;
                        ?>
                        <tr>
                            <td>{{$mediate->created_at}}</td>
                            <td>
                                <a href="{{url("client/publish-detail/{$publish->id}")}}">
                                    {{$publish->design_name}}
                                </a>
                            </td>
                            <td>{{$offer->hours}}</td>
                            <td>{{$offer->price}}</td>
                            <td>
                                 <span class="p-1 text-light @if($mstatus==='issued') bg-danger @else bg-success @endif">
                                    {{$mediate->status_label}}
                                </span>
                            </td>
                            <td>{{$mediate->title}}</td>
                            <td>
                                <a class="btn btn-info" href="{{url("client/mediate-detail/{$mediate->id}")}}">Detail</a>
                            </td>
                            {{--                <td>--}}
                            {{--                    <a class="btn btn-primary" href="{{url("client/mediate-edit/{$mediate->id}")}}">Edit</a>--}}
                            {{--                </td>--}}
                            {{--                <td>--}}
                            {{--                    <a class="btn btn-warning" href="{{url("client/mediate-cancel/{$mediate->id}")}}">Cancel</a>--}}
                            {{--                </td>--}}
                            <td>
                                @if ($mediate['status'] === 'issued')
                                    <a class="btn btn-success" href="{{url("client/mediate-complete/{$mediate->id}")}}">Complete</a>
                                @endif
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

        $('#mediates_table').dataTable( {
            "language": {
                "emptyTable": "There are no mediations"
            }
        } );
    </script>
@endsection
