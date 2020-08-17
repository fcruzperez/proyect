@extends('layouts.client')

@section('content')

    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="text-align:center;">
                        <span class="card-title" style="font-size: 25px;">Mediate</span>
                    </div>

                    <div class="card-body">
                        <div class="alert alert-warning" >
{{--                            This is a mediation on <b>{{$offer->designer->name}}</b>'s Offer #{{$offer->id}}--}}
{{--                            for your Publish <b>#{{$offer->request->id}} {{$offer->request->name}}</b>.--}}
                            You are requesting mediation for the design <b>"{{$offer->request->design_name}}".</b>
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
                            <div class="col-md-12 text-center offset-9">
                                <a class="btn btn-primary" href="{{route('client.mediate.list')}}">
                                    {{ __('Back') }}
                                </a>
                            </div>
                        </div>
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
