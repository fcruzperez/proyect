@extends('layouts.admin')

@section('content')

    <table id="publishes_table" class="table table-striped table-bordered col-md-8 text-center">

        <thead>
        <tr>
            <th>Time</th>
            <th>Client</th>
            <th>Name</th>
            <th>Time Required</th>
            <th>Time Left</th>
            <th>Details</th>
            <th>Status</th>
            <th>Payment</th>
            <th>Offers</th>
        </tr>
        </thead>

        <tbody>
        @foreach($publishes as $publish)
            <tr>
                @php
                    $client_id = $publish['client_id'];
                    $user = \App\Models\User::find($client_id);
                @endphp
                <td>{{$publish['created_at']}}</td>
                <td>{{$user['name']}}</td>
                <td>{{$publish['name']}}</td>
                <td>{{$publish['hours']}}</td>
                <td> ------ </td>
                <td>
                    <button type="button" class="btn btn-info text-center" data-toggle="modal" data-target = "#zzz{{$publish->id}}">Details</button>
                    <div class="modal fade" id="zzz{{$publish->id}}" role="dialog" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header text-center">
                                    <h4 class="modal-title text-center">Details</h4>
                                </div>

                                <div class="modal-body text-left">
                                    @php
                                        $str = '';
                                        for ($i = 0; $i < 3; $i++){
                                            if (isset($publish->formats[$i]->name)) {
                                                $str = $str . $publish->formats[$i]->name . ',';
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
                                        <b style="color:blue; margin-left: 50px;">Size:</b> <b> {{$publish->width}} x {{$publish->height}} cm</b>
                                    </div>
                                    @php
                                        $str = '';
                                        for ($i = 0; $i < 3; $i++){
                                            if (isset($publish->fabrics[$i]->name)) {
                                                $str = $str . $publish->fabrics[$i]->name . ',';
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
                                            if (isset($publish->technics[$i]->name)) {
                                                $str = $str . $publish->technics[$i]->name . ',';
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
                <td>{{$publish['status']}}</td>
                <td>{{$publish['deposit']}}</td>
                <td>{{count($publish->offers)}}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection



@section('stylesheet')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">

@endsection


@section('js')

    <script src = https://code.jquery.com/jquery-3.5.1.js></script>
    <script src = https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js></script>
    <script src = https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#publishes_table').DataTable();
        } );
    </script>
@endsection
