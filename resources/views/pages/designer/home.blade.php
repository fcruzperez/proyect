@extends('layouts.designer')

@section('content')

    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="rating" data-rate-value="{{$rate}}"></div>

        <h3 class="text-center"><b>My Offers</b></h3>

        <div class="row">
            <div class="col-12">
                <table id="offers_table" class="table table-striped table-bordered text-center">

                    <thead>
                    <tr>
                        <th>Time</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Offer Status</th>
                        <th>Time Left</th>
                        <th>Details</th>
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
                            <td>{{$request['design_name']}}</td>
                            <td>{{$offer['price']}}</td>
                            <td>
                                {{--  sent, accepted, mediated, canceled, completed--}}
                                @if($offer->status === 'sent')
                                    @if ($request['status'] <> 'published')
                                        Not accepted
                                    @else
                                        Proposal Sent
                                    @endif
                                @elseif($offer->status === 'accepted')
                                    Accepted
                                @elseif($offer->status === 'sent' && $request['status'] <> 'published')
                                    Not Accepted
                                @elseif($offer->status === 'delivered')
                                    Delivered
                                @elseif($offer->status === 'mediated')
                                    In mediate
                                @elseif($offer->status === 'canceled')
                                    Canceled
                                @elseif($offer->status === 'completed')
                                    Completed
                                @endif
                            </td>
                            <td>
{{--                                @php--}}
{{--                                    $accepted_at = new \Carbon\Carbon($request->accepted_at);--}}
{{--                                    $deadline = $accepted_at->addHours($offer->hours);--}}
{{--                                    $now = new \Carbon\Carbon();--}}
{{--                                    $timeLeft = $deadline->diffInMinutes($now);--}}
{{--                                    $mins = $timeLeft % 60;--}}
{{--                                    $hr = intdiv($timeLeft, 60);--}}
{{--                                @endphp--}}
                                @php
                                    if ($offer->status === 'accepted') {
                                        $nowTime = strtotime(date("Y-m-d h:i:sa"));
                                        $acceptedTime = strtotime((string)$request['accepted_at']);
                                        $interval = abs($nowTime - $acceptedTime);
                                        $minutes = round($interval / 60);

                                        $hours = floor($minutes / 60);
                                        $minutes = $minutes - 60 * $hours;
                                        $accepted_offer_id = $request['accepted_offer_id'];
                                        $deadline = \App\Models\Offer::find($accepted_offer_id)['hours'];
                                        $hours = $deadline - $hours - 1;
                                        $minutes = 60 - $minutes;
                                    }

                                @endphp
                                @if ($offer->status === 'accepted' && $hours > 0)
                                    {{$hours}}:{{$minutes}} hours
                                @elseif ($offer->status === 'accepted' && $hours === 0)
                                    {{$minutes}} minutes
                                @else
                                    -------
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-info" href="{{url('/designer/offer-detail/'.$offer->id)}}">Details</a>
                                {{--                    <button type="button" class="btn btn-info text-center" id="details" data-toggle="modal" data-target = "#www{{$request->id}}">Details</button>--}}
                                {{--                    <div class="modal fade" id="www{{$request->id}}" role="dialog" tabindex="-1" aria-hidden="true">--}}
                                {{--                        <div class="modal-dialog" role="document">--}}
                                {{--                            <div class="modal-content">--}}
                                {{--                                <div class="modal-header text-center">--}}
                                {{--                                    <h4 class="modal-title text-center">Details</h4>--}}
                                {{--                                </div>--}}

                                {{--                                <div class="modal-body text-left">--}}
                                {{--                                    @php--}}
                                {{--                                        $str = '';--}}
                                {{--                                        for ($i = 0; $i < 3; $i++){--}}
                                {{--                                            if (isset($request->formats[$i]->name)) {--}}
                                {{--                                                $str = $str . $request->formats[$i]->name . ',';--}}
                                {{--                                            }--}}
                                {{--                                        }--}}
                                {{--                                        $n = strlen($str);--}}
                                {{--                                        $str = substr($str, 0, $n - 1);--}}
                                {{--                                        //dd($str);--}}
                                {{--                                    @endphp--}}
                                {{--                                    <div>--}}
                                {{--                                        <b class="text-center" style="color:blue; margin-left: 50px;">Format(s):</b> <b>{{ empty($str) ? 'Undefined' : $str }}</b>--}}
                                {{--                                    </div>--}}

                                {{--                                    <div>--}}
                                {{--                                        <b style="color:blue; margin-left: 50px;">Size:</b> <b> {{$request->width}} x {{$request->height}} cm</b>--}}
                                {{--                                    </div>--}}
                                {{--                                    @php--}}
                                {{--                                        $str = '';--}}
                                {{--                                        for ($i = 0; $i < 3; $i++){--}}
                                {{--                                            if (isset($request->fabrics[$i]->name)) {--}}
                                {{--                                                $str = $str . $request->fabrics[$i]->name . ',';--}}
                                {{--                                            }--}}
                                {{--                                        }--}}
                                {{--                                        $n = strlen($str);--}}
                                {{--                                        $str = substr($str, 0, $n - 1);--}}
                                {{--                                    @endphp--}}
                                {{--                                    <div>--}}
                                {{--                                        <b style="color:blue; margin-left: 50px;">Fabric(s):</b> <b> {{ empty($str) ? 'Undefined' : $str }}</b>--}}
                                {{--                                    </div>--}}
                                {{--                                    @php--}}
                                {{--                                        $str = '';--}}
                                {{--                                        for ($i = 0; $i < 3; $i++){--}}
                                {{--                                            if (isset($request->technics[$i]->name)) {--}}
                                {{--                                                $str = $str . $request->technics[$i]->name . ',';--}}
                                {{--                                            }--}}
                                {{--                                        }--}}
                                {{--                                        $n = strlen($str);--}}
                                {{--                                        $str = substr($str, 0, $n - 1);--}}
                                {{--                                    @endphp--}}
                                {{--                                    <div>--}}
                                {{--                                        <b style="color:blue; margin-left: 50px;">Technic(s):</b> <b> {{ empty($str) ? 'Undefined' : $str }}</b>--}}
                                {{--                                    </div>--}}

                                {{--                                </div>--}}
                                {{--                                <div class="modal-footer">--}}
                                {{--                                    <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>--}}
                                {{--                                </div>--}}
                                {{--                            </div>--}}
                                {{--                        </div>--}}
                                {{--                    </div>--}}
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
    </style>
    <link  href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section('js')

    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('plugins/raterjs/rater.min.js')}}"></script>

    <script>
        $(document).ready(function() {
            $('#offers_table').DataTable();
            $('.rating').rate({
                max_value: 5,
                step_size: 0.1,
                readonly: true,
            })
        });
    </script>
@endsection
