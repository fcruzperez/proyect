<?php

namespace App\Http\Controllers\Designer;

use App\Events\AdminEvent;
use App\Events\ClientEvent;
use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Mediate;
use App\Models\Message;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Request as Publish;
use App\Models\Technic;
use App\Models\Format;
use App\Models\Fabric;
use App\Models\RequestFabric;
use App\Models\RequestFormat;
use App\Models\RequestTechnic;
use App\Models\RequestImage;
use App\Models\Offer;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class DesignerController extends Controller
{

    public function home(Request $request)
    {

        $userId = Auth::id();
        $offers = Offer::where('designer_id', $userId)->get();

        $rate = User::find($userId)->rate;

        $data = ['offers' => $offers, 'rate' => $rate];
        return view('pages.designer.home', $data);
    }

    public function viewPosts(Request $request)
    {
        $designer_id = Auth::id();

//        $request_ids = Offer::where('designer_id', $designer_id)->pluck('request_id')->toArray();

//        if (!is_null($request_ids)) {
//            $data = Publish::where('status', 'published')->whereNotIn('id', $request_ids)->get();
//        } else {
//            $data = Publish::where('status', 'published')->get();
//        }
        $data = Publish::where('status', 'published')->get();


        return view('pages.designer.posts', ['publishes' => $data]);
    }

    public function saveBid(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'request_id' => 'required',
            'bid_price' => 'required|integer|min:1',
            'bid_time' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $top_id = Settings::count();
        if ($top_id <> 0) {
            $settings = Settings::limit($top_id)->get();
            $setting = $settings[count($settings) - 1];
        }
        $min_time = $setting['minimum_work_time'];
        $min_price = $setting['minimum_work_price'];
        if ($inputs['bid_price'] < $min_price || $inputs['bid_time'] < $min_time) {
            echo '<script>alert("To ensure the best conditions for both you and the client, you must keep minimum work time and price. Please see bid window again.")</script>';
            return back();
        }

//        $fee_rate = floatval(config('setting.designer_fee_rate'));

        $price = intval($inputs['bid_price']);
//        $price = intval($price * 1.1);
//        $fee = $price * 1.1;
//        $paid = $price - $fee;

        $data = [
            'designer_id' => Auth::id(),
            'request_id' => $inputs['request_id'],
            'price' => $price,
//            'fee' => $fee,
//            'paid' => $paid

            'hours' => $inputs['bid_time'],
        ];

        Offer::create($data);

        $publish = Publish::find($inputs['request_id']);

        $msg = "You have received an offer for your design {$publish->design_name}.";

        $message = Message::create([
            'user_id' => $publish->client_id,
            'request_id' => $publish->id,
//            'offer_id' => $offer->id,
            'subject' => $msg,
            'content' => $msg,
            'action_url' => "/client/myposts",
        ]);

        $data = [
            'user_id' => $publish->client_id,
            'action_url' => "/client/myposts",
            'message' => $msg
        ];

        event(new ClientEvent($data));

        return redirect('/designer/posts');
    }


    public function updateBid(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'request_id' => 'required',
            'bid_price' => 'required|integer|min:1',
            'bid_time' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

//        $fee_rate = floatval(config('setting.designer_fee_rate'));
        $request_id = $inputs['request_id'];
        $price = intval($inputs['bid_price']);
        $time = intval($inputs['bid_time']);
//        $price = intval($price * 1.1);

        $data = [
            'designer_id' => Auth::id(),
            'request_id' => $request_id,
            'price' => $price,
//            'fee' => $fee,
//            'paid' => $paid

            'hours' => $time,
        ];

        Offer::where('designer_id', Auth::id())->where('request_id', $request_id)->delete();
        Offer::create($data);

        return redirect('/designer/home');

    }

    public function cancelBid($rid) {

        $designer_id = Auth::user()->id;
        Offer::where('request_id', $rid)->where('designer_id', $designer_id)->delete();
        return redirect('/designer/home');

    }

    public function offerDetail(Request $request, $id) {
        $offer = Offer::find($id);
        $publish = $offer->request;

        if($request->has('message_id')) {
            $message = Message::find($request->get('message_id'));
            $message->status = 'read';
            $message->save();
        }

        return view('pages.designer.offer_detail', ['publish' => $publish, 'offer' => $offer]);
    }

    public function downloadImage($file) {
        if (strtoupper(substr(PHP_OS, 0, 3)) <> 'WIN') {
            if (file_exists('laravel/storage/app/public/images/' . $file))
                return response()->download('laravel/storage/app/public/images/' . $file);
        }
        else {
            if(Storage::exists('public/images/'.$file))
                return Storage::download('public/images/'.$file);
        }
        return response('', 404);
    }

    public function downloadErrors(Request $request, $id){

        $mediate = Mediate::find($id);
        $file = $mediate['error_images'];

        if (strtoupper(substr(PHP_OS, 0, 3)) <> 'WIN') {
            if (file_exists($file)) {
                return response()->download($file);
            }
            else {
                echo "No exist error images !";
            }
        }
        else {
            if(Storage::exists($file)) {
                return Storage::download($file);
            }
        }
        return redirect()->to('/designer/mediate-detail/'.$id);

    }

    public function deliveryUpload(Request $request) {

        $input = $request->all();

        $validator = Validator::make($input, [
            'offer_id' => 'required|exists:offers,id',
            'delivery_files' => 'required'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        $offer = Offer::find($input['offer_id']);
        $publish = $offer->request;
        $designerId = Auth::id();

        $files = $request->file('delivery_files');
        DB::beginTransaction();
        try {
            foreach ($files as $file) {
                $name = $file->getClientOriginalName();
                $path = $file->store('public/delivery');
                Delivery::create([
                    'designer_id' => $designerId,
                    'request_id' => $publish->id,
                    'offer_id' => $offer->id,
                    'path' => $path,
                ]);
            }

            $now = now();
            if ($publish->status <> 'in mediation') {
                $publish->status = 'delivered';
                $publish->delivered_at = $now;
                $publish->save();

                $offer->status = 'delivered';
                $offer->delivered_at = $now;
                $offer->save();
            }

            // send notification to client
            if ($publish->status <> 'in mediation') {
                $msg = "Your {$publish->design_name} design is finished.";
            }
            else {
                $msg = "Your {$publish->design_name} design is redelivered.";
            }

            $message = Message::create([
                'user_id' => $publish->client_id,
                'request_id' => $publish->id,
                'offer_id' => $offer->id,
                'subject' => $msg,
                'content' => $msg,
                'action_url' => "/client/publish-detail/{$publish->id}",
            ]);

            $data = [
                'user_id' => $publish->client_id,
                'action_url' => "/client/publish-detail/{$publish->id}?message_id={$message->id}",
                'message' => $msg
            ];
            event(new ClientEvent($data));

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error($e->getMessage());
            return back()->withErrors(['db error' => $e->getMessage()]);
        }
        DB::commit();

        return back()->with(['success' => 'OK']);
    }


    function financeList(Request $request) {

        $user_id = Auth::id();
        $user = User::find($user_id);
        $offers = Offer::where('designer_id', $user_id)->where('status', 'completed')->get();
        $balance = $user['balance'];
        $data = [
            'offers' => $offers,
            'balance' => $balance
        ];
        return view('pages.designer.finance', $data);
    }

    public function withdraw(Request $request) {

        $inputs = $request->all();

        $validator = Validator::make($inputs, [

            'withdraw_amount' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }



        $user_id = Auth::id();
        $amount = $inputs['withdraw_amount'];

        $top_id = Settings::count();
        $settings = Settings::limit($top_id)->get();
        $setting = $settings[count($settings) - 1];
        $minimum_withdrawal_amount = $setting['minimum_withdrawal_amount'];

        if ($amount < $minimum_withdrawal_amount) {
            $message = "Please see Note again!";
            echo "<script type='text/javascript'>alert('$message');</script>";
            return back();
        }

        $user = User::find($user_id);
        $name = $user['name'];

        $msg = "ID {$user_id}, {$name} is requesting withdraw the amount {$amount}USD.";

        $message = Message::create([
            'user_id' => 1,
            'subject' => $msg,
            'content' => $msg,
            'action_url' => "/admin/balances",
        ]);

        $data = [
            'user_id' => 1,
            'action_url' => "/admin/balances",
            'message' => $msg
        ];

        event(new AdminEvent($data));

        return back()->with(['success' => 'OK']);
    }


//    public function redeliveryUpload(Request $request) {
//        $input = $request->all();
//
//        $validator = Validator::make($input, [
//            'offer_id' => 'required|exists:offers,id',
//            'delivery_files' => 'required'
//        ]);
//
//        if($validator->fails()) {
//            return back()->withErrors($validator);
//        }
//
//        $offer = Offer::find($input['offer_id']);
//        $publish = $offer->request;
//        $designerId = Auth::id();
//
//        $files = $request->file('delivery_files');
//        DB::beginTransaction();
//        try {
//            foreach ($files as $file) {
//                $name = $file->getClientOriginalName();
//                $path = $file->storeAs('public/redelivery', $name);
//                Delivery::create([
//                    'designer_id' => $designerId,
//                    'request_id' => $publish->id,
//                    'offer_id' => $offer->id,
//                    'path' => $path,
//                ]);
//            }
//
////            $now = now();
////            $publish->status = 'delivered';
////            $publish->delivered_at = $now;
////            $publish->save();
//
////            $offer->status = 'delivered';
////            $offer->delivered_at = $now;
////            $offer->save();
//
//            // send notification to client
//            $msg = "Your {$publish->design_name} design is redelivered.";
//            $message = Message::create([
//                'user_id' => $publish->client_id,
//                'request_id' => $publish->id,
//                'subject' => $msg,
//                'content' => $msg,
//                'action_url' => "/client/correction/{$publish->id}",
//            ]);
//
//            $data = [
//                'user_id' => $publish->client_id,
//                'action_url' => "/client/correction/{$publish->id}",
//                'message' => $msg
//            ];
//            event(new ClientEvent($data));
//
//        } catch (\Exception $e) {
//            DB::rollBack();
//            logger()->error($e->getMessage());
//            return back()->withErrors(['db error' => $e->getMessage()]);
//        }
//        DB::commit();
//
//        return back()->with(['success' => 'OK']);
//    }
}
