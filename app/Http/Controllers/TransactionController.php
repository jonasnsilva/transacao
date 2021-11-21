<?php

namespace App\Http\Controllers;

use App\Exceptions\TransactionException;
use App\Exceptions\UserException;
use App\Http\Requests\StoreTransactionRequest;
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
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{

    private ITransactionService $transactionService;
    private IUserService $userService;

    public function __construct()
    {

        $this->userService = new UserService(new UserRepository());
        $this->transactionService = new TransactionService(
            new TransactionRepository(),
            new AuthorizationService(new AuthorizationRepository()),
            $this->userService,
            new NotificationService(new NotificationRepository(), $this->userService)
        );
    }

    public function store(StoreTransactionRequest $request)
    {

        try {
            $errors = $request->validate();

            if (is_null($errors)) {
                $payer = $this->userService->find($request->id_payer);
                $payee = $this->userService->find($request->id_payee);

                return $this->transactionService->store($payer, $payee,$request->value);
            } else {
                return $errors;
            }
        } catch (TransactionException $te)
        {
            return response()->json(['code' => $te->getCode(), 'message' => $te->getMessage()], $te->getCode());
        }
        catch (UserException $ue)
        {
            return response()->json(['code' => $ue->getCode(), 'message' => $ue->getMessage()], $ue->getCode());
        } catch (Exception $e)
        {
            DB::rollBack();
            return response()->json([
                'code' => Response::HTTP_BAD_REQUEST,
                'message' => $e->getMessage()
            ], Response::HTTP_BAD_REQUEST);
        }

    }
}
