<?php

use App\Repositories\AuthorizationRepository;
use App\Services\AuthorizationService;
use App\Services\Interfaces\IAuthorizationService;

class AuthorizationServiceTest extends TestCase
{
    protected IAuthorizationService $serviceAuthorization;

    protected function setUp(): void
    {
        $this->serviceAuthorization = new AuthorizationService(new AuthorizationRepository());
        parent::setUp();
    }

    public function testTransactionAuthorizationSuccess()
    {
        $this->assertIsBool($this->serviceAuthorization->transactionAuthorization());
    }
}
