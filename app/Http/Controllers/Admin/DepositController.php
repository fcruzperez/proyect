<?php
//
//namespace App\Http\Controllers\Admin;
//
//use App\Events\AdminEvent;
//use App\Events\DesignerEvent;
//use App\Models\Message;
//use App\Models\Offer;
//use App\Models\User;
//use App\Models\Request as Publish;
//use App\Models\Withdraw;
//use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Validator;
//
//class DepositController extends Controller
//{
//
//    public function __construct()
//    {
//
//    }
//
//    public function list() {
//        $publishes = Publish::get();
//
//        return view('pages.admin.deposit.list', ['publishes' => $publishes]);
//    }
//
//    public function detail(Request $request, $id) {
//        $publish = Publish::find($id);
//        $accepted = Offer::where('id', $publish->accepted_offer_id)->first();
//
//        if($request->has('message_id')) {
//            Message::find($request->get('message_id'))->update(['status' => 'read']);
//        }
//
//        return view('pages.admin.publish.detail', ['publish' => $publish, 'accepted' => $accepted]);
//    }
///*
//    public function refund(Request $request, $id) {
//        $publish = Publish::find($id);
//        $client = $publish->client->id;
//
//        try {
//            $now = now();
//            $withdraw->update([
//                'status' => 'paid',
//                'paid_at' => $now,
//            ]);
//            // send notification to designer
//            $msg = "Your withdraw request #{$withdraw->id} has been completed!";
//            $message = Message::create([
//                'user_id' => $designer,
//                'subject' => $msg,
//                'content' => $msg,
//                'action_url' => url("designer/withdraw-detail/{$withdraw->id}"),
//            ]);
//
//            $payload = [
//                'user_id' => $withdraw->designer_id,
//                'action_url' => url("designer/withdraw-detail/{$withdraw->id}?message_id={$message->id}"),
//                'message' => $msg,
//            ];
//            event(new DesignerEvent($payload));
//
//            return redirect(url("admin/withdraw-list"));
//        } catch (\Exception $e) {
//            return back()->withErrors(['complete error' => "We can not complete designer {$withdraw->designer->name}'s withdraw request #{$withdraw->id}."]);
//        }
//    }
//*/
//}
