<?php

use App\Repositories\AuthorizationRepository;
use App\Services\AuthorizationService;
use App\Services\Interfaces\IAuthorizationService;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthorizationServiceTest extends TestCase
{
    protected IAuthorizationService $serviceAuthorization;

    protected function setUp(): void
    {
        $this->serviceAuthorization = new AuthorizationService(new AuthorizationRepository());
        parent::setUp();
    }

    public function testTransactionAuthorizationSuccessfully()
    {
        $this->assertIsBool($this->serviceAuthorization->transactionAuthorization());
    }
}
