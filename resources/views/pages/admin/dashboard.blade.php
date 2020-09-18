@extends('layouts.admin')

@section('content')

    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="row">
            <div class="col-12">
                <table id="publishes_table" class="table table-striped table-bordered text-center">

                    <thead>
                    <tr>
                        <th>Time</th>
                        <th>Client</th>
                        <th>Design Name</th>
                        <th>Time Left</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Offers</th>
                        <th>Delivery</th>

                    </tr>
                    </thead>

                    <tbody>
                    @foreach($publishes as $pub_index => $publish)
                        <tr>
                            @php
                                $client_id = $publish['client_id'];
                                $user = \App\Models\User::find($client_id);
                            @endphp
                            <td>{{$publish['created_at']}}</td>
                            <td>{{$user['name']}}</td>
                            <td>{{$publish['design_name']}}</td>
                            <td>
                                @php
                                    if ($publish->status === 'accepted') {
                                        $nowTime = strtotime(date("Y-m-d h:i:sa"));
                                        $acceptedTime = strtotime((string)$publish['accepted_at']);
                                        $interval = abs($nowTime - $acceptedTime);
                                        $minutes = round($interval / 60);

                                        $hours = floor($minutes / 60);
                                        $minutes = $minutes - 60 * $hours;
                                        $accepted_offer_id = $publish['accepted_offer_id'];
                                        $deadline = \App\Models\Offer::find($accepted_offer_id)['hours'];
                                        $hours = $deadline - $hours - 1;
                                        $minutes = 60 - $minutes;
                                    }

                                @endphp
                                @if ($publish->status === 'accepted' && $hours > 0)
                                    {{$hours}}:{{$minutes}} hours
                                @elseif ($publish->status === 'accepted' && $hours === 0)
                                    {{$minutes}} minutes
                                @else
                                    -------
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-info text-center" data-toggle="modal" data-target = "#zzz{{$publish->id}}">Details</button>
                                <div class="modal fade" id="zzz{{$publish->id}}" role="dialog" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form method="post" action="{{route('admin.update_publish')}}">
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
                                                     $len1 = count($publish->formats);
                                                     $len2 = count($publish->fabrics);
                                                     $len3 = count($publish->technics);

                                                     for ($i = 0; $i < $len1; $i++){
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
                                                    for ($i = 0; $i < $len2; $i++){
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
                                                    for ($i = 0; $i < $len3; $i++){
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
                                                @if ($publish->status <> 'published')
                                                    @php
                                                        $accepted_offer_id = $publish->accepted_offer_id;
                                                        $designer_id = \App\Models\Offer::find($accepted_offer_id)['designer_id'];
                                                        $designer_name = \App\Models\User::find($designer_id)['name'];
                                                        $deadline = \App\Models\Offer::find($accepted_offer_id)['hours'];
                                                        $price = \App\Models\Offer::find($accepted_offer_id)['price'];
                                                    @endphp
                                                    <div>
                                                        <b style="color:blue; margin-left: 50px;">Deadline:</b>
                                                        <b>{{$deadline}} hours</b>
                                                    </div>
                                                    <div>
                                                        <b style="color:blue; margin-left: 50px;">Price:</b>
                                                        <b>USD {{$price}}</b>
                                                    </div>
                                                    <div>
                                                        <b style="color:blue; margin-left: 50px;">Accepted Designer:</b>
                                                        <b>{{$designer_name}}</b>
                                                    </div>
                                                @endif
                                                <div>
                                                    <b style="color:blue; margin-left: 50px; vertical-align: top;">Description:</b>
                                                    <textarea cols="50" id="description" name="description" style="margin-top: 7px; margin-left: 50px;">{{$publish->description}}</textarea>
                                                </div>

                                                <input type="hidden" name="pub_id" value="{{$publish->id}}">

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
                            <td>{{$publish['status']}}</td>
                            <td>
                                @php
                                    $offer_count = count($publish->offers);

                                @endphp
                                {{$offer_count}}&nbsp;
                                @if($offer_count > 0)
                                    <button type="button" class="btn btn-info text-center" id="details" onclick="show(this)">Offers</button>
                                    <div class="modal fade" id="offersModal{{ $pub_index }}" role="dialog" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-lg" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header text-center">
                                                    <h4 class="modal-title text-center"><b>Offers</b></h4>
                                                </div>

                                                <div class="modal-body">
                                                    <table class="text-center table-responsive">
                                                        <thead>
                                                            <tr style="font-weight: bold;">
                                                                <td>Designer Name</td>
                                                                <td>Price(USD)</td>
                                                                <td>Time(hours)</td>
                                                                <td>Designer Rating</td>
                                                                <td>Accepted Offer</td>
                                                            </tr>
                                                        </thead>

                                                        <tbody>
                                                        @php
                                                            $offers = $publish->offers;
                                                            $pstatus = $publish['status'];
                                                        @endphp

                                                        @foreach($offers as $offer)
                                                            @php
                                                                $designer_id = $offer['designer_id'];
                                                                $designer = \App\Models\User::find($designer_id);
                                                                $rate = $offer->designer->rate;
                                                            @endphp
                                                            <tr>
                                                                <td style="text-align: center">{{$designer['name']}}</td>
                                                                <td style="text-align: center">{{$offer->price}}</td>
                                                                <td style="text-align: center">{{$offer->hours}}</td>
                                                                <td>
                                                                    <div class="rating" data-rate-value = {{$rate}}></div>
                                                                </td>
                                                                <td>
                                                                    @if(in_array($pstatus, ['accepted', 'undelivered', 'delivered', 'in mediation', 'completed']))
                                                                        @if($publish->accepted_offer_id === $offer->id)
                                                                            <strong>Accepted</strong>
                                                                        @endif
                                                                    @endif
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                        </tbody>
                                                    </table>
                                                </div>
                                                <div class="modal-footer text-center">
                                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <div class="row col-12">
                                    @empty($publish->deliveries)
                                        <div class="alert alert-info">There is no delivered design.</div>
                                    @endempty

                                    @foreach($publish->deliveries as $key => $delivery)

                                        <div class="col-4">
                                            <div class="col-4" style="margin-right: 5px;"><label>File{!! $key + 1 !!}</label></div>
                                            <div class="col-8">
                                                <a class="btn btn-primary"
                                                   href="{{url('admin/delivery-download/'.$delivery->id)}}">
                                                    Download
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach

                                    @if ($publish['status'] === 'in mediation')
                                    @php
                                        $accepted_id = $publish['accepted_offer_id'];
                                        $mediate = \App\Models\Mediate::where('offer_id', $accepted_id)->first();
                                    @endphp
                                    <div class="col-4">
                                        <div class="col-4" style="margin-right: 5px;"><label>Errors</label></div>
                                        <div class="col-8">

                                            <a class="btn btn-primary" href="{{url('admin/download_errors/'.$mediate->id)}}">
                                                Download
                                            </a>
                                        </div>
                                    </div>
                                    @endif
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

    <style>
        .rating {
            font-size: 28px;
        }
        .rating .rate-hover-layer {
            color: orange;
        }
        .rating .rate-select-layer {
            color: orange;
        }

        /*.modal-body {*/
        /*    overflow: auto;*/
        /*}*/

    </style>

@endsection


@section('js')

    <script src = https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js></script>
    <script src = https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js></script>
    <script src="{{asset('plugins/raterjs/rater.min.js')}}"></script>


    <script>
        $(document).ready(function() {
            $('#publishes_table').DataTable();

            // $('.rating').rate({
            //     max_value: 5,
            //     step_size: 0.1,
            //     readonly: true,
            // });
        } );

        function show(el) {

            $(el).next().find('.rating').rate({
                max_value: 5,
                step_size: 0.1,
                readonly: true,
            });

            $(el).next().modal('show')
        }

    </script>
@endsection
