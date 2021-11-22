<?php


use App\Models\Enum\NotificationTypeEnum;
use App\Models\Enum\SendNotificationEnum;
use App\Models\Notification;
use App\Models\User;
use App\Repositories\NotificationRepository;
use App\Repositories\UserRepository;
use App\Services\Interfaces\INotificationService;
use App\Services\Interfaces\IUserService;
use App\Services\NotificationService;
use App\Services\UserService;
use Laravel\Lumen\Testing\DatabaseTransactions;

class NotificationServiceTest extends TestCase
{

    use DatabaseTransactions;

    private array $notification;
    private array $payer;
    private array $payee;
    private INotificationService $notificationService;
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
        $this->notificationService = new NotificationService(new NotificationRepository(), $this->userService);

        parent::setUp();
    }

    public function testSendFailedTransactionSuccessfully()
    {
        $id_payer = $this->userService->store(new User($this->payer));
        $id_payee = $this->userService->store(new User($this->payee));
        $payer =  $this->userService->find($id_payer);
        $payee = $this->userService->find($id_payee);
        $this->notification["message"] = "Você recebeu uma transferência de {$payer->getName()} no valor de 100 R$.";
        $this->notification["user"] = $payee;
        $this->assertIsBool($this->notificationService->sendFailedTransaction(new Notification($this->notification)));
    }
}
