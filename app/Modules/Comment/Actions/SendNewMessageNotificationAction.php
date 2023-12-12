<?php

namespace App\Modules\Comment\Actions;

use App\Modules\Comment\Models\CommentModel;
use App\Modules\Comment\Notifications\AdminNewCommentsNotification;
use App\Modules\User\Models\UserModel;

class SendNewMessageNotificationAction
{
    private $comment;

    public function __construct(CommentModel $comment)
    {
        $this->comment = $comment;
    }


    public function run()
    {
        $usersNotification = UserModel::whereRoleIs(['superAdmin'])->get();
        foreach ($usersNotification as $user) {
            \Notification::send($user, new AdminNewCommentsNotification($this->comment));
        }
    }


}