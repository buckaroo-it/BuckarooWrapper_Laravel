<?php

namespace Buckaroo\Laravel\Http\Requests;

use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Laravel\Handlers\ResponseParser;
use Buckaroo\Laravel\Handlers\ResponseParserInterface;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ReplyHandlerRequest extends FormRequest
{
    public BuckarooTransaction $buckarooTransaction;
    protected ?string $message;
    protected ResponseParserInterface $data;

    public function authorize()
    {
        /* @var ResponseParserInterface $data */
        $this->data = ResponseParser::make($this->all());
        $transaction = Buckaroo::getTransactionModelClass()::fromResponse($this->data)->first();

        if (!$transaction) {
            $this->message = 'Transaction [' . $this->data->getTransactionKey() . '] not found';

            return false;
        }

        $this->setBuckarooTransaction($transaction);

        if (!$this->validateBody()) {
            $this->message = 'Invalid signature';
        }

        return true;
    }

    protected function validateBody()
    {
        return Buckaroo::api()->validateBody($this->input(), $this->header('Authorization') ?? '', route('buckaroo.push'));
    }

    public function getBuckarooTransaction(): BuckarooTransaction
    {
        return $this->buckarooTransaction;
    }

    public function setBuckarooTransaction(BuckarooTransaction $buckarooTransaction): self
    {
        $this->buckarooTransaction = $buckarooTransaction;

        return $this;
    }

    protected function failedAuthorization()
    {
        if (
            $this->data->getMutationType() == 'Informational' &&
            Buckaroo::getTransactionModelClass()::whereAny(['transaction_key', 'related_transaction_key'], '=', $this->data->getTransactionKey())->exists()
        ) {
            throw new HttpException(200, $this->message . '. It is informational requests, and the transaction exists by key or related key');
        }

        throw new HttpException(400, $this->message);
    }
}
