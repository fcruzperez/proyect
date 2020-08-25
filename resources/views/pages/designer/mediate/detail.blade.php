@extends('layouts.designer')

@section('content')

    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="text-align:center;">
                        <span class="card-title text-center" style="font-size: 25px;">Mediate</span>
                    </div>

                    <div class="card-body">
                        <div class="alert alert-warning" >
{{--                            This is a mediation on <b>your Offer #{{$offer->id}}</b>--}}
{{--                            for <b>{{$offer->request->client->name}}</b>'s Publish <b>#{{$offer->request->id}} {{$offer->request->name}}</b>.--}}
                            This is a mediation for the design <b>{{$offer->request->design_name}}</b>
                        </div>
                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">{{ __('Title') }}</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="title" value="{{$mediate->title}}" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-4 col-form-label text-md-right">{{ __('Content') }}</label>
                            <div class="col-md-6">
                                <textarea readonly class="form-control" name="content">{{$mediate->content}}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6">
                                <a class="btn btn-primary" href="{{url('/designer/download_errors/'.$mediate->id)}}">Download Error Images</a>
                            </div>
                            <div class="col-md-6" style="float: right;">
                                <a class="btn btn-danger" href="{{route('designer.mediate.list')}}">
                                    {{ __('Back') }}
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="text-center" style="color: blue; font-size: 25px;">Note:</div>
                        <div>
                            @php
                            $top_id = \App\Models\Settings::count();
                            if ($top_id <> 0) {
                                $settings = \App\Models\Settings::limit($top_id)->get();
                                $setting = $settings[count($settings) - 1];
                                $correction_time = $setting['correction_time'];
                            }
                            @endphp
                            Correct the design based on customer feedback and test photos.
                            Send it before "<b>{{$correction_time}}</b> hours" to send the corrected files or you will be penalized.
                        </div>
                    </div>
                </div>
                @if($offer['status'] === 'mediated')
                    <div class="card mt-5" id="deliveryCard">
                        <div class="card-header text-center" style="font-size: 25px">Delivery  Correction</div>
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
@endsection

@section('stylesheet')
@endsection

@section('js')
@endsection
