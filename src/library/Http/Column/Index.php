<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Column;

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

        $columns = $db->select('ebcms_scms_column', '*', [
            'site_id' => $request->get('site_id'),
            'ORDER' => [
                'rank' => 'DESC',
                'id' => 'ASC',
            ],
        ]);

        return $template->renderFromFile('column/index@ebcms/scms', [
            'columns' => $columns,
        ]);
    }
}
