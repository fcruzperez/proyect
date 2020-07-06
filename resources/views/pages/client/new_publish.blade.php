@extends('layouts.client')

<?php
//    var_dump($errors->has('images'));
?>

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="text-align:center;"><h2>Publish</h2></div>

                    <div class="card-body">
                        <form method="POST" action="{{route('client.save_publish')}}" id="publish_form" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label for="image1"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Image1:') }}</label>

                                <div class="col-md-6">
                                    <input type="file" accept="image/*" id="image1" class="form-control-file  {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                           name="image1" autofocus>
                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback d-block">
                                            {{ $errors->first('image1') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="image2"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Image2:') }}</label>

                                <div class="col-md-6">
                                    <input type="file" accept="image/*" id="image2" class="form-control-file"
                                           name="image2" autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="image3"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Image3:') }}</label>

                                <div class="col-md-6">
                                    <input type="file" accept="image/*" id="image3" class="form-control-file"
                                           name="image3" autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="image4"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Image4:') }}</label>

                                <div class="col-md-6">
                                    <input type="file" accept="image/*" id="image4" class="form-control-file"
                                           name="image4" autofocus>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name"
                                       class="col-md-4 col-form-label text-md-right">{{__('Name:')}}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                           value="{{old('name')}}" name="name" placeholder="Design Name" autofocus>
                                    @if ($errors->has('name'))
                                        <div class="invalid-feedback d-block">
                                            {{ $errors->first('name') }}
                                        </div>
                                    @endif
                                </div>

                            </div>

                            <div class="form-group row">
                                <label for="format"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Format:') }}</label>

                                <div class="col-md-6">

                                    <select id="format_select" name="format" class="selectpicker" multiple>
                                        @foreach($formats as $format)
                                            <option value="{{$format->id}}">{{$format->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="formats" id="formats">
                            </div>

                            <div class="form-group row">
                                <label for="width"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Width:') }}</label>

                                <div class="col-md-6">
                                    <input id="width" type="number" class="form-control {{ $errors->has('width') ? 'is-invalid' : '' }}"
                                           value="{{old('width')}}" name="width" placeholder="mm">
                                    @if ($errors->has('width'))
                                        <div class="invalid-feedback d-block">
                                            {{ $errors->first('width') }}
                                        </div>
                                    @endif
                                </div>

                            </div>


                            <div class="form-group row">
                                <label for="height"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Height:') }}</label>

                                <div class="col-md-6">
                                    <input id="height" type="number" class="form-control {{ $errors->has('height') ? 'is-invalid' : '' }}"
                                           value="{{old('height')}}" name="height" placeholder="mm">
                                    @if ($errors->has('height'))
                                        <div class="invalid-feedback d-block">
                                            {{ $errors->first('height') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="fabric"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Fabric:') }}</label>

                                <div class="col-md-6">
                                    <select id="fabric_select" name="fabric" class="selectpicker" multiple>
                                        @foreach($fabrics as $fab)
                                            <option value="{{$fab->id}}">{{$fab->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="fabrics" id="fabrics">
                            </div>

                            <div class="form-group row">
                                <label for="hours"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Time:') }}</label>

                                <div class="col-md-6">
                                    <input id="hours" type="number" class="form-control {{ $errors->has('hours') ? 'is-invalid' : '' }}"
                                           value="{{old('hours')}}" name="hours" placeholder="hours">
                                    @if ($errors->has('hours'))
                                        <div class="invalid-feedback d-block">
                                            {{ $errors->first('hours') }}
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="technic"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Technic:') }}</label>

                                <div class="col-md-6">
                                    <select id="technic_select" name="technic" class="selectpicker" multiple>
                                        @foreach($technics as $tech)
                                            <option value="{{$tech->id}}">{{$tech->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="technics" id="technics">
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-9">
                                    <button class="btn btn-primary" onclick="post()">
                                        {{ __('POST') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('stylesheet')

@endsection

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <script>
        $(document).ready(function(){
            $('#format_select').selectpicker();
        });

        function post() {
            var format_select = $('#format_select').selectpicker('val');
            var technic_select = $('#technic_select').selectpicker('val');
            var fabric_select = $('#fabric_select').selectpicker('val');

            if(format_select !== null) $('#formats').val(format_select.join())
            if(technic_select !== null) $('#technics').val(technic_select.join());
            if(fabric_select !== null) $('#fabrics').val(fabric_select.join());

            $('#form').submit();
        }
    </script>
@endsection
