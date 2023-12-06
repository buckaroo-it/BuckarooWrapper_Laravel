<?php

namespace Buckaroo\Laravel\Console;

use Illuminate\Console\Command;

class TransactionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'buckaroo:transaction {transactionKey}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get transaction data';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        print($this->argument('user'));
    }
}
