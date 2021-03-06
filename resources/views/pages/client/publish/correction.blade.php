@extends('layouts.client')

@php
    $pstatus = $publish->status;
@endphp

@section('content')
    <div class="container" style="font-family: Arial, Helvetica, sans-serif">

{{--        @isset($complete_success)--}}
{{--            <div class="alert alert-success">Your task has been successfully completed! </div>--}}
{{--        @endif--}}

{{--        @error('complete error')--}}
{{--            <div class="alert alert-warning">An error occurred in completing! </div>--}}
{{--        @enderror--}}

        <div class="row">
            <div class="col-12" style="margin-top: 20px;">
                <div class="card" id="offerCard">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-12 col-sm-6 col-lg-2 px-0 image-wrapper">
                                @if(!is_null($publish->image1))
                                    <img src="{{url($publish->image1)}}" class="image-box">
                                @endif
                            </div>
                            <div class="col-12 col-sm-6 col-lg-2 px-0 image-wrapper">
                                @if(!is_null($publish->image2))
                                    <img src="{{url($publish->image2)}}" class="image-box">
                                @endif
                            </div>
                            <div class="col-12 col-sm-6 col-lg-2 px-0 image-wrapper">
                                @if(!is_null($publish->image3))
                                    <img src="{{url($publish->image3)}}" class="image-box">
                                @endif
                            </div>
                            <div class="col-12 col-sm-6 col-lg-2 px-0 image-wrapper">
                                @if(!is_null($publish->image4))
                                    <img src="{{url($publish->image4)}}" class="image-box">
                                @endif
                            </div>
                            <div class="col-12 col-sm-6 col-lg-2 px-0 image-wrapper">
                                @if(!is_null($publish->image5))
                                    <img src="{{url($publish->image5)}}" class="image-box">
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="row">
                                    <div class="col-3"><label>Name</label></div>
                                    <div class="col-9"><strong>{{$publish->design_name}}</strong></div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="row">
                                    @php
                                        $width = $publish->width;
                                        $height = $publish->height;
                                    @endphp
                                    <div class="col-3"><label>Size</label></div>
                                    <div class="col-5" id="size">{{$width}} x {{$height}} {{$publish['unit']}} </div>
                                    <input type="hidden" name="width" id="width" value={{$width}}>
                                    <input type="hidden" id="height" value={{$height}}>
{{--                                    <div class="col-4">--}}
{{--                                        <select name="unit" id="unit" onchange="unitChange()">--}}
{{--                                            <option value="mm">mm</option>--}}
{{--                                            <option value="inch">inch</option>--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                @php
                                    $now = new DateTime();
                                    $pp = new DateTime($publish->created_at);
                                    $diff = $now->diff($pp);
                                    $hour = $diff->days * 24 + $diff->h;
                                    $min = $diff->i;
                                    if ($hour === 0)
                                        $str = "{$min} minutes";
                                    else
                                        $str = "{$hour} hour {$min} minutes";


                                @endphp
                                <div class="row">
                                    <div class="col-4"><label>Published</label></div>
                                    <div class="col-8">{{$str}} ago</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                @php
                                    $tmp = [];
                                    foreach ($publish->formats as $fmt){ $tmp[] = $fmt->name;}
                                    $str = implode(', ', $tmp);
                                @endphp
                                <div class="row">
                                    <div class="col-3"><label>Format</label></div>
                                    <div class="col-9">{{ empty($str) ? 'Undefined' : $str }}</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                @php
                                    $tmp = [];
                                    foreach ($publish->fabrics as $fmt){ $tmp[] = $fmt->name;}
                                    $str = implode(', ', $tmp);
                                @endphp
                                <div class="row">
                                    <div class="col-3"><label>Fabrics</label></div>
                                    <div class="col-9">{{ empty($str) ? 'Undefined' : $str }}</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                @php
                                    $tmp = [];
                                    foreach ($publish->technics as $fmt){ $tmp[] = $fmt->name;}
                                    $str = implode(', ', $tmp);
                                @endphp
                                <div class="row">
                                    <div class="col-3"><label>Technic</label></div>
                                    <div class="col-9">{{empty($str) ? 'Undefined' : $str }}</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                @php
                                    $counts = \App\Models\Offer::where('request_id', $publish->id)->count();
                                @endphp
                                <div class="row">
                                    <div class="col-3"><label>Offers</label></div>
                                    <div class="col-9">{{$counts}}</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="row">
                                    <div class="col-3"><label>Status</label></div>
                                    <div class="col-9">{{$pstatus}}</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="row">
                                    <div class="col-3">
                                        <button type="button" class="btn btn-info" id="description" onclick="seeDescription()">Description</button>
                                        <div class="modal fade" id="descriptionModal" role="dialog" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header text-center">
                                                        <h4 class="modal-title text-center"><b>Description</b></h4>
                                                    </div>
                                                    <div class="modal-body">
                                                        {{$publish['description']}}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                @if(in_array($pstatus, ['accepted', 'delivered', 'in mediation']))
                <div class="card mt-5" id="deliveryCard">
                    <div class="card-header">
                        <div class="card-title">Accepted Offer</div>
                        <?php //dd($publish->accepted_at); ?>
                        <div class="card-subtitle">accepted at {{$publish->accepted_at}}</div>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="row">
                                    <div class="col-3"><label>Deadline</label></div>
                                    <div class="col-9">{{$offer->hours}}</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="row">
                                    @php
                                        $top_id = \App\Models\Settings::count();
                                        if ($top_id <> 0) {
                                            $settings = \App\Models\Settings::limit($top_id)->get();
                                            $setting = $settings[count($settings) - 1];
                                            $client_fee = $setting['client_fee'];
                                        }
                                    @endphp
                                    <div class="col-3"><label>Price</label></div>
                                    <div class="col-9">USD {{intval($offer->price + $client_fee)}}</div>
                                </div>
                            </div>
                        </div>
                        @if($pstatus !== 'published')
                        <div class="row">
                            <div class="col-12 my-3">
                                <div class="card-subtitle">Delivered Correction</div>
                            </div>
                            @empty($publish->deliveries)
                                <div class="alert alert-info">There is no delivered design.</div>
                            @endempty

                            @foreach($publish->deliveries as $key => $delivery)
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="row">
                                    <div class="col-3"><label>File{!! $key + 1 !!}</label></div>
                                    <div class="col-9">
                                        <a class="btn btn-primary"
                                           href="{{url('client/delivery-download/'.$delivery->id)}}">
                                            Download
                                        </a>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        <div class="row mt-3">
                            <div class="col-12 text-center">
                                @if($pstatus === 'in mediation')
                                    <a class="btn btn-success" href="{{url('client/complete-request/'.$publish->id)}}">Complete</a>
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif
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
        .btn-download {
            position: absolute;
            bottom: 0;
            right: 0;
            border-radius: 50%;
            height: 41px;
            width: 41px;
        }
        label{
            color: #777;
            font-size: 0.8rem;
        }
    </style>
@endsection

@section('js')


    <script>

        function show(para) {
            document.getElementById('request_id').value = para;
            document.getElementById('btn_modal').click();
        }

        function seeDescription() {
            $('#descriptionModal').modal();

        }


    </script>

@endsection

