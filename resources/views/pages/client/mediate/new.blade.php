@extends('layouts.client')

@section('content')

    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        @isset($success)
        <div class="alert alert-success">Your mediation has been successfully posted.</div>
        @endisset

        @error('db error')
        <div class="alert alert-warning">New Mediation couldn't be saved.</div>
        @enderror

        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="text-align:center;">
                        <span class="card-title" style="font-size: 25px;">Mediate</span>
                    </div>

                    <div class="card-body">
                        <div class="alert alert-warning">
                            You are requesting mediation for the design <b>"{{$offer->request->design_name}}".</b>

{{--                            You are posting mediation on <b>{{$offer->designer->name}}</b>'s Offer #{{$offer->id}}--}}
{{--                            for your Publish <b>#{{$offer->request->id}} {{$offer->request->name}}</b>.--}}
                        </div>
                        <form method="POST" action="{{route('client.mediate.save')}}" id="mediate_form" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" name="offer_id" value="{{$offer->id}}">
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">{{ __('Title:') }}</label>
                                <div class="col-md-6">
                                    <input type="text" id="title" class="form-control @error('title') is-invalid @enderror"
                                           name="title" value="{{old('title')}}" autofocus>
                                    @error('title')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">{{ __('Content:') }}</label>
                                <div class="col-md-6">
                                    <textarea id="content" class="form-control @error('content') is-invalid @enderror"
                                              name="content">{{old('content')}}</textarea>
                                    @error('content')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="image1"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Error Images:') }}</label>

                                <div class="col-md-6">
                                    <input type="file" id="error_images" name="error_images" class="form-control-file">
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-9">
                                    <button class="btn btn-primary" >
                                        {{ __('SEND') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer">
                        <div style="color: blue;"><b>Note:</b></div>
                        Specify the problems that you think should be corrected. Keep in mind that some problems are caused by the embroidery machine and mediation may not work in your favor. The designer will appreciate that you attach photos of the embroidery sample.
                        Do not use this medium to communicate with the designer because you risk blocking your account.
                        The designer will send the correction as soon as possible, but if you still consider that the problem was not solved, your case will be analyzed by a panel of Expert Designers who will determine if the work delivered meets your requirements.
                        Finally, the Mediator will determine the percentage of return corresponding to each part.
                        Add <b>one zip file</b> with evidence images of existing errors.
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('stylesheet')
@endsection

@section('js')
@endsection
