<?php

namespace App\Http\Controllers\Client;

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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
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


        if(!is_null($inputs['formats'])) {
            $format_ids = explode(',', $inputs['formats']);

            foreach ($format_ids as $fid) {
                RequestFormat::create([
                    'request_id' => $request_id,
                    'format_id' => $fid,
                ]);
            }
        }

        if(!is_null($inputs['technics'])) {
            $technic_ids = explode(',', $inputs['technics']);

            foreach ($technic_ids as $tid) {
                RequestTechnic::create([
                    'request_id' => $request_id,
                    'technic_id' => $tid,
                ]);
            }
        }

        if(!is_null($inputs['fabrics'])) {
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
//        dd($inputs);
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

//
//        if(!is_null($inputs['formats'])) {
//            RequestFormat::where('request_id', $rid)->delete();
//
//            $format_ids = explode(',', $inputs['formats']);
//
//            foreach ($format_ids as $fid) {
//                RequestFormat::create([
//                    'request_id' => $rid,
//                    'format_id' => $fid,
//                ]);
//            }
//        }
//
//        if(!is_null($inputs['technics'])) {
//            RequestFabric::where('request_id', $rid)->delete();
//            $technic_ids = explode(',', $inputs['technics']);
//
//            foreach ($technic_ids as $tid) {
//                RequestTechnic::create([
//                    'request_id' => $rid,
//                    'technic_id' => $tid,
//                ]);
//            }
//        }
//
//        if(!is_null($inputs['fabrics'])) {
//            RequestTechnic::where('request_id', $rid)->delete();
//            $fabric_ids = explode(',', $inputs['fabrics']);
//
//            foreach ($fabric_ids as $id) {
//                RequestFabric::create([
//                    'request_id' => $rid,
//                    'fabric_id' => $id,
//
//                ]);
//            }
//        }

       return redirect('/client/home');

    }


    public function showNewPublish(Request $request)
    {
        $formats = Format::get();
        $fabrics = Fabric::get();
        $technics = Technic::get();

        $data = ['technics' => $technics, 'formats' => $formats, 'fabrics' => $fabrics];

        return view('pages.client.new_publish', $data);
    }


    public function showUpdatePublish($rid)
    {
        $old = Publish::find($rid);
        $formats = Format::get();
        $fabrics = Fabric::get();
        $technics = Technic::get();

        $data = ['publish' => $old, 'technics' => $technics, 'formats' => $formats, 'fabrics' => $fabrics];

        return view('pages.client.update_publish', $data);
    }



    public function showWithdraw(Request $request)
    {
//        $technics = Technic::get();
//        $formats = Format::get();
//        $fabrics = Fabric::get();
//        $data = ['technics' => $technics, 'formats' => $formats, 'fabrics' => $fabrics];
//
//        return view('pages.client.new_publish', $data);
//    }
    }
    public function showPublishes(Request $request){

        $userId = Auth::id();
        $publishes = Publish::where('client_id', $userId)->get();

        $data = ['publishes' => $publishes];
        return view('pages.client.home', $data);

    }

    public function showDeposit(Request $request) {

        $inputs = $request->all();

//                dd($inputs);
        $validator = Validator::make($inputs, [
            'request_id' => 'required',
            'offer_id' => 'required',
        ]);
        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        $data = ['values' => $inputs];
        return view('pages.client.show_deposit', $data);
    }


    public function paypalDeposit(Request $request){

        return view('pages.client.paypal_deposit');
    }

    public function acceptBid(Request $request){
        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'request_id' => 'required|unique:requests,id',
            'offer_id' => 'required|unique:offers,id',
        ]);

        if($validator->fails()) {
            return response()->json(['message' => 'wrong parameters'], 400);
        }

        $publish = Publish::find($request->get($inputs['request_id']));
        $publish->status = '';
        $publish->save();

        return response()->json(['message' => 'success']);


    }

    public function cancel($id) {
        $publish = Publish::find($id);

        $publish['status'] = 'cancelled';

        $publish->save();
        return back();

    }
}


