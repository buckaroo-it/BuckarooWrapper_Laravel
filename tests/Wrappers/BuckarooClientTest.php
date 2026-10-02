<?php

use Buckaroo\Config\DefaultConfig;
use Buckaroo\Handlers\HMAC\Generator;
use Buckaroo\Handlers\Reply\ReplyHandler;
use Buckaroo\Laravel\Facades\Buckaroo;
use Buckaroo\PaymentMethods\PaymentFacade;

function sdkAcceptsSignature(array $payload): bool
{
    return (new ReplyHandler(Buckaroo::api()->getClientConfig(), $payload))->validate()->isValid();
}

/**
 * A payload without fields that sort between `brq_customer_name` and `brq_t`,
 * so the re-split bytes line up exactly.
 */
function resplitFields(array $fields): array
{
    return array_merge([
        'brq_amount' => '10.00',
        'brq_currency' => 'EUR',
        'brq_test' => 'true',
        'brq_transaction_method' => 'ideal',
        'brq_transactions' => 'TX1',
    ], $fields);
}

it('builds the SDK client from the package config', function () {
    $config = Buckaroo::api()->getClientConfig();

    expect($config->websiteKey())->toBe(TEST_WEBSITE_KEY);
    expect($config->secretKey())->toBe(TEST_SECRET_KEY);
    expect($config->isLiveMode())->toBeFalse();
});

it('can be pointed at another account', function () {
    $config = new DefaultConfig('other-website-key', 'other-secret-key', 'live');

    Buckaroo::api()->setBuckarooClient($config);

    expect(Buckaroo::api()->getClientConfig())->toBe($config);
    expect(Buckaroo::api()->validateBody(signForm(pushFields(), 'other-secret-key')))->toBeTrue();
});

it('forwards SDK calls to the Buckaroo client', function () {
    expect(Buckaroo::api()->method('ideal'))->toBeInstanceOf(PaymentFacade::class);
});

it('rejects calls the SDK client does not have', function () {
    Buckaroo::api()->refundEverything();
})->throws(BadMethodCallException::class, 'Method refundEverything does not exist.');

it('accepts a correctly signed form reply', function () {
    expect(Buckaroo::api()->validateBody(signForm(pushFields())))->toBeTrue();
});

it('rejects a form reply signed with another secret', function () {
    expect(Buckaroo::api()->validateBody(signForm(pushFields(), 'another-secret')))->toBeFalse();
});

it('rejects a form reply whose signed customer name was re-split into other fields', function () {
    $original = signForm(resplitFields([
        'brq_customer_name' => 'Abrq_statuscode=190brq_t=',
        'brq_statuscode' => '490',
    ]));
    $replayed = resplitFields([
        'brq_customer_name' => 'A',
        'brq_statuscode' => '190',
        'brq_t' => 'brq_statuscode=490',
        'brq_signature' => $original['brq_signature'],
    ]);

    expect(sdkAcceptsSignature($replayed))->toBeTrue();
    expect(Buckaroo::api()->validateBody($replayed))->toBeFalse();
    expect(Buckaroo::api()->validateBody($original))->toBeFalse();
});

it('rejects a form reply whose signed additional parameter was re-split into other fields', function () {
    $original = signForm([
        'add_note' => 'xbrq_statuscode=190brq_t=',
        'brq_statuscode' => '490',
        'brq_transactions' => 'TX1',
    ]);
    $replayed = [
        'add_note' => 'x',
        'brq_statuscode' => '190',
        'brq_t' => 'brq_statuscode=490',
        'brq_transactions' => 'TX1',
        'brq_signature' => $original['brq_signature'],
    ];

    expect(sdkAcceptsSignature($replayed))->toBeTrue();
    expect(Buckaroo::api()->validateBody($replayed))->toBeFalse();
});

it('rejects a signed value that contains a signed-key pattern', function (string $field, string $value) {
    expect(Buckaroo::api()->validateBody(signForm(pushFields([$field => $value]))))->toBeFalse();
})->with([
    'custom parameter, mixed case' => ['cust_reference', 'BRQ_StatusCode=190'],
    'html-encoded equals sign' => ['brq_customer_name', 'A brq_statuscode&#61;190'],
    'upper-case field name' => ['ADD_note', 'add_x=1'],
]);

it('accepts equals signs in values that are not a signed-key pattern', function (string $field, string $value) {
    expect(Buckaroo::api()->validateBody(signForm(pushFields([$field => $value]))))->toBeTrue();
})->with([
    'name with an equals sign' => ['brq_customer_name', 'J. de Vries = buyer'],
    'prefix without equals sign' => ['brq_description', 'Order 42 brq_ promo'],
    'url with a query string' => ['add_returnurl', 'https://shop.test/return?a=b&c=d'],
    'buckaroo redirect url' => ['brq_redirect_url', 'https://checkout.buckaroo.nl/html/redirect.ashx?r=ABC123&s=1'],
]);

it('ignores the pattern in fields that are not signed', function () {
    expect(Buckaroo::api()->validateBody(signForm(pushFields()) + ['note' => 'brq_statuscode=490']))->toBeTrue();
});

it('validates a JSON reply by its HMAC header only', function () {
    $payload = [
        'Transaction' => [
            'Key' => 'TX1',
            'Status' => ['Code' => ['Code' => 190]],
            'Description' => 'brq_statuscode=490brq_t=',
        ],
    ];
    $url = 'https://shop.test/buckaroo/push';
    $header = 'hmac ' . (new Generator(Buckaroo::api()->getClientConfig(), $payload, $url))->generate();

    expect(Buckaroo::api()->validateBody($payload, $header, $url))->toBeTrue();
    expect(Buckaroo::api()->validateBody($payload, 'hmac ' . TEST_WEBSITE_KEY . ':wrong:nonce:1', $url))->toBeFalse();
});

it('refuses every reply while the secret is blank', function () {
    Buckaroo::api()->setBuckarooClient(TEST_WEBSITE_KEY, '   ', 'test');
    $payload = signForm(pushFields(), '');

    expect(sdkAcceptsSignature($payload))->toBeTrue();
    expect(Buckaroo::api()->validateBody($payload))->toBeFalse();
});

it('resolves without credentials and refuses every reply', function () {
    useDefaultPackageConfig();

    expect(Buckaroo::api()->getClientConfig())->toBeNull();
    expect(Buckaroo::api()->validateBody(signForm(pushFields(), '')))->toBeFalse();
});

it('names the missing credentials when the API is called without them', function () {
    useDefaultPackageConfig();

    Buckaroo::api()->method('ideal');
})->throws(RuntimeException::class, 'Set BPE_WEBSITE_KEY and BPE_SECRET_KEY');

it('confirms live credentials', function () {
    $websiteKey = $_ENV['BPE_WEBSITE_KEY'] ?? null;
    $secretKey = $_ENV['BPE_SECRET_KEY'] ?? null;

    if (empty($websiteKey) || empty($secretKey)) {
        $this->markTestSkipped('Buckaroo credentials are not configured.');
    }

    Buckaroo::api()->setBuckarooClient($websiteKey, $secretKey);

    expect(Buckaroo::api()->confirmCredential())->toBeTrue();
});
