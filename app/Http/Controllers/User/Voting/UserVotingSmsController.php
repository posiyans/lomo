<?php

namespace App\Http\Controllers\User\Voting;


use App\Models\Notification\Sms\Sms;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cookie;


class UserVotingSmsController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function send(Request $request)
    {
        $phone = $request->phone;
        if ($this->sanitazePhone($phone)) {
            if (Cookie::has('snt_vt_ph')) {
                $array = json_decode(Cookie::get('snt_vt_ph'));
                if (in_array($this->sanitazePhone($phone), $array)) {
                    return ['status'=> true, 'send' => false];
                }
            }
            $count_sms = Cookie::get('count_sms', 0);
            if ($count_sms < 3) {
                $minutes = 60 * 3;
                if ($this->sendSms($this->sanitazePhone($phone))) {
                    Cookie::queue('count_sms', ++$count_sms, $minutes);
                    return ['status' => true, 'send' => true];
                }
            } else {
                return ['status' => false, 'data' => 'Превышенно кол-во отправленных СМС за сутки.'];
            }
        }

        return ['status'=> false, 'data' => 'Ошибка отправки СМС'];

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function valid(Request $request)
    {
        $phone = $request->phone;
        $code = $request->code;
        $code = str_replace('-', '', $code);
        if ($this->sanitazePhone($phone)) {
            if (Sms::validCode($this->sanitazePhone($phone), $code)) {
//            if ($code == 1234) {
                $minutes = 60*24*30;
                Cookie::queue('snt_vt_ph', json_encode([$this->sanitazePhone($phone)]), $minutes);
                return ['status' => true, 'data' => ['phone' => $phone]];
            }
        }
        return ['status' => false, 'data' => 'Код не найден'];
    }



    public function sanitazePhone($phone) {
        $phone = str_replace(' ', '', $phone);
        $phone = str_replace('(', '', $phone);
        $phone = str_replace(')', '', $phone);
        $phone = str_replace('-', '', $phone);
        $phone = str_replace('+', '', $phone);
        if (is_numeric($phone) && preg_match('/^[0-9]{11}+$/', $phone)) {
            return $phone;
        }
        return false;
    }

    public function sendSms($phone)
    {
        return Sms::sendCode($phone);
    }
}
