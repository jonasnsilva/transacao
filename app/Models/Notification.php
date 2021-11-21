<?php


namespace App\Models;


class Notification
{

    private string $type;
    private string $send;
    private User $user;


    public function __construct(array $notification)
    {
        $this->type = $notification["type"];
        $this->send = $notification["send"];
        $this->user = $notification["user"];
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getSend(): string
    {
        return $this->send;
    }

}
