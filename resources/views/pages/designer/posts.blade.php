@extends('layouts.designer')

@section('content')
    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="row">
            @if($publishes->isEmpty())
                <div class="col-12 alert-warning alert d-block">There is no new publishes.</div>
            @endif

            @foreach($publishes as $publish)
                @php
                    $now = new DateTime();
                    $pp = new DateTime($publish->created_at);
                    $diff = $now->diff($pp);
                    $h = $diff->days * 24 + $diff->h;

                    $top_id = \App\Models\Settings::count();
                    if ($top_id <> 0) {
                        $settings = \App\Models\Settings::limit($top_id)->get();
                        $setting = $settings[count($settings) - 1];
                        $expiration_time = $setting['expiration_time'];
                    }
                @endphp

                @if ($publish->status <> 'published' || $h < $expiration_time)
                <div class="col-sm-6 col-lg-4" style="margin-top: 20px;">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="main image-wrapper">
                                    @if(!is_null($publish->image1))
                                        <img src="{{url($publish->image1)}}" class="image-box">
                                    @endif
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-3 px-0">
                                    <div class="row mx-0">
                                        <div class="col-12 sub image-wrapper">
                                            @if(!is_null($publish->image2))
                                                <img src="{{url($publish->image2)}}" class="image-box">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3 px-0">
                                    <div class="row mx-0">
                                        <div class="col-12 sub image-wrapper">
                                            @if(!is_null($publish->image3))
                                                <img src="{{url($publish->image3)}}" class="image-box">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3 px-0">
                                     <div class="row mx-0">
                                        <div class="col-12 sub image-wrapper">
                                            @if(!is_null($publish->image4))
                                                <img src="{{url($publish->image4)}}" class="image-box">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-3 px-0">
                                    <div class="row mx-0">
                                        <div class="col-12 sub image-wrapper">
                                            @if(!is_null($publish->image5))
                                                <img src="{{url($publish->image5)}}" class="image-box">
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-4">Design Name</div>
                                <div class="col-8" style="padding-top: 10px;"><strong>{{$publish->design_name}}</strong></div>
                            </div>
                            <div class="row bl1 tl1">
                                <div class="col-4">Size</div>
                                <div class="col-8">{{$publish->width}} x {{$publish->height}} {{$publish['unit']}}</div>
                            </div>
                            <div class="row bl1">
                                @php
                                    $now = new DateTime();
                                    $pp = new DateTime($publish->created_at);
                                    $diff = $now->diff($pp);
                                    $hour = $diff->days * 24 + $diff->h;
                                    $min = $diff->i;
                                    if ($hour === 0)
                                        $str = "{$min} minutes ago";
                                    else
                                        $str = "{$hour} hour {$min} minutes ago";

                                @endphp
                                <div class="col-4">Published</div>
                                <div class="col-8">
                                    {{$str}}
                                </div>
                            </div>
                            <div class="row bl1">
                                @php
                                    $tmp = [];
                                    foreach ($publish->formats as $fmt){ $tmp[] = $fmt->name;}
                                    $str = implode(', ', $tmp);
                                @endphp
                                <div class="col-4">Format</div>
                                <div class="col-8">{{ empty($str) ? 'Undefined' : $str }}</div>
                            </div>
                            <div class="row bl1">
                                @php
                                    $tmp = [];
                                    foreach ($publish->fabrics as $fmt){ $tmp[] = $fmt->name;}
                                    $str = implode(', ', $tmp);
                                @endphp
                                <div class="col-4">Fabrics</div>
                                <div class="col-8">{{ empty($str) ? 'Undefined' : $str }}</div>
                            </div>
                            <div class="row bl1">
                                @php
                                    $tmp = [];
                                    foreach ($publish->technics as $fmt){ $tmp[] = $fmt->name;}
                                    $str = implode(', ', $tmp);
                                @endphp
                                <div class="col-4">Technic</div>
                                <div class="col-8">{{empty($str) ? 'Undefined' : $str }}</div>
                            </div>
                            <div class="row bl1">
                                @php
                                    $counts = \App\Models\Offer::where('request_id', $publish->id)->count();
                                @endphp
                                <div class="col-4">Offers</div>
                                <div class="col-8">{{$counts}}</div>
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            @php
                                $designer_id = \Illuminate\Support\Facades\Auth::user()['id'];
                                $current_offer = \App\Models\Offer::where('designer_id', $designer_id)->where('request_id', $publish->id)->get();
                            @endphp
                            @if (count($current_offer))
                                <button type="button" id="bid_button" onclick="show({{$publish->id}})" class="btn btn-info btn-lg" disabled>Bid</button>
                            @else
                                <button type="button" id="bid_button" onclick="show({{$publish->id}})" class="btn btn-info btn-lg">Bid</button>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            @endforeach

            <div class="modal fade" id="bidModal" role="dialog" tabindex="-1" aria-hidden="true" style="font-family: Arial, Helvetica, sans-serif">
                <div class="modal-dialog modal-dialog-centered" role="document">

                    <form method="POST" action="{{route('designer.offer-save')}}" id="bid_modal_form">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header text-center">
                                <h4 class="modal-title text-center">Bid</h4>
                            </div>
                            <div class="modal-body">
                                <input type="hidden" name="request_id" id="request_id" >
                                <div class="form-group">
                                    <label for="bid_price">Price</label>
                                    <input type="number" class="form-control" name="bid_price" min="1" id="bid_price" placeholder="$">
                                </div>
                                <div class="form-group">
                                    <label for="bid_time">Time</label>
                                    <input type="number" class="form-control" name="bid_time" min="1" id="bid_time" placeholder="hours">
                                </div>
                                @php
                                    $top_id = \App\Models\Settings::count();
                                    if ($top_id <> 0) {
                                        $settings = \App\Models\Settings::limit($top_id)->get();
                                        $setting = $settings[count($settings) - 1];
                                        $min_work_time = $setting['minimum_work_time'];
                                        $min_work_price = $setting['minimum_work_price'];
                                    }
                                @endphp

                                <p>By clicking <strong>bid</strong> you agree to abide by the terms and conditions.<br>
                                   If the client accepts your proposal, a countdown will begin immediately.<br>
                                   Minimum work time: {{$min_work_time}} hours, Minimum work price: USD {{$min_work_price}}</p>
                            </div>
                            <div class="modal-footer text-center">
                                <div class="text-center">
                                    <button type="button" class="btn btn-danger" data-dismiss="modal">CANCEL</button>
                                    <button type="submit" class="btn btn-primary ml-3">&nbsp;&nbsp;BID &nbsp;&nbsp;</button>
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
    <style>
        .image-wrapper {
            border: 1px solid #eee;
            background: #ddd;
            align-content: center;
            justify-content: center;
            display: flex;
        }
        .image-wrapper.main {
            height: 300px;
        }
        .image-wrapper.sub {
            height: 100px;
            padding: 0;
        }
        .image-box {
            object-fit: contain;
            object-position: center;
            width: 100%;
        }
        .tl1 {
            border-top: 1px solid #ddd;
        }
        .bl1 {
            border-bottom: 1px solid #ddd;
        }
    </style>
@endsection

@section('js')
    <script>
        function  show(para) {
            $('#request_id').val(para);
            $('#bidModal').modal();
        }


    </script>

@endsection

