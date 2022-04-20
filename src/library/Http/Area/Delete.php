<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Area;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Request\Request;

class Delete extends Common
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

        if ($db->get('ebcms_scms_area', '*', [
            'pid' => $request->get('id'),
        ])) {
            return $this->error('请先删除下级~');
        }

        // 删除内容
        $db->delete('ebcms_scms_content', [
            'area_id' => $area['id'],
        ]);

        $db->delete('ebcms_scms_area', [
            'id' => $area['id'],
        ]);

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
