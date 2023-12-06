<?php

namespace App\Modules\Article\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Article\Repositories\StatusArticleRepository;
use Illuminate\Http\Request;

/**
 * получить список возможных статусов статей
 *
 */
class StatusGetListController extends Controller
{


    /**
     * проверка на суперадмин или на доступ в админ панель
     */
    public function __construct()
    {
//        $this->middleware('ability:superAdmin,access-admin-panel');
    }

    public function index(Request $request): array
    {
        return StatusArticleRepository::getStatus();
    }
}
