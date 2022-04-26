<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Model;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Request\Request;

class Delete extends Common
{
    public function get(
        Request $request,
        Db $db
    ) {

        if (!$model = $db->get('ebcms_scms_model', '*', [
            'id' => $request->get('id'),
        ])) {
            return $this->error('信息不存在~');
        }

        // 判断模型是否可删除
        if ($column = $db->get('ebcms_scms_column', '*', [
            'model_id' => $model['id']
        ])) {
            return $this->error('栏目[' . $column['title'] . ']使用了该模型，暂无法删除~');
        }

        // 删除字段信息
        $db->delete('ebcms_scms_field', [
            'model_id' => $model['id'],
        ]);

        // 删除模型
        $db->delete('ebcms_scms_model', [
            'id' => $model['id'],
        ]);

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
