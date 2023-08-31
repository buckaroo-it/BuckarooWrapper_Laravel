<?php

namespace Buckaroo\Laravel\Http\Controllers;

use App\Http\Controllers\Controller;
use Buckaroo\Laravel\Facades\Buckaroo;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

use Buckaroo\Laravel\Models\BuckarooTransaction;

class BuckarooController extends Controller
{
    public function return(Request $request)
    {
        $bodyIsValid = Buckaroo::client()->validateBody((!empty($_POST))? $_POST : $request->getContent(),  $request->header('Authorization') ?? '', route('buckaroo.return'));

        if($bodyIsValid)
        {
            try{
                $transaction_id = $request->get('BRQ_TRANSACTIONS') ?? $request->get('brq_transactions');
                $transaction = BuckarooTransaction::firstWhere('brq_transactions', $transaction_id);
                $transaction->update([
                    'brq_statuscode'                => $request->get('BRQ_STATUSCODE') ?? $request->get('brq_statuscode'),
                    'brq_statuscode_detail'         => $request->get('BRQ_STATUSCODE_DETAIL') ?? $request->get('brq_statuscode_detail'),
                    'brq_statusmessage'             => $request->get('BRQ_STATUSMESSAGE') ?? $request->get('brq_statusmessage')
                ]);
            }catch(QueryException $e){}

            return 'Success';
        }

        return 'Body is not valid';
    }

    public function returnCancel()
    {
        //Return Cancel
    }

    public function returnError()
    {
        //Return Error
    }

    public function returnReject()
    {
        //Return Reject
    }

    public function push()
    {
        //Check push
    }
}
