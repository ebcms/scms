<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Cate;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Request\Request;
use DiggPHP\Template\Template;

class Index extends Common
{

    public function get(
        Request $request,
        Template $template,
        Db $db
    ) {

        if (!$column = $db->get('ebcms_scms_column', '*', [
            'id' => $request->get('column_id'),
        ])) {
            return $this->error('栏目不存在~');
        }

        $cates = $db->select('ebcms_scms_cate', '*', [
            'column_id' => $request->get('column_id', 0),
            'pid' => $request->get('pid', 0),
            'ORDER' => [
                'rank' => 'DESC',
                'id' => 'ASC',
            ],
        ]);

        $pcate = $db->get('ebcms_scms_cate', '*', [
            'id' => $request->get('pid', 0),
        ]);

        return $template->renderFromFile('cate/index@ebcms/scms', [
            'column' => $column,
            'cates' => $cates,
            'pcate' => $pcate,
        ]);
    }
}
