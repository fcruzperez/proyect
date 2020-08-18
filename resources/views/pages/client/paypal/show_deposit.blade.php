@extends('layouts.client')

@section('content')
    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="text-align:center;"><h2>Payment</h2></div>
                    <div class="card-body">
                        <div class="name" style="font-size: 27px; font-weight:bold; margin-bottom: 20px;" >
                            <u>{{$name}}</u>
                        </div>
                        @php
                            $top_id = \App\Models\Settings::count();
                            if ($top_id <> 0) {
                                $settings = \App\Models\Settings::limit($top_id)->get();
                                $setting = $settings[count($settings) - 1];
                                $client_fee = $setting['client_fee'];
                            }
                        @endphp
                        <h5>We will proceed to make the payment for <b>{{intval((1 + $client_fee / 100) * $price)}}$</b> and you will receive your order
                            in a maximum time of <b>{{$time}}</b> hours from the payment.</h5>
                    </div>
                    <div class="card-footer">
                        <a href="{{route('client.home')}}">
                            <button type="button" class="btn btn-danger float-left">CANCEL</button>
                        </a>
                        <a href="{{url("/client/payment/{$offer_id}")}}" >
                            <button type="button" class="btn btn-primary float-right" style="text-align: right">&nbsp;&nbsp;&nbsp;PAY&nbsp;&nbsp;&nbsp;</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
