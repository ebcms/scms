<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Field;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Request\Request;

class Delete extends Common
{
    public function get(
        Request $request,
        Db $db
    ) {

        if (!$field = $db->get('ebcms_scms_field', '*', [
            'id' => $request->get('id'),
        ])) {
            return $this->error('字段不存在~');
        }

        // todo 删除内容中该字段数据

        $db->delete('ebcms_scms_field', [
            'id' => $field['id'],
        ]);

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
