<?php

namespace App\Http\Controllers;

use App\Models\Stead;
use App\Models\Temper\TemperModel;
use Illuminate\Http\Request;
use Socialite;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
//        $temper = TemperModel::getTemper();
        $temper = [];
        return view('welcome', compact('temper'));
    }

    public function test()
    {
//        $row = 1;
//        if (($handle = fopen(__DIR__."/Reestr_LOMO.csv", "r")) !== FALSE) {
//            while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
//                $stead = new Stead();
//                $stead->gardening_id = 1;
//                $stead->number = str_replace("'", '', $data[0]);
//                $stead->size = (int)$data[4];
//                $discr = [];
//                $discr['fio'] = trim(str_replace("'", '', str_replace("  ", ' ', $data[1])));
//                $discr['kadastr'] = trim(str_replace("'", '', $data[3]));
//                $discr['address'] = trim(str_replace("'", '', $data[2]));
//                $stead->discriptions = $discr;
////                $stead->save();
////                dump($stead);
//                echo "Участок ". $data[0] . "<br />\n";
////                $num = count($data);
////                echo "<p> $num полей в строке $row: <br /></p>\n";
////                $row++;
////                for ($c=0; $c < $num; $c++) {
////                    echo $data[$c] . "<br />\n";
////                }
//            }
//            fclose($handle);
//        }
//        return 'fsdfsdfs';
//        $directory = __DIR__.'/rosreestr_json';
//        $scanned_directory = array_diff(scandir($directory), array('..', '.'));
//        foreach ($scanned_directory as $r) {
////            echo $r;
////            echo "<br>";
////            47:23:2304001:102 .json
//        }
//        $data = [];
//        $steads = Stead::query()->limit(10)->get();
        $steads = Stead::all();
        $data = [];
        foreach ($steads as $item) {
            if (isset($item->descriptions['geodata'])) {
                $js=$item->descriptions['geodata'];
                $temp = [
                    'center' => [$js['properties']['center']['y'],$js['properties']['center']['x']],
                    'size' => $item->size,
                    'number' => $item->number
                ];
                if ($item->number == 400) {
                    $temp['color'] = ['color' => '#00ff00', 'opacity' => '0.4'];
                } else {
                    $temp['color'] = ['color' => '#ff0000', 'opacity' => '0.4'];
                }
                $temp['cols'] = '#00ff00';
                if (isset($js['geometry']['coordinates'])) {
                    $ks = $js['geometry']['coordinates'][0][0];
                    $l=[];
                    foreach ($ks as $k) {
                        $l[]=[$k[1] + 0.00004500100000, $k[0] + 0.00006000100000];
                    }
                    $temp['krd'] = [$l, []];
                }
                $data[] = $temp;
            }
        }
        return json_encode($data);
    }
}
