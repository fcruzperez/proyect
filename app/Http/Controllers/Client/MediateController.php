<?php

namespace App\Http\Controllers\Client;

use App\Events\AdminEvent;
use App\Events\ClientEvent;
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
        $offer->status = 'completed';
        $offer->completed_at = $now;
        $offer->save();

        $request = $offer->request;
        $request->status = 'completed';
        $request->completed_at = $now;
        $request->save();

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

            $mediate = Mediate::create([
                'client_id' => Auth::id(),
                'designer_id' => $offer->designer_id,
                'offer_id' => $offer_id,
                'title' => $request->get('title'),
                'content' => $request->get('content'),
            ]);

            $now = now();

            $offer->status = 'mediated';
            $offer->mediated_at = $now;
            $offer->save();

            $publish = $offer->request;
            $publish->status = 'in mediation';
            $publish->mediated_at = $now;
            $publish->save();
        } catch (\Exception $e) {
            logger()->error($e->getMessage());
            return back()->withErrors(['db error' => 'yes'])->withInput();
        }

        $payload = [
            'user_id' => $offer->designer_id,
            'action_url' => "/designer/mediate-detail/{$offer->id}?mediate_id={$mediate->id}",
            'message' => 'Your offer has been mediated!'
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

        DB::beginTransaction();
        try {
            $msg = "Client {$client_name} is requesting mediation about the design {$publish_name} what is made by Designer {$designer_name}";

            $message = Message::create([
                'user_id' => 1,
                'request_id' => $publish_id,
                'offer_id' => $offer_id,
                'subject' => $msg,
                'content' => $msg,
                'action_url' => "/admin/mediation/",
            ]);

            $data = [
                'user_id' => 1,
                'action_url' => "/admin/mediation/",
                'message' => $msg
            ];

            event(new AdminEvent($data));

        } catch (\Exception $e) {
            DB::rollBack();
            logger()->error($e->getMessage());
            return back()->withErrors(['db error' => $e->getMessage()]);
        }

        DB::commit();

        return back()->with(['success' => 'OK']);

    }



}


