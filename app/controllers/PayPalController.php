<?php
use PayPal\Rest\ApiContext;
use PayPal\Auth\OAuthTokenCredential;
use PayPal\Api\Amount;
use PayPal\Api\Details;
use PayPal\Api\Item;
use PayPal\Api\ItemList;
use PayPal\Api\Payer;
use PayPal\Api\Payment;
use PayPal\Api\RedirectUrls;
use PayPal\Api\ExecutePayment;
use PayPal\Api\PaymentExecution;
use PayPal\Api\Transaction;

class PaypalController extends BaseController
{
    private $_api_context;
    public function __construct()
    {
        // setup PayPal api context
        $paypal_conf = Config::get('paypal');
        $this->_api_context = new ApiContext(new OAuthTokenCredential($paypal_conf['client_id'], $paypal_conf['secret']));
        $this->_api_context->setConfig($paypal_conf['settings']);
    }

    public function getPaymentStatus()
    {
        // Get the payment ID before session clear
        $payment_id = Session::get('paypal_payment_id');
        //dd(Session::get('transId'));
        $transId = Session::get('transId');
        $sell = coinsSell::find($transId);
        $sell->paymentId = $payment_id;

        // clear the session payment ID
        Session::forget('transId');
        Session::forget('paypal_payment_id');
        if (empty(Input::get('PayerID')) || empty(Input::get('token'))) {
            $sell->status = "FAILED";
            $sell->save();
            return Redirect::route('coins.status')
                ->with('status', 'failed');
        }
        $payment = Payment::get($payment_id, $this->_api_context);
        // PaymentExecution object includes information necessary
        // to execute a PayPal account payment.
        // The payer_id is added to the request query parameters
        // when the user is redirected from paypal back to your site
        $execution = new PaymentExecution();
        $execution->setPayerId(Input::get('PayerID'));

        //Execute the payment
        $result = $payment->execute($execution, $this->_api_context);
        //echo '<pre>';print_r($result);echo '</pre>';exit; // DEBUG RESULT, remove it later
        if ($result->getState() == 'approved') { // payment made

            $item = coinsItem::where('id', $sell->itemId)->first();
//dd(Coin::where('uuid', $sell->uuid)->where('coin', $item->rewardCoin)->count());
            if (Coin::where('uuid', $sell->uuid)->where('coin', $item->rewardCoin)->count() > 0){
              $coin = Coin::where('uuid', $sell->uuid)->where('coin', $item->rewardCoin)->first();
              $coin->balance = $coin->balance + $item->rewardQty;
            }else{
              $coin = new Coin;
              $coin->balance = $item->rewardQty;
              $coin->coin = $item->rewardCoin;
              $coin->uuid = $sell->uuid;
              $coin->nick = $sell->nick;
            }
            if ($coin->save()){
              $sell->status = "APPROVED";
              $sell->save();
              return Redirect::route('coins.status')
                  ->with('status', 'success');
            }else{
              $sell->status = "FAILED";
              $sell->save();
              return Redirect::route('coins.status')
                  ->with('status', 'failed');
            }
        }
        $sell->status = "FAILED";
        $sell->save();
        return Redirect::route('coins.status')
            ->with('status', 'failed');
    }
}
