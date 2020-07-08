<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;
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

        $data = [
            'designer_id' => Auth::id(),
            'request_id' => $inputs['request_id'],
            'price' => $inputs['bid_price'],
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

        return view('pages.designer.offer_detail', ['publish' => $publish, 'offer' => $offer]);
    }

    public function downloadImage($file) {
        if(Storage::exists('public/images/'.$file)) {
            return Storage::download('public/images/'.$file);
        }
        return response('', 404);
    }
}
