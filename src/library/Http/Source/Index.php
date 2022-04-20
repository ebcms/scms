<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Source;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Template\Template;

class Index extends Common
{

    public function get(
        Template $template,
        Db $db
    ) {

        $sources = $db->select('ebcms_scms_source', '*', [
            'ORDER' => [
                'id' => 'ASC',
            ],
        ]);

        return $template->renderFromFile('source/index@ebcms/scms', [
            'sources' => $sources,
        ]);
    }
}
