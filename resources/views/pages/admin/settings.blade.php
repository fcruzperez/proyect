@extends('layouts.admin')

@section('content')
    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="row">

            <!---Format Settings--->
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Formats</h4>
                        <button type="button" class="btn btn-primary float-right" style="margin-bottom: -5px; margin-right: -10px;" data-toggle="modal" data-target="#newFormatModal">
                            Add Format
                        </button>
                    </div>
                    <div class="card-body">

                        <!-- Modal -->
                        <div class="modal fade" id="newFormatModal" tabindex="-1" role="dialog" aria-labelledby="formatModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="{{route('admin.format.new')}}" method="post">
                                        @csrf
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="exampleModalLabel">Add Format</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" name="format"/>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table>
                                <tbody id="formatTable">
                                @foreach($formats as $format)
                                    <tr>
                                        <td class="text-center col-8">
                                            {{$format->name}}
                                        </td>
                                        <td class="col-4">

                                            <button class="btn btn-info" style="margin-bottom: 3px;" onclick="editFormat('{{$format->id}}','{{$format->name}}')">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <a class="btn btn-danger" href="{{url("admin/format_delete/{$format->id}")}}">
                                                <i class="fa fa-remove"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="modal fade" id="editFormatModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="{{route('admin.format.update')}}" method="post">
                                        @csrf
                                        <div class="modal-header">
                                            <h5>Edit Format</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="format_id" id="format_id"/>
                                            <input type="text" name="format_name" id="format_name"/>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <!---Fabric Settings--->
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Fabrics</h4>
                        <button type="button" class="btn btn-primary float-right" style="margin-bottom: -5px; margin-right: -10px;" data-toggle="modal" data-target="#newFabricModal">
                            Add Fabric
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="modal fade" id="newFabricModal" tabindex="-1" role="dialog" aria-labelledby="fabricModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="{{route('admin.fabric.new')}}" method="post">
                                        @csrf
                                        <div class="modal-header">
                                            <h5>Add Fabric</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" name="fabric"/>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table>
                                <tbody id="fabricTable">
                                @foreach($fabrics as $fabric)
                                    <tr>
                                        <td class="text-center col-8">
                                            {{$fabric->name}}
                                        </td>
                                        <td class="col-4">
                                            <button class="btn btn-info" style="margin-bottom: 3px;" onclick="editFabric('{{$fabric->id}}', '{{$fabric->name}}')">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <a class="btn btn-danger" onclick="return confirm('Will you delete this, really?')" href="{{url('admin/fabric_delete/'.$fabric->id)}}">
                                                <i class="fa fa-remove"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="modal fade" id="editFabricModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="{{route('admin.fabric.update')}}" method="post">
                                        @csrf
                                        <div class="modal-header">
                                            <h5>Edit Fabric</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="fabric_id" id="fabric_id"/>
                                            <input type="text" name="fabric_name" id="fabric_name"/>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!---Technic Settings--->
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <h4 class="text-center">Technics</h4>
                        <button type="button" class="btn btn-primary float-right" style="margin-bottom: -5px; margin-right: -10px;" data-toggle="modal" data-target="#newTechnicModal">
                            Add Technic
                        </button>
                    </div>
                    <div class="card-body">
                        <div class="modal fade" id="newTechnicModal" tabindex="-1" role="dialog" aria-labelledby="technicModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="{{route('admin.technic.new')}}" method="post">
                                        @csrf
                                        <div class="modal-header">
                                            <h5>Add Technic</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="text" name="technic"/>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table>
                                <tbody id="technicTable">
                                @foreach($technics as $technic)
                                    <tr>
                                        <td class="text-center col-8">
                                            {{$technic->name}}
                                        </td>
                                        <td class="col-4">
                                            <button class="btn btn-info" style="margin-bottom: 3px;" onclick="editTechnic('{{$technic->id}}', '{{$technic->name}}')">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <a class="btn btn-danger" onclick="return confirm('Will you delete this, really?')" href="{{url('admin/technic_delete/'.$technic->id)}}">
                                                <i class="fa fa-remove"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>

                        <div class="modal fade" id="editTechnicModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="{{route('admin.technic.update')}}" method="post">
                                        @csrf
                                        <div class="modal-header">
                                            <h5>Edit Technic</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="technic_id" id="technic_id"/>
                                            <input type="text" name="technic_name" id="technic_name"/>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12" style="margin-top: 50px;">
                <form method="POST" action="{{route('admin.other_settings')}}">
                    @csrf
                    <div class="card">
                        <div class="card-header text-center" style="font-size: 25px;">Other Settings</div>
                        <div class="card-body">
                            <table class="table table-responsive text-center">
                                <tr>
                                    <th>Client Fee (USD)</th>
                                    <th>Designer Fee (%)</th>
                                    <th>Minimum Work Time</th>
                                    <th>Minimum Work Price (USD)</th>
                                    <th>Delta Time</th>
                                    <th>Claim Time</th>
                                    <th>Correction Time</th>
                                    <th>Payment Time to Designer</th>
                                    <th>Minimum Withdrawal Amount (USD)</th>
                                    <th>Expiration Time</th>

                                </tr>
                                <tr>
{{--                                    <input type="number" id="client_fee" name="client_fee"/>--}}
{{--                                    <input type="number" id="designer_fee" name="designer_fee"/>--}}
{{--                                    <input type="number" id="minimum_work_time" name="minimum_work_time"/>--}}
{{--                                    <input type="number" id="minimum_work_price" name="minimum_work_price"/>--}}
{{--                                    <input type="number" id="delta_time" name="delta_time"/>--}}
{{--                                    <input type="number" id="claim_time" name="claim_time"/>--}}
{{--                                    <input type="number" id="correction_time" name="correction_time"/>--}}
{{--                                    <input type="number" id="payment_time_to_designer" name="payment_time_to_designer"/>--}}
{{--                                    <input type="number" id="minimum_withdrawal_amount" name="minimum_withdrawal_amount"/>--}}

                                    <td>
                                        <input type="number" id="client_fee" name="client_fee" value="{{$client_fee}}"/>
                                    </td>
                                    <td>
                                        <input type="number" id="designer_fee" name="designer_fee" value="{{$designer_fee}}"/>
                                    </td>
                                    <td>
                                        <input type="number" id="minimum_work_time" name="minimum_work_time" value="{{$minimum_work_time}}"/>
                                    </td>
                                    <td>
                                        <input type="number" id="minimum_work_price" name="minimum_work_price" value="{{$minimum_work_price}}"/>
                                    </td>
                                    <td>
                                        <input type="number" id="delta_time" name="delta_time" value="{{$delta_time}}"/>
                                    </td>
                                    <td>
                                        <input type="number" id="claim_time" name="claim_time" value="{{$claim_time}}"/>
                                    </td>
                                    <td>
                                        <input type="number" id="correction_time" name="correction_time" value="{{$correction_time}}"/>
                                    </td>
                                    <td>
                                        <input type="number" id="payment_time_to_designer" name="payment_time_to_designer" value="{{$payment_time_to_designer}}"/>
                                    </td>
                                    <td>
                                        <input type="number" id="minimum_withdrawal_amount" name="minimum_withdrawal_amount" value="{{$minimum_withdrawal_amount}}"/>
                                    </td>
                                    <td>
                                        <input type="number" id="expiration_time" name="expiration_time" value="{{$expiration_time}}"/>
                                    </td>


