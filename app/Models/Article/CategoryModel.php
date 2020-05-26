<?php

namespace App\Models\Article;

use Illuminate\Database\Eloquent\Model;

class CategoryModel extends Model
{
    //
    public static function updateSort(array $data){
        $i = 1;
        foreach ($data as $item){
            $category = CategoryModel::find($item['id']);
            $category->parent = null;
            $category->position = $i++;
            $category->save();
            self::saveChildren($item);
        }
        return true;
    }

    public static function saveChildren(array $data){
        if (key_exists('children', $data)){
            $j = 1;
            foreach ($data['children'] as $i) {
                $category = CategoryModel::find($i['id']);
                $category->parent = $data['id'];
                $category->position = $j++;
                $category->save();
                self::saveChildren($i);
            }
        }
        return true;
    }

    public function getChildren($full_list=false){
        $query = CategoryModel::query()->where('parent', $this->id);
        if (!$full_list){
            $query->where('show_menu', 1);
        }
        $category = $query->orderBy('position', 'asc')->get();
        if ($category) {
            $rez = [];
            foreach ($category as $item) {
                $i = [];
                $child = $item->getChildren($full_list);
                $i['id'] = $item->id;
                $i['show_menu'] = $item->show_menu;
                $i['menu_name'] = $item->menu_name;
//                $i['basePath'] = '/article/list/'.$item->id;
                $i['label'] = $item->name;
                if ($item->menu_name){
                    $i['basePath'] = $item->menu_name;
                } else {
                    $i['basePath'] = '/article/list/'.$item->id;
                }

                if (count($child)>0){
                    $i['children'] = $child;
                }
                $rez[] = $i;
            }
            return $rez;
        }
        return false;
    }

    public static function getListChildren($full_list=false){
        $query = CategoryModel::query()->whereNull('parent');
        if (!$full_list){
            $query->where('show_menu', 1);
        }
        $category = $query->orderBy('position', 'asc')->get();
        $rez = [];
//        $rez[] = ['label'=>'Главная', 'basePath'=>'/index'];
        foreach ($category as $item) {
            $i = [];
            $child = $item->getChildren($full_list);
            $i['id'] = $item->id;
            $i['show_menu'] = $item->show_menu;
            $i['menu_name'] = $item->menu_name;
//            $i['basePath'] = '/article/list/'.$item->id;
            if ($item->menu_name){
                $i['basePath'] = $item->menu_name;
            } else {
                $i['basePath'] = '/article/list/'.$item->id;
            }
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
