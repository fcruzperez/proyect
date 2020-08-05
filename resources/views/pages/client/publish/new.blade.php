@extends('layouts.client')

<?php
//    var_dump($errors);
?>

@section('content')

    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <b>
            <div style="color: blue; font-size: 21px;"><b>Note:</b></div>
            <div style="font-size: 20px; padding-bottom: 20px;">
                <div>• You can upload up to 5 images of the design you need.</div>
                <div>• The images must correspond to the same design.</div>
                <div>• Make sure that the images are of good quality so that you have better offers.</div>
                <div>• Do not add personal details (your name, email, phone number, etc.) to avoid blocking your account.</div>
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="text-align:center;"><h2><b>Publish</b></h2></div>

                    <div class="card-body">
                        <form method="POST" action="{{route('client.save_publish')}}" id="publish_form" enctype="multipart/form-data">
                            @csrf

                            <div class="form-group row">
                                <label for="image1"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Image1:') }}</label>

                                <div class="col-md-6">
                                    <input type="file" accept="image/*" id="image1" class="form-control-file  @error('image1') is-invalid @enderror"
                                           name="image1" autofocus>
                                    @error('image1')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
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
                                <label for="image5"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Image5:') }}</label>

                                <div class="col-md-6">
                                    <input type="file" accept="image/*" id="image5" class="form-control-file"
                                           name="image5" autofocus>
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="design_name"
                                       class="col-md-4 col-form-label text-md-right">{{__('Design Name:')}}</label>

                                <div class="col-md-6">
                                    <input id="design_name" type="text" class="form-control @error('name') is-invalid @enderror"
                                           value="{{old('design_name')}}" name="design_name" placeholder="Design Name" autofocus>
                                    @error('design_name')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="format"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Format:') }}</label>

                                <div class="col-md-6">

                                    <select id="format_select" name="format" class="selectpicker" style="width: 100%" multiple>
                                        @foreach($formats as $format)
                                            <option value="{{$format->id}}">{{$format->name}}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <input type="hidden" name="formats" id="formats">
                            </div>

                            <div class="form-group row" >
                                <label for="unit"
                                       class="col-md-4 col-form-label text-md-right" style="margin-top: -8px;">Unit:</label>
                                <div class="col-md-6">
                                    <div>
                                        <input id="unit_mm" type="radio" name="unit" checked value="mm" required>
                                        <label for="unit_mm" style="padding-right: 20px;" class="form-check-label">{{ __('mm') }}</label>
                                        <input id="unit_in" type="radio" name="unit" value="in" required>
                                        <label for="unit_in" class="form-check-label">{{ __('in') }}</label>
                                    </div>
                                </div>

                            </div>

                            </div>

                            <div class="form-group row" style="margin-top: -20px;">
                                <label for="width"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Width:') }}</label>

                                <div class="col-md-6">
                                    <input id="width" type="number" class="form-control @error('width') is-invalid @enderror"
                                           value="{{old('width')}}" name="width">

                                    @error('width')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                            </div>


                            <div class="form-group row">
                                <label for="height"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Height:') }}</label>

                                <div class="col-md-6">
                                    <input id="height" type="number" class="form-control @error('height') is-invalid @enderror"
                                           value="{{old('height')}}" name="height">
                                    @error('height')
                                        <div class="invalid-feedback d-block">
                                            {{ $message }}
                                        </div>
                                    @enderror
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
                                <label for="add_info"
                                       class="col-md-4 col-form-label text-md-right">{{ __('Addictional Details:') }}</label>

                                <div class="col-md-6">
                                    <textarea id="description" name="description" rows="5" cols="50" autofocus placeholder="Description"></textarea>
                                </div>
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
    <style>
        .bootstrap-select{
            width: 100% !important;
        }
    </style>




@endsection

@section('js')
{{--        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>--}}
{{--        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <script>
        $(document).ready(function(){
            $('#format_select').selectpicker();

        });

        function post() {
            var format_select = $('#format_select').selectpicker('val');
            var technic_select = $('#technic_select').selectpicker('val');
            var fabric_select = $('#fabric_select').selectpicker('val');

            if(format_select !== null) $('#formats').val(format_select.join());
            if(technic_select !== null) $('#technics').val(technic_select.join());
            if(fabric_select !== null) $('#fabrics').val(fabric_select.join());

            $('#form').submit();
        }
    </script>
@endsection
