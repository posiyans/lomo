<?php

namespace App\Modules\Camera\Actions;

/**
 * получить список камер
 *
 */
class SaveImageFromCameraAction
{
    private $camera;

    public function __construct($camera)
    {
        $this->camera = $camera;
    }

    public function run()
    {
        ignore_user_abort(true);
        set_time_limit(500);
        return $this->rtspToJpeg();
    }

    /**
     * получить jpg из rtsp потока
     *
     * @param int $count макс кол-во неудачных попыток
     * @return bool
     */
    private function rtspToJpeg($count = 2)
    {
        $y = date('Y');
        $m = date('m');
        $folder = __DIR__ . '/../../../../storage/app/file/camera/id_' . $this->camera->id . '/' . $y . '/' . $m;
        if (!file_exists($folder)) {
            umask(0);
            mkdir($folder, 0777, true);
        }
        $file = $folder . '/' . date('Y-m-d_H:i:s') . '.jpg';
        $ffmpeg = env('FFMPEG_BIN', '/usr/bin/ffmpeg');

        if ($ffmpeg) {
            $cmd = $ffmpeg . " -rtsp_transport tcp  -y -i " . $this->camera->url . " -f image2 -vframes 1 " . $file;
            shell_exec($cmd);
            $size = env('CAMERA_IMG_MIN_FILE_SIZE', 10000);
            if (!file_exists($file) || filesize($file) < $size) {
                if ($count > 0) {
                    $count = $count - 1;
                    return $this->rtspToJpeg($count);
                } else {
                    return false;
                }
            }
        }
        return $file;
    }


}
