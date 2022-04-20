<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Field;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Request\Request;
use DigPHP\Template\Template;

class Index extends Common
{

    public function get(
        Request $request,
        Template $template,
        Db $db
    ) {

        if (!$model = $db->get('ebcms_scms_model', '*', [
            'id' => $request->get('model_id'),
        ])) {
            return $this->error('模型不存在~');
        }

        $fields = $db->select('ebcms_scms_field', '*', [
            'model_id' => $model['id'],
            'ORDER' => [
                'rank' => 'DESC',
                'id' => 'ASC',
            ],
        ]);

        return $template->renderFromFile('field/index@ebcms/scms', [
            'model' => $model,
            'fields' => $fields,
        ]);
    }
}
