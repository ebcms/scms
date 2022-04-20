<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Field;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Request\Request;

class Rank extends Common
{
    public function get(
        Request $request,
        Db $db
    ) {
        if (!$field = $db->get('ebcms_scms_field', '*', [
            'id' => $request->get('id'),
        ])) {
            return $this->error('信息不存在~');
        }

        $fields = $db->select('ebcms_scms_field', '*', [
            'model_id' => $field['model_id'],
            'is_filter' => $field['is_filter'],
            'ORDER' => [
                'rank' => 'DESC',
                'id' => 'ASC',
            ],
        ]);

        $count = $db->count('ebcms_scms_field', [
            'model_id' => $field['model_id'],
            'is_filter' => $field['is_filter'],
            'id[!]' => $field['id'],
            'rank[<=]' => $field['rank'],
            'ORDER' => [
                'rank' => 'DESC',
                'id' => 'ASC',
            ],
        ]);

        $type = $request->get('type');
        $change_key = $type == 'up' ? $count + 1 : $count - 1;

        if ($change_key < 0) {
            return $this->error('已经是最有一位了！');
        }
        if ($change_key > count($fields) - 1) {
            return $this->error('已经是第一位了！');
        }
        $fields = array_reverse($fields);
        foreach ($fields as $key => $value) {
            if ($key == $change_key) {
                $db->update('ebcms_scms_field', [
                    'rank' => $count,
                ], [
                    'id' => $value['id'],
                ]);
            } elseif ($key == $count) {
                $db->update('ebcms_scms_field', [
                    'rank' => $change_key,
                ], [
                    'id' => $value['id'],
                ]);
            } else {
                $db->update('ebcms_scms_field', [
                    'rank' => $key,
                ], [
                    'id' => $value['id'],
                ]);
            }
        }

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
