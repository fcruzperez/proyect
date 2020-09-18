@extends('layouts.admin')

@section('content')

    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="row">
            <div class="col-12">
                <div class="text-center" style="font-size: 30px; margin-bottom: 15px;"> Mediations </div>
                <table id="publishes_table" class="table table-striped table-bordered text-center">
                    <thead>
                    <tr>
                        <th>Time</th>
                        <th>Client</th>
                        <th>Designer</th>
                        <th>Design Name</th>
                        <th>Details</th>
                        <th>Mediate</th>
                        <th>Dicision</th>

                    </tr>
                    </thead>

                    <tbody>
                    @foreach($publishes as $publish)
                        <tr>
                            @php
                                $client_id = $publish['client_id'];
                                $client = \App\Models\User::find($client_id);

                                $offer_id = $publish['accepted_offer_id'];
                                $designer_id = \App\Models\Offer::find($offer_id)['designer_id'];
                                $designer = \App\Models\User::find($designer_id);

                                $mediate = \App\Models\Mediate::where('offer_id', $offer_id)->first();

                            @endphp
                            <td>{{$publish['created_at']}}</td>
                            <td>{{$client['name']}}</td>
                            <td>{{$designer['name']}}</td>
                            <td>{{$publish['design_name']}}</td>
                            <td>
                                <button type="button" class="btn btn-info text-center" data-toggle="modal" data-target = "#zzz{{$publish->id}}">Details</button>
                                <div class="modal fade" id="zzz{{$publish->id}}" role="dialog" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form method="post" action="{{route('admin.update_mediate')}}">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header text-center">
                                                    <h4 class="modal-title text-center">Details</h4>
                                                </div>

                                                <div class="modal-body text-left">
                                                    <div>
                                                        <b style="color:blue; margin-left: 50px;">Size:</b> <b> {{$publish->width}} x {{$publish->height}} {{$publish->unit}}</b>
                                                    </div>
                                                    @php
                                                        $str = '';
                                                        for ($i = 0; $i < 10; $i++){
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


                                                    @php
                                                        $str = '';
                                                        for ($i = 0; $i < 10; $i++){
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
                                                        for ($i = 0; $i < 10; $i++){
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
                                                    @php
                                                        $mediate = \App\Models\Mediate::where('offer_id', $offer_id)->first();
                                                        $mediate_id = $mediate['id'];
                                                    @endphp

                                                    <input type="hidden" name="mediate_id" value="{{$mediate_id}}" />

                                                    <div>
                                                        <b style="color:blue; margin-left: 50px;">Title:</b>
                                                        <input type="text" name="title" value="{{$mediate['title']}}">
                                                    </div>
                                                    <div>
                                                        <b style="color:blue; margin-left: 50px; vertical-align: top;">Content:</b>
                                                        <textarea style="margin-left: 50px;" cols="50" name="content">{{$mediate['content']}}</textarea>
                                                    </div>


                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div class="row col-12">
                                    @empty($publish->deliveries)
                                        <div class="alert alert-info">There is no delivered design.</div>
                                    @endempty

                                    @foreach($publish->deliveries as $key => $delivery)

                                        <div class="col-4">
                                            <div class="col-4" style="margin-right: 10px;"><label>File{!! $key + 1 !!}:</label></div>
                                            <div class="col-8">
                                                <a class="btn btn-primary"
                                                   href="{{url('admin/delivery-download/'.$delivery->id)}}">
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="col-4">
                                        <div class="col-4" style="margin-left: 10px;"><label>Errors:</label></div>
                                        <div class="col-8">
                                            @php

                                            @endphp
                                            <a class="btn btn-primary" href="{{url('admin/download_errors/'.$mediate->id)}}">
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger text-center" data-toggle="modal" data-target = "#www{{$publish->id}}">Decision</button>
                                <div class="modal fade" id="www{{$publish->id}}" role="dialog" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form method="post" action="{{route('admin.decision')}}">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header text-center"><h3><b>Dicision</b></h3></div>
                                                <div class="modal-body">
                                                    <div class="text-md-right" style="font-size: 20px;">
                                                        <label for="client">Client(%)</label>
                                                        <input type="number" name="client_percent" style="width: 20%;"/>
                                                    </div>
                                                    <div class="text-md-right" style="font-size: 20px;">
                                                        <label for="designer">Designer(%)</label>
                                                        <input type="number" name="designer_percent" style="width: 20%;"/>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary" onclick="return confirm('Really?')">Save</button>
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                </div>
                                                <input type="hidden" name="publish_id" value="{{$publish->id}}">
                                            </div>

                                        </form>
                                    </div>
                                </div>

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
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
@endsection


@section('js')

    <script src = https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js></script>
    <script src = https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js></script>

    <script>
        $(document).ready(function() {
            $('#publishes_table').DataTable();
        } );
    </script>
@endsection
