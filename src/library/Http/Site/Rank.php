<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Site;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Request\Request;

class Rank extends Common
{
    public function get(
        Request $request,
        Db $db
    ) {
        if (!$site = $db->get('ebcms_scms_site', '*', [
            'id' => $request->get('id'),
        ])) {
            return $this->error('信息不存在~');
        }

        $sites = $db->select('ebcms_scms_site', '*', [
            'pid' => $site['pid'],
            'ORDER' => [
                'rank' => 'DESC',
                'id' => 'ASC',
            ],
        ]);

        $count = $db->count('ebcms_scms_site', [
            'pid' => $site['pid'],
            'id[!]' => $site['id'],
            'rank[<=]' => $site['rank'],
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
        if ($change_key > count($sites) - 1) {
            return $this->error('已经是第一位了！');
        }
        $sites = array_reverse($sites);
        foreach ($sites as $key => $value) {
            if ($key == $change_key) {
                $db->update('ebcms_scms_site', [
                    'rank' => $count,
                ], [
                    'id' => $value['id'],
                ]);
            } elseif ($key == $count) {
                $db->update('ebcms_scms_site', [
                    'rank' => $change_key,
                ], [
                    'id' => $value['id'],
                ]);
            } else {
                $db->update('ebcms_scms_site', [
                    'rank' => $key,
                ], [
                    'id' => $value['id'],
                ]);
            }
        }

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
