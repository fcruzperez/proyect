<?php

namespace App\Http\Controllers\Admin;

use App\Events\AdminEvent;
use App\Models\Messages;
use App\Models\Offer;
use App\Models\User;
use App\Models\Withdraw;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Srmklive\PayPal\Services\ExpressCheckout;

class WithdrawController extends Controller
{
    public $paypal;

    public function __construct()
    {
        $this->paypal = new ExpressCheckout();
    }

    public function list() {
        $withdraws = Withdraw::get();

        return view('pages.admin.withdraw.list', ['withdraws' => $withdraws]);
    }

    public function detail(Request $request, $id) {
        $withdraw = Withdraw::find($id);
        $offers = $withdraw->offers;

        if($request->has('message_id')) {
            Messages::find($request->get('message_id'))->update(['status' => 'read']);
        }

        return view('pages.designer.withdraw.detail', ['withdraw' => $withdraw, 'offers' => $offers]);
    }
}
