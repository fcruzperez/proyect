<?php
//
//namespace App\Http\Controllers\Admin;
//
//use App\Events\AdminEvent;
//use App\Events\DesignerEvent;
//use App\Models\Message;
//use App\Models\Offer;
//use App\Models\User;
//use App\Models\Withdraw;
//use Illuminate\Http\Request;
//use App\Http\Controllers\Controller;
//use Illuminate\Support\Facades\Auth;
//use Illuminate\Support\Facades\Config;
//use Illuminate\Support\Facades\Validator;
//use PayPal\Api\Amount;
//use PayPal\Api\Currency;
//use PayPal\Api\Payout;
//use PayPal\Api\PayoutBatchHeader;
//use PayPal\Api\PayoutItem;
//use PayPal\Api\PayoutSenderBatchHeader;
//use PayPal\Auth\OAuthTokenCredential;
//use PayPal\Rest\ApiContext;
//
//class WithdrawController extends Controller
//{
//    private $apiContext;
//
//    public function __construct()
//    {
//        /** PayPal api context **/
//        $paypalConfig = Config::get('paypal');
//        $this->apiContext = new ApiContext(new OAuthTokenCredential(
//                $paypalConfig['client_id'],
//                $paypalConfig['secret'])
//        );
//        $this->apiContext->setConfig($paypalConfig['settings']);
//    }
//
//    public function list() {
//        $withdraws = Withdraw::get();
//
//        return view('pages.admin.finance.list', ['withdraws' => $withdraws]);
//    }
//
//    public function detail(Request $request, $id) {
//        $finance = Withdraw::find($id);
//        $offers = $finance->offers;
//
//        if($request->has('message_id')) {
//            Message::find($request->get('message_id'))->update(['status' => 'read']);
//        }
//
//        return view('pages.admin.finance.detail', ['finance' => $finance, 'offers' => $offers]);
//    }
//
//    public function complete(Request $request, $id) {
//        $finance = Withdraw::find($id);
//        $designer = $finance->designer;
//        // payout finance
//        $payout = new Payout();
//        $senderBatchHeader = new PayoutSenderBatchHeader();
//        $senderBatchHeader->setSenderBatchId(uniqid())
//            ->setEmailSubject("You have a payout of finance request #{$id}");
//        $currencyString = json_encode([
//            'value' => $finance->paid,
//            'currency' => "USD"
//        ]);
//        $amount = new Amount();
//        $amount->setCurrency('USD')->setTotal($finance->paid);
//        $senderItem = new PayoutItem();
//        $senderItem->setRecipientType('EMAIL')
//            ->setNote("You have a payout of finance request #{$id}")
//            ->setReceiver($designer->paypal_email)
//            ->setSenderItemId(date('YmdHis'))
//            ->setAmount($amount);
////            ->setAmount(new Currency($currencyString));
//
//        $payout->setSenderBatchHeader($senderBatchHeader)->addItem($senderItem);
//
//        $req = clone $payout;
//
//        try {
//            $output = $payout->createSynchronous($this->apiContext);
//        } catch(\Exception $e) {
//            logger()->error($e->getMessage());
//            return back()->withErrors(['error' => $e->getMessage()]);
//        }
//
//        $finance = Withdraw::find($id);
//        $designer = $finance->designer->id;
//        try {
//            $now = now();
//            $finance->update([
//                'status' => 'paid',
//                'paid_at' => $now,
//            ]);
//            // send notification to designer
//            $msg = "Your finance request #{$finance->id} has been completed!";
//            $message = Message::create([
//                'user_id' => $designer,
//                'subject' => $msg,
//                'content' => $msg,
//                'action_url' => url("designer/finance-detail/{$finance->id}"),
//            ]);
//
//            $payload = [
//                'user_id' => $finance->designer_id,
//                'action_url' => url("designer/finance-detail/{$finance->id}?message_id={$message->id}"),
//                'message' => $msg,
//            ];
//            event(new DesignerEvent($payload));
//
//            return redirect(url("admin/finance-list"));
//        } catch (\Exception $e) {
//            return back()->withErrors(['complete error' => "We can not complete designer {$finance->designer->name}'s finance request #{$finance->id}."]);
//        }
//    }
//}
