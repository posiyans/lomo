<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    //
    public static function updateSort(array $data){
        foreach ($data as $item){
            $category = CategoryModel::find($item['id']);
            $category->parent = null;
            $category->save();
            self::saveChildren($item);
        }
        return true;
    }

    public static function saveChildren(array $data){
        if (key_exists('children', $data)){
            foreach ($data['children'] as $i) {
                $category = CategoryModel::find($i['id']);
                $category->parent = $data['id'];
                $category->save();
                self::saveChildren($i);
            }
        }
        return true;
    }

    public function getChildren(){
        $category = CategoryModel::where('parent', $this->id)->get();
        if ($category) {
            $rez = [];
            foreach ($category as $item) {
                $child = $item->getChildren();
                $i['id'] = $item->id;
                $i['basePath'] = '/article/list/'.$item->id;
                $i['label'] = $item->name;
//                if ($item->menu_name){
//                    $i['basePath'] = $item->menu_name;
//                } else {
//                    $i['basePath'] = '/';
//                }
                if ($child){
                    $i['children'] = $child;
                }
                $rez[] = $i;
            }
            return $rez;
        }
        return false;
    }
    public static function getListChildren(){
        $category = self::whereNull('parent')->get();
        $rez = [];
//        $rez[] = ['label'=>'Главная', 'basePath'=>'/index'];
        foreach ($category as $item) {
            $child = $item->getChildren();
            $i['id'] = $item->id;
            $i['basePath'] = '/article/list/'.$item->id;
//            if ($item->menu_name){
//                $i['basePath'] = $item->menu_name;
//            } else {
//                $i['basePath'] = '/';
//            }
            $i['menu'] = $item->menu_name;
//            $i['basePath'] = '/icon';
            $i['label'] = $item->name;
            if ($child){
                $i['children'] = $child;
            }
            $rez[] = $i;
        }
        return $rez;
    }

}
