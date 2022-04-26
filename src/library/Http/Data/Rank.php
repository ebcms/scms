<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Data;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Request\Request;

class Rank extends Common
{
    public function get(
        Request $request,
        Db $db
    ) {
        if (!$data = $db->get('ebcms_scms_data', '*', [
            'id' => $request->get('id'),
        ])) {
            return $this->error('信息不存在~');
        }

        $datas = $db->select('ebcms_scms_data', '*', [
            'field_id' => $data['field_id'],
            'pid' => $data['pid'],
            'ORDER' => [
                'rank' => 'DESC',
                'id' => 'ASC',
            ],
        ]);

        $count = $db->count('ebcms_scms_data', [
            'field_id' => $data['field_id'],
            'pid' => $data['pid'],
            'id[!]' => $data['id'],
            'rank[<=]' => $data['rank'],
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
        if ($change_key > count($datas) - 1) {
            return $this->error('已经是第一位了！');
        }
        $datas = array_reverse($datas);
        foreach ($datas as $key => $value) {
            if ($key == $change_key) {
                $db->update('ebcms_scms_data', [
                    'rank' => $count,
                ], [
                    'id' => $value['id'],
                ]);
            } elseif ($key == $count) {
                $db->update('ebcms_scms_data', [
                    'rank' => $change_key,
                ], [
                    'id' => $value['id'],
                ]);
            } else {
                $db->update('ebcms_scms_data', [
                    'rank' => $key,
                ], [
                    'id' => $value['id'],
                ]);
            }
        }

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
