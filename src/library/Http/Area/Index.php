<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Area;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Request\Request;
use DiggPHP\Template\Template;

class Index extends Common
{

    public function get(
        Template $template,
        Request $request,
        Db $db
    ) {

        if (!$site = $db->get('ebcms_scms_site', '*', [
            'id' => $request->get('site_id'),
        ])) {
            return $this->error('信息不存在~');
        }

        $areas = $db->select('ebcms_scms_area', '*', [
            'site_id' => $request->get('site_id', 0),
            'pid' => $request->get('pid', 0),
            'ORDER' => [
                'rank' => 'DESC',
                'id' => 'ASC',
            ],
        ]);

        $parea = $db->get('ebcms_scms_area', '*', [
            'id' => $request->get('pid', 0),
        ]);

        return $template->renderFromFile('area/index@ebcms/scms', [
            'site' => $site,
            'areas' => $areas,
            'parea' => $parea,
        ]);
    }
}
