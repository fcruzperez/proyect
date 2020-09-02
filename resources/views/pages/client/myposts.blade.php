@extends('layouts.client')

@section('content')

    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="row">
            <div class="col-12">
                <table id="publishes_table" class="table table-striped table-bordered text-center">

                    <thead>
                    <tr>
                        <th>Time</th>
                        <th>Name</th>
                        <th>Time Left</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Offers</th>
                        <th>Edit</th>
                        <th>Cancel</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($publishes as $publish)
                        @php
                            $now = new DateTime();
                            $pp = new DateTime($publish->created_at);
                            $diff = $now->diff($pp);
                            $hour = $diff->days * 24 + $diff->h;
                            $min = $diff->i;

                            $top_id = \App\Models\Settings::count();
                            if ($top_id <> 0) {
                                $settings = \App\Models\Settings::limit($top_id)->get();
                                $setting = $settings[count($settings) - 1];
                                $expiration_time = $setting['expiration_time'];
                            }
                            if ($publish->status === 'accepted') {
                                $offer_id = $publish['accepted_offer_id'];
                                $offer = \App\Models\Offer::find($offer_id);
                                $deadline = $offer['hours'];
                                $designer_id = $offer['designer_id'];
                                $designer = \App\Models\User::find($designer_id);
                                $designer_name = $designer['name'];
                                $client_id = $publish['client_id'];
                                $client = \App\Models\User::find($client_id);
                                $client_name = $client['name'];

                                $now = new DateTime();
                                $accepted_time = new DateTime($publish->accepted_at);
                                $diff2 = $now->diff($accepted_time);
                                $hour = $diff->days * 24 + $diff->h;

                                if ($hour >= $deadline) {

                                    $msg1 = "Designer {$designer_name} hasn't submitted the accepted work {$publish->design_name} of Client {$client_name}.";

                                    $message = \App\Models\Message::create([
                                        'user_id' => 1,
                                        'subject' => $msg1,
                                        'content' => $msg1,
                                        'action_url' => "/admin/refund/{$publish->id}",
                                    ]);

                                    $data1 = [
                                        'user_id' => 1,
                                        'action_url' => "/admin/refund/{$publish->id}",
                                        'message' => $msg1
                                    ];

                                    event(new \App\Events\AdminEvent($data1));

                                    $msg2 = "You haven't delivered design {$publish->design_name} within your deadline";

                                    $message = \App\Models\Message::create([
                                        'user_id' => $designer_id,
                                        'subject' => $msg2,
                                        'content' => $msg2,
                                        'action_url' => "/designer/finance-list",
                                    ]);

                                    $data2 = [
                                        'user_id' => $designer_id,
                                        'action_url' => "/designer/finance-list",
                                        'message' => $msg2
                                    ];
                                    event(new \App\Events\DesignerEvent($data2));


                                    $msg3 = "Designer haven't delivered your design {$publish->design_name} within the deadline, you will have a refund soon.";

                                    $message = \App\Models\Message::create([
                                        'user_id' => $client_id,
                                        'subject' => $msg3,
                                        'content' => $msg3,
                                        'action_url' => "/client/finance-list",
                                    ]);

                                    $data3 = [
                                        'user_id' => $client_id,
                                        'action_url' => "/client/finance-list",
                                        'message' => $msg3
                                    ];
                                    event(new \App\Events\ClientEvent($data3));

                                    $publish['status'] = 'canceled';
                                    $publish->save();


                                }
                            }


                        @endphp
                        @if ($publish->status === 'published' && $hour >= $expiration_time)
                            @php
                                $msg = "Your post {$publish->design_name} has expired.";
                                $message = \App\Models\Message::create([
                                    'user_id' => $publish->client_id,
                                    'subject' => $msg,
                                    'content' => $msg,
                                    'action_url' => "/client/delete_publish/{$publish->id}",
                                ]);
                                $data = [
                                    'user_id' => $publish->client_id,
                                    'action_url' => "/client/delete_publish/{$publish->id}",
                                    'message' => $msg
                                ];
                                event(new \App\Events\ClientEvent($data));

                                $offers = $publish->offers;

                                foreach ($offers as $offer) {
                                    $offer->delete();
                                }

                                $publish->delete();

                            @endphp
                        @endif
                        @if ($publish->status <> 'published' || $hour < $expiration_time)

                        <tr>
                            <td>{{$publish['created_at']}}</td>
                            <td>{{$publish['design_name']}}</td>
                            <td>
                                @php
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

                                    $top_id = \App\Models\Settings::count();
                                        if ($top_id <> 0) {
                                            $settings = \App\Models\Settings::limit($top_id)->get();
                                            $setting = $settings[count($settings) - 1];
                                            $delta_time = $setting['delta_time'];
                                        }
                                    $hours = $hours + $delta_time;
                                @endphp

                                @if ($publish['status'] === 'accepted' && $hours > 0)
                                    {{--                                @if ($hours == 0)--}}
                                    {{--                                    {{$minutes}}--}}
                                    {{--                                @elsdee--}}
                                    {{--                                    {{$hours. 'hour ' . $minutes . 'minutes'}}--}}
                                    {{--                                @endif--}}
                                    {{$hours}}:{{$minutes}} hours
                                @elseif ($publish['status'] === 'accepted' & $hours === 0)
                                    {{$minutes}} minutes
                                @else
                                    --------
                                @endif
                            </td>
                            <td>
                                <a class="btn btn-info text-center" href="{{url("client/publish-detail/{$publish->id}")}}">
                                    Details
                                </a>
                            </td>
                            <td>{{$publish['status']}}</td>
                            <td>
                                @php
                                    $offer_count = count($publish->offers);
                                @endphp
                                {{$offer_count}}&nbsp;
                                @if($offer_count > 0)
                                    <button type="button" class="btn btn-info text-center" id="details" data-toggle="modal" data-target = "#sss{{$publish->id}}">Offers</button>
                                    <div class="modal fade" id="sss{{$publish->id}}" role="dialog" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header text-center">
                                                    <h4 class="modal-title text-center"><b>Offers</b></h4>
                                                </div>

                                                <div class="modal-body">
                                                    <table class="text-center">
                                                        <thead>
                                                        <tr style="font-weight: bold;">
                                                            <td>Price(USD)</td>
                                                            <td>Time(hours)</td>
                                                            <td>Designer Rating</td>
                                                            <td></td>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        @php
                                                            $offers = $publish->offers;
                                                            $top_id = \App\Models\Settings::count();
                                                            if ($top_id <> 0) {
                                                                $settings = \App\Models\Settings::limit($top_id)->get();
                                                                $setting = $settings[count($settings) - 1];
                                                                $client_fee = $setting['client_fee'];
                                                            }
                                                            $top_id = \App\Models\Settings::count();
                                                            if ($top_id <> 0) {
                                                                $settings = \App\Models\Settings::limit($top_id)->get();
                                                                $setting = $settings[count($settings) - 1];
                                                                $delta_time = $setting['delta_time'];
                                                             }
                                                        @endphp
                                                        @foreach($offers as $offer)
                                                            <tr>
                                                                <td style="text-align: center">{{intval($offer->price + $client_fee)}}</td>
                                                                <td style="text-align: center">{{$offer->hours + $delta_time}}</td>
                                                                <td>
                                                                    <div class="rating" data-rate-value = {{$offer->designer->rate}}></div>
                                                                </td>
                                                                <td>
                                                                    @if($publish->status === 'published')
                                                                        <form method="get" action="{{route('client.show_deposit')}}">
                                                                            @csrf
                                                                            <input type="hidden" name="request_id" value="{{$publish->id}}" />
                                                                            <input type="hidden" name="offer_id" value="{{$offer->id}}" />
                                                                            <input type="hidden" name="price" value="{{$offer->price}}" />
                                                                            <input type="hidden" name="time" value="{{$offer->hours}}" />
                                                                            <input type="hidden" name="name" value="{{$offer->name}}" />

                                                                            <button type="submit" class="btn btn-primary">Accept</button>
                                                                        </form>
                                                                    @else
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
                                @if($publish['status'] == 'published' && count($publish->offers) === 0)
                                    @php
                                        $request_id = $publish->id;
                                    @endphp
                                    <a href="{{url('/client/edit_publish/'. $request_id) }}">
                                        <button type="button" class="btn btn-primary">Edit</button>
                                    </a>
                                @else

                                @endif
                            </td>
                            <td>
                                @if($publish['status'] === 'published')

                                    <form action="{{ route('client.cancel', $publish->id) }}" method="post">
                                        @csrf
                                        <button type="submit" class="btn btn-danger" onclick="return confirm('Will you cancel this post? Really?')" name="changeStatus">Cancel</button>
                                    </form>

                                @else

                                @endif
                            </td>
                        </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('stylesheet')

    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">

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

@endsection


@section('js')

    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('plugins/raterjs/rater.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#publishes_table').DataTable();

            $('.rating').rate({
                max_value: 5,
                step_size: 0.1,
                readonly: true,
            });
        } );


        function acceptBid(publish_id, offer_id) {
            var data = {
                _token: '{{csrf_token()}}',
                request_id: publish_id,
                offer_id: offer_id
            };

            $.ajax({
                url: '{{route('client.accept_bid')}}',
                method: 'post',
                data: data,
                success: function(data) {
                    console.log(data);
                },
                error: function(status, error, xsrf) {
                    console.log(error);
                }
            })
        }
    </script>
@endsection
