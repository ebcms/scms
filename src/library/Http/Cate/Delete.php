<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Cate;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Request\Request;

class Delete extends Common
{
    public function get(
        Request $request,
        Db $db
    ) {

        if (!$cate = $db->get('ebcms_cms_cate', '*', [
            'id' => $request->get('id'),
        ])) {
            return $this->error('分类不存在~');
        }

        if ($db->get('ebcms_cms_cate', '*', [
            'pid' => $request->get('id'),
        ])) {
            return $this->error('请先删除子栏目~');
        }

        // 删除内容
        $db->delete('ebcms_cms_content', [
            'cate_id' => $cate['id'],
        ]);

        $db->delete('ebcms_cms_cate', [
            'id' => $cate['id'],
        ]);

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
