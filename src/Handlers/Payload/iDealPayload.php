<?php

namespace App\Buckaroo\Handlers\Payload;

use Buckaroo\Exceptions\BuckarooException;

class iDealPayload extends DefaultPayload
{
    public function setIssuer(string $issuer)
    {
        $this->payload['issuer'] = $issuer;

        return $this;
    }

    public function getPayload()
    {
        if(!isset($this->payload['issuer']) || empty($this->payload['issuer']))
        {
            throw new BuckarooException(null, 'iDEAL requires issuer id');
        }

        return parent::getPayload();
    }
}
