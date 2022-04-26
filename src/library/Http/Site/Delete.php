<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Site;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Request\Request;

class Delete extends Common
{
    public function get(
        Request $request,
        Db $db
    ) {

        if (!$site = $db->get('ebcms_scms_site', '*', [
            'id' => $request->get('id'),
        ])) {
            return $this->error('站点不存在~');
        }

        $db->delete('ebcms_scms_content', [
            'site_id' => $site['id'],
        ]);

        $db->delete('ebcms_scms_area', [
            'site_id' => $site['id'],
        ]);

        $db->delete('ebcms_scms_site', [
            'id' => $site['id'],
        ]);

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
