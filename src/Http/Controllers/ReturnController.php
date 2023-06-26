<?php

namespace App\Buckaroo\Http\Controllers;

use App\Buckaroo\Facades\Buckaroo;
use App\Http\Controllers\Controller;
use App\Models\Status;
use App\Models\Transaction;
use App\Models\Type;

use Str;
class ReturnController extends Controller
{
    public function return()
    {
        $transactionKey = $this->request->brq_transactions ?? $this->request->BRQ_TRANSACTIONS ?? $this->request->brq_datarequest ?? $this->request->BRQ_DATAREQUEST;
        $transaction = Transaction::with('order.status')->where('key', $transactionKey)->firstOrFail();

        $reply_handler = Buckaroo::verify((!empty($_POST))? $_POST : $this->request->all(), $this->request->header('Authorization') ?? '','');

        if($reply_handler->isValid())
        {
            if($reply_handler->data('brq_statuscode') == 190)
            {
                if($transaction->order->status->name === 'awaiting_payment')
                {
                    $transaction->order->update([
                        'status_id' => Status::where('type_id', Type::firstOrCreate(['name' => 'order'])->id)->where('name', 'awaiting_fulfillment')->first()->id
                    ]);

                    $this->request->session()->setId(Str::random(40));
                }

                return redirect()->route('checkout-confirmed', ['reference' => $transaction->order->reference]);
            }
        }

        return redirect()->route('checkout')->with('paymentError', 'Payment failed.');
    }

    public function returnError()
    {
        return redirect()->route('checkout')->with('paymentError', 'Payment failed.');
    }
}
