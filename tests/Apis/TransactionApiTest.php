<?php


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
use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseTransactions;

class TransactionApiTest extends TestCase
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
    public function testPerformTransactionSuccessfully()
    {
        $id_payer = $this->userService->store(new User($this->payer));
        $id_payee = $this->userService->store(new User($this->payee));
        $this->post('/api/transaction', ['value' => 10, 'id_payer' => $id_payer, 'id_payee' => $id_payee])
            ->seeJson(['code' => Response::HTTP_OK, 'message' => 'Transação realizada com sucesso!'])
            ->assertResponseStatus(Response::HTTP_OK);
    }

    public function testRequiredIdPayerPerformTransactionFailed()
    {
        $id_payee = $this->userService->store(new User($this->payee));
        $this->post('/api/transaction', ['value' => 10, 'id_payee' => $id_payee])
            ->seeJsonStructure(['code','message', 'errors' => ['id_payer']])
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testRequiredIdPayeePerformTransactionFailed()
    {
        $id_payer = $this->userService->store(new User($this->payer));
        $this->post('/api/transaction', ['value' => 10, 'id_payer' => $id_payer])
            ->seeJsonStructure(['code','message', 'errors' => ['id_payee']])
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testRequiredValuePerformTransactionFailed()
    {
        $id_payer = $this->userService->store(new User($this->payer));
        $id_payee = $this->userService->store(new User($this->payee));
        $this->post('/api/transaction', ['id_payer' => $id_payer, 'id_payee' => $id_payee])
            ->seeJsonStructure(['code','message', 'errors' => ['value']])
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testNegativeValuePerformTransactionFailed()
    {
        $id_payer = $this->userService->store(new User($this->payer));
        $id_payee = $this->userService->store(new User($this->payee));
        $this->post('/api/transaction', ['value' => -1, 'id_payer' => $id_payer, 'id_payee' => $id_payee])
            ->seeJsonStructure(['code','message', 'errors' => ['value']])
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testPerformTransactionWithOutBalanceFailed()
    {
        $id_payer = $this->userService->store(new User($this->payer));
        $id_payee = $this->userService->store(new User($this->payee));
        $this->post('/api/transaction', ['value' => 5000, 'id_payer' => $id_payer, 'id_payee' => $id_payee])
            ->seeJson(['code' => Response::HTTP_BAD_REQUEST, 'message' => 'Pagador não possui saldo suficiente para realizar a transação.'])
            ->assertResponseStatus(Response::HTTP_BAD_REQUEST);
    }

    public function testPerformTransactionWithMerchantAsPayerFailed()
    {
        $id_payer = $this->userService->store(new User($this->payer));
        $id_payee = $this->userService->store(new User($this->payee));
        $this->post('/api/transaction', ['value' => 10, 'id_payer' => $id_payee, 'id_payee' => $id_payer])
            ->seeJson(['code' => Response::HTTP_BAD_REQUEST, 'message' => 'Pagador não é do tipo comum.'])
            ->assertResponseStatus(Response::HTTP_BAD_REQUEST);
    }
}
