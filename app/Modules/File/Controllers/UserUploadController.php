<?php

namespace App\Modules\File\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\File\Classes\SaveFileForObjectClass;
use App\Modules\File\Classes\TempDirectoryPathClass;
use App\Modules\File\Repositories\GetModelNameByType;
use App\Modules\File\Resources\FileResource;
use Illuminate\Http\Request;

/**
 * получить список категорий статей
 *
 */
class UserUploadController extends Controller
{


    /**
     * проверка на суперадмин или на доступ в админ панель
     */
    public function __construct()
    {
//        $this->middleware('ability:superAdmin,access-admin-panel');
    }

    public function index(Request $request)
    {
        $type = $request->get('action', false);
        $tmpDir = (new TempDirectoryPathClass())->get();
        $file_path = $tmpDir . '/' . $request->uid;
        if ($request->chunk === 0) {
            $this->deleteTempFile($file_path);
        }
        $inputFile = $request->file;
        $this->saveChunk($file_path, $inputFile);
        if ($type == 'done') {
//            return [$request->model, $request->model_uid];
            $class = (new GetModelNameByType($request->model))->run();
//            return $model;
            $model = new $class();
            $model->uid = $request->model_uid;
            $file = (new SaveFileForObjectClass($model))->file($file_path)->name($request->name)->size($request->size)->type($request->type)->uid($request->uid)->run();
            $this->deleteTempFile($file_path);
            return new FileResource($file);
        }
        return 'ok';
    }

    private function saveChunk($file_path, $file_chunk)
    {
        $fout = fopen($file_path, "ab");
        $fin = fopen($file_chunk, "rb");
        if ($fin) {
            while (!feof($fin)) {
                $data = fread($fin, 5 * 1024 * 1024);
                fwrite($fout, $data);
            }
            fclose($fin);
        }
        fclose($fout);
    }

    private function deleteTempFile($path)
    {
        if (file_exists($path)) {
            unlink($path);
        }
    }

}
