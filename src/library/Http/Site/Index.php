<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Site;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Template\Template;

class Index extends Common
{

    public function get(
        Template $template,
        Db $db
    ) {

        $sites = $db->select('ebcms_scms_site', '*', [
            'ORDER' => [
                'rank' => 'DESC',
                'id' => 'ASC',
            ],
        ]);

        return $template->renderFromFile('site/index@ebcms/scms', [
            'sites' => $sites,
        ]);
    }
}
