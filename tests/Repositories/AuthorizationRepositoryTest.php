<?php

use App\Repositories\AuthorizationRepository;
use App\Repositories\Interfaces\IAuthorizationRepository;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AuthorizationRepositoryTest extends TestCase
{
    protected IAuthorizationRepository $repository;

    protected function setUp(): void
    {
        $this->repository = new AuthorizationRepository();
        parent::setUp();
    }

    public function testTransactionAuthorization()
    {
        $this->assertIsBool($this->repository->transactionAuthorization());
    }
}
