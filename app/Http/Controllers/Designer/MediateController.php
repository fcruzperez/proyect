<?php

namespace App\Http\Controllers\Designer;

use App\Events\DesignerEvent;
use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\Mediate;
use App\Models\Message;
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
use App\Services\MailService;
use App\Models\User;

class MediateController extends Controller
{
    /**
     */
    protected $mailService;

    public function __construct(MailService $mailService)
    {
        $this->mailService = $mailService;
    }

    public function list() {
        $mediates = Mediate::where('designer_id', Auth::id())->get();

        return view('pages.designer.mediate.list', ['mediates' => $mediates]);
    }

    public function detail($id) {
        $mediate = Mediate::find($id);

        $offer_id = $mediate['offer_id'];
        $offer = Offer::find($offer_id);
        dd($offer);

        return view('pages.designer.mediate.detail', ['mediate' => $mediate, 'offer' => $offer]);
    }

    public function complete($id) {
        $mediate = Mediate::find($id);
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
            Mediate::create([
                'client_id' => Auth::id(),
                'offer_id' => $offer_id,
                'title' => $request->get('title'),
                'content' => $request->get('content'),
            ]);

            $now = now();

            $offer = Offer::find($offer_id);
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
            'action_url' => "/public/designer/offer-detail/{$offer->id}?message_id={$message->id}",
            'message' => 'Your offer has been completed!'
        ];
        event(new DesignerEvent($payload));

        return redirect(route('client.mediate.list'));
    }
}


