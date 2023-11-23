<?php

namespace App\Modules\BanUser\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\BanUser\Repositories\BanUserRepository;
use Illuminate\Http\Request;

use function response;

/**
 *
 * Изменить бан
 *
 */
class UpdateUserBanController extends Controller
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
            // todo сделать валидацию
//            $ban->fill($request->all());
            $ban->end_ban_time = $request->input('end_ban_time', null);
            $ban->logAndSave('Изменение бана');
            return ['status' => true];
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 410);
        }
    }


}
