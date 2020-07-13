@extends('layouts.client')

@section('content')
<div class="col-12 col-md-4 offset-md-4">
    <div class="card">
        <div class="card-body">
            <h3 class="text-center">Thank you for your order!</h3>
            <div class="col-12">
                <p>
                    You will receive the matrix {{$request->name}} in less than {{$left_time}} hours.
                </p>
                <p>
                    Your number is #{{$request->id}}.
                </p>
            </div>
            <div class="col-12">
                <a class="btn btn-primary" href="{{route('client.home')}}">Home</a>
            </div>
        </div>
    </div>
</div>

@endsection
