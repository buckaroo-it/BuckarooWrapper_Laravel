<?php

namespace Buckaroo\Laravel\PaymentProcessing;

use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\Laravel\Handlers\ResponseParser;
use Buckaroo\Laravel\Handlers\ResponseParserInterface;
use Buckaroo\Laravel\Http\Requests\ReplyHandlerRequest;
use Buckaroo\Laravel\Models\BuckarooTransaction;
use Exception;

class BaseService
{
    protected ?BuckarooTransaction $buckarooTransaction;
    protected ResponseParserInterface $responseParser;

    public function __construct(ReplyHandlerRequest $request)
    {
        $this->responseParser = ResponseParser::make($request->all());
        $this->buckarooTransaction = $this->findBuckarooTransaction();

        if (!$this->buckarooTransaction) {
            $this->handleMissingBuckarooTransaction();
        }
    }

    public static function make(ReplyHandlerRequest $request): static
    {
        return new static($request);
    }

    protected function handleMissingBuckarooTransaction()
    {
        throw new Exception('Transaction [' . $this->responseParser->getTransactionKey() . '] not found');
    }

    public function findBuckarooTransaction()
    {
        return Buckaroo::getTransactionModelClass()::fromResponse($this->responseParser)->first();
    }
}
