@extends('layouts.client')

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
                    $str = $diff->format('%h hour %i minutes ago');
                    $h = explode(' ', $str);

                    $top_id = \App\Models\Settings::count();
                    if ($top_id <> 0) {
                        $settings = \App\Models\Settings::limit($top_id)->get();
                        $setting = $settings[count($settings) - 1];
                        $expiration_time = $setting['expiration_time'];
                    }
                @endphp

                @if ($publish->status <> 'published' || $h[0] < $expiration_time)
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
                                    $str = $diff->format('%h hour %i minutes ago');
                                    $h = explode(' ', $str);
                                @endphp
                                <div class="col-4">Published</div>
                                <div class="col-8">
                                    @if ((int)$h[0] === 0)
                                        {{$h[2]}} minutes ago
                                    @else
                                        {{$str}}
                                    @endif
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
                    </div>
                </div>
                @endif
            @endforeach
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

