<?php

namespace App\Http\Controllers\Client;

use App\Events\AdminEvent;
use App\Events\DesignerEvent;
use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Mediate;
use App\Models\Message;
use App\Models\Offer;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\Settings;
use App\Services\MailService;
use Illuminate\Support\Facades\Config;
//use PayPal\Api\Amount;
//use PayPal\Api\Item;
//use PayPal\Api\ItemList;
//use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\PaymentExecution;
//use PayPal\Api\RedirectUrls;
//use PayPal\Api\Transaction;
//use PayPal\Auth\OAuthTokenCredential;
//use PayPal\Rest\ApiContext;

use Srmklive\PayPal\Services\ExpressCheckout;

class ClientController extends Controller
{
    protected $mailService;
    private $apiContext;

//    public function __construct(MailService $mailService)
//    {
//        $this->mailService = $mailService;
//
//        /** PayPal api context **/
//        $paypalConfig = Config::get('paypal');
//        $this->apiContext = new ApiContext(new OAuthTokenCredential(
//                $paypalConfig['client_id'],
//                $paypalConfig['secret'])
//        );
//        $this->apiContext->setConfig($paypalConfig['settings']);
//    }

    public function savePublish(Request $request)
    {

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'image1' => 'required',
//            'image2' => 'file|size:10',
//            'image3' => 'file|size:10',
//            'image4' => 'file|size:10',
//            'image5' => 'file|size:10',
            'design_name' => 'required',
            'unit' => 'required',
            'format' => 'required',
            'width' => 'required|integer',
            'height' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $publish_data = $request->except(['format', 'formats', 'technic', 'technics', 'image1', 'image2', 'image3', 'image4', 'image5', 'fabric', 'fabrics']);

        $publish_data['client_id'] = Auth::id();

        $imageNames = ['image1', 'image2', 'image3', 'image4', 'image5'];

        foreach ($imageNames as $imgname) {
            if ($request->hasFile($imgname)) {
                $storageName = $request->file($imgname)->store('public/images');
                $publish_data[$imgname] = str_replace('public/', 'storage/', $storageName);
//                dd($publish_data[$imgname]);

//                $fileName = time() . '_' . $request->file($imgname)->getClientOriginalName();
//
//                $destination = base_path() . '/public/uploads';
//                if (!file_exists($destination)) {
//                    mkdir($destination, 0777);
//                }
//                $request->file($imgname)->move($destination, $fileName);
                if (strtoupper(substr(PHP_OS, 0, 3)) <> 'WIN') {
//                    $filePath = '/uploads/' . $fileName;
//                } else {
                    $fileName = str_replace('storage/', '', $storageName);
                    $filePath = '/laravel/storage/app/' . $fileName;
                    $publish_data[$imgname] = $filePath;
                }

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
            'design_name' => 'required',
            'format' => 'required',
            'width' => 'required|integer',
            'height' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $rid = $inputs['request_id'];
//        dd($rid);
        $old_publish = Publish::find($rid);
        $imageNames = ['image1', 'image2', 'image3', 'image4', 'image5'];

        foreach ($imageNames as $imgname) {
            if ($request->hasFile($imgname)) {
                $storageName = $request->file($imgname)->store('public/images');
                $old_publish[$imgname] = str_replace('public/', 'storage/', $storageName);
//                $fileName = time() . '_' . $request->file($imgname)->getClientOriginalName();
//
//                $destination = base_path() . '/public/uploads';
//                if (!file_exists($destination)) {
//                    mkdir($destination, 0777);
//                }
//                $request->file($imgname)->move($destination, $fileName);
//                if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
//                    $filePath = '/uploads/' . $fileName;
//                } else {
//                    $filePath = '/laravel/public/uploads/' . $fileName;
//                }
//
//                $old_publish[$imgname] = $filePath;
                if (strtoupper(substr(PHP_OS, 0, 3)) <> 'WIN') {
//                    $filePath = '/uploads/' . $fileName;
//                } else {
                    $fileName = str_replace('storage/', '', $storageName);
                    $filePath = '/laravel/storage/app/' . $fileName;
                    $publish_data[$imgname] = $filePath;

                }

            }
        }

        $old_publish->design_name = $inputs['design_name'];
        $old_publish->unit = $inputs['unit'];
        $old_publish->width = $inputs['width'];
        $old_publish->height = $inputs['height'];
        $old_publish->description = $inputs['add_info'];

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

        return redirect('/client/myposts');

    }

    public function showNewPublish(Request $request)
    {
        $formats = Format::get();
        $fabrics = Fabric::get();
        $technics = Technic::get();

        $data = ['technics' => $technics, 'formats' => $formats, 'fabrics' => $fabrics];

        return view('pages.client.publish.new', $data);
    }

    public function showPublishes(Request $request)
    {

        $publishes = Publish::where('status', 'published')->get();

        $data = ['publishes' => $publishes];

        return view('pages.client.home', $data);
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

//    public function showWithdraw(Request $request)
//    {
////        $technics = Technic::get();
////        $formats = Format::get();
////        $fabrics = Fabric::get();
////        $data = ['technics' => $technics, 'formats' => $formats, 'fabrics' => $fabrics];
////
////        return view('pages.client.publish.new', $data);
////    }
//    }


    public function deletePublish(Request $request, $id) {

        $publish = Publish::find($id);
        $publish->delete();
        return redirect()->to('client/myposts');
    }

    public function listMyPosts(Request $request)
    {

        $userId = Auth::id();
        $publishes = Publish::where('client_id', $userId)->get();

        $data = ['publishes' => $publishes];
        return view('pages.client.myposts', $data);

    }

//    public function showDeposit(Request $request)
//    {
//
//        $inputs = $request->all();
//        $validator = Validator::make($inputs, [
//            'request_id' => 'required',
//            'offer_id' => 'required',
//        ]);
//        if ($validator->fails()) {
//            return back()->withErrors($validator);
//        }
//        $request_id = $inputs['request_id'];
//        $design_name = Publish::find($request_id)['design_name'];
//        $inputs['design_name'] = $design_name;
//        return view('pages.client.paypal.show_deposit', $inputs);
//    }

##### Exercise without payment #####
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
        $publish = Publish::find($request_id);
        $publish['status'] = 'accepted';
        $publish['accepted_offer_id'] = $inputs['offer_id'];
        $publish['accepted_at'] = date('Y-m-d H:i:s');
        $publish->save();

        $offer = Offer::find($inputs['offer_id']);
        $offer['status'] = 'accepted';
        $offer->save();

        return back();
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


    public function deposit(Request $request, $offer_id)
    {
        $offer = Offer::find($offer_id);
        if (!$offer) {
            return back()->withErrors(['errors' => 'wrong offer id']);
        }

        $publish = $offer->request;

        $top_id = Settings::count();
        if ($top_id <> 0) {
            $settings = Settings::limit($top_id)->get();
            $setting = $settings[count($settings) - 1];
            $client_fee = $setting['client_fee'];
        }
        $client_fee = isset($client_fee) ? $client_fee : 0;
        $depositMoney = intval($offer->price + $client_fee);

        $data = [];
        $data['invoice_id'] = 1;
        $data['invoice_description'] = "Order #{$data['invoice_id']} Invoice";
        $data['return_url'] = route('payment.success');
        $data['cancel_url'] = route('payment.cancel');
        $data['total'] = $depositMoney;

        $provider = new ExpressCheckout;
        $response = $provider->setExpressCheckout($data);
        $response = $provider->setExpressCheckout($data, true);

        return redirect($response['paypal_link']);

    }

    public function cancel($id)
    {
        $publish = Publish::find($id);

        $publish['status'] = 'canceled';

        $publish->save();
        return back();

    }



    public function cancelPayment(Request $request) {

        echo 'Sorry your payment is canceled';
        return back();
    }

    public function successPayment(Request $request) {

        $provider = new ExpressCheckout;
        $response = $provider->getExpressCheckoutDetails($request->token);

        if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
            echo 'Your payment was successful. You can create success page here.';
            return redirect('/client/posts');
        }

        dd('Something is wrong');

    }


//        try {
//            $payer = new Payer();
//            $payer->setPaymentMethod('paypal');
//
//            $items = [];
//            $item = new Item();
//            $item->setName($publish->name)
//                ->setCurrency('USD')
//                ->setQuantity(1)
//                ->setPrice($depositMoney);
//
//            array_push($items, $item);
//
//            $itemList = new ItemList();
//            $itemList->setItems($items);
//
//            $amount = new Amount();
//            $amount->setCurrency('USD')->setTotal($depositMoney);
//
//            $transaction = new Transaction();
//            $transaction->setAmount($amount)
//                ->setItemList($itemList)
//                ->setDescription("Deposit for Design publish #{$publish->id} {$publish->name}");
//
//            $redirectUrls = new RedirectUrls();
//            $redirectUrls->setReturnUrl(url('client/deposit-status?success=true'))
//                ->setCancelUrl(url('client/deposit-status?success=false'));
//
//            $payment = new Payment();
//            $payment->setIntent('Sale')
//                ->setPayer($payer)
//                ->setRedirectUrls($redirectUrls)
//                ->setTransactions(array($transaction));
//
//
//            if (!empty($this->apiContext)) {
//                $payment->create($this->apiContext);
//            }
//
//
//            foreach ($payment->getLinks() as $link) {
//                if($link->getRel() == 'approval_url') {
//                    $redirectUrl = $link->getHref();
//                    break;
//                }
//            }
//
//            if(isset($redirectUrl)) {
//                return Redirect::away($redirectUrl);
//            }
//        } catch (\Exception $e) {
//            return back()->withErrors(['error' => $e->getMessage()]);
//        }

    public function depositStatus(Request $request)
    {
        try {
            $dataArray = $request->all();

            if ($dataArray['success'] == 'true') {
                if (empty($dataArray['PayerID']) || empty($dataArray['token'])) {
                    Session::put('alert-danger', 'There was a problem processing your payment');
                    return Redirect::route('client.home');
                }
                $payment = Payment::get($dataArray['paymentId'], $this->apiContext);
                $execution = new PaymentExecution();
                $execution->setPayerId($dataArray['PayerID']);

                /** Execute the payment **/
                $result = $payment->execute($execution, $this->apiContext);

                /** Put success message in session and redirect home **/
                if ($result->getState() == 'approved') {

                    $offer_id = Session::get('payment_offer_id');
                    Session::forget('payment_offer_id');

                    $now = now();
                    $offer = Offer::find($offer_id);
                    $offer->accepted_at = $now;
                    $offer->status = 'accepted';
                    $offer->save();

                    $request = $offer->request;
                    $request->status = 'accepted';
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

                    $error = $this->mailService->queue('emails.progress_start', $params, $receiver);

                    // send push notification
                    $content = "Your offer for {$request->name} was accepted.
        You must attach the embroidery matrix {$format} within {$offer->hours}.
        See details.";

                    $message = Message::create([
                        'user_id' => $offer->designer_id,
                        'offer_id' => $offer->id,
                        'subject' => 'You have new order!',
                        'action_url' => "/designer/offer-detail/{$offer->id}",
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
                }

                /** Put error message in session and redirect home **/
                Session::put('alert-danger', 'Payment failed');
                return Redirect::route('client.home');
            }
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
            return back()->withErrors(['error' => $e->getMessage()]);
        }
//        }
//
//        Session::forget('payment_offer_id');
//        return view('pages.client.paypal.cancel', ['flag' => 'error']);
    }

    public function publishDetail(Request $request, $id)
    {

        $publish = Publish::find($id);

        $offer = Offer::find($publish->accepted_offer_id);
//        dd($offer);

        if ($request->has('message_id')) {
            $message = Message::find($request->get('message_id'));
            $message->status = 'read';
            $message->save();
        }

        $data = [
            'publish' => $publish,
            'offer' => $offer,
        ];
        return view('pages.client.publish.detail', $data);
    }

    public function seeCorrection(Request $request, $id)
    {

        $publish = Publish::find($id);

        $offer = Offer::find($publish->accepted_offer_id);
//        dd($offer);

        if ($request->has('message_id')) {
            $message = Message::find($request->get('message_id'));
            $message->status = 'read';
            $message->save();
        }

        $data = [
            'publish' => $publish,
            'offer' => $offer,
        ];
        return view('pages.client.publish.correction', $data);
    }




    public function downloadDelivery(Request $request, $id)
    {
        $delivery = Delivery::find($id);

        if (Storage::exists($delivery->path)) {
            return Storage::download($delivery->path);
        }
        return response('', 404);
    }

    public function completeRequest(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $publish = Publish::find($id);

            $now = now();
            $publish->status = 'completed';
            $publish->completed_at = $now;
            $publish->save();

            $offer = Offer::find($publish->accepted_offer_id);
            $offer_id = $offer['id'];
            $offer->status = 'completed';
            $offer->completed_at = $now;
            $offer->save();

            $mediate = Mediate::where('offer_id', $offer_id)->get();
            if (isset($mediate)) {
                $mediate['status'] = 'completed';
                $mediate->save();
            }

            $message = Message::create([
                'user_id' => $offer->designer_id,
                'offer_id' => $offer->id,
                'request_id' => $publish->id,
                'subject' => 'Your offer has been completed!',
                'content' => "Your offer #{$offer->id} for {$request->name} has been completed.",
                'action_url' => "/public/designer/offer-detail/{$offer->id}",
            ]);

            $payload = [
                'user_id' => $offer->designer_id,
                'action_url' => "/public/designer/offer-detail/{$offer->id}?message_id={$message->id}",
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

    public function financeList(Request $request)
    {

        return back();
    }


}


