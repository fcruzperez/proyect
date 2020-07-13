@extends('layouts.designer')

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="text-align:center;">
                        <span class="card-title">Mediate</span>
                    </div>

                    <div class="card-body">
                        <div class="alert alert-warning" >
                            This is a mediation on <b>your Offer #{{$offer->id}}</b>
                            for <b>{{$offer->request->client->name}}</b>'s Publish <b>#{{$offer->request->id}} {{$offer->request->name}}</b>.
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
                            <div class="col-md-12 text-center">
                                <a class="btn btn-primary" href="{{route('designer.mediate.list')}}">
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
