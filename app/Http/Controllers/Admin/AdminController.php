<?php

namespace App\Http\Controllers\Admin;

use App\Events\ClientEvent;
use App\Events\DesignerEvent;
use App\Http\Controllers\Controller;
use App\Models\Delivery;
use App\Models\DesignerRate;
use App\Models\Mediate;
use App\Models\Message;
use App\Models\Offer;
use App\Models\Settings;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Request as Publish;
use App\Models\Technic;
use App\Models\Format;
use App\Models\Fabric;
use App\Models\Withdraw;
use App\Models\RequestFabric;
use App\Models\RequestFormat;
use App\Models\RequestTechnic;
use App\Models\RequestImage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class AdminController extends Controller
{
    public function dashboard(Request $request) {

        $publishes = Publish::all()->sortByDesc('id');
        $data = ['publishes' => $publishes];


//        dd($data);

        return view('pages.admin.dashboard', $data);
    }


    public function settings(Request $request){

        $formats = Format::all();
        $fabrics = Fabric::all();
        $technics = Technic::all();

        $id = Settings::count();
        if ($id <> 0) {
            $settings = Settings::limit($id)->get();
            $setting = $settings[count($settings) - 1];
            //        dd($setting['client_fee']);

            $client_fee = $setting['client_fee'];
            $designer_fee = $setting['designer_fee'];
            $minimum_work_time = $setting['minimum_work_time'];
            $minimum_work_price = $setting['minimum_work_price'];
            $delta_time = $setting['delta_time'];
            $claim_time = $setting['claim_time'];
            $correction_time = $setting['correction_time'];
            $payment_time_to_designer = $setting['payment_time_to_designer'];
            $minimum_withdrawal_amount = $setting['minimum_withdrawal_amount'];
            $expiration_time = $setting['expiration_time'];
        }


        $data = [
            'formats' => $formats,
            'fabrics' => $fabrics,
            'technics' => $technics,

            'client_fee' => isset($client_fee) ? $client_fee : 0 ,
            'designer_fee' => isset($designer_fee) ? $designer_fee : 0,
            'minimum_work_time' => isset($minimum_work_time) ? $minimum_work_time : 0,
            'minimum_work_price' => isset($minimum_work_price) ? $minimum_work_price : 0,
            'delta_time' => isset($delta_time) ? $delta_time : 0,
            'claim_time' => isset($claim_time) ? $claim_time : 0,
            'correction_time' => isset($correction_time) ? $correction_time : 0,
            'payment_time_to_designer' => isset($payment_time_to_designer) ? $payment_time_to_designer : 0,
            'minimum_withdrawal_amount' => isset($minimum_withdrawal_amount) ? $minimum_withdrawal_amount : 0,
            'expiration_time' => isset($expiration_time) ? $expiration_time : 0
        ];
        return view('pages.admin.settings', $data);
    }

    public function otherSettings(Request $request) {

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'client_fee' => 'required',
            'designer_fee' => 'required',
            'minimum_work_time' => 'required',
            'minimum_work_price' => 'required',
            'delta_time' => 'required',
            'claim_time' => 'required',
            'correction_time' => 'required',
            'payment_time_to_designer' => 'required',
            'minimum_withdrawal_amount' => 'required',
            'expiration_time' => 'required',

        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }


        $client_fee = $inputs['client_fee'];
        $designer_fee = $inputs['designer_fee'];
        $minimum_work_time = $inputs['minimum_work_time'];
        $minimum_work_price = $inputs['minimum_work_price'];
        $delta_time = $inputs['delta_time'];
        $claim_time = $inputs['claim_time'];
        $correction_time = $inputs['correction_time'];
        $payment_time_to_designer = $inputs['payment_time_to_designer'];
        $minimum_withdrawal_amount = $inputs['minimum_withdrawal_amount'];
        $expiration_time = $inputs['expiration_time'];

        Settings::create([

            'client_fee' => $client_fee,
            'designer_fee' => $designer_fee,
            'minimum_work_time' => $minimum_work_time,
            'minimum_work_price' => $minimum_work_price,
            'delta_time' => $delta_time,
            'claim_time' => $claim_time,
            'correction_time' => $correction_time,
            'payment_time_to_designer' => $payment_time_to_designer,
            'minimum_withdrawal_amount' => $minimum_withdrawal_amount,
            'expiration_time' => $expiration_time
        ]);
        return back();
    }


    public function formatNew(Request $request) {
        $input = $request->all();
        $validator = Validator::make($input, [
            'format' => 'required|unique:formats,name'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        try{
            Format::create(['name' => $input['format']]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->to('admin/settings');

    }

    public function formatUpdate(Request $request) {

        $input = $request->all();
        $validator = Validator::make($input, [
            'format_id' => 'required',
            'format_name' => 'required|unique:formats,name'
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        try{
            $format = Format::find($input['format_id']);
            $format->name = $input['format_name'];
            $format->save();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->to('admin/settings');
    }

    public function formatDelete(Request $request, $id)
    {
        Format::find($id)->delete();

        return redirect()->to('admin/settings');
    }

    public function fabricNew(Request $request) {

        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'fabric' => 'required|unique:fabrics,name'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            Fabric::create(['name' => $inputs['fabric']]);
        }
        catch(\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->to('admin/settings');
    }

    public function fabricUpdate(Request $request) {
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
           'fabric_id' => 'required',
           'fabric_name' => 'required|unique:fabrics,name',
        ]);
        if($validator->fails()){
            return back()->withErrors($validator);
        }

        try{
            $fabric = Fabric::find($inputs['fabric_id']);
            $fabric->name = $inputs['fabric_name'];
            $fabric->save();
        } catch (\Exception $e) {
            return back()->withErrors(['errors' => $e->getMessage()]);
        }

        return redirect()->to('admin/settings');
    }

    public function fabricDelete(Request $request, $id)
    {

        Fabric::find($id)->delete();
        return redirect()->to('admin/settings');
    }

    public function technicNew(Request $request) {

        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'technic' => 'required|unique:technics,name'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator);
        }

        try {
            Technic::create(['name' => $inputs['technic']]);
        }
        catch(\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->to('admin/settings');
    }

    public function technicUpdate(Request $request) {
        $inputs = $request->all();
        $validator = Validator::make($inputs, [
            'technic_id' => 'required',
            'technic_name' => 'required|unique:technics,name',
        ]);
        if($validator->fails()){
            return back()->withErrors($validator);
        }

        try{
            $technic = Technic::find($inputs['technic_id']);
            $technic->name = $inputs['technic_name'];
            $technic->save();
        } catch (\Exception $e) {
            return back()->withErrors(['errors' => $e->getMessage()]);
        }

        return redirect()->to('admin/settings');
    }

    public function technicDelete(Request $request, $id) {

        Technic::find($id)->delete();
        return redirect()->to('admin/settings');
    }

    public function score(Request $request) {

        $designers = User::where('role', 'designer')->get();
        $designer_ids = array();
        foreach ($designers as $designer) {

            array_push($designer_ids, $designer['id']);
        }

//        foreach ($designer_ids as $designer_id) {
//            try {
//                DesignerRate::create([
//                    'designer_id' => $designer_id,
//                    'rate' => 0,
//                ]);
//            }
//            catch(\Exception $e) {
//                return back()->withErrors(['error' => $e->getMessage()]);
//            }
//
//        }
//        dd($designer_ids);

        $data = ['designer_ids' => $designer_ids];


        return view('pages.admin.score', $data);


    }

    public function updateScore(Request $request) {

        $input = $request->all();
        $validator = Validator::make($input, [
            'designer_id' => 'required',
            'designer_rate' => 'required',
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        try{
            $id = DesignerRate::where('designer_id', $input['designer_id'])->get()[0]['id'];
            $item = DesignerRate::find($id);
//            dd($item);
            $item->rate = $input['designer_rate'];
            $item->save();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }

        return redirect()->to('admin/score');

    }


    public function updatePublish(Request $request) {

        $inputs = $request->all();
        $publish_id = $inputs['pub_id'];
        $publish = Publish::find($publish_id);
        $publish['description'] = $inputs['description'];
        $publish->save();

        return redirect()->to('admin/dashboard');

    }

    public function mediation(Request $request) {

        $publishes = Publish::where('status', 'in mediation')->orderBy('created_at', 'desc')->get();
        $data = ['publishes' => $publishes];

        return view('pages.admin.mediation', $data);

    }

    public function updateMediateContent(Request $request) {

        $inputs = $request->all();
        $mediate_id = $inputs['mediate_id'];
        $mediate = Mediate::find($mediate_id);
        $mediate['title'] = $inputs['title'];
        $mediate['content'] = $inputs['content'];
        $mediate->save();

        return redirect()->to('admin/mediation');
    }

    public function downloadDelivery(Request $request, $id)
    {
        $delivery = Delivery::find($id);

        if (Storage::exists($delivery->path)) {
            return Storage::download($delivery->path);
        }
        return response('', 404);
    }

    public function downloadErrors(Request $request, $id)
    {
        $mediate = Mediate::find($id);
        $file = $mediate['error_images'];

        if (strtoupper(substr(PHP_OS, 0, 3)) <> 'WIN') {
            if (file_exists($file))
                return response()->download($file);
        }
        else {
            if(Storage::exists($file)) {
                return Storage::download($file);
            }
        }
        return response('', 404);
    }

    public function refund(Request $request, $id) {

        $publish = Publish::find($id);
//        if ($publish != null) {
//            $accepted_offer_id = $publish['accepted_offer_id'];
//            $offer = Offer::find($accepted_offer_id);

        $design_name = $publish['design_name'];
        $client_id = $publish['client_id'];
        $client = User::find($client_id);

        $offer_id = $publish['accepted_offer_id'];
        $offer = Offer::find($offer_id);
        $offer_price = $offer['price'];

        $top_id = Settings::count();
        $settings = Settings::limit($top_id)->get();
        $setting = $settings[count($settings) - 1];
        $client_fee = $setting['client_fee'];

        $price = $offer_price + $client_fee;

        $designer_id = $offer['designer_id'];
        $designerRate = DesignerRate::where('designer_id', $designer_id)->first();
        $rate = $designerRate['rate'];
        $rate = 0.8 * $rate;
        $designerRate['rate'] = round($rate,1);
        $designerRate->save();

        $now = now();
        $publish['refund'] = $price;
        $publish['completed_at'] = $now;
        $publish->save();

        $client['balance'] += $price;
        $client->save();

        $msg = "You are received refund about your design {$design_name}";

        $message = Message::create([
            'user_id' => $client_id,
            'request_id' => $id,
            'offer_id' => $offer_id,
            'subject' => $msg,
            'content' => $msg,
            'action_url' => "/client/finance-list",
        ]);

        $data = [
            'user_id' => $client_id,
            'action_url' => "/client/finance-list",
            'message' => $msg
        ];

        event(new ClientEvent($data));

        return back();

    }

    public function decision(Request $request) {

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'client_percent' => 'required',
            'designer_percent' => 'required',
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        $publish_id = $inputs['publish_id'];
        $publish = Publish::find($publish_id);
        $design_name = $publish['design_name'];
        $offer_id = $publish['accepted_offer_id'];
        $offer = Offer::find($offer_id);
        $designer_id = $offer['designer_id'];
        $client_id = $publish['client_id'];

        $price = $offer['price'];
        $client_percent = $inputs['client_percent'];
        $designer_percent = $inputs['designer_percent'];

        $top_id = Settings::count();
        $settings = Settings::limit($top_id)->get();
        $setting = $settings[count($settings) - 1];
        $client_fee = $setting['client_fee'];
        $designer_fee = $setting['designer_fee'];

        $for_client = floatval(round($client_percent * ($price + $client_fee) / 100, 2));
        $for_designer = floatval(round($price * (100 - $designer_fee) * $designer_percent / 10000, 2));

        $now = now();
        $publish['status'] = 'completed';
        $publish->completed_at = $now;
        $publish['refund'] = $for_client;
        $publish->save();

        $offer['status'] = 'completed';
        $offer['paid'] = $for_designer;
        $offer->completed_at = $now;
        $offer->save();

        $designer = User::find($designer_id);
        $client = User::find($client_id);

        $designer['balance'] += $for_designer;
        $designer->save();
        $client['balance'] += $for_client;
        $client->save();

        $designerRate = DesignerRate::where('designer_id', $designer_id)->first();
        $rate = $designerRate['rate'];
        $designerRate['rate'] = floatval(round(($rate + $designer_percent / 100 * 5) / 2, 1));
        $designerRate->save();

        $msg_client = "You are received {$client_percent}% refund about your design {$design_name} by the Support";

        $message = Message::create([
            'user_id' => $client_id,
            'request_id' => $publish_id,
            'offer_id' => $offer_id,
            'subject' => $msg_client,
            'content' => $msg_client,
            'action_url' => "/client/finance-list",
        ]);

        $data_client = [
            'user_id' => $client_id,
            'action_url' => "/client/finance-list",
            'message' => $msg_client
        ];

        event(new ClientEvent($data_client));


        $msg_designer = "You are received {$designer_percent}% payment about the design {$design_name} by the Support";

        $message = Message::create([
            'user_id' => $designer_id,
            'request_id' => $publish_id,
            'offer_id' => $offer_id,
            'subject' => $msg_designer,
            'content' => $msg_designer,
            'action_url' => "/designer/finance-list",
        ]);

        $data_designer = [
            'user_id' => $designer_id,
            'action_url' => "/designer/finance-list",
            'message' => $msg_designer
        ];

        event(new DesignerEvent($data_designer));

        return redirect('admin/transactions');

    }

    public function registerUser(Request $request) {

        $data = $request->all();
//        dd($data);
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'paypal_email' => $data['paypal_email'],
            'mobile' => $data['mobile'],
            'role' => $data['role'],
            'password' => Hash::make($data['password']),
        ]);

        if($user->role === 'designer') {
            DesignerRate::create([
                'designer_id' => $user->id,
                'rate' => 0
            ]);
        }
        return redirect()->to('admin/dashboard');
    }

    public function showRegisterUser(Request $request){

        return view('pages.admin.register');
    }


    //Finance part
    public function showBalances(Request $request) {

        $users = User::get();
        return view('pages.admin.finance.balances', ['users' => $users]);

    }

    public function showTransactions(Request $request) {

        $offers = Offer::where('status', 'completed')->get();
        $publishes = Publish::where('refund', '<>', 0.00)->get();
        $data = [
            'offers' => $offers,
            'publishes' => $publishes
        ];
        return view('pages.admin.finance.transactions', $data);

    }

    public function showWithdraws(Request $request) {

        $withdraws = Withdraw::get();
        return view('pages.admin.finance.withdraws', ['withdraws' => $withdraws]);
    }

    public function applyWithdraw(Request $request) {

        $inputs = $request->all();

        $validator = Validator::make($inputs, [
            'withdraw_amount' => 'required|integer',
        ]);

        if($validator->fails()) {
            return back()->withErrors($validator);
        }

        $user_id = $inputs['user_id'];
        $amount = $inputs['withdraw_amount'];

        $top_id = Settings::count();
        $settings = Settings::limit($top_id)->get();
        $setting = $settings[count($settings) - 1];
        $minimum_withdrawal_amount = $setting['minimum_withdrawal_amount'];

        if ($amount < $minimum_withdrawal_amount) {
            $message = "Please see Note again!";
            echo "<script type='text/javascript'>alert('$message');</script>";
            return back();
        }

        $user = User::find($user_id);
        $balance = $user['balance'];

        if ($balance < $amount) {
            $message = "We can't withdraw";
            echo "<script type='text/javascript'>alert('$message');</script>";
            return back();
        }

        $user['balance'] -= $amount;
        $user->save();

        Withdraw::create([
            'user_id' => $user_id,
            'user_name' => $user['name'],
            'amount' => $amount
        ]);


        $msg = "Your {$amount}USD are withdrawed.";

        if ($user['role'] === 'client') {

            $message = Message::create([
                'user_id' => $user_id,
                'subject' => $msg,
                'content' => $msg,
                'action_url' => "/client/withdraw-list",
            ]);

            $data = [
                'user_id' => $user_id,
                'action_url' => "/client/withdraw-list",
                'message' => $msg
            ];

            event(new ClientEvent($data));
        }

        else {
            $message = Message::create([
                'user_id' => $user_id,
                'subject' => $msg,
                'content' => $msg,
                'action_url' => "/designer/finance-list",
            ]);

            $data = [
                'user_id' => $user_id,
                'action_url' => "/designer/finance-list",
                'message' => $msg
            ];

            event(new DesignerEvent($data));

        }

        return back();


    }
}
