@extends('layouts.client')

@section('content')

    <table id="publishes_table" class="table table-striped table-bordered col-md-8 text-center">

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
                <td>-------</td>
                <td>
                    <button type="button" class="btn btn-info text-center" id="details" data-toggle="modal" data-target = "#fff{{$publish->id}}">Details</button>
                    <div class="modal fade" id="fff{{$publish->id}}" role="dialog" tabindex="-1" aria-hidden="true">
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
                <td>
                    @php
                        $offer_count = count($publish->offers);
                    @endphp
                    {{$offer_count}} &nbsp;
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
                                                        <form method="get" action="{{route('client.show_deposit')}}">
                                                            @csrf
                                                            <td style="text-align: center">{{$offer->price}}</td>
                                                            <td style="text-align: center">{{$offer->hours}}</td>
                                                            <td>
                                                                <div>
                                                                    @php
                                                                        $rating = 3.5;
                                                                    @endphp
                                                                    @if(0 <= $rating and $rating < 0.5)
                                                                        <span class="fa fa-star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <div style="font-weight: bold; color:red;">{{$rating}}</div>

                                                                    @elseif(0.5 <= $rating and $rating < 1)
                                                                        <span class="fa fa-star-half checked_star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <div style="font-weight: bold; color:red;">{{$rating}}</div>

                                                                    @elseif(1 <= $rating and $rating < 1.5)
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <div style="font-weight: bold; color:red;">{{$rating}}</div>

                                                                    @elseif(1.5 <= $rating and $rating < 2)
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star-half checked_star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <div style="font-weight: bold; color:red;">{{$rating}}</div>

                                                                    @elseif(2 <= $rating and $rating < 2.5)
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <div style="font-weight: bold; color:red;">{{$rating}}</div>

                                                                    @elseif(2.5 <= $rating and $rating < 3)
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star-half checked_star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <div style="font-weight: bold; color:red;">{{$rating}}</div>

                                                                    @elseif(3 <= $rating and $rating < 3.5)
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <div style="font-weight: bold; color:red;">{{$rating}}</div>

                                                                    @elseif(3.5 <= $rating and $rating < 4)
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star-half checked_star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <div style="font-weight: bold; color:red;">{{$rating}}</div>

                                                                    @elseif(4 <= $rating and $rating < 4.5)
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star"></span>
                                                                        <div style="font-weight: bold; color:red;">{{$rating}}</div>

                                                                    @elseif(4.5 <= $rating and $rating < 5)
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star-half checked_star"></span>
                                                                        <div style="font-weight: bold; color:red;">{{$rating}}</div>

                                                                    @elseif($rating == 5)
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <span class="fa fa-star checked_star"></span>
                                                                        <div style="font-weight: bold; color:red;">{{$rating}}</div>

                                                                    @endif

                                                                </div>

                                                            </td>
                                                            <td>
                                                                <input type="hidden" name="request_id" value="{{$publish->id}}" />
                                                                <input type="hidden" name="offer_id" value="{{$offer->designer_id}}" />
                                                                <input type="hidden" name="price" value="{{$offer->price}}" />
                                                                <input type="hidden" name="time" value="{{$offer->hours}}" />
                                                                <input type="hidden" name="name" value="{{$offer->name}}" />

                                                                <button type="submit" class="btn btn-primary">AWARD</button>
                                                            </td>
                                                        </form>
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
@endsection



@section('stylesheet')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">

    <style>
        .checked_star{
            color: orange;
        }
    </style>

@endsection


@section('js')

    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#publishes_table').DataTable();
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

        // function showDetail() {
        //     document.getElementById('details').click();
        //
        // }

    </script>
@endsection
