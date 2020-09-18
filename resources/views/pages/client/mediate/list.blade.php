@extends('layouts.client')

@section('content')

    <div class="container" style="font-family: Arial, Helvetica, sans-serif">
        <div class="row">
            <div class="col-12">
                <table id="mediates_table" class="table table-striped table-bordered text-center">
                    <thead>
                    <tr>
                        <th>Time</th>
                        <th>Publish Name</th>
                        <th>Offer Hours</th>
                        <th>Offer Price</th>
                        <th>Status</th>
                        <th>Title</th>
                        <th>Detail</th>
                        {{--                <th>Edit</th>--}}
                        {{--                <th>Cancel</th>--}}
                        <th>Complete</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($mediates as $mediate)
                        <?php

                            $now = new DateTime();
                            $pp = new DateTime($mediate->created_at);
                            $diff = $now->diff($pp);
                            $h = $diff->days * 24 + $diff->h;

                            $offer_id = $mediate['offer_id'];
                            $offer = \App\Models\Offer::find($offer_id);
                            $mstatus = $mediate->status;
                            $publish = $offer->request;
                            $designer_id = $offer['designer_id'];
                            $designer = \App\Models\User::find($designer_id);
                            $designer_name = $designer['name'];

                            $client_id = $publish['client_id'];
                            $client = \App\Models\User::find($client_id);
                            $client_name = $client['name'];


                            $top_id = \App\Models\Settings::count();
                            $settings = \App\Models\Settings::limit($top_id)->get();
                            $setting = $settings[count($settings) - 1];
                            $client_fee = $setting['client_fee'];
                            $delta_time = $setting['delta_time'];
                            $correction_time = $setting['correction_time'];

                            $time = $offer['hours'] + $delta_time;
                            $price = $offer['price'] + $client_fee;

                            if ($mediate['status'] === 'issued' && $h >= $correction_time) {

                                $msg1 = "Designer {$designer_name} hasn't sent the correction about the {$publish->design_name} of Client {$client_name}.";

                                $message = \App\Models\Message::create([
                                    'user_id' => 1,
                                    'request_id' => $publish->id,
                                    'offer_id' => $offer_id,
                                    'subject' => $msg1,
                                    'content' => $msg1,
                                    'action_url' => "/admin/mediation/",
                                ]);

                                $data1 = [
                                    'user_id' => 1,
                                    'action_url' => "/admin/mediation/",
                                    'message' => $msg1
                                ];

                                event(new \App\Events\AdminEvent($data1));


                                $msg2 = "You haven't delivered the correction about the design {$publish->design_name} within correction time. Wait for the result of Support.";

                                $message = \App\Models\Message::create([
                                    'user_id' => $designer_id,
                                    'request_id' => $publish->id,
                                    'offer_id' => $offer_id,
                                    'subject' => $msg2,
                                    'content' => $msg2,
                                    'action_url' => "/designer/mediate-list",
                                ]);

                                $data2 = [
                                    'user_id' => $designer_id,
                                    'action_url' => "/designer/mediate-list",
                                    'message' => $msg2
                                ];
                                event(new \App\Events\DesignerEvent($data2));


                                $msg3 = "Designer hasn't delivered the correction about your design {$publish->design_name} within correction time, Wait for the result of Support.";

                                $message = \App\Models\Message::create([
                                    'user_id' => $client_id,
                                    'request_id' => $publish->id,
                                    'offer_id' => $offer_id,
                                    'subject' => $msg3,
                                    'content' => $msg3,
                                    'action_url' => "/client/mediate-list",
                                ]);

                                $data3 = [
                                    'user_id' => $client_id,
                                    'action_url' => "/client/mediate-list",
                                    'message' => $msg3
                                ];
                                event(new \App\Events\ClientEvent($data3));

                                $mediate['status'] = 'rejected';
                                $mediate->save();


                            }
                        ?>
                        <tr>
                            <td>{{$mediate->created_at}}</td>
                            <td>
                                <a href="{{url("client/publish-detail/{$publish->id}")}}">
                                    {{$publish->design_name}}
                                </a>
                            </td>
                            <td>{{$time}}</td>
                            <td>{{$price}}</td>
                            <td>
                                 <span class="p-1 text-light @if($mstatus ==='issued' || $mstatus ==='redelivered' || $mstatus === 'rejected') bg-danger @else bg-success @endif">
                                    {{$mediate->status_label}}
                                </span>
                            </td>
                            <td>{{$mediate->title}}</td>
                            <td>
                                <a class="btn btn-info" href="{{url("client/mediate-detail/{$mediate->id}")}}">Detail</a>
                            </td>
                            {{--                <td>--}}
                            {{--                    <a class="btn btn-primary" href="{{url("client/mediate-edit/{$mediate->id}")}}">Edit</a>--}}
                            {{--                </td>--}}
                            {{--                <td>--}}
                            {{--                    <a class="btn btn-warning" href="{{url("client/mediate-cancel/{$mediate->id}")}}">Cancel</a>--}}
                            {{--                </td>--}}
                            <td>
                                @if ($mediate['status'] === 'issued')
                                    <a class="btn btn-success" onclick="return confirm('Really?')" href="{{url("client/mediate-complete/{$mediate->id}")}}">Complete</a>
                                @endif
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
@endsection


@section('js')
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#mediates_table').DataTable();
        } );

        $('#mediates_table').dataTable( {
            "language": {
                "emptyTable": "There are no mediations"
            }
        } );
    </script>
@endsection
