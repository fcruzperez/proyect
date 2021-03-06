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
                            <th>Price(USD)</th>
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
                            if ($offer->status === 'accepted') {
                                $now = new DateTime();
                                $accepted_time = new DateTime($offer->accepted_at);
                                $diff = $now->diff($accepted_time);
                                $hour = $diff->days * 24 + $diff->h;
                                $min = $diff->i;

                                $top_id = \App\Models\Settings::count();
                                $settings = \App\Models\Settings::limit($top_id)->get();
                                $setting = $settings[count($settings) - 1];
                                $delta_time = $setting['delta_time'];

                                $deadline = $offer['hours'];

                            }
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
                                        @if ($request['status'] === 'canceled')
                                            Canceled
                                        @else
                                            Not accepted
                                        @endif
                                    @else
                                        Proposal Sent
                                    @endif
                                @elseif($offer->status === 'accepted')
                                    @if ($hour < $deadline + $delta_time)
                                    Accepted
                                    @else
                                    Not Delivered
                                    @endif
                                @elseif($offer->status === 'undelivered')
                                    Not Delivered
                                @elseif($offer->status === 'delivered')
                                    Delivered
                                @elseif($offer->status === 'mediated')
                                    In mediation
                                @elseif($offer->status === 'canceled')
                                    Canceled
                                @elseif($offer->status === 'completed')
                                    Completed
                                @endif
                            </td>
                            <td>
                                @php
                                    if ($offer->status === 'accepted') {

                                        $now = new DateTime();
                                        $acceptedTime  = new DateTime($request->accepted_at);
                                        $diff = $now->diff($acceptedTime);
                                        $hours = $diff->days * 24 + $diff->h;
                                        $minutes = $diff->i;
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


