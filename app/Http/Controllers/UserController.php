<?php

namespace App\Http\Controllers;

use App\Exceptions\UserException;
use App\Http\Requests\StoreUserRequest;
use App\Models\User;
use App\Repositories\UserRepository;
use App\Services\Interfaces\IUserService;
use App\Services\UserService;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class UserController extends Controller
{
    //
    private IUserService $userService;

    public function __construct()
    {
        $this->userService = new UserService(new UserRepository());
    }

    public function store(StoreUserRequest $request): ?JsonResponse
    {
        try {

            $errors = $request->validate();
            if (is_null($errors)) {
                if($this->userService->store(new User($request->toArray())))
                {
                    return response()->json([
                        'code' => Response::HTTP_CREATED,
                        'message' => 'Usuário cadastrado com sucesso.'
                    ], Response::HTTP_CREATED);
                }
            } else {
                return $errors;
            }

        } catch (UserException $ue)
        {
            return response()->json(['code' => $ue->getCode(), 'message' => $ue->getMessage()], $ue->getCode());
        } catch (Exception $e)
        {
            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

    }
}
