<?php

use App\Exceptions\TransactionException;
use App\Models\User;
use App\Repositories\AuthorizationRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Services\AuthorizationService;
use App\Services\Interfaces\ITransactionService;
use App\Services\Interfaces\IUserService;
use App\Services\NotificationService;
use App\Services\TransactionService;
use App\Services\UserService;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TransactionServiceTest extends TestCase
{
    use DatabaseTransactions;

    protected array $payer;
    protected array $payee;
    protected ITransactionService $transactionService;
    protected IUserService $userService;

    protected function setUp(): void
    {

        $this->userService = new UserService(new UserRepository());

        $faker = Faker\Factory::create('pt_BR');

        $this->payer = [
            'name' => $faker->name,
            'email'=> $faker->email,
            'password' => $faker->password,
            'user_type' => 'C',
            'document' => $faker->cpf(false),
            'balance' => $faker->numerify("###.#")
        ];

        $this->payee = [
            'name' => $faker->name,
            'email'=> $faker->email,
            'password' => $faker->password,
            'user_type' => 'S',
            'document' => $faker->cnpj(false),
            'balance' => $faker->numerify("###.#")
        ];

        $this->transactionService = new TransactionService(
            new TransactionRepository(),
            new AuthorizationService(new AuthorizationRepository()),
            $this->userService,
            new NotificationService(new NotificationRepository(), $this->userService)
        );

        parent::setUp();
    }


    public function testStoreTransactionSuccessfully()
    {
        $id_payer = $this->userService->store(new User($this->payer));
        $id_payee = $this->userService->store(new User($this->payee));
        $payer =  $this->userService->find($id_payer);
        $payee = $this->userService->find($id_payee);
        $this->assertIsInt($this->transactionService->store($payer, $payee, 10));

    }

    public function testStoreTransactionWithOutBalanceFailed()
    {
        $id_payer = $this->userService->store(new User($this->payer));
        $id_payee = $this->userService->store(new User($this->payee));
        $payer =  $this->userService->find($id_payer);
        $payee = $this->userService->find($id_payee);
        $this->expectException(TransactionException::class);
        $this->expectExceptionMessage('Pagador não possui saldo suficiente para realizar a transação.');
        $this->assertIsInt($this->transactionService->store($payer, $payee, 5000));

    }

    public function testStoreTransactionWithMerchantAsPayerFailed()
    {
        $id_payer = $this->userService->store(new User($this->payer));
        $id_payee = $this->userService->store(new User($this->payee));
        $payer =  $this->userService->find($id_payer);
        $payee = $this->userService->find($id_payee);
        $this->expectException(TransactionException::class);
        $this->expectExceptionMessage('Pagador não é do tipo comum.');
        $this->assertIsInt($this->transactionService->store($payee, $payer, 10));

    }
}
