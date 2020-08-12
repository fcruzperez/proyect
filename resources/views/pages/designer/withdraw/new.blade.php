{{--@extends('layouts.designer')--}}

{{--@section('content')--}}

{{--    <div class="container" style="font-family: Arial, Helvetica, sans-serif">--}}
{{--        <div class="row justify-content-center">--}}
{{--            <div class="col-md-8">--}}
{{--                <div class="card">--}}
{{--                    <div class="card-header" style="text-align:center;">--}}
{{--                        <span class="card-title">New Withdraw</span>--}}
{{--                    </div>--}}

{{--                    <form method="POST" action="{{route('designer.withdraw.save')}}">--}}
{{--                        @csrf--}}
{{--                        <div class="card-body pt-3">--}}
{{--                            <div class="col-12">--}}
{{--                                <table id="offer_table" class="table table-bordered table-striped">--}}
{{--                                    <thead>--}}
{{--                                    <tr>--}}
{{--                                        <th>ID</th>--}}
{{--                                        <th>Name</th>--}}
{{--                                        <th>Price</th>--}}
{{--                                        <th>Fee</th>--}}
{{--                                        <th>Payment</th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}
{{--                                    @foreach($offers as $offer)--}}
{{--                                        <tr>--}}
{{--                                            <td>--}}
{{--                                                <label>--}}
{{--                                                    <input type="checkbox" name="offer_id[]" value="{{$offer->id}}" checked class="d-none">--}}
{{--                                                    {{$offer->id}}--}}
{{--                                                </label>--}}
{{--                                            </td>--}}
{{--                                            <td>{{$offer->request->name}}</td>--}}
{{--                                            <td>{{$offer->price}}</td>--}}
{{--                                            <td>{{$offer->fee}}</td>--}}
{{--                                            <td>{{$offer->paid}}</td>--}}
{{--                                        </tr>--}}
{{--                                    @endforeach--}}
{{--                                    </tbody>--}}
{{--                                    <tfoot>--}}
{{--                                    <tr>--}}
{{--                                        <td></td>--}}
{{--                                        <td></td>--}}
{{--                                        <td>--}}
{{--                                            <input type="hidden" name="total" value="{{$offers->sum('price')}}">--}}
{{--                                            {{$offers->sum('price')}}--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            <input type="hidden" name="fee" value="{{$offers->sum('fee')}}">--}}
{{--                                            {{$offers->sum('fee')}}--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            <input type="hidden" name="paid" value="{{$offers->sum('paid')}}">--}}
{{--                                            {{$offers->sum('paid')}}--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                    </tfoot>--}}
{{--                                </table>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                        <div class="card-footer text-center">--}}
{{--                            <button class="btn btn-primary"--}}
{{--                                    @if($offers->isEmpty()) disabled @endif--}}
{{--                                    id="submit" type="submit">--}}
{{--                                Request Payment--}}
{{--                            </button>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--@endsection--}}

{{--@section('stylesheet')--}}
{{--    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">--}}
{{--    <style>--}}
{{--        #submit {--}}
{{--            cursor: pointer;--}}
{{--        }--}}
{{--        #submit[disabled] {--}}
{{--            cursor: not-allowed;--}}
{{--        }--}}
{{--    </style>--}}
{{--@endsection--}}

{{--@section('js')--}}
{{--    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>--}}
{{--    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>--}}

{{--    <script>--}}
{{--        var offers = @json($offers);--}}
{{--        console.log(offers);--}}

{{--        $(document).ready(function () {--}}
{{--            $('#offer_table').DataTable();--}}
{{--        })--}}
{{--    </script>--}}

{{--@endsection--}}

