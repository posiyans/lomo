<?php

namespace App\Modules\BanUser\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\BanUser\Repositories\BanUserRepository;
use Illuminate\Http\Request;

use function response;

/**
 *
 * Удалить бан
 *
 */
class DeleteUserBanController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,comment-ban');
    }

    public function __invoke(Request $request)
    {
        try {
            $ban_uid = $request->ban_uid;
            $ban = (new BanUserRepository())->byUid($ban_uid)->one();
            $ban->logAndDelete('Удаление бана');
            return ['status' => true];
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 410);
        }
    }


}
