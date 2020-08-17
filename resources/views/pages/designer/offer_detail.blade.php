@extends('layouts.designer')

@section('content')
    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        @if(isset($success))
        <div class="alert alert-success">Your design has been successfully delivered! </div>
        @endif

        @isset($errors)
            <ul>
                @foreach($errors->all() as $err)
                    <li>{{$err}}</li>
                @endforeach
            </ul>
        @endisset

        <div class="row">
            <div class="col-12" style="margin-top: 20px;">
                <div class="card" id="offerCard">
                    <div class="card-header text-center" style="font-size: 25px;">
                        Design Details
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-12 col-sm-6 col-lg-2 px-0 image-wrapper">
                                @if(!is_null($publish->image1))
                                    <img src="{{url($publish->image1)}}" class="image-box">
                                    <a class="btn btn-primary btn-download"
                                        @if (strtoupper(substr(PHP_OS, 0, 3)) <> 'WIN')
                                            href="{{url('designer/download-image/'.str_replace('laravel/storage/app/public/images/', '', $publish->image1))}}"
                                        @else
                                            href="{{url('designer/download-image/'.str_replace('storage/images/', '', $publish->image1))}}"
                                        @endif
                                    >
                                        <i class="fa fa-download"></i>
                                    </a>
                                @endif
                            </div>
                            <div class="col-12 col-sm-6 col-lg-2 px-0 image-wrapper">
                                @if(!is_null($publish->image2))
                                    <img src="{{url($publish->image2)}}" class="image-box">
                                    <a class="btn btn-primary btn-download"
                                       @if (strtoupper(substr(PHP_OS, 0, 3)) <> 'WIN')
                                           href="{{url('designer/download-image/'.str_replace('laravel/storage/app/public/images/', '', $publish->image2))}}">
                                       @endif
                                       href="{{url('designer/download-image/'.str_replace('storage/images/', '', $publish->image2))}}">


                                    href="{{url('designer/download-image/'.str_replace('storage/images/', '', $publish->image2))}}">
                                        <i class="fa fa-download"></i>
                                    </a>
                                @endif
                            </div>
                            <div class="col-12 col-sm-6 col-lg-2 px-0 image-wrapper">
                                @if(!is_null($publish->image3))
                                    <img src="{{url($publish->image3)}}" class="image-box">
                                    <a class="btn btn-primary btn-download"
                                       href="{{url('designer/download-image/'.str_replace('storage/images/', '', $publish->image3))}}">
                                        <i class="fa fa-download"></i>
                                    </a>
                                @endif
                            </div>
                            <div class="col-12 col-sm-6 col-lg-2 px-0 image-wrapper">
                                @if(!is_null($publish->image4))
                                    <img src="{{url($publish->image4)}}" class="image-box">
                                    <a class="btn btn-primary btn-download"
                                       href="{{url('designer/download-image/'.str_replace('storage/images/', '', $publish->image4))}}">
                                        <i class="fa fa-download"></i>
                                    </a>
                                @endif
                            </div>
                            <div class="col-12 col-sm-6 col-lg-2 px-0 image-wrapper">
                                @if(!is_null($publish->image5))
                                    <img src="{{url($publish->image5)}}" class="image-box">
                                    <a class="btn btn-primary btn-download"
                                       href="{{url('designer/download-image/'.str_replace('storage/images/', '', $publish->image5))}}">
                                        <i class="fa fa-download"></i>
                                    </a>
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
                                    <div class="col-5" id="size">{{$width}} x {{$height}} {{$publish['unit']}}</div>
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
                                    $str = $diff->format('%h hour %i minutes');
                                    $kkk = explode(' ', $str);
                                    $h = (int)$kkk[0];
                                    if($h === 0){
                                        $str = $diff->format('%i minutes');
                                    }
                                    else {
                                        $str = $diff->format('%h hour %i minutes');

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
                                    <div class="col-9">{{$publish->status}}</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="row">
                                    <div class="col-3"><label>My offer</label></div>
                                    <div class="col-9">{{$offer->price}}$ in {{$offer->hours}} hours</div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-4">
                                <div class="row">
                                    <button type="button" class="btn btn-primary" style="margin-left: 10px;" onclick="seeDescription()">Description</button>
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
                    <div class="card-footer text-center">

                        <a href="{{url('/designer/home/')}}">
                            <button type="button" class="btn btn-info">&nbsp;&nbsp;Back&nbsp;&nbsp;</button>
                        </a>
                        @if ($offer['status'] === 'sent' && $publish['status'] <> 'accepted')
                            <button type="button" class="btn btn-primary" onclick="show({{$publish->id}})">Update</button>
                            <form action="{{ route('designer.offer-cancel', $publish->id) }}" method="post">
                                @csrf
                                <button type="submit" class="btn btn-danger" onclick="return confirm('Will you cancel this post? Really?')">Cancel</button>
                            </form>
                        @endif
                    </div>
                </div>

{{--                Update Modal--}}
                <div class="modal fade" id="updateBidModal" role="dialog" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">

                        <form method="POST" action="{{route('designer.offer-update')}}" id="updateBid_modal_form">
                            @csrf
                            <div class="modal-content">
                                <div class="modal-header text-center">
                                    <h4 class="modal-title text-center">Bid</h4>
                                </div>
                                <div class="modal-body">

                                    <input type="hidden" name="request_id" id="request_id" >

                                    <div class="form-group row">
                                        <label for="bid_price" style="padding-left: 5px;">Price</label>
                                        <input type="number"  id="bid_price" class="form-control @error('bid_price') is-invalid @enderror" name="bid_price" min="1"
                                               value="{{old('bid_price')}}"  placeholder="$">
{{--                                        @error('bid_price')--}}
{{--                                        <div class="invalid-feedback d-block">--}}
{{--                                            {{ $message }}--}}
{{--                                        </div>--}}
{{--                                        @enderror--}}
                                    </div>
                                    <div class="form-group row">
                                        <label for="bid_time" style="padding-left: 5px;">Time</label>
                                        <input type="number" id="bid_time" class="form-control @error('bid_time') is-invalid @enderror"
                                               value="{{old('bid_time')}}" name="bid_time" min="1" placeholder="hours">
{{--                                        @error('bid_time')--}}
{{--                                        <div class="invalid-feedback d-block">--}}
{{--                                            {{ $message }}--}}
{{--                                        </div>--}}
{{--                                        @enderror--}}

                                    </div>

                                    <p>By clicking <strong>OK</strong> you agree to abide by the terms and conditions.</p>
                                </div>
                                <div class="modal-footer text-center">
                                    <div class="text-center">
                                        <a href="{{url('/designer/home/')}}">
                                            <button type="button" class="btn btn-danger">Cancel</button>
                                        </a>
                                        <button type="submit" class="btn btn-primary ml-3">&nbsp;&nbsp;&nbsp;OK&nbsp;&nbsp;&nbsp;</button>
                                    </div>
                                </div>
                            </div>
                        </form>

                    </div>
                </div>

                @if($offer['status'] === 'accepted')
                <div class="card mt-5" id="deliveryCard">
                    <div class="card-header text-center" style="font-size: 25px">Delivery</div>
                    <div class="card-body">
                        <form action="{{route('designer.delivery-upload')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <input type="hidden" name="offer_id" value="{{$offer->id}}">
                                    <input type="file" name="delivery_files[]" required multiple
                                           class="@error('delivery_files') is-invalid @enderror">
                                    @error('offer_id')
                                    <div class="invalid-feedback d-block">Offer Id is required</div>
                                    @enderror
                                    @error('delivery_files')
                                    <div class="invalid-feedback d-block">{{$message}}</div>
                                    @enderror
                                    @error('db error')
                                    db error
                                    <div class="invalid-feedback d-block">{{$message}}</div>
                                    @enderror
                                </div>
                                <div class="col-12 text-center">
                                    <input type="reset" class="btn btn-warning mr-2" value="Reset"/>
                                    <button type="submit" class="btn btn-success">Delivery</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

{{--    cancel modal--}}
{{--    <div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">--}}
{{--        <div class="modal-dialog" role="document">--}}
{{--            <div class="modal-content">--}}
{{--                <div class="modal-header">--}}
{{--                    <h5 class="modal-title" id="exampleModalLabel">Cancel offer</h5>--}}
{{--                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">--}}
{{--                        <span aria-hidden="true">&times;</span>--}}
{{--                    </button>--}}
{{--                </div>--}}
{{--                <div class="modal-body">--}}
{{--                    <p>Do you really want to cancel this offer?</p>--}}
{{--                </div>--}}
{{--                <div class="modal-footer">--}}
{{--                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>--}}
{{--                    <a class="btn btn-primary" href="{{url('/designer/offer-cancel/'.$offer->id)}}">Confirm</a>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}

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
        // function show(para) {
        //     document.getElementById('request_id').value = para;
        //     document.getElementById('btn_modal').click();
        // }

        function seeDescription() {
            $('#descriptionModal').modal();

        }

        function show(para) {
            $('#request_id').val(para);
            $('#updateBidModal').modal();

        }
    </script>

@endsection

