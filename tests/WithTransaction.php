<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseTransactions;

trait WithTransaction
{
    use DatabaseTransactions;

    protected array $connectionsToTransact = ['mysql'];
}
