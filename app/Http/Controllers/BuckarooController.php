<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Buckaroo\BuckarooWrapper\Buckaroo;
class BuckarooController extends Controller
{
    public function buckaroo() {
        return Buckaroo::event();
    }
}
