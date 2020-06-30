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
  public function home(Request $request) {
      $data = Publish::where('status', 'published')->orWhere('status', 'awarded')->get();
      return view('pages.designer.home',  ['publishes' => $data]);
  }

  public function saveBid(Request $request) {

      $inputs = $request->all();

//      dd($inputs);

      $validator = Validator::make($inputs, [
         'request_id' => 'required',
         'bid_price' => 'required',
         'bid_time' => 'required',
      ]);

      if ($validator->fails()){
          return back()->withErrors($validator)->withInput();
      }

      $data = [
          'designer_id' => Auth::id(),
          'request_id' => $inputs['request_id'],
          'price' => $inputs['bid_price'],
          'hours' => $inputs['bid_time'],
          ];

      $new_offer = Offer::create($data);

      $publish = Publish::where('id', $inputs['request_id'])->first();
//      $publish->offer_id = $new_offer->id;
      $publish->save();

      return redirect('/designer/home');

  }
}
