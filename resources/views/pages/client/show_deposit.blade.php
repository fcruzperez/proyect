@extends('layouts.client')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="text-align:center;"><h2>Payment</h2></div>
                    <div class="card-body">
                        <div class="name" style="font-size: 27px; font-weight:bold; margin-bottom: 20px;" >
                            <u>{{$name}}</u>
                        </div>
                        <h5>We will proceed to make the payment for {{$values['price']}}$ and you will receive your order
                            in a maximum time of {{$values['time']}} hours from the payment.</h5>
                    </div>
                    <div class="card-footer">
                        <a href="{{url('/client/paypal_deposit')}}" >
                            <button type="button" class="btn btn-primary float-right" style="text-align: right">&nbsp;&nbsp;&nbsp;PAY&nbsp;&nbsp;&nbsp;</button>
                        </a>
                        <a href="{{url('/client/home')}}">
                            <button type="button" class="btn btn-danger float-left">CANCEL</button>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
