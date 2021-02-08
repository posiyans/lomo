<?php

namespace App\Http\Controllers\User\Voting;


use App\Models\AppealModel;
use App\Models\Stead;
use App\Models\Voting\VotingFileModel;
use App\Models\Voting\VotingModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Mail;
use Ramsey\Uuid\Uuid;


class UserVotingFileUploadController extends Controller
{

    public function upload(Request $request)
    {
        if ($request->voting && $request->stead_id  && $request->phone && Input::hasFile('file')) {
            $voitng_id = $request->voting;
            $stead_id = $request->stead_id;
            $phone = $request->phone;
            $voting = VotingModel::find($voitng_id);
            $stead = Stead::find($stead_id);
            if ($voting && $stead) {
                $file = new VotingFileModel();
                $inputFile = Input::file('file');
                $md5 = Controller::md5_file($inputFile);
                $inputFile->move($md5['folder'], $md5['md5']);
                $file->hash = $md5['md5'];
                $file->uid = Uuid::uuid4()->toString();
                $file->name = $inputFile->getClientOriginalName();
                $file->size = $inputFile->getSize();
                $file->type = $inputFile->getClientMimeType();
                $file->voting_id = $voitng_id;
                $file->description = 'phone=' . $phone . ', stead=' . $stead_id . ', voting=' . $voitng_id;
                $file->logAndSave('Загружен бюллетень', $stead_id);
                self::createAppeal($inputFile, $file->uid, $phone, $stead->number, $voitng_id);
                if (self::sendEmail($inputFile, $md5['folder'] .'/'. $md5['md5'],  $phone, $stead->number)) {
                    return ['status' => true, 'data' => 'Фаил успешно отправлен'];
                }
            }
        }
        return ['status' => false, 'data' => 'Ошибка отправки'];
    }


    public static function sendEmail($inputFile, $path, $phone, $stead_number)
    {
        $mails = env('VOTING_MAILS', false);
        if ($mails) {
            $mails = explode(',', $mails);
                $text = 'Добавлен бюллетень для обработки на сайте. Участок № ' . $stead_number;
                $text .= "\n" . 'Номер телефона ' . $phone;
                $text .= "\n\n\n" . 'С Уважением Администрация сайта.';

                Mail::raw($text, function ($message) use ($mails, $phone, $stead_number, $inputFile, $path) {
                    $message->to($mails);
                    $message->subject('Добавлен бюллетень уч. ' . $stead_number . ' тел. '. $phone);
                    $message->attach(
                        $path,
                        [
                            'as' => $inputFile->getClientOriginalName(),
                            'mime' => $inputFile->getClientMimeType()
                        ]
                    );
                });
                return true;
        }
        return false;
    }

    public static function createAppeal($inputFile, $uid, $phone, $stead_number, $voting)
    {
        $appeal = new AppealModel();
        $appeal->title = 'Загружен бюллетень уч. № ' . $stead_number. ' тел. ' . $phone;
        $appeal->type = 'voting_' . $voting;
        $text = '<a href="/api/v1/admin/voting/owner/get-file?uid='.$uid.'" >Файл: ' . $inputFile->getClientOriginalName().'</a>';
        $appeal->text = $text;
        $appeal->save();
    }
}
