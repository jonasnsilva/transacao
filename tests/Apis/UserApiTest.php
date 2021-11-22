<?php



use Illuminate\Http\Response;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserApiTest extends TestCase
{

    use DatabaseTransactions;
    protected array $user;

    protected function setUp(): void
    {
        $faker = Faker\Factory::create('pt_BR');
        $this->user = [
            'name' => $faker->name,
            'email'=> $faker->email,
            'password' => $faker->password(8,10),
            'user_type' => 'C',
            'document' => $faker->cpf(false),
            'balance' => $faker->numerify("##.#")
        ];
        parent::setUp();
    }


    public function testRegisterUserCommomSuccessfully()
    {
        $this->post('/api/user', $this->user)
            ->seeJson(['code' => Response::HTTP_CREATED, 'message' => 'Usuário cadastrado com sucesso.'])
            ->assertResponseStatus(Response::HTTP_CREATED);
    }

    public function testRegisterUserShopKeeperSuccessfully()
    {
        $this->user["user_type"] = "S";
        $this->post('/api/user', $this->user)
            ->seeJson(['code' => Response::HTTP_CREATED, 'message' => 'Usuário cadastrado com sucesso.'])
            ->assertResponseStatus(Response::HTTP_CREATED);
    }

    public function testExistingUserRegistrationFailed()
    {
        $this->post('/api/user', $this->user);
        $this->post('/api/user', $this->user)
            ->seeJson(['code' => Response::HTTP_UNPROCESSABLE_ENTITY, 'message' => 'Já existe um usuário com esse documento ou email.'])
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testRequiredEmailUserRegistrationFailed()
    {
        $this->user["email"] = "";
        $this->post('/api/user', $this->user)
            ->seeJsonStructure(['code','message', 'errors' => ['email']])
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testRequiredDocumentUserRegistrationFailed()
    {
        $this->user["document"] = "";
        $this->post('/api/user', $this->user)
            ->seeJsonStructure(['code','message', 'errors' => ['document']])
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testRequiredNameUserRegistrationFailed()
    {
        $this->user["name"] = "";
        $this->post('/api/user', $this->user)
            ->seeJsonStructure(['code','message', 'errors' => ['name']])
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testRequiredUserTypeUserRegistrationFailed()
    {
        $this->user["user_type"] = "";
        $this->post('/api/user', $this->user)
            ->seeJsonStructure(['code','message', 'errors' => ['user_type']])
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testRequiredPasswordUserRegistrationFailed()
    {
        $this->user["password"] = "";
        $this->post('/api/user', $this->user)
            ->seeJsonStructure(['code','message', 'errors' => ['password']])
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testRequiredBalanceUserRegistrationFailed()
    {
        $this->user["balance"] = "";
        $this->post('/api/user', $this->user)
            ->seeJsonStructure(['code','message', 'errors' => ['balance']])
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testNegativeBalanceUserRegistrationFailed()
    {
        $this->user["balance"] = -1;
        $this->post('/api/user', $this->user)
            ->seeJsonStructure(['code','message', 'errors' => ['balance']])
            ->assertResponseStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }



}
