<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Content;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Scms\Model\Content;
use DiggPHP\Request\Request;

class Delete extends Common
{
    public function get(
        Request $request,
        Content $contentModel
    ) {

        if (!$content = $contentModel->get('*', [
            'id' => $request->get('id'),
        ])) {
            return $this->error('内容不存在！');
        }

        $contentModel->delete([
            'id' => $content['id'],
        ]);

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}
