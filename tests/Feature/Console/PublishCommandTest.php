<?php

use Illuminate\Support\Facades\File;

function publishedFiles(): array
{
    return [
        base_path('config/buckaroo.php'),
        base_path('routes/buckaroo.php'),
        base_path('database/migrations/2023_01_02_000000_create_buckaroo_transactions_table.php'),
    ];
}

beforeEach(fn () => File::delete(publishedFiles()));
afterEach(fn () => File::delete(publishedFiles()));

it('publishes the config, routes and migration into the app', function () {
    $this->artisan('buckaroo:publish')->assertExitCode(0);

    foreach (publishedFiles() as $file) {
        expect(File::exists($file))->toBeTrue("{$file} was not published");
    }
});
