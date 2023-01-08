<?php

/**
 * User:ylf
 * Time:2022/11/23/10:56
 * FileName:Theme.php
 * Desc:'Theme Controller'
 */


namespace app\api\controller\v1;

use app\api\validate\IDCollections;
use app\api\model\Theme as ThemeModel;
use app\api\validate\IdMustBeInt;
use app\lib\exception\ThemeException;

class Theme
{
    /*
     * 获取主题列表
     * @url /theme?ids=1,2,3 类似这种
     * @return json
     * */
    public function getThemeList($ids='')
    {
        (new IDCollections())->goCheck();
        $id_array = explode(',', $ids);
        $themeModel = new ThemeModel();
        $themes = $themeModel->getThemes($id_array);
        // 如果查询出来没有记录,不能直接判断$result是否存在
        if(count($themes)==0){
            throw new ThemeException();
        }
        return json($themes);
    }


    /*
     * 获取单个主题
     * @url /theme/1 类似这种
     * @return json
     * */

    public function getOneTheme($theme_id){
        (new IdMustBeInt())->goCheck();
        $themeModel = new ThemeModel();
        // 获取单个
        $theme = $themeModel->getThemeWithProducts($theme_id);
        if($theme==null){
            throw new ThemeException();
        }
        return json($theme);
    }
}