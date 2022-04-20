<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Source;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Request\Request;

class Delete extends Common
{
    public function get(
        Request $request,
        Db $db
    ) {

        if (!$source = $db->get('ebcms_scms_source', '*', [
            'id' => $request->get('id'),
        ])) {
            return $this->error('数据源不存在~');
        }

        if ($field = $db->get('ebcms_scms_field', '*', [
            'source_id' => $source['id'],
        ])) {
            return $this->error('数据源被[' . $field['title'] . ']使用，暂时无法删除~');
        }

        $db->delete('ebcms_scms_data', [
            'source_id' => $source['id'],
        ]);

        $db->delete('ebcms_scms_source', [
            'id' => $source['id'],
        ]);

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
