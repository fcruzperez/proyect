@extends('layouts.designer')

@section('content')
    <div class="container">
        <div class="row">
            @foreach($publishes as $publish)
                <div class="col-sm-6 col-lg-4" style="margin-top: 20px;">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-9 main-image-wrapper">
                                    @if(!is_null($publish->image1))
                                    <img src="{{url($publish->image1)}}" class="main-image">
                                    @endif
                                </div>
                                <div class="col-3">
                                    <div class="row" style="margin-bottom: 7px;">
                                        <div class="col-12  sub-image-wrapper">
                                            @if(!is_null($publish->image2))
                                                <img src="{{url($publish->image2)}}" class="sub-image">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row" style="margin-bottom: 7px;">
                                        <div class="col-12 sub-image-wrapper">
                                            @if(!is_null($publish->image3))
                                                <img src="{{url($publish->image3)}}" class="sub-image">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="row" style="margin-bottom: 7px;">
                                        <div class="col-12 sub-image-wrapper">
                                            @if(!is_null($publish->image4))
                                                <img src="{{url($publish->image4)}}" class="sub-image">
                                            @endif
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="row">
                                @php
                                    $nowTime = strtotime(date("Y-m-d h:i:sa"));
                                    $pusblishTime = strtotime((string)$publish['created_at']);
                                    //dd(gettype($nowTime));
                                    $interval = abs($nowTime - $pusblishTime);
                                    $minutes = round($interval / 60);
                                    if ($minutes > 59){
                                        $hours = floor($minutes / 60);
                                        $minutes = $minutes - 60 * $hours;
                                    }
                                    else {
                                        $hours = 0;
                                    }
                                @endphp
                                <div class="col-4">
                                    <strong>{{$publish->name}}</strong>
                                </div>
                                @if ($hours > 0)
                                    <div class="col-8 text-right">
                                        {{$publish->width}} x {{$publish->height}} cm  Time Left: {{$hours.'hours'. ' ' . $minutes . 'minutes ago'}}
                                    </div>
                                @else
                                    <div class="col-8 text-right">
                                        {{$publish->width}} x {{$publish->height}} cm  Time Left: {{$minutes . 'minutes ago'}}
                                    </div>
                                @endif

                            </div>
                            <div class="row">
                                <div class="col-4">
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
                                    Fabric: {{ empty($str) ? 'Undefined' : $str }}
                                </div>
                                <div class="col-8 text-right">
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
                                    Technic: {{empty($str) ? 'Undefined' : $str }}
                                </div>
                            </div>
                            <div class="row">
                                @php
                                    $counts = \App\Models\Offer::where('request_id', '=', $publish->id)->pluck('request_id')->toArray();
                                    $count = count($counts);
                                    //dd(count(array($counts)));
                                @endphp
                                Offers: {{$count}}
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <button type="button" onclick="show(this.name)" class="btn btn-info btn-lg" name={{$publish->id}}>Bid</button>
                        </div>
                    </div>
                </div>
            @endforeach

                    <button type="button" class="btn btn-info" data-toggle="modal" data-target="#bidModal" id="btn_modal" style="display: none;">Bid</button>
                    <div class="modal fade" id="bidModal" role="dialog" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog" role="document">

                            <form method="POST" action="{{route('designer.home')}}" id="bid_modal_form">
                                @csrf
                                <div class="modal-content">
                                    <div class="modal-header text-center">
                                        <h4 class="modal-title text-center">Bid</h4>
                                    </div>
                                    <div class="modal-body">
                                        <input type="hidden" name="request_id" id="request_id" >
                                        <div>
                                            <label for="bid_price">Price:</label>
                                            <input type="number" name="bid_price" id="bid_price" placeholder="$">
                                        </div>
                                        <div>
                                            <label for="bid_time">Time:</label>
                                            <input type="number" name="bid_time" id="bid_time" placeholder="hours">
                                        </div>

                                        <p>By clicking <strong>bid</strong> you agree to abide by the terms and conditions.</p>
                                    </div>
                                    <div class="modal-footer text-center">
                                        <div class="text-center">
                                            <button type="button" class="btn btn-danger" data-dismiss="modal">CANCEL</button>
                                            <button type="submit" class="btn btn-primary">BID</button>
                                        </div>
                                    </div>
                                </div>
                            </form>

                        </div>
                    </div>
        </div>
    </div>
@endsection


@section('stylesheet')
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <style>
        .main-image-wrapper {
            height: 300px;
        }
        .sub-image-wrapper {
            padding: 0;
        }
        .main-image{
            object-fit: contain;
            object-position: center;
            width: 100%;
        }
        .sub-image {
            /*height: 80px;*/
            object-fit: contain;
            object-position: center;
            width: 100%;
        }
    </style>
@endsection

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <script>
        function  show(para) {
            document.getElementById('request_id').value = para;
            document.getElementById('btn_modal').click();
        }
    </script>

@endsection

