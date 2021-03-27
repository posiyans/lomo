<?php

namespace App\Http\Controllers\Camera;

use Illuminate\Support\Facades\Cache;
use Socialite;
use App\Http\Controllers\Controller;

class Camera
{

    public static function updateCache($force = false)
    {
        $optionName = 'siteCameraSetting';
        $camera = GlobalOptionModel::getOptionList($optionName);
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
