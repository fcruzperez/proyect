@extends('layouts.admin')

@section('content')

    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="row">
            <div class="col-12">
                <table id="publishes_table" class="table table-striped table-bordered text-center">

                    <thead>
                        <tr>
                            <th>Time</th>
                            <th>Role</th>
                            <th>Name</th>
                            <th>Design Name</th>
                            <th>Amount</th>
                        </tr>
                    </thead>

                    <tbody>

                    @foreach($publishes as $publish)
                        <tr>
                            <td> {{$publish['completed_at']}}</td>
                            <td> Client </td>
                            <td>
                                @php
                                    $client_id = $publish['client_id'];
                                    $client= \App\Models\User::find($client_id);
                                    $client_name = $client['name'];
                                @endphp
                                {{$client_name}}
                            </td>
                            <td>
                                {{$publish['design_name']}}
                            </td>
                            <td>{{$publish['refund']}}</td>
                        </tr>
                    @endforeach

                    @foreach($offers as $offer)
                        <tr>
                            <td>{{$offer['completed_at']}}</td>
                            <td> Designer </td>
                            <td>
                                @php
                                    $designer_id = $offer['designer_id'];
                                    $designer = \App\Models\User::find($designer_id);
                                    $designer_name = $designer['name'];
                                @endphp
                                {{$designer_name}}
                            </td>
                            <td>{{$offer->request->design_name}}</td>
                            <td>{{$offer['paid']}}</td>

                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('stylesheet')
    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
@endsection


@section('js')

    <script src = https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js></script>
    <script src = https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js></script>

    <script>
        $(document).ready(function() {
            $('#publishes_table').DataTable();
        } );
    </script>
@endsection
