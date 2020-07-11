<?php

namespace App\Http\Controllers\Designer;

use App\Events\ClientEvent;
use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Messages;
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
        $desinger_id = Auth::id();

//        $request_ids = Offer::where('designer_id', $desinger_id)->pluck('request_id')->toArray();
//
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

        $fee_rate = floatval(config('setting.designer_fee_rate'));
        $price = intval($inputs['bid_price']);
        $fee = $price * $fee_rate;
        $paid = $price - $fee;

        $data = [
            'designer_id' => Auth::id(),
            'request_id' => $inputs['request_id'],
            'price' => $price,
            'fee' => $fee,
            'paid' => $paid,
            'hours' => $inputs['bid_time'],
        ];

        Offer::create($data);

        return redirect('/designer/home');
    }

    public function cancelBid(Request $request, $id)
    {

    }

    public function offerDetail(Request $request, $id) {
        $offer = Offer::find($id);
        $publish = $offer->request;

        if($request->has('message_id')) {
            $message = Messages::find($request->get('message_id'));
            $message->status = 'read';
            $message->save();
        }

        return view('pages.designer.offer_detail', ['publish' => $publish, 'offer' => $offer]);
    }

    public function downloadImage($file) {
        if(Storage::exists('public/images/'.$file)) {
            return Storage::download('public/images/'.$file);
        }
        return response('', 404);
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
                $path = $file->store('public/delivery');
                Delivery::create([
                    'designer_id' => $designerId,
                    'request_id' => $publish->id,
                    'offer_id' => $offer->id,
                    'path' => $path,
                ]);
            }

            $now = now();
            $publish->status = 'delivered';
            $publish->delivered_at = $now;
            $publish->save();

            $offer->status = 'delivered';
            $offer->delivered_at = $now;
            $offer->save();

            // send notification to client
            $msg = "You have got a design of {$publish->name}!";
            $message = Messages::create([
                'user_id' => $publish->client_id,
                'request_id' => $publish->id,
                'subject' => $msg,
                'content' => $msg
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
}
