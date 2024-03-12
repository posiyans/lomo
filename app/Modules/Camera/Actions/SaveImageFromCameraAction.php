<?php

namespace App\Modules\Camera\Actions;

use Illuminate\Support\Facades\Storage;

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
        $ffmpeg = env('FFMPEG_BIN', '/usr/bin/ffmpeg');
        $full_path = '';
        if ($ffmpeg) {
            $tmpfname = tempnam(sys_get_temp_dir(), "camera_" . $this->camera->id);
            $cmd = $ffmpeg . " -rtsp_transport tcp  -y -i " . $this->camera->url . " -f image2 -vframes 1 " . $tmpfname;
            shell_exec($cmd);
            $size = env('CAMERA_IMG_MIN_FILE_SIZE', 10000);
            if (!file_exists($tmpfname) || filesize($tmpfname) < $size) {
                if ($count > 0) {
                    $count = $count - 1;
                    return $this->rtspToJpeg($count);
                } else {
                    return false;
                }
            } else {
                $preview_path = 'camera/id_' . $this->camera->id . '/' . $y . '/' . $m;
                $preview_name = 'camera_' . $this->camera->id . '_' . date('Y-m-d_H:i:s') . '.jpg';
                Storage::putFileAs($preview_path, $tmpfname, $preview_name);
                $full_path = $preview_path . '/' . $preview_name;
            }
        }
        return $full_path;
    }


}
