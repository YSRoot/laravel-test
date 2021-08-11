<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    use DatabaseTransactions;

    protected array $connectionsToTransact = ['mysql'];
}
