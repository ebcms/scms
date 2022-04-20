<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Data;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Request\Request;
use DigPHP\Template\Template;

class Index extends Common
{

    public function get(
        Template $template,
        Request $request,
        Db $db
    ) {

        if (!$source = $db->get('ebcms_scms_source', '*', [
            'id' => $request->get('source_id'),
        ])) {
            return $this->error('字段不存在~');
        }

        $datas = $db->select('ebcms_scms_data', '*', [
            'source_id' => $request->get('source_id'),
            'pid' => $request->get('pid', 0),
            'ORDER' => [
                'rank' => 'DESC',
                'id' => 'ASC',
            ],
        ]);

        $pdata = $db->get('ebcms_scms_data', '*', [
            'id' => $request->get('pid', 0),
        ]);

        return $template->renderFromFile('data/index@ebcms/scms', [
            'source' => $source,
            'datas' => $datas,
            'pdata' => $pdata,
        ]);
    }
}
