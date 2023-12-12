<?php

namespace App\Modules\Comment\Actions;

use App\Modules\Comment\Notifications\CensorCommentsNotification;
use App\Modules\User\Models\UserModel;

class SendCensorNotificationAction
{
    private $user;
    private $text;


    public function __construct(UserModel $user, $text)
    {
        $this->user = $user;
        $this->text = $text;
    }


    public function run()
    {
        $usersNotification = UserModel::whereRoleIs(['superAdmin'])->get();
        foreach ($usersNotification as $user) {
            \Notification::send($user, new CensorCommentsNotification($this->user, $this->text));
        }
    }


}