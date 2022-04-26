<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Model;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Form\Builder;
use DiggPHP\Form\Component\Col;
use DiggPHP\Form\Field\Input;
use DiggPHP\Form\Component\Row;
use DiggPHP\Request\Request;

class Create extends Common
{
    public function get()
    {
        $form = new Builder('添加模型');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Input('标题', 'title'))->set('help', '一般不超过20个字符')->set('attr.required', 1),
                    (new Input('名称', 'name'))->set('help', '用英文')->set('attr.required', 1)
                )
            )
        );
        return $form;
    }

    public function post(
        Request $request,
        Db $db
    ) {
        $db->insert('ebcms_scms_model', [
            'title' => $request->post('title', ''),
            'name' => $request->post('name', ''),
        ]);
        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
