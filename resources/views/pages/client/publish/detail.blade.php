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

                                    if($hour === 0){
                                        $str = "{$min} minutes";
                                    }
                                    else {
                                        $str = "{$hour} hour {$min} minutes";

                                    }

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
                    <div class="card-footer text-center">

                        @if($pstatus === 'published' && $offer != null && $offer->status === 'sent')
                            <button type="button" class="btn btn-warning"
                                    data-toggle="modal" data-target="#cancelModal">Cancel</button>
                        @endif
                    </div>
                </div>

                @if(in_array($pstatus, ['accepted', 'undelivered', 'delivered', 'in mediation', 'completed']))
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
                        @if(in_array($pstatus, ['delivered', 'in mediation', 'completed']))
                        <div class="row">
                            <div class="col-12 my-3">
                                <div class="card-subtitle">Delivered Files</div>
                            </div>
                            @empty($publish->deliveries)
                                <div class="alert alert-info">There is no delivered design.</div>
                            @endempty

                            @foreach($publish->deliveries as $key => $delivery)
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="row" style="margin-bottom: 5px;">
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
                                @php
                                    $now = new DateTime();
                                    $pp = new DateTime($publish->delivered_at);
                                    $diff = $now->diff($pp);
                                    $hour = $diff->days * 24 + $diff->h;
                                    $min = $diff->i;


                                    $top_id = \App\Models\Settings::count();
                                    if ($top_id <> 0) {
                                        $settings = \App\Models\Settings::limit($top_id)->get();
                                        $setting = $settings[count($settings) - 1];
                                        $claim_time = $setting['claim_time'];
                                        $correction_time = $setting['correction_time'];
                                    }


                                @endphp
                                @if($pstatus === 'delivered')
                                    @if($hour < $claim_time)
                                        <a class="btn btn-danger mr-3" href="{{url('client/mediate-offer/'.$offer->id)}}">Mediate</a>
        {{--                                @endif--}}
        {{--                                @if($pstatus != 'in mediate' && $publish->deliverd_at)--}}
                                        <a class="btn btn-success" onclick="return confirm('Really?')" href="{{url('client/complete-request/'.$publish->id)}}">Complete</a>
                                    @else
                                        @php
                                            $msg = "Your design {$publish['design_name']} is completed because the claim time is passed.";
                                            $message = \App\Models\Message::create([
                                                'user_id' => $publish['client_id'],
                                                'request_id' => $publish->id,
                                                'offer_id' => $offer->id,
                                                'subject' => $msg,
                                                'content' => $msg,
                                                'action_url' => "/client/publish-detail/{$publish->id}",

                                            ]);

                                            $data = [
                                                'user_id' => $publish['client_id'],
                                                'action_url' => "/client/publish-detail/{$publish->id}",
                                                'message' => $msg
                                            ];
                                            event(new \App\Events\ClientEvent($data));



                                            $publish->status = 'completed';
                                            $publish->completed_at = $now;
                                            $publish->save();

                                            $top_id = \App\Models\Settings::count();
                                            $settings = \App\Models\Settings::limit($top_id)->get();
                                            $setting = $settings[count($settings) - 1];
                                            $designer_fee = $setting['designer_fee'];

                                            $offer = \App\Models\Offer::find($publish->accepted_offer_id);

                                            $paid = floatval(round($offer['price'] * (100 - $designer_fee) / 100, 2));
                                    //            dd($offer['price'], $paid); return;

                                            $offer_id = $offer['id'];
                                            $offer->status = 'completed';
                                            $offer->completed_at = $now;
                                            $offer->paid = $paid;
                                            $offer->save();


                                            //Add balance
                                            $designer_id = $offer['designer_id'];
                                            $designer = \App\Models\User::find($designer_id);
                                            $designer['balance'] += $paid;
                                            $designer->save();

                                            $msg1 = "Your offer for the design {$publish['design_name']} has been completed.";
                                            $message = \App\Models\Message::create([
                                                'user_id' => $designer_id,
                                                'request_id' => $publish->id,
                                                'offer_id' => $offer_id,
                                                'subject' => $msg1,
                                                'content' => $msg1,
                                                'action_url' => "/designer/finance-list",
                                            ]);

                                            $data1 = [
                                                'user_id' => $designer_id,
                                                'action_url' => "/designer/finance-list",
                                                'message' => $msg1
                                            ];
                                            event(new \App\Events\DesignerEvent($data1));

                                            $designerRate = \App\Models\DesignerRate::where('designer_id', $designer_id)->first();
                                            $rate = $designerRate['rate'];
                                            if (abs($rate) < 0.01) {
                                                $designerRate['rate'] = 5;
                                            }
                                            else {
                                                $designerRate['rate'] = round(($rate + 5) / 2, 1);
                                            }
                                            $designerRate->save();

                                        @endphp
                                    @endif
                                @endif
                                @if($pstatus === 'in mediation')
                                    @php
                                        $offer_id = $publish['accepted_offer_id'];
                                        $mediate = \App\Models\Mediate::where('offer_id', $offer_id)->first();

                                        $top_id = \App\Models\Settings::count();
                                        $settings = \App\Models\Settings::limit($top_id)->get();
                                        $setting = $settings[count($settings) - 1];
                                        $claim_time = $setting['claim_time'];

                                        $now = new DateTime();
                                        $redelivered_time = new DateTime($mediate['redelivered_at']);
                                        $diff = $now->diff($redelivered_time);
                                        $h = $diff->days * 24 + $diff->h;


                                    @endphp
                                    @if ($mediate['status'] === 'redelivered')
                                        @if($h > $claim_time)
    {{--                                    <a class="btn btn-success" style="float: right; margin-left: 5px;" href="{{url("client/mediate-complete/{$mediate->id}")}}">Complete</a>--}}
                                            <a class="btn btn-success" style="float: right; margin-left: 7px;" onclick="return confirm('Will you complete this, Really?')" href="{{url("client/mediate-complete/{$mediate->id}")}}">Complete</a>

                                            <form action="{{route('client.mediate.rejection')}}" method="post">
                                                @csrf
                                                <input type="hidden" name="publish_id" value="{{$publish->id}}">
                                                <input type="hidden" name="publish_name" value="{{$publish->design_name}}">
                                                <input type="hidden" name="offer_id" value="{{$offer->id}}">

                                                <button type="submit" class="btn btn-danger" style="float: right;" onclick="return(confirm('Will you reject this offer, really?'))">Rejection</button>
                                            </form>
                                        @else
                                            @php
                                                $msg = "Your design {$publish['design_name']} is completed because the claim time is passed.";

                                                $message = \App\Models\Message::create([
                                                    'user_id' => $publish['client_id'],
                                                    'request_id' => $publish->id,
                                                    'offer_id' => $offer->id,
                                                    'subject' => $msg,
                                                    'content' => $msg,
                                                    'action_url' => "/client/mediate-list",
                                                ]);

                                                $data = [
                                                'user_id' => $publish['client_id'],
                                                'action_url' => "/client/mediate-list",
                                                'message' => $msg
                                                ];
                                                event(new \App\Events\ClientEvent($data));


                                                $mediate->status = 'completed';
                                                $mediate->save();

                                                $now = now();

                                                $offer = $mediate->offer;
                                                $designer_id = $offer['designer_id'];

                                                $top_id = \App\Models\Settings::count();
                                                $settings = \App\Models\Settings::limit($top_id)->get();
                                                $setting = $settings[count($settings) - 1];
                                                $designer_fee = $setting['designer_fee'];
                                                $paid = floatval(round($offer['price'] * (100 - $designer_fee) / 100, 2));

                                                $offer->status = 'completed';
                                                $offer->completed_at = $now;
                                                $offer->paid = $paid;
                                                $offer->save();

                                                $request = $offer->request;
                                                $request->status = 'completed';
                                                $request->completed_at = $now;
                                                $request->save();

                                                $designerRate = \App\Models\DesignerRate::where('designer_id', $designer_id)->first();
                                                $rate = $designerRate['rate'];
                                                if (abs($rate) < 0.001) {
                                                    $designerRate['rate'] = 4.5;
                                                }
                                                else {
                                                    $designerRate['rate'] = round(($rate + 4.5) / 2, 1);
                                                }
                                                $designerRate->save();

                                                //Add balance
                                                $designer_id = $offer['designer_id'];
                                                $designer = \App\Models\User::find($designer_id);
                                                $designer['balance'] += $paid;
                                                $designer->save();

                                                $msg = "Your offer about the design {$request->design_name} is completed.";

                                                $message = \App\Models\Message::create([
                                                    'user_id' => $offer->designer_id,
                                                    'request_id' => $request->id,
                                                    'offer_id' => $offer->id,
                                                    'subject' => $msg,
                                                    'content' => $msg,
                                                    'action_url' => "/designer/finance-list",
                                                ]);

                                                $data = [
                                                    'user_id' => $offer->designer_id,
                                                    'action_url' => "/designer/finance-list",
                                                    'message' => $msg
                                                ];

                                                event(new \App\Events\DesignerEvent($data));

                                            @endphp
                                        @endif
                                    @endif
                                @endif
                            </div>
                        </div>
                        @endif
                    </div>
                    @if(in_array($pstatus, ['delivered', 'in mediation']))
                    <div class="card-footer">
                        <b>Note:</b> You can make mediation  within {{$claim_time}} hours. Then, designer will send you the correction within {{$correction_time}} hours.
                                     If the correction is not also in your mind, you can reject this. At this time, you must wait for the result of the Support.
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </div>

{{--    cancel modal--}}
    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Cancel offer</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Do you really want to cancel this offer?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <a class="btn btn-primary" href="{{url('/designer/offer-cancel/'.$offer['id'])}}">Confirm</a>
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
        // function unitChange(){
        //     var HH=document.getElementById('height').value;
        //     var WW=document.getElementById('width').value;
        //     var inch_HH=Number((HH/25.4).toFixed(1));
        //     var inch_WW=Number((WW/25.4).toFixed(1));
        //     var val=document.getElementById("unit").value;
        //     if(val==="mm"){
        //         document.getElementById("size").innerHTML = WW + " x " + HH + " mm";
        //     }
        //     else{
        //         document.getElementById("size").innerHTML = inch_WW + " x " + inch_HH + " inch";
        //     }
        // }

    </script>

@endsection

