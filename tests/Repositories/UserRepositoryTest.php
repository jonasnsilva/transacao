<?php

use App\Exceptions\UserException;
use App\Models\User;
use App\Repositories\Interfaces\IUserRepository;
use App\Repositories\UserRepository;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    protected IUserRepository $repository;
    private array $user;

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

        $this->repository = new UserRepository();

        parent::setUp();
    }

    public function testStoreUserCommomSuccess()
    {

        $this->assertIsInt($this->repository->store(new User($this->user)));
    }

    public function testStoreUserShopKeeperSuccess()
    {
        $this->user["user_type"] = "S";
        $this->assertIsInt($this->repository->store(new User($this->user)));
    }

    public function testExistUserSuccess()
    {
        $user = new User($this->user);
        $this->repository->store($user);
        $this->assertTrue($this->repository->exists($user->getDocument(), $user->getEmail()));
    }

    public function testExistUserFail()
    {
        $user = new User($this->user);
        $this->assertFalse($this->repository->exists($user->getDocument(), $user->getEmail()));
    }

    public function testFindUserSuccess()
    {
        $id_user = $this->repository->store(new User($this->user));
        $this->assertIsObject($this->repository->find($id_user));
    }

    public function testFindUserFail()
    {
        $this->expectException(UserException::class);
        $this->expectExceptionMessage('Usuário não encontrado.');
        $this->repository->find(0);
    }

    public function testUpdateUserSuccess()
    {
        $id = $this->repository->store(new User($this->user));
        $this->user["name"] = "Testando método";
        $this->user["id"] = $id;
        $this->assertTrue($this->repository->update(new User($this->user)));
    }

    public function testUpdateUserFail()
    {
        $this->repository->store(new User($this->user));
        $this->user["name"] = "Testando método";
        $this->assertFalse($this->repository->update(new User($this->user)));
    }
}
