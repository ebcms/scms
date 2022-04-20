<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Column;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Request\Request;

class Rank extends Common
{
    public function get(
        Request $request,
        Db $db
    ) {
        if (!$column = $db->get('ebcms_scms_column', '*', [
            'id' => $request->get('id'),
        ])) {
            return $this->error('信息不存在~');
        }

        $columns = $db->select('ebcms_scms_column', '*', [
            'site_id' => $column['site_id'],
            'ORDER' => [
                'rank' => 'DESC',
                'id' => 'ASC',
            ],
        ]);

        $count = $db->count('ebcms_scms_column', [
            'site_id' => $column['site_id'],
            'id[!]' => $column['id'],
            'rank[<=]' => $column['rank'],
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
        if ($change_key > count($columns) - 1) {
            return $this->error('已经是第一位了！');
        }
        $columns = array_reverse($columns);
        foreach ($columns as $key => $value) {
            if ($key == $change_key) {
                $db->update('ebcms_scms_column', [
                    'rank' => $count,
                ], [
                    'id' => $value['id'],
                ]);
            } elseif ($key == $count) {
                $db->update('ebcms_scms_column', [
                    'rank' => $change_key,
                ], [
                    'id' => $value['id'],
                ]);
            } else {
                $db->update('ebcms_scms_column', [
                    'rank' => $key,
                ], [
                    'id' => $value['id'],
                ]);
            }
        }

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
