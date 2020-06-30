@extends('layouts.client')

<?php
//dd(($publish->id));
?>

@section('content')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="text-align:center;"><h2>Publish</h2></div>

                    <div class="card-body">
                        <form method="POST" action="{{route('client.update_publish')}}" id="publish_form" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">{{ __('Image1:') }}</label>

                                <div class="col-md-6">

                                    <div class="row">
                                        <div class="col-12">
                                            <div class="image-wrap">
                                                @if($publish->image1)
                                                    <img src="{{url($publish->image1)}}" class="image">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <input type="file" accept="image/*" id="image1" class="form-control-file"
                                                   name="image1" value="{{$publish->image1}}" autofocus>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">{{ __('Image2:') }}</label>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="image-wrap">
                                                @if($publish->image2)
                                                    <img src="{{url($publish->image2)}}" class="image">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <input type="file" accept="image/*" id="image2" class="form-control-file"
                                                   name="image2" value="{{$publish->image2}}" autofocus>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">{{ __('Image3:') }}</label>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="image-wrap">
                                                @if($publish->image3)
                                                    <img src="{{url($publish->image3)}}" class="image">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <input type="file" accept="image/*" class="form-control-file"
                                                   name="image3" value="{{$publish->image3}}" autofocus>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <input type="hidden" id="request_id" name="request_id" value="{{$publish->id}}" />

                            <div class="form-group row">
                                <label class="col-md-4 col-form-label text-md-right">{{ __('Image4:') }}</label>

                                <div class="col-md-6">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="image-wrap">
                                                @if($publish->image4)
                                                    <img src="{{url($publish->image4)}}" class="image">
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <input type="file" accept="image/*" class="form-control-file"
                                                   name="image4" value="{{$publish->image4}}" autofocus>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="name"
                                       class="col-md-4 col-form-label text-md-right">{{__('Name:')}}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                           value="{{$publish['name']}}" name="name" placeholder="Design Name" autofocus>
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
                                            @php
                                                $str = '12345';
                                                $count = count($publish->formats);
                                                for ($i = 0; $i < $count; $i++){
                                                    $str = $str . $publish->formats[$i]->name . ',';
                                                }
                                            @endphp
                                            <option @if((strpos($str, $format->name)) > 0) selected @endif>{{$format->name}}</option>
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
                                           value="{{$publish['width']}}" name="width" placeholder="mm">
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
                                           value="{{$publish['height']}}" name="height" placeholder="mm">
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
                                            @php
                                                $str = '12345';
                                                $count = count($publish->fabrics);
                                                for ($i = 0; $i < $count; $i++){
                                                    $str = $str . $publish->fabrics[$i]->name . ',';
                                                }
                                            @endphp
                                            <option @if((strpos($str, $fab->name)) > 0) selected @endif>{{$fab->name}}</option>
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
                                           value="{{$publish['hours']}}" name="hours" placeholder="hours">
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
                                            @php
                                                $str = '12345';
                                                $count = count($publish->technics);
                                                for ($i = 0; $i < $count; $i++){
                                                    $str = $str . $publish->technics[$i]->name . ',';
                                                }
                                            @endphp
                                            <option @if((strpos($str, $tech->name)) > 0) selected @endif>{{$tech->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <input type="hidden" name="technics" id="technics">
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-9">
                                    <button class="btn btn-primary" onclick="post()">
                                        {{ __('UPDATE') }}
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
        .image {
        object-fit: contain;
        object-position: center;
        width: 100%;
        }
    </style>

@endsection

@section('js')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <script>
        $(document).ready(function(){
            $('#format_select').selectpicker();
        })

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
