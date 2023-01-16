<?php

namespace Buckaroo\BuckarooWrapper;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Buckaroo
{
    public static function event(){
        $client = new Client();
        $res = $client->request('GET', 'https://dummyjson.com/products');

        try {
            return response()->json([
                'code' => $res->getStatusCode(),
                'message' => $res->getReasonPhrase()
            ]);
        }catch (GuzzleException $e){
            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }

    }
}
