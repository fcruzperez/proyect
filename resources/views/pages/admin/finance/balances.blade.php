@extends('layouts.admin')

@section('content')

    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="row">
            <div class="col-12">
                <table id="balances_table" class="table table-striped table-bordered text-center">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email Address</th>
                            <th>Paypal Address</th>
                            <th>Balance</th>
                            <th>Withdraw</th>
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td> {{$user['id']}} </td>
                            <td> {{$user['name']}} </td>
                            <td> {{$user['email']}} </td>
                            <td> {{$user['paypal_email']}} </td>
                            <td> {{$user['balance']}} </td>
                            <td>
                                <button type="button" class="btn btn-info text-center" data-toggle="modal" data-target = "#withdraw_button">Withdraw</button>

                                <div class="modal fade" id="withdraw_button" role="dialog" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <form method="post" action="{{route('admin.apply_withdraw')}}">
                                            @csrf
                                            <div class="modal-content">
                                                <div class="modal-header text-center">
                                                    <h4 class="modal-title text-center">Withdraw</h4>
                                                </div>
                                                <div class="modal-body text-left">
                                                    <div>
                                                        <label for="amount" style="font-size: 22px;">Amount</label>
                                                        <input type="number" name="withdraw_amount"/>
                                                        <input type="hidden" name="user_id" value="{{$user['id']}}"/>
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
                            </td>

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
{{--    <link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">--}}
@endsection


@section('js')

    <script src = https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js></script>
    <script src = https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js></script>

    <script>
        $(document).ready(function() {
            $('#balances_table').DataTable();
        });
    </script>
@endsection
