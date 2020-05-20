<?php

namespace App\Http\Controllers\Camera;

use Illuminate\Support\Facades\Cache;
use Socialite;
use App\Http\Controllers\Controller;

class CameraController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       //$this->middleware('auth');
    }


    public function index()
    {
        ignore_user_abort(true);
        set_time_limit(500);
        $count = env('CAMERA_COUNT',0);

        if ($count > 0){
            $date = date_create();
            $date = date_timestamp_get($date);
            for ($i=1; $i<=$count; $i++){
                $camera = 'CAMERA'.$i;
                if (!Cache::has('Img'.$camera)) {
                    $value = Cache::remember('Img'.$camera, 600, function () use ($date) {
                        echo 'update ';
                        return $date;
                    });
                    $camera_rtsp = env($camera,false);
                    $ffmpeg = env('FFMPEG_BIN',false);
                    if ($camera_rtsp && $ffmpeg) {
                        $file = __DIR__ . '/../../../../storage/app/file/camera/' . $camera.'_' . $date . '.jpg';
                        shell_exec($ffmpeg." -rtsp_transport tcp  -y -i " . $camera_rtsp . " -f image2 -vframes 1 " . $file);
                    }
                } else {
                    $value = Cache::get('Img'.$camera);
                }
                echo $value.'ok <br>';
            }
            echo $date.' <br>';
        }
    }


    public function getImages($camera = false){
        if(!$camera) {
            $camera = 1;
        }
        $directory = __DIR__ . '/../../../../storage/app/file/camera/';
        $scanned_directory = array_diff(scandir($directory), array('..', '.'));
        sort($scanned_directory);
        $file = [];
        foreach ($scanned_directory as $item) {
            $cam = explode('_', $item);
            $file[$cam[0]] = $item;
        }
        if (isset($file['CAMERA'.$camera])){
             $path = __DIR__ . '/../../../../storage/app/file/camera/' . $file['CAMERA'.$camera];
            if (file_exists($path)) {
                return response()->download($path, $file['CAMERA'.$camera]);
            }
        }

    }

}
