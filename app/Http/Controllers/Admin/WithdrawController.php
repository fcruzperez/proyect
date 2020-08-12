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
//        return view('pages.admin.withdraw.list', ['withdraws' => $withdraws]);
//    }
//
//    public function detail(Request $request, $id) {
//        $withdraw = Withdraw::find($id);
//        $offers = $withdraw->offers;
//
//        if($request->has('message_id')) {
//            Message::find($request->get('message_id'))->update(['status' => 'read']);
//        }
//
//        return view('pages.admin.withdraw.detail', ['withdraw' => $withdraw, 'offers' => $offers]);
//    }
//
//    public function complete(Request $request, $id) {
//        $withdraw = Withdraw::find($id);
//        $designer = $withdraw->designer;
//        // payout withdraw
//        $payout = new Payout();
//        $senderBatchHeader = new PayoutSenderBatchHeader();
//        $senderBatchHeader->setSenderBatchId(uniqid())
//            ->setEmailSubject("You have a payout of withdraw request #{$id}");
//        $currencyString = json_encode([
//            'value' => $withdraw->paid,
//            'currency' => "USD"
//        ]);
//        $amount = new Amount();
//        $amount->setCurrency('USD')->setTotal($withdraw->paid);
//        $senderItem = new PayoutItem();
//        $senderItem->setRecipientType('EMAIL')
//            ->setNote("You have a payout of withdraw request #{$id}")
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
//        $withdraw = Withdraw::find($id);
//        $designer = $withdraw->designer->id;
//        try {
//            $now = now();
//            $withdraw->update([
//                'status' => 'paid',
//                'paid_at' => $now,
//            ]);
//            // send notification to designer
//            $msg = "Your withdraw request #{$withdraw->id} has been completed!";
//            $message = Message::create([
//                'user_id' => $designer,
//                'subject' => $msg,
//                'content' => $msg,
//                'action_url' => url("designer/withdraw-detail/{$withdraw->id}"),
//            ]);
//
//            $payload = [
//                'user_id' => $withdraw->designer_id,
//                'action_url' => url("designer/withdraw-detail/{$withdraw->id}?message_id={$message->id}"),
//                'message' => $msg,
//            ];
//            event(new DesignerEvent($payload));
//
//            return redirect(url("admin/withdraw-list"));
//        } catch (\Exception $e) {
//            return back()->withErrors(['complete error' => "We can not complete designer {$withdraw->designer->name}'s withdraw request #{$withdraw->id}."]);
//        }
//    }
//}
