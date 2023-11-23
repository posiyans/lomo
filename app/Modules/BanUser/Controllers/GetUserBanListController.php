<?php

namespace App\Modules\BanUser\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\BanUser\Repositories\BanUserRepository;
use App\Modules\BanUser\Resources\BanUserResource;
use Illuminate\Http\Request;

/**
 * Получить список заблокированных пользователей
 *
 */
class GetUserBanListController extends Controller
{

    public function __construct()
    {
        $this->middleware('ability:superAdmin,comment-ban');
    }

    public function __invoke(Request $request)
    {
        try {
            $find = $request->get('find', false);
            $query = new BanUserRepository();
            if ($find) {
                $query->findUser($find);
            }
            $sort_by = $request->get('sortBy', 'created_at');
            $direction = $request->boolean('descending', true) ? 'asc' : 'desc';
            $query->sortBy($sort_by, $direction);
            $bans = $query->paginate($request->limit);
            return BanUserResource::collection($bans);
        } catch (\Exception $e) {
            return response(['errors' => $e->getMessage()], 420);
        }
    }


}
