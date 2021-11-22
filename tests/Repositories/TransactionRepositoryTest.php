<?php

use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Interfaces\ITransactionRepository;
use App\Repositories\TransactionRepository;
use App\Repositories\UserRepository;
use App\Services\Interfaces\IUserService;
use App\Services\UserService;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TransactionRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    protected array $payer;
    protected array $payee;
    protected ITransactionRepository $repository;
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

        $this->repository = new TransactionRepository();

        parent::setUp();
    }

    public function testStoreTransactionSuccessfully()
    {
        $id_payer = $this->userService->store(new User($this->payer));
        $id_payee = $this->userService->store(new User($this->payee));
        $payer =  $this->userService->find($id_payer);
        $payee = $this->userService->find($id_payee);
        $transaction = new Transaction(['payer' => $payer, 'payee' => $payee, 'value' => 10]);
        $this->assertIsInt($this->repository->store($transaction));

    }
}
