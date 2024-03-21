<?php

namespace App\Modules\Camera\Actions;

/**
 * создать камеру
 *
 */
class Command
{

    /**
     * Пример использования
     *
     * PsExecute('ffmpeg -i video_file.flv', 5, 1, '/tmp/1.txt');
     * Если за 5 секунд ffmpeg не завершится, то он будет завершен автоматически.
     *
     * Run process with timeout
     * @param str $command
     * @param int $timeout - sec
     * @param int $sleep
     * @param str $file_out_put - if default value, then return true else return out of process
     * @return bool or str
     */
    function PsExecute($command, $timeout = 10, $sleep = 1, $file_out_put = '/dev/null')
    {
        $pid = $this->PsExec($command, $file_out_put);

        if ($pid === false) {
            return false;
        }

        $cur = 0;

        // пока не истекло время отведенное на выполнение скрипта продолжаем ждать
        while ($cur < $timeout) {
            sleep($sleep);
            $cur += $sleep;

            if (!$this->PsExists($pid)) {
                // скрипт завершил своё выполнение, можно посмотреть его результат или просто вернуть true
                if ($file_out_put != '/dev/null') {
                    return file_get_contents($file_out_put);
                } else {
                    return true;
                }
            }
        }

        // не дождались пока звершиться скрипт, по этому автоматически убиваем его
        $this->PsKill($pid);
        return false;
    }

    /**
     * Run process in background with out buffer to file
     * @param str $commandJob
     * @param str $file_out_put
     * @return int or false
     */
    function PsExec($commandJob, $file_out_put)
    {
        $command = $commandJob . ' > ' . $file_out_put . ' 2>&1 & echo $!';
        exec($command, $op);
        $pid = (int)$op[0];

        if ($pid != "") {
            return $pid;
        }

        return false;
    }

    /**
     * If process exists then return true else return false
     * @param int $pid
     * @return bool
     */
    function PsExists($pid)
    {
        exec("ps ax | grep $pid 2>&1", $output);

        while (list(, $row) = each($output)) {
            $row_array = explode(" ", $row);
            $check_pid = $row_array[0];

            if ($pid == $check_pid) {
                return true;
            }
        }

        return false;
    }

    /**
     * Kill process
     * @param int $pid
     */
    function PsKill($pid)
    {
        exec("kill -9 $pid", $output);
    }
}
