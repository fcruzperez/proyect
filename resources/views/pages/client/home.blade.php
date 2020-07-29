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
                    <th>Hours</th>
                    <th>Time Left</th>
                    <th>Details</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Offers</th>
                    <th>Edit</th>
                    <th>Cancel</th>
                </tr>
                </thead>

                <tbody>
                @foreach($publishes as $publish)
                    <tr>
                        @php
                            /*---
                            $nowTime = strtotime(date("Y-m-d h:i:sa"));
                            $publishTime = strtotime((string)$publish['created_at']);
                            $interval = abs($nowTime - $publishTime);
                            $minutes = round($interval / 60);

                            $hours = floor($minutes / 60);
                            $minutes = $minutes - 60 * $hours;
                            */
                        @endphp
                        <td>{{$publish['created_at']}}</td>
                        <td>{{$publish['name']}}</td>
                        <td>{{$publish['hours']}}</td>
                        {{--                @if($hours == 0)--}}
                        {{--                    <td>{{$minutes . 'minutes ago'}}</td>--}}
                        {{--                @else--}}
                        {{--                    <td>{{$hour . 'hour' . $minutes . 'minutes ago'}}</td>--}}
                        {{--                @endif--}}
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
                            @endphp
                            @if ($publish['status'] == 'in production')
{{--                                @if ($hours == 0)--}}
{{--                                    {{$minutes}}--}}
{{--                                @else--}}
{{--                                    {{$hours. 'hour ' . $minutes . 'minutes'}}--}}
{{--                                @endif--}}
                                {{$hours}}:{{$minutes}} hours

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
                        <td>{{$publish['deposit']}}</td>
                        <td>
                            @php
                                $offer_count = count($publish->offers);
                            @endphp
                            {{$offer_count}}
                            @if($offer_count > 0)
                                <button type="button" class="btn btn-info text-center" id="details" data-toggle="modal" data-target = "#sss{{$publish->id}}">Offers</button>
                                <div class="modal fade" id="sss{{$publish->id}}" role="dialog" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header text-center">
                                                <h4 class="modal-title text-center">Offers</h4>
                                            </div>

                                            <div class="modal-body">
                                                <table>
                                                    <thead>
                                                    <tr style="font-weight: bold;">
                                                        <td>Price($)</td>
                                                        <td>Time(hours)</td>
                                                        <td>Desinger Rating</td>
                                                        <td></td>
                                                    </tr>
                                                    </thead>
                                                    <tbody>
                                                    @php
                                                        $offers = $publish->offers;
                                                    @endphp
                                                    @foreach($offers as $offer)
                                                        <tr>

                                                            <td style="text-align: center">{{$offer->price}}</td>
                                                            <td style="text-align: center">{{$offer->hours}}</td>
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

                                                                        <button type="submit" class="btn btn-primary">AWARD</button>
                                                                    </form>
                                                                @else
                                                                    @if($publish->accepted_offer_id === $offer->id)
                                                                        <strong>Awarded</strong>
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
                            @if($publish['status'] == 'published')
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
                            @if($publish['status'] == 'published')

                                <form action="{{ route('client.cancel', $publish->id) }}" method="post">
                                    @csrf
                                    <button type="submit" class="btn btn-danger" onclick="return confirm('Will you cancel this post? Really?')" name="changeStatus">Cancel</button>
                                </form>

                            @else

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
