<?php


namespace App\Models;


class Notification
{

    private ?int $id;
    private string $type;
    private string $send;
    private User $user;
    private string $title;
    private string $message;


    public function __construct(array $notification)
    {
        $this->type = $notification["type"];
        $this->send = $notification["send"];
        $this->user = $notification["user"];
        $this->title = $notification["title"];
        $this->message = $notification["message"];
        $this->id = array_key_exists('id', $notification) ? $notification["id"] : null;
    }



    public function getId(): ?int
    {
        return $this->id;
    }



    public function getType(): string
    {
        return $this->type;
    }

    public function getUser(): User
    {
        return $this->user;
    }


    public function getSend(): string
    {
        return $this->send;
    }


    public function getTitle(): string
    {
        return $this->title;
    }


    public function getMessage(): string
    {
        return $this->message;
    }

    public function setSend(string $send): void
    {
        $this->send = $send;
    }

}
