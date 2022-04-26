<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Area;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Request\Request;

class Rank extends Common
{
    public function get(
        Request $request,
        Db $db
    ) {
        if (!$area = $db->get('ebcms_scms_area', '*', [
            'id' => $request->get('id'),
        ])) {
            return $this->error('信息不存在~');
        }

        $areas = $db->select('ebcms_scms_area', '*', [
            'site_id' => $area['site_id'],
            'pid' => $area['pid'],
            'ORDER' => [
                'rank' => 'DESC',
                'id' => 'ASC',
            ],
        ]);

        $count = $db->count('ebcms_scms_area', [
            'site_id' => $area['site_id'],
            'pid' => $area['pid'],
            'id[!]' => $area['id'],
            'rank[<=]' => $area['rank'],
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
        if ($change_key > count($areas) - 1) {
            return $this->error('已经是第一位了！');
        }
        $areas = array_reverse($areas);
        foreach ($areas as $key => $value) {
            if ($key == $change_key) {
                $db->update('ebcms_scms_area', [
                    'rank' => $count,
                ], [
                    'id' => $value['id'],
                ]);
            } elseif ($key == $count) {
                $db->update('ebcms_scms_area', [
                    'rank' => $change_key,
                ], [
                    'id' => $value['id'],
                ]);
            } else {
                $db->update('ebcms_scms_area', [
                    'rank' => $key,
                ], [
                    'id' => $value['id'],
                ]);
            }
        }

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
