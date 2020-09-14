<?php

namespace App\Http\Controllers\Client;

use App\Events\AdminEvent;
use App\Events\ClientEvent;
use App\Events\DesignerEvent;
use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\DesignerRate;
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
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Services\MailService;

class MediateController extends Controller
{
    protected $paypal;
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function list() {

        $mediates = Mediate::where('client_id', Auth::id())->get();

        return view('pages.client.mediate.list', ['mediates' => $mediates]);
    }

    public function detail($id) {
        $mediate = Mediate::find($id);
        $offer = $mediate->offer;

        return view('pages.client.mediate.detail', ['mediate' => $mediate, 'offer' => $offer]);
    }

    public function complete($id) {

        $mediate = Mediate::find($id);
//        dd($mediate);
        $mediate->status = 'completed';
        $mediate->save();

        $now = now();

        $offer = $mediate->offer;
        $designer_id = $offer['designer_id'];

        $top_id = \App\Models\Settings::count();
        $settings = \App\Models\Settings::limit($top_id)->get();
        $setting = $settings[count($settings) - 1];
        $designer_fee = $setting['designer_fee'];
        $paid = floatval(round($offer['price'] * (100 - $designer_fee) / 100, 2));

        $offer->status = 'completed';
        $offer->completed_at = $now;
        $offer->paid = $paid;
        $offer->save();

        $request = $offer->request;
        $request->status = 'completed';
        $request->completed_at = $now;
        $request->save();

        $designerRate = DesignerRate::where('designer_id', $designer_id)->first();
        $rate = $designerRate['rate'];
        if (abs($rate) < 0.001) {
            $designerRate['rate'] = 4.5;
        }
        else {
            $designerRate['rate'] = round(($rate + 4.5) / 2, 1);
        }
        $designerRate->save();

        //Add balance
        $designer_id = $offer['designer_id'];
        $designer = User::find($designer_id);
        $designer['balance'] += $paid;
        $designer->save();

        $msg = "Your offer about the design {$request->design_name} is completed.";

        $message = Message::create([
            'user_id' => $offer->designer_id,
            'request_id' => $request->id,
            'offer_id' => $offer->id,
            'subject' => $msg,
            'content' => $msg,
            'action_url' => "/designer/finance-list",
        ]);

        $data = [
            'user_id' => $offer->designer_id,
            'action_url' => "/designer/finance-list",
            'message' => $msg
        ];

        event(new DesignerEvent($data));

        return redirect(route('client.mediate.list'));
    }

    public function new(Request $request, $id) {

        $offer = Offer::find($id);
        return view('pages.client.mediate.new', ['offer' => $offer]);
    }

    public function save(Request $request) {

        $validator = Validator::make($request->all(), [
            'offer_id' => 'required|exists:offers,id',
            'title' => 'required',
            'content' => 'required',

        ]);

        if($validator->fails()) {
            return back()->withInput()->withErrors($validator);
        }

        $offer_id = $request->get('offer_id');
        try {
            $offer = Offer::find($offer_id);

            if ($request->hasFile('error_images')) {
                $storageName = $request->file('error_images')->store('public/error_images');
                $error_images = str_replace('public/', 'storage/', $storageName);
                if (strtoupper(substr(PHP_OS, 0, 3)) <> 'WIN') {
                    $fileName = str_replace('storage/', '', $storageName);
                    $filePath = 'laravel/storage/app/' . $fileName;
                    $error_images = $filePath;
                }
            }

            $mediate = Mediate::create([
                'client_id' => Auth::id(),
                'designer_id' => $offer->designer_id,
                'offer_id' => $offer_id,
                'title' => $request->get('title'),
                'content' => $request->get('content'),
                'error_images' => isset($error_images) ? $error_images : ''
            ]);

            $mediate_id = $mediate['id'];

            $now = now();

            $offer->status = 'mediated';
            $offer->mediated_at = $now;
            $offer->save();

            $publish = $offer->request;
            $publish->status = 'in mediation';
            $publish->mediated_at = $now;
            $publish->save();
            $design_name = $publish['design_name'];
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
            return back()->withErrors(['db error' => 'yes'])->withInput();
        }

        $payload = [
            'user_id' => $offer->designer_id,
            'action_url' => "/designer/mediate-detail/{$mediate->id}",
            'message' => "Your offer for the design {$design_name} has been mediated!"
        ];
        event(new DesignerEvent($payload));

        return redirect(route('client.mediate.list'));
    }


    public function rejection(Request $request) {

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'publish_id' => 'required',
            'offer_id' => 'required'
        ]);
        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        $publish_id = $inputs['publish_id'];
        $publish_name = $inputs['publish_name'];
        $offer_id = $inputs['offer_id'];
        $client_id = Publish::find($publish_id)['client_id'];
        $client_name = User::find($client_id)['name'];
        $designer_id = Offer::find($offer_id)['designer_id'];
        $designer_name = User::find($designer_id)['name'];

        $mediate = Mediate::where('offer_id', $offer_id)->first();
        $mediate['status'] = 'rejected';
        $mediate->save();

        DB::beginTransaction();
        try {

            $msg1 = "Client {$client_name} is requesting mediation about the design {$publish_name} what is made by Designer {$designer_name}";

            $message = Message::create([
                'user_id' => 1,
                'request_id' => $publish_id,
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

            event(new AdminEvent($data1));

            $msg2 = "Client is requesting mediation about the design {$publish_name} what is made by you. Wait for the result of the Support.";

            $message = Message::create([
                'user_id' => $designer_id,
                'request_id' => $publish_id,
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

            event(new DesignerEvent($data2));


        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error($e->getMessage());
            return back()->withErrors(['db error' => $e->getMessage()]);
        }

        DB::commit();

        return back()->with(['success' => 'OK']);

    }



}


