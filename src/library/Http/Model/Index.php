<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Model;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Template\Template;

class Index extends Common
{

    public function get(
        Template $template,
        Db $db
    ) {

        $models = $db->select('ebcms_scms_model', '*', [
            'ORDER' => [
                'id' => 'ASC',
            ],
        ]);

        return $template->renderFromFile('model/index@ebcms/scms', [
            'models' => $models,
        ]);
    }
}
