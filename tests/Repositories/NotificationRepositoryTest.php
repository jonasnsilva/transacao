<?php

use App\Models\Enum\NotificationTypeEnum;
use App\Models\Enum\SendNotificationEnum;
use App\Models\Notification;
use App\Models\User;
use App\Repositories\Interfaces\INotificationRepository;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Services\Interfaces\IUserService;
use App\Services\UserService;
use Laravel\Lumen\Testing\DatabaseTransactions;

class NotificationRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    private array $notification;
    private array $payer;
    private array $payee;
    private INotificationRepository $repository;
    private IUserService $userService;

    protected function setUp(): void
    {
        $faker = Faker\Factory::create('pt_BR');
        $this->notification = [
            'type' => NotificationTypeEnum::TRANSACTION,
            'send'=> SendNotificationEnum::NO,
            'title' => 'Você recebeu uma transferência.',
        ];
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

        $this->userService = new UserService(new UserRepository());
        $this->repository = new NotificationRepository();

        parent::setUp();
    }

    public function testTransactionNotification()
    {
        $id_payer = $this->userService->store(new User($this->payer));
        $id_payee = $this->userService->store(new User($this->payee));
        $payer =  $this->userService->find($id_payer);
        $payee = $this->userService->find($id_payee);
        $this->notification["message"] = "Você recebeu uma transferência de {$payer->getName()} no valor de 100 R$.";
        $this->notification["user"] = $payee;
        $this->assertIsBool($this->repository->sendTransaction(new Notification($this->notification)));
    }

    public function testStoreNotification()
    {
        $id_payer = $this->userService->store(new User($this->payer));
        $id_payee = $this->userService->store(new User($this->payee));
        $payer =  $this->userService->find($id_payer);
        $payee = $this->userService->find($id_payee);
        $this->notification["message"] = "Você recebeu uma transferência de {$payer->getName()} no valor de 100 R$.";
        $this->notification["user"] = $payee;
        $this->assertIsInt($this->repository->store(new Notification($this->notification)));
    }

    public function testFindNotification()
    {
        $id_payer = $this->userService->store(new User($this->payer));
        $id_payee = $this->userService->store(new User($this->payee));
        $payer =  $this->userService->find($id_payer);
        $payee = $this->userService->find($id_payee);
        $this->notification["message"] = "Você recebeu uma transferência de {$payer->getName()} no valor de 100 R$.";
        $this->notification["user"] = $payee;
        $id_repository = $this->repository->store(new Notification($this->notification));
        $this->assertIsObject($this->repository->find($id_repository));
    }

    public function testUpdateSuccess()
    {
        $id_payer = $this->userService->store(new User($this->payer));
        $id_payee = $this->userService->store(new User($this->payee));
        $payer =  $this->userService->find($id_payer);
        $payee = $this->userService->find($id_payee);
        $this->notification["message"] = "Você recebeu uma transferência de {$payer->getName()} no valor de 100 R$.";
        $this->notification["user"] = $payee;
        $id_repository = $this->repository->store(new Notification($this->notification));
        $this->notification["title"] = "Teste";
        $this->notification["id"] = $id_repository;
        $this->assertTrue($this->repository->update(new Notification($this->notification)));

    }

    public function testUpdateFail()
    {
        $id_payer = $this->userService->store(new User($this->payer));
        $id_payee = $this->userService->store(new User($this->payee));
        $payer =  $this->userService->find($id_payer);
        $payee = $this->userService->find($id_payee);
        $this->notification["message"] = "Você recebeu uma transferência de {$payer->getName()} no valor de 100 R$.";
        $this->notification["user"] = $payee;
        $this->assertFalse($this->repository->update(new Notification($this->notification)));
    }
}
