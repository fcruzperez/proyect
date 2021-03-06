<?php
//
//namespace App\Http\Controllers\Designer;
//
//use App\Events\AdminEvent;
//use App\Models\Message;
//use App\Models\Offer;
//use App\Models\User;
//use App\Models\Withdraw;
//use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Validator;
//
//class WithdrawController extends Controller
//{
//    public function __construct()
//    {
//    }
//
//    public function list() {
//        $withdraws = Withdraw::where('designer_id', Auth::id())->get();
//
//        return view('pages.designer.finance.list', ['withdraws' => $withdraws]);
//    }
//
//    public function new() {
//        $myId = Auth::id();
//        $offers = Offer::where('designer_id', $myId)
//            ->whereNull('withdraw_id')
//            ->where('status', 'completed')
//            ->get();
//
//        return view('pages.designer.finance.new', ['offers' => $offers]);
//    }
//
//    public function detail(Request $request, $id) {
//        $finance = Withdraw::find($id);
//        $offers = $finance->offers;
//
//        if($request->has('message_id')) {
//            Message::find($request->get('message_id'))->update(['status' => 'read']);
//        }
//
//        return view('pages.designer.finance.detail', ['finance' => $finance, 'offers' => $offers]);
//    }
//
//    public function save(Request $request) {
//        $input = $request->all();
//
//        $validator = Validator::make($input, [
//            'offer_id' => 'required|array',
//            'total' => 'required|numeric',
//            'fee' => 'required|numeric',
//            'paid' => 'required|numeric',
//        ]);
//
//        if($validator->fails()) {
//            return back()->withInput()->withErrors($validator);
//        }
//
//        $finance = Withdraw::create([
//            'designer_id' => Auth::id(),
//            'total' => $input['total'],
//            'fee' => $input['fee'],
//            'paid' => $input['paid'],
//            'pending_at' => now(),
//        ]);
//
//        $offer_ids = $request->get('offer_id');
//        Offer::whereIn('id', $offer_ids)->update(['withdraw_id' => $finance->id]);
//
//        // send notification to admin
//        $designer = Auth::user();
//        $msg = "Designer {$designer->name} sent finance request of \${$finance->paid}!";
//
//        $admins = User::where('role', 'admin')->get();
//
//        foreach ($admins as $admin) {
//            $message = Message::create([
//                'user_id' => $admin->id,
//                'subject' => $msg,
//                'content' => $msg,
//                'action_url' => url("admin/finance-detail/{$finance->id}"),
//            ]);
//
//            $payload = [
//                'user_id' => $admin->id,
//                'action_url' => url("admin/finance-detail/{$finance->id}?message_id=$message->id"),
//                'message' => $msg,
//            ];
//
//            event(new AdminEvent($payload));
//        }
//
//        return redirect(route('designer.finance.list'))->with(['new_success' => 'ok']);
//    }
//}
