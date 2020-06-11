<?php
/**
 * create by XinyuLi
 * @since 11/06/2020 22:17
 */

namespace app\admin\controller;
use app\common\business\SpecsValue as SpecsValueBusiness;

class SpecsValue extends AdminBase
{
    /**
     * Add specification
     */
    public function save() {
        $specsId = input("param.specs_id", 0, "intval");
        $name = input("param.name", "", "trim");

        $data = [
            "specs_id" => $specsId,
            "name" => $name,
        ];
        $id = (new SpecsValueBusiness())->add($data);
        if(!$id) {
            return show(config('status.error'), "Failed to add specification!");
        }

        return show(config("status.success"), "OK", ["id" => $id]);
    }

    public function getBySpecsId() {
        $specsId = input("param.specs_id", 0, "intval");
        if(!$specsId) {
            return show(config('status.success'), "No exist Data!");
        }
        $result = (new SpecsValueBusiness())->getBySpecsId($specsId);
        return show(config('status.success'), "OK", $result);
    }
}