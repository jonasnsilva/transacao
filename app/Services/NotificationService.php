<?php


namespace App\Services;


use App\Exceptions\NotificationException;
use App\Jobs\SendNotificationTransactionJob;
use App\Models\Enum\NotificationTypeEnum;
use App\Models\Enum\SendNotificationEnum;
use App\Models\Notification;
use App\Models\User;
use App\Repositories\Interfaces\INotificationRepository;
use App\Repositories\NotificationRepository;
use App\Services\Interfaces\INotificationService;
use App\Services\Interfaces\IUserService;
use Illuminate\Http\Response;

class NotificationService implements INotificationService
{

    const URL = 'http://o4d9z.mocklab.io/notify';

    private INotificationRepository $repository;
    private IUserService $userService;

    public function __construct(
        NotificationRepository $repository,
        UserService $userService

    )
    {
        $this->repository = $repository;
        $this->userService = $userService;
    }
    public function sendTransaction(User $payer, User $payee, float $value): void
    {

       $notification = new Notification([
           'type' => NotificationTypeEnum::TRANSACTION,
           'send' => SendNotificationEnum::NO,
           'user' => $payee,
           'title' => 'Você recebeu uma transferência.',
           'message' => "Você recebeu uma transferência de {$payer->getName()} no valor de {$value} R$."]);

        $notification->setSend(
            $this->repository->sendTransaction($notification) ?
                SendNotificationEnum::YES :
                SendNotificationEnum::NO
                              );

        $id = $this->repository->store($notification);

        if($notification->getSend() === SendNotificationEnum::NO)
        {
            dispatch(new SendNotificationTransactionJob($id));
        }

    }

    public function sendFailedTransaction(Notification $notification): bool
    {
        return $this->repository->sendTransaction($notification);
    }

    public function find(int $id): Notification
    {
        $notification = $this->repository->find($id);
        if($notification)
        {
            $notification = (array)$notification;
            $notification["user"] = $this->userService->find($notification["id_user"]);
            return new Notification($notification);
        } else {
            throw new NotificationException('Notificação não encontrado.', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
    }

    public function update(Notification $notification): bool
    {
        return $this->repository->update($notification);
    }
}
