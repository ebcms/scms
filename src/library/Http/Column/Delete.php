<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Column;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Request\Request;

class Delete extends Common
{
    public function get(
        Request $request,
        Db $db
    ) {

        if (!$column = $db->get('ebcms_scms_column', '*', [
            'id' => $request->get('id'),
        ])) {
            return $this->error('栏目不存在~');
        }

        $db->delete('ebcms_scms_content', [
            'column_id' => $column['id'],
        ]);

        $db->delete('ebcms_scms_cate', [
            'column_id' => $column['id'],
        ]);

        $db->delete('ebcms_scms_column', [
            'id' => $column['id'],
        ]);

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
