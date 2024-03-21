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
    private $timeout = 25;

    public function __construct($camera)
    {
        $this->camera = $camera;
    }

    public function run()
    {
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
            $this->PsExecute($cmd, $tmpfname);
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


    function PsExec($commandJob)
    {
        $command = $commandJob . ' & echo $!';
        exec($command, $op);
        $pid = (int)$op[0];

        if ($pid != "") {
            return $pid;
        }

        return false;
    }

    function PsExecute($command)
    {
        $pid = $this->PsExec($command);
        if ($pid === false) {
            return false;
        }

        $cur = 0;
        // пока не истекло время отведенное на выполнение скрипта продолжаем ждать
        while ($cur < $this->timeout) {
            sleep(1);
            $cur += 1;
        }
        exec("kill -9 $pid", $output);
    }


}
