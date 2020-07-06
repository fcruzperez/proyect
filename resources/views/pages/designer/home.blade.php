@extends('layouts.designer')

@section('content')

    <div>
        <div>
            <span class="fa fa-star checked_star"></span>
            <span class="fa fa-star checked_star"></span>
            <span class="fa fa-star checked_star"></span>
            <span class="fa fa-star"></span>
            <span class="fa fa-star"></span>
            <div style="margin-left: 35px; font-weight: bold; color:red;">3</div>

        </div>

        <h3 class="text-center"><b>My Offers</b></h3>
    </div>

    <table id="offers_table" class="table table-striped table-bordered col-md-8">

        <thead>
        <tr>
            <th>Time</th>
            <th>Name</th>
            <th>Details</th>
            <th>Offer Status</th>
            <th>Time Left</th>
            <th>Price</th>
            <th>Attached</th>
            <th>Cancel</th>
        </tr>
        </thead>

        <tbody>
        @foreach($offers as $offer)
            @php
                $request_id = $offer['request_id'];
                $request = \App\Models\Request::find($request_id);
                //dd($request);
            @endphp
            <tr>
                <td>{{$request['created_at']}}</td>
                <td>{{$request['name']}}</td>
                <td>
                    <button type="button" class="btn btn-info text-center" id="details" data-toggle="modal" data-target = "#www{{$request->id}}">Details</button>
                    <div class="modal fade" id="www{{$request->id}}" role="dialog" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header text-center">
                                    <h4 class="modal-title text-center">Details</h4>
                                </div>

                                <div class="modal-body text-left">
                                    @php
                                        $str = '';
                                        for ($i = 0; $i < 3; $i++){
                                            if (isset($request->formats[$i]->name)) {
                                                $str = $str . $request->formats[$i]->name . ',';
                                            }
                                        }
                                        $n = strlen($str);
                                        $str = substr($str, 0, $n - 1);
                                        //dd($str);
                                    @endphp
                                    <div>
                                        <b class="text-center" style="color:blue; margin-left: 50px;">Format(s):</b> <b>{{ empty($str) ? 'Undefined' : $str }}</b>
                                    </div>

                                    <div>
                                        <b style="color:blue; margin-left: 50px;">Size:</b> <b> {{$request->width}} x {{$request->height}} cm</b>
                                    </div>
                                    @php
                                        $str = '';
                                        for ($i = 0; $i < 3; $i++){
                                            if (isset($request->fabrics[$i]->name)) {
                                                $str = $str . $request->fabrics[$i]->name . ',';
                                            }
                                        }
                                        $n = strlen($str);
                                        $str = substr($str, 0, $n - 1);
                                    @endphp
                                    <div>
                                        <b style="color:blue; margin-left: 50px;">Fabric(s):</b> <b> {{ empty($str) ? 'Undefined' : $str }}</b>
                                    </div>
                                    @php
                                        $str = '';
                                        for ($i = 0; $i < 3; $i++){
                                            if (isset($request->technics[$i]->name)) {
                                                $str = $str . $request->technics[$i]->name . ',';
                                            }
                                        }
                                        $n = strlen($str);
                                        $str = substr($str, 0, $n - 1);
                                    @endphp
                                    <div>
                                        <b style="color:blue; margin-left: 50px;">Technic(s):</b> <b> {{ empty($str) ? 'Undefined' : $str }}</b>
                                    </div>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </td>
                <td></td>
                <td></td>
                <td>{{$offer['price']}}</td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection



@section('stylesheet')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet">
    <link  href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">

@endsection


@section('js')

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#offers_table').DataTable();
        } );
    </script>
@endsection
