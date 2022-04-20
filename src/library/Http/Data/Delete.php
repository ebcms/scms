<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Data;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Request\Request;
use Medoo\Medoo;

class Delete extends Common
{
    public function get(
        Request $request,
        Db $db
    ) {

        if (!$data = $db->get('ebcms_scms_data', '*', [
            'id' => $request->get('id'),
        ])) {
            return $this->error('数据不存在~');
        }
        if ($db->get('ebcms_scms_data', '*', [
            'pid' => $data['id'],
        ])) {
            return $this->error('请先删除下级~');
        }
        if (!$field = $db->get('ebcms_scms_field', '*', [
            'id' => $data['field_id'],
        ])) {
            return $this->error('字段不存在~');
        }
        if (!$model = $db->get('ebcms_scms_model', '*', [
            'id' => $field['model_id'],
        ])) {
            return $this->error('模型不存在~');
        }

        // // 删除内容 todo..
        // if (in_array($field['type'], ['radio'])) {
        //     while ($filters = $db->master()->select('ebcms_scms_filter_' . $model['name'], ['id', 'content_id'], [
        //         $field['name'] => $data['value'],
        //         'LIMIT' => '1000'
        //     ])) {
        //         $filter_ids = [];
        //         $content_ids = [];
        //         foreach ($filters as $vo) {
        //             $filter_ids[] = $vo['id'];
        //             $content_ids[] = $vo['content_id'];
        //         }
        //         $db->master()->delete('ebcms_scms_filter_' . $model['name'], [
        //             'id' => $filter_ids,
        //         ]);
        //         $db->delete('ebcms_scms_content', [
        //             'id' => $content_ids,
        //         ]);
        //     }
        // } elseif (in_array($field['type'], ['checkbox', 'attr'])) {
        //     $dec = bindec('1' . str_repeat('0', $data['value']));
        //     while ($filters = $db->master()->select('ebcms_scms_filter_' . $model['name'], ['id', 'content_id'], Medoo::raw('WHERE `' . $field['name'] . '` and ' . $dec . ' = ' . $dec . ' LIMIT 1000'))) {
        //         $filter_ids = [];
        //         $content_ids = [];
        //         foreach ($filters as $vo) {
        //             $filter_ids[] = $vo['id'];
        //             $content_ids[] = $vo['content_id'];
        //         }
        //         $db->master()->delete('ebcms_scms_filter_' . $model['name'], [
        //             'id' => $filter_ids,
        //         ]);
        //         $db->delete('ebcms_scms_content', [
        //             'id' => $content_ids,
        //         ]);
        //     }
        // } else {
        //     return $this->error('字段信息暂不支持~');
        // }

        $db->delete('ebcms_scms_data', [
            'id' => $data['id'],
        ]);

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
