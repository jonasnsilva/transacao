<?php


namespace App\Services;


use App\Exceptions\TransactionException;
use App\Models\Enum\NotificationTypeEnum;
use App\Models\Enum\UserTypeEnum;
use App\Models\Transaction;
use App\Models\User;
use App\Repositories\Interfaces\ITransactionRepository;
use App\Repositories\TransactionRepository;
use App\Services\Interfaces\IAuthorizationService;
use App\Services\Interfaces\INotificationService;
use App\Services\Interfaces\ITransactionService;
use App\Services\Interfaces\IUserService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class TransactionService implements ITransactionService
{
    private ITransactionRepository $repository;
    private IAuthorizationService $authorizationService;
    private IUserService $userService;
    private INotificationService $notificationService;

    public function __construct(
        TransactionRepository $repository,
        AuthorizationService $authorizationService,
        UserService $userService,
        NotificationService $notificationService
    )
    {
        $this->repository = $repository;
        $this->authorizationService = $authorizationService;
        $this->userService = $userService;
        $this->notificationService = $notificationService;
    }

    public function store(User $payer, User $payee, $value): int
    {
        if($this->validateTransaction($payer, $value))
        {
            return DB::transaction(function () use ($payer,$payee, $value) {
                $this->userService->removeValue($payer, $value);
                $this->userService->addValue($payee, $value);

                $id_transaction = $this->repository->store(new Transaction(
                    ["value" => $value, "payer" => $payer, "payee" => $payee]
                ));
                if ($id_transaction)
                {
                    $this->notificationService->sendTransaction($payer, $payee, $value);
                }

                return $id_transaction;

            });
        }
    }

    public function validateTransaction(User $payer, float $value): bool
    {
        if($payer->getUserType() !== UserTypeEnum::COMMON)
        {
            throw new TransactionException('Pagador n??o ?? do tipo comum.');
        }

        if($payer->getBalance() < $value)
        {
            throw new TransactionException('Pagador n??o possui saldo suficiente para realizar a transa????o.');
        }
        if(!$this->authorizationService->transactionAuthorization())
        {
            throw new TransactionException('Transa????o n??o autorizada.');
        }

        return true;
    }
}
