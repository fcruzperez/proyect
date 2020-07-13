<?php

namespace App\Http\Controllers\Client;

use App\Events\DesignerEvent;
use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Mediate;
use App\Models\Messages;
use App\Models\Offer;
use Illuminate\Http\Request;
use App\Models\Request as Publish;
use App\Models\Technic;
use App\Models\Format;
use App\Models\Fabric;
use App\Models\RequestFabric;
use App\Models\RequestFormat;
use App\Models\RequestTechnic;
use App\Models\RequestImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Srmklive\PayPal\Services\ExpressCheckout;
use App\Services\MailService;

class ClientController extends Controller
{
    /**
     * @var ExpressCheckout
     */
    protected $paypal;
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
        $this->paypal = new ExpressCheckout();
    }

    public function savePublish(Request $request)
    {

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'image1' => 'required',
            'name' => 'required',
            'width' => 'required|integer',
            'height' => 'required|integer',
            'hours' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $publish_data = $request->except(['format', 'formats', 'technic', 'technics', 'image1', 'image2', 'image3', 'image4', 'fabric', 'fabrics']);

        $publish_data['client_id'] = Auth::id();

        $imageNames = ['image1', 'image2', 'image3', 'image4'];

        foreach ($imageNames as $imgname) {
            if ($request->hasFile($imgname)) {
                $storageName = $request->file($imgname)->store('public/images');
                $publish_data[$imgname] = str_replace('public/', 'storage/', $storageName);
            }
        }

        $new_publish = null;
        $new_publish = Publish::create($publish_data);

        $request_id = $new_publish->id;


        if (!is_null($inputs['formats'])) {
            $format_ids = explode(',', $inputs['formats']);
            foreach ($format_ids as $fid) {
                RequestFormat::create([
                    'request_id' => $request_id,
                    'format_id' => $fid,
                ]);
            }
        }

        if (!is_null($inputs['technics'])) {
            $technic_ids = explode(',', $inputs['technics']);

            foreach ($technic_ids as $tid) {
                RequestTechnic::create([
                    'request_id' => $request_id,
                    'technic_id' => $tid,
                ]);
            }
        }

        if (!is_null($inputs['fabrics'])) {
            $fabric_ids = explode(',', $inputs['fabrics']);

            foreach ($fabric_ids as $id) {
                RequestFabric::create([
                    'request_id' => $request_id,
                    'fabric_id' => $id,

                ]);
            }
        }

        return redirect('/client/home');
    }

    public function updatePublish(Request $request)
    {
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
//            'image1' => 'required',
            'name' => 'required',
            'width' => 'required|integer',
            'height' => 'required|integer',
            'hours' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $rid = $inputs['request_id'];
        $old_publish = Publish::find($rid);
        $imageNames = ['image1', 'image2', 'image3', 'image4'];

        foreach ($imageNames as $imgname) {
            if ($request->hasFile($imgname)) {
                $storageName = $request->file($imgname)->store('public/images');
                $old_publish[$imgname] = str_replace('public/', 'storage/', $storageName);
            }
        }

        $old_publish->name = $inputs['name'];
        $old_publish->width = $inputs['width'];
        $old_publish->height = $inputs['height'];
        $old_publish->hours = $inputs['hours'];

        $old_publish->save();


        RequestFormat::where('request_id', $rid)->delete();

        if (!is_null($inputs['formats'])) {

            $format_ids = explode(',', $inputs['formats']);
            foreach ($format_ids as $fid) {
                RequestFormat::create([
                    'request_id' => $rid,
                    'format_id' => $fid
                ]);
            }
        }


        RequestTechnic::where('request_id', $rid)->delete();

        if (!is_null($inputs['technics'])) {

            $technic_ids = explode(',', $inputs['technics']);

            foreach ($technic_ids as $tid) {
                RequestTechnic::create([
                    'request_id' => $rid,
                    'technic_id' => $tid,
                ]);
            }
        }


        RequestFabric::where('request_id', $rid)->delete();

        if (!is_null($inputs['fabrics'])) {

            $fabric_ids = explode(',', $inputs['fabrics']);
            foreach ($fabric_ids as $id) {
                RequestFabric::create([
                    'request_id' => $rid,
                    'fabric_id' => $id,

                ]);
            }
        }

        return redirect('/client/home');

    }

    public function showNewPublish(Request $request)
    {
        $formats = Format::get();
        $fabrics = Fabric::get();
        $technics = Technic::get();

        $data = ['technics' => $technics, 'formats' => $formats, 'fabrics' => $fabrics];

        return view('pages.client.publish.new', $data);
    }

    public function showUpdatePublish($rid)
    {
        $old = Publish::find($rid);
        $formats = Format::get();
        $fabrics = Fabric::get();
        $technics = Technic::get();

        $data = ['publish' => $old, 'technics' => $technics, 'formats' => $formats, 'fabrics' => $fabrics];

        return view('pages.client.publish.update', $data);
    }

    public function showWithdraw(Request $request)
    {
//        $technics = Technic::get();
//        $formats = Format::get();
//        $fabrics = Fabric::get();
//        $data = ['technics' => $technics, 'formats' => $formats, 'fabrics' => $fabrics];
//
//        return view('pages.client.publish.new', $data);
//    }
    }

    public function showPublishes(Request $request)
    {

        $userId = Auth::id();
        $publishes = Publish::where('client_id', $userId)->get();

        $data = ['publishes' => $publishes];
        return view('pages.client.home', $data);

    }

    public function showDeposit(Request $request)
    {

        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'request_id' => 'required',
            'offer_id' => 'required',
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }
        $request_id = $inputs['request_id'];
        $name = Publish::find($request_id)['name'];
        $inputs['name'] = $name;
        return view('pages.client.paypal.show_deposit', $inputs);
    }

    public function acceptBid(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'request_id' => 'required|unique:requests,id',
            'offer_id' => 'required|unique:offers,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'wrong parameters'], 400);
        }

        $publish = Publish::find($request->get($inputs['request_id']));
        $publish->status = '';
        $publish->save();

        return response()->json(['message' => 'success']);
    }

    public function cancel($id)
    {
        $publish = Publish::find($id);

        $publish['status'] = 'cancelled';

        $publish->save();
        return back();

    }

    public function deposit(Request $request, $offer_id)
    {
        $offer = Offer::find($offer_id);
        if (!$offer) {
            return back()->withErrors(['errors' => 'wrong offer id']);
        }
        $request = $offer->request;

        Session::put('payment_offer_id', $offer_id);
        return redirect(route('client.deposit.success'));
        /*
        $data = [];
        $deposit_money = round($offer->price * 1.1);

        $data['items'] = [
            [
                'name' => env('APP_NAME'),
                'price' => $deposit_money,
                'desc'  => 'Payment '. $deposit_money . 'for task "'. $request->name.'"',
                'qty' => 1
            ]
        ];

        $data['invoice_id'] = $request->id;
        $data['invoice_description'] = "Order #{$request->id} Invoice";
        $data['return_url'] = route('client.deposit.success');
        $data['cancel_url'] = route('client.deposit.cancel');
        $data['total'] = $deposit_money;

        $response = $this->paypal->setExpressCheckout($data);

//        $response = $provider->setExpressCheckout($data, true);  // for recurring payment(subscription)
        Session::put('payment_offer_id', $offer->id);

        return redirect($response['paypal_link']);
        */
    }

    /**
     * Responds with a welcome message with instructions
     */
    public function deposit_cancel()
    {
        Session::forget('payment_offer_id');
        return view('pages.client.paypal.cancel', ['flag' => 'cancel']);
    }

    /**
     * Responds with a welcome message with instructions
     */
    public function deposit_success(Request $request)
    {
//        $response = $this->paypal->getExpressCheckoutDetails($request->token);
//
//        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
        $offer_id = Session::get('payment_offer_id');
        Session::forget('payment_offer_id');

        $now = now();
        $offer = Offer::find($offer_id);
        $offer->accepted_at = $now;
        $offer->status = 'accepted';
        $offer->save();

        $request = $offer->request;
        $request->status = 'in production';
        $request->deposit = $offer->price;
        $request->accepted_offer_id = $offer->id;
        $request->accepted_at = $now;
        $request->save();

        // email notification
        $tmp = [];
        foreach ($request->formats as $fmt) {
            $tmp[] = "in {$fmt->name} format";
        }
        $format = implode(' and ', $tmp);

        $params = [
            'task_name' => $request->name,
            'format' => $format,
            'left_time' => $offer->hours
        ];
        $receiver = [
            'from' => config('mail.from.address'),
            'to' => $offer->designer->email,
            'subject' => 'You have new order!'
        ];

        $error = $this->mailService->send('emails.progress_start', $params, $receiver);

        // send push notification
        $content = "Your offer for {$request->name} was accepted.
        You must attach the embroidery matrix {$format} within {$offer->hours}.
        See details.";

        $message = Messages::create([
            'user_id' => $offer->designer_id,
            'offer_id' => $offer->id,
            'subject' => 'You have new order!',
            'content' => $content,
        ]);

        $data = [
            'user_id' => $offer->designer_id,
            'action_url' => "/designer/offer-detail/{$offer->id}?message_id={$message->id}",
            'message' => 'You have new order!',
        ];

        event(new DesignerEvent($data));

        return view('pages.client.paypal.success', [
            'request' => $request,
            'left_time' => $offer->hours,
        ]);
//        }
//
//        Session::forget('payment_offer_id');
//        return view('pages.client.paypal.cancel', ['flag' => 'error']);
    }

    public function publishDetail(Request $request, $id) {
        $publish = Publish::find($id);
        $offer = Offer::find($publish->accepted_offer_id);

        if($request->has('message_id')) {
            $message = Messages::find($request->get('message_id'));
            $message->status = 'read';
            $message->save();
        }

        $data = [
            'publish' => $publish,
            'offer' => $offer,
        ];
        return view('pages.client.publish.detail', $data);
    }

    public function downloadDelivery(Request $request, $id) {
        $delivery = Delivery::find($id);

        if(Storage::exists($delivery->path)) {
            return Storage::download($delivery->path);
        }
        return response('', 404);
    }

    public function completeRequest(Request $request, $id) {
        DB::beginTransaction();

        try{
            $publish = Publish::find($id);

            $now = now();
            $publish->status = 'completed';
            $publish->completed_at = $now;
            $publish->save();

            $offer = Offer::find($publish->accepted_offer_id);
            $offer->status = 'completed';
            $offer->completed_at = $now;
            $offer->save();

            $message = Messages::create([
                'user_id' => $offer->designer_id,
                'offer_id' => $offer->id,
                'request_id' => $publish->id,
                'subject' => 'Your offer has been completed!',
                'content' => "Your offer #{$offer->id} for {$request->name} has been completed."
            ]);

            $payload = [
                'user_id' => $offer->designer_id,
                'action_url' => "/designer/offer-detail/{$offer->id}?message_id={$message->id}",
                'message' => 'Your offer has been completed!'
            ];
            event(new DesignerEvent($payload));
        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error($e->getMessage());
            return back()->withErrors(['complete error' => $e->getMessage()]);
        }
        DB::commit();

        return back()->with(['complete_success' => 'ok']);
    }
}


