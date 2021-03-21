<?php

namespace App\Http\Controllers\Admin\Bookkeeping\Billing\Balance\BalanceList;

use App\Models\Stead;
use Illuminate\Http\Request;


class GetListController
{




    /**
     * Добавить счета  группк показаний
     *
     * @return \Illuminate\Http\Response
     */
    public static function getData(Request $request)
    {
        $query = Stead::query();
        $zeroline = $request->get('zeroLine', 0);
        if ($request->find) {
            $query->where('number', 'like', "%$request->find%");
        }
        $steads = $query->get();

        if ($request->category || $request->payment) {
            $data = [];
            if ($request->category) {
                foreach ($steads as $stead) {
                    $balans = $stead->getBalans($request->get('receipt_type', false));
                    if ($request->category == 1 && $balans >= $zeroline) {
                        $data[] = $stead;
                    } else if ($request->category == 2 && $balans < $zeroline) {
                        $data[] = $stead;
                    }
                }
            }
        } else {
            $data = $steads->all();
        }
        return $data;
    }


}
