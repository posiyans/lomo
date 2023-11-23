<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $paginate_limit = 15;

    public static function md5_file($file)
    {
        $md5 = md5_file($file);
        $md5_sm = substr($md5, 0, 2);
        $folder = config('filesystems.file_folder');
        if (!file_exists($folder . '/' . $md5_sm)) {
            mkdir($folder . '/' . $md5_sm, 0777, true);
        }
        return ['md5' => $md5, 'folder' => $folder . '/' . $md5_sm];
    }

    public function save_file($file)
    {
        $md5 = $this->md5_file($file);
        if ($file->move($md5['folder'], $md5['md5'])) {
            return $md5['md5'];
        } else {
            return false;
        }
    }

    /**
     * пагинация для массива
     *
     * @param $array
     * @param  int  $page
     * @param  int  $limit
     * @return array
     */
    public function paginate($array, $limit = false, $page = false)
    {
        $page = $page ? $page : request()->get('page', 1);
        $limit = $limit ? $limit : request()->get('limit', 20);
        if ($page < 1) {
            $page = 1;
        }
        $this->offset = ($page - 1) * $limit;
        return array_slice($array, $this->offset, $limit);
    }

    /**
     * установить лимит для пагинации
     * @param  false  $limit
     * @return $this
     */
    public function setPainateLimit($limit = false)
    {
        $this->paginate_limit = $limit;
        return $this;
    }
}
