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
                    if ($camera_rtsp) {
                        $file = __DIR__ . '/../../../../storage/app/file/camera/' . $camera.'_' . $date . '.jpg';
                        $this->rtspToJpeg($camera_rtsp, $file);
                    }
                } else {
                    $value = Cache::get('Img'.$camera);
                }
                echo $value.'ok <br>';
            }
            echo $date.' <br>';
        }
    }

    public function rtspToJpeg($camera_rtsp, $file, $count = 2){
        $ffmpeg = env('FFMPEG_BIN',false);
        if ($ffmpeg) {
            shell_exec($ffmpeg . " -rtsp_transport tcp  -y -i " . $camera_rtsp . " -f image2 -vframes 1 " . $file);
            $size = env('CAMERA_IMG_MIN_FILE_SIZE',10000);
            if (filesize($file) < $size && $count > 0) {
                $today = date("Y-m-d H:i:s");
                $file_log = __DIR__ . '/../../../../storage/app/file/camera/log.txt';
//                $current = file_get_contents($file_log);
                $current = $today. ' - '. $camera_rtsp. ' - size: '. filesize($file).' - count :'. $count . "\n";
                file_put_contents($file_log, $current, FILE_APPEND | LOCK_EX);
                $count = $count - 1;
                $this->rtspToJpeg($camera_rtsp, $file, $count);
            }
        }
        return true;
    }

    public function getImages($camera = false){
        if(!$camera) {
            $camera = 1;
        }
        if ($camera == 'gif1'){
            $path = __DIR__ . '/../../../../storage/app/file/camera/GIF_CAMERA1.gif' ;
            if (file_exists($path)) {
                return response()->download($path, 'cam1.gif');
            }
        }
        if ($camera == 'gif2'){
            $path = __DIR__ . '/../../../../storage/app/file/camera/GIF_CAMERA2.gif' ;
            if (file_exists($path)) {
                return response()->download($path, 'cam2.gif');
            }
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

    public function createGif($token = false){
        ignore_user_abort(true);
        set_time_limit(500);
        if($token === env('CAMERA_TOKEN',false) && $conver = env('CONVERT_BIN',false) ) {
            $delay = env('CONVERT_DELAY',10);
            $directory = __DIR__ . '/../../../../storage/app/file/camera/';
            $count = env('CAMERA_COUNT',0);
            if ($count > 0){
                for ($i=1; $i<=$count; $i++) {
                    $camera = 'CAMERA' . $i;
                    shell_exec($conver . ' -delay ' . $delay . ' -loop 0 ' . $directory . $camera . '_*.jpg ' . $directory . 'GIF_' . $camera . '.gif');
                }
            }
            return 'yes';
        }
        return 'no';
    }


}