{{--                                    <td contenteditable="true" id="td_client_fee">{{$client_fee}}</td>--}}
{{--                                    <td contenteditable="true" id="td_designer_fee">{{$designer_fee}}</td>--}}
{{--                                    <td contenteditable="true" id="td_minimum_work_time">{{$minimum_work_time}}</td>--}}
{{--                                    <td contenteditable="true" id="td_minimum_work_price">{{$minimum_work_price}}</td>--}}
{{--                                    <td contenteditable="true" id="td_delta_time">{{$delta_time}}</td>--}}
{{--                                    <td contenteditable="true" id="td_claim_time">{{$claim_time}}</td>--}}
{{--                                    <td contenteditable="true" id="td_correction_time">{{$correction_time}}</td>--}}
{{--                                    <td contenteditable="true" id="td_payment_time_to_designer">{{$payment_time_to_designer}}</td>--}}
{{--                                    <td contenteditable="true" id="td_minimum_withdrawal_amount">{{$minimum_withdrawal_amount}}</td>--}}
                                </tr>
                            </table>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary float-right" id="save_settings" name="save_settings">Save</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@section('stylesheet')
    <style>
        table, th, td {
            border: 1px solid black;
        }
        td {
            padding: 5px;
        }

    </style>

@endsection

@section('js')

    <script>
        function editFormat(id, name) {
            $('#format_id').val(id);
            $('#format_name').val(name);
            $('#editFormatModal').modal('show');
        }

        function editFabric(id, name) {
            $('#fabric_id').val(id);
            $('#fabric_name').val(name);
            $('#editFabricModal').modal('show');
        }

        function editTechnic(id, name) {
            $('#technic_id').val(id);
            $('#technic_name').val(name);
            $('#editTechnicModal').modal('show');
        }

        $('#td_client_fee').blur(function () {
            var value = parseInt($(this).text());
            $('#client_fee').val(value);
        });

        $('#td_designer_fee').blur(function () {
            var value = parseInt($(this).text());
            $('#designer_fee').val(value);
        });
        $('#td_minimum_work_time').blur(function () {
            var value = parseInt($(this).text());
            $('#minimum_work_time').val(value);
        });
        $('#td_minimum_work_price').blur(function () {
            var value = parseInt($(this).text());
            $('#minimum_work_price').val(value);
        });
        $('#td_delta_time').blur(function () {
            var value = parseInt($(this).text());
            $('#delta_time').val(value);
        });
        $('#td_claim_time').blur(function () {
            var value = parseInt($(this).text());
            $('#claim_time').val(value);
        });
        $('#td_correction_time').blur(function () {
            var value = parseInt($(this).text());
            $('#correction_time').val(value);
        });
        $('#td_payment_time_to_designer').blur(function () {
            var value = parseIunt($(this).text());
            $('#payment_time_to_designer').val(value);
        });
        $('#td_minimum_withdrawal_amount').blur(function () {
            var value = parseInt($(this).text());
            $('#minimum_withdrawal_amount').val(value);
        });

    </script>

@endsection
