<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Source;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Form\Builder;
use DigPHP\Form\Component\Col;
use DigPHP\Form\Field\Input;
use DigPHP\Form\Component\Row;
use DigPHP\Request\Request;

class Create extends Common
{
    public function get()
    {
        $form = new Builder('添加数据源');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Input('标题', 'title'))->set('help', '一般不超过20个字符')->set('attr.required', 1)
                )
            )
        );
        return $form;
    }

    public function post(
        Request $request,
        Db $db
    ) {
        $db->insert('ebcms_scms_source', [
            'title' => $request->post('title', ''),
        ]);
        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
