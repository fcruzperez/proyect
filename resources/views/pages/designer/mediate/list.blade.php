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
                            <td>{{$publish->design_name}}</td>
                            <td>{{$offer->hours}}</td>
                            <td>{{$offer->price}}</td>
                            <td>
                                <div>
                                    <span class="p-1 text-light @if($mstatus==='issued') bg-danger @else bg-success @endif">
                                        {{$mediate->status_label}}
                                    </span>
                                </div>
                                <div>
                                    <form action="{{route('designer.redelivery-upload')}}" method="post" enctype="multipart/form-data">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12">
                                                <input type="hidden" name="offer_id" value="{{$offer->id}}">
                                                <input type="file" name="delivery_files[]" required multiple
                                                       class="@error('delivery_files') is-invalid @enderror">
                                                @error('offer_id')
                                                <div class="invalid-feedback d-block">Offer Id is required</div>
                                                @enderror
                                                @error('delivery_files')
                                                <div class="invalid-feedback d-block">{{$message}}</div>
                                                @enderror
                                                @error('db error')
                                                db error
                                                <div class="invalid-feedback d-block">{{$message}}</div>
                                                @enderror
                                            </div>
                                            <div class="col-12 text-center">
                                                <input type="reset" class="btn btn-warning mr-2" value="Reset"/>
                                                <button type="submit" class="btn btn-success">Delivery</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>

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

        $('#mediates_table').dataTable( {
            "language": {
                "emptyTable": "There are no mediations"
            }
        } );
    </script>
@endsection
