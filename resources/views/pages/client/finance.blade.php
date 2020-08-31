@extends('layouts.client')

@section('content')

    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <h3 class="text-center"><b>Finances</b></h3>
        <div style="margin-bottom: 10px;">
            <label for="balance" style="font-size: 20px;"><b>Balance:</b></label>
            <input type="text" class="text-right" name="balance" style="font-size: 20px; width: 7%; text-align: left;" value="{{$balance}}" readonly>
            <b style="font-size: 20px;">USD</b>
        </div>
        <div class="row">
            <div class="col-12">
                <table id="finance-table" class="table table-striped table-bordered text-center">

                    <thead>
                    <tr>
                        <th>Time</th>
                        <th>Design Name</th>
                        <th>Amount(USD)</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($publishes as $publish)
                        @php
                            $design_name = $publish['design_name'];
                            $offer_id = $publish['accepted_offer_id'];

                            $mediate = \App\Models\Mediate::where('offer_id', $offer_id)->first();
                            $amount = $publish['refund'];

                        @endphp
                        @if($mediate <> null && $mediate['status'] === 'rejected')
                            <tr>
                                <td>{{$publish['completed_at']}}</td>
                                <td>{{$design_name}}</td>
                                <td>{{$amount}}</td>

                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

@section('stylesheet')

    <link  href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">
@endsection

@section('js')

    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#finance-table').DataTable();
        });
    </script>

@endsection


