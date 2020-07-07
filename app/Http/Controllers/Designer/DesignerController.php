<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;
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

        $data = ['offers' => $offers];
        return view('pages.designer.home', $data);
    }

    public function viewPosts(Request $request)
    {

        $desinger_id = Auth::id();

        $request_ids = Offer::where('designer_id', $desinger_id)->pluck('request_id')->toArray();

        if (!is_null($request_ids)) {
            $data = Publish::where('status', 'published')->whereNotIn('id', $request_ids)->get();
        } else {
            $data = Publish::where('status', 'published')->get();
        }
        $offers = Offer::get();

        return view('pages.designer.posts', ['publishes' => $data]);
    }

    public function saveBid(Request $request)
    {
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'request_id' => 'required',
            'bid_price' => 'required',
            'bid_time' => 'required',
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
}
