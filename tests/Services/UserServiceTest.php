<?php

use App\Exceptions\UserException;
use App\Models\User;
use App\Services\Interfaces\IUserService;
use App\Services\UserService;
use App\Repositories\UserRepository;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserServiceTest extends TestCase
{

    use DatabaseTransactions;

    private array $user;
    private IUserService $userService;

    protected function setUp(): void
    {
        $faker = Faker\Factory::create('pt_BR');
        $this->user = [
            'name' => $faker->name,
            'email'=> $faker->email,
            'password' => $faker->password,
            'user_type' => 'C',
            'document' => $faker->cpf(false),
            'balance' => $faker->numerify("##.#")
        ];

        $this->userService = new UserService(new UserRepository());

        parent::setUp();
    }


    public function testStoreUserCommomSuccess()
    {

        $this->assertIsInt($this->userService->store(new User($this->user)));
    }

    public function testStoreUserShopKeeperSuccess()
    {
        $this->user["user_type"] = "S";
        $this->assertIsInt($this->userService->store(new User($this->user)));
    }

    public function testStoreUserFail()
    {

        $this->expectException(UserException::class);
        $this->expectExceptionMessage('Já existe um usuário com esse documento ou email.');
        $this->userService->store(new User($this->user));
        $this->userService->store(new User($this->user));
    }

    public function testFindUserSuccess()
    {

        $id = $this->userService->store(new User($this->user));
        $user = $this->userService->find($id);
        $this->assertIsObject($user);
    }

    public function testFindUserFail()
    {
        $this->expectException(UserException::class);
        $this->expectExceptionMessage('Usuário não encontrado.');
        $this->userService->find(0);
    }

    public function testUpdateUserSuccess()
    {
        $id = $this->userService->store(new User($this->user));
        $this->user["name"] = "Testando método";
        $this->user["id"] = $id;
        $this->assertTrue($this->userService->update(new User($this->user)));
    }

    public function testUpdateUserFail()
    {
        $this->userService->store(new User($this->user));
        $this->user["name"] = "Testando método";
        $this->assertFalse($this->userService->update(new User($this->user)));
    }

    public function testAddValueUserSuccess()
    {
        $id = $this->userService->store(new User($this->user));
        $this->user["id"] = $id;
        $this->assertTrue($this->userService->addValue(new User($this->user), 5));
    }

    public function testAddValueUserFail()
    {
        $this->userService->store(new User($this->user));
        $this->assertFalse($this->userService->addValue(new User($this->user), 5));
    }

    public function testRemoveValueUserSuccess()
    {
        $id = $this->userService->store(new User($this->user));
        $this->user["id"] = $id;
        $this->assertTrue($this->userService->addValue(new User($this->user), 5));
    }

    public function testRemoveValueUserFail()
    {
        $this->userService->store(new User($this->user));
        $this->assertFalse($this->userService->addValue(new User($this->user), 5));
    }
}
