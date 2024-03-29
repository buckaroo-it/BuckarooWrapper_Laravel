<?php

namespace Buckaroo\Laravel\Handlers\Payload;

class RivertyPayload extends DefaultPayload
{
    public function setBillingAddress(array $billing)
    {
        $this->payload['billing'] = $billing;
    }

    public function setShippingAddress(array $shipping)
    {
        $this->payload['shipping'] = $shipping;
    }

    public function setArticles(array $articles)
    {
        $this->payload['articles'] = $articles;
    }
}
