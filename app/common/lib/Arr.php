<?php
/**
 * create by XinyuLi
 * @since 11/06/2020 13:32
 */

namespace app\common\lib;

class Arr
{
    /**
     * Classification tree无限极分类
     * @param $data
     * @return array
     */
    public static function getTree($data){
        $items = [];
        foreach ($data as $v) {
            $items[$v['category_id']] = $v;
        }
        $tree = [];
        foreach ($items as $id => $item){
            if (isset($items[$item['pid']])){
                $items[$item['pid']]['list'][] = &$items[$id];
            }else{
                $tree[] = &$items[$id];
            }
        }
        return $tree;
    }

    /**
     * 对无限极分类进行截取
     * @param $data
     * @param int $firstCount
     * @param int $secondCount
     * @param int $threeCount
     * @return array
     */
    public static function sliceTreeArr($data, $firstCount = 5, $secondCount = 3, $threeCount = 5) {
        $data = array_slice($data, 0, $firstCount);
        foreach($data as $k => $v) {
            if(!empty($v['list'])) {
                $data[$k]['list'] = array_slice($v['list'], 0, $secondCount);
                foreach($v['list'] as $kk => $vv) {
                    if(!empty($vv['list'])) {
                        $data[$k]['list'][$kk]['list'] = array_slice($vv['list'], 0, $threeCount);
                    }
                }
            }
        }
        return $data;
    }

    /**
     * goods分页默认返回数据
     * @param $num
     * @return array
     */
    public static function getPaginateDefaultData($num){
        $result = [
            'total' => 0,
            'per_page' => $num,
            'current_page' => 1,
            'last_page' => 0,
            'data' => [],
        ];
        return $result;
    }

    public static function arrsSortByKey($result, $key, $sort = SORT_DESC){
        if (!is_array($result) || !$key){
            return [];
        }
        $resultSort = array_column($result,$key);
        array_multisort($resultSort, $sort, $result);
        return $result;
    }

    /**
     * 二三级分类获取
     * @param $name
     * @param $pathArr
     * @param $data
     * @return array
     */
    public static function searchTree($name,$pathArr,$data)
    {
        $category = $threeCategory = [];
        $series = count($pathArr);
        foreach($data as $key=>$val)
        {
            //Delete unused data
            unset($val['path']);
            $category[$val['series']][] = $val;
            //three category data
            if($val['series'] == 3 && $series >= 2){
                $threeCategory[$val['pid']][] = $val;
            }
        }
        //second category ID
        $secondId = $pathArr[1] ?? ($category[2][0]['id'] ?? '');

        $result = [
            "name" => $name,
            "focus_ids" => [
                (int)($pathArr[1] ?? ''),
                (int)($pathArr[2] ?? '')
            ],
            "list" => [
                $category[2] ?? [],
                $threeCategory[$secondId] ?? []
            ]
        ];
        //dump($result);exit;
        return $result;
    }
}