@extends('layouts.admin')

@section('content')

    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="row">
            <div class="col-12">
                <table id="score_table" class="table table-striped table-bordered text-center">

                    <thead>
                        <tr>
                            <th>Designer_ID</th>
                            <th>Designer_Name</th>
                            <th>Score</th>
                            <th>Update</th>

                        </tr>
                    </thead>
                    @foreach($designer_ids as $designer_id)
                        <tbody>
                            <tr>
                                <td>{{ $designer_id }}</td>
                                <td>
                                    @php
                                        $designer_name = \App\Models\User::find($designer_id)['name'];
                                    @endphp
                                    {{ $designer_name }}
                                </td>
                                <td>
                                    @php
                                        $designer_rate = \App\Models\DesignerRate::where('designer_id', $designer_id)->get()[0]['rate'];
                                        if ($designer_rate == null) $designer_rate = 0;
                                    @endphp
                                    {{$designer_rate}}
{{--                                    @if ($designer_name->rates != null)--}}
{{--                                        {{  $designer_name->rates }}--}}
{{--                                    @endif--}}
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info" onclick="edit('{{$designer_id}}', '{{$designer_rate}}')">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                </td>
                            </tr>
                        </tbody>
                        <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <form action="{{route('admin.update_score')}}" method="post">
                                        @csrf
                                        <div class="modal-header">
                                            <h5>Update Score</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="designer_id" id="designer_id"/>
                                            <input type="text" name="designer_rate" id="designer_rate"/>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="submit" class="btn btn-primary">Save</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach


                </table>
            </div>
        </div>
    </div>

@endsection

@section('stylesheet')

    <link href="https://cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css" rel="stylesheet">


@endsection

@section('js')

    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script src="{{asset('plugins/raterjs/rater.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#score_table').DataTable();

            $('.rating').rate({
                max_value: 5,
                step_size: 0.1,
                readonly: true,
            });
        } );

        function edit(id, rate) {
            $('#designer_id').val(id);
            $('#designer_rate').val(rate);
            $('#editModal').modal('show');
        }
    </script>

@endsection
