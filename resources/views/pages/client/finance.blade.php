@extends('layouts.client')

@section('content')

    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <h3 class="text-center"><b>Finances</b></h3>
        <div style="margin-bottom: 10px;">
            <label for="balance" style="font-size: 20px;"><b>Balance:</b></label>
            <input type="text" class="text-right" name="balance" style="font-size: 20px; width: 7%; text-align: left;" value="{{$balance}}" readonly>
            <b style="font-size: 20px;">USD</b>

            <button type="button" class="btn btn-info text-center" data-toggle="modal" data-target = "#withdraw_button">Withdraw</button>

            <div class="modal fade" id="withdraw_button" role="dialog" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form method="post" action="{{route('client.withdraw')}}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header text-center">
                                <h4 class="modal-title text-center">Withdraw</h4>
                            </div>
                            <div class="modal-body text-left">
                                <div>
                                    @php
                                        $user_id = \Illuminate\Support\Facades\Auth::id();

                                        $top_id = \App\Models\Settings::count();
                                        $settings = \App\Models\Settings::limit($top_id)->get();
                                        $setting = $settings[count($settings) - 1];
                                        $minimum_withdrawal_amount = $setting['minimum_withdrawal_amount'];
                                    @endphp
                                    <label for="amount" style="font-size: 22px;">Amount:</label>
                                    <input type="number" name="withdraw_amount"/>
                                    <input type="hidden" name="user_id" value="{{$user_id}}"/>
                                </div>
                                <div style="font-size: 15px;">
                                    <b>Note:</b> You can withdraw only when your balance is more than {{$minimum_withdrawal_amount}}USD.
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">&nbsp;&nbsp; OK &nbsp;&nbsp;</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
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
                            $amount = $publish['refund'];

                        @endphp

                        <tr>
                            <td>{{$publish['completed_at']}}</td>
                            <td>{{$design_name}}</td>
                            <td>{{$amount}}</td>

                        </tr>
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


