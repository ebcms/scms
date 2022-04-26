<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Model;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Form\Builder;
use DiggPHP\Form\Component\Col;
use DiggPHP\Form\Field\Hidden;
use DiggPHP\Form\Field\Input;
use DiggPHP\Form\Component\Row;
use DiggPHP\Request\Request;

class Update extends Common
{
    public function get(
        Request $request,
        Db $db
    ) {
        if (!$model = $db->get('ebcms_scms_model', '*', [
            'id' => $request->get('id'),
        ])) {
            return $this->error('模型不存在~');
        }

        $form = new Builder('修改模型');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Hidden('id', $model['id'])),
                    (new Input('标题', 'title', $model['title']))->set('help', '一般不超过20个字符')->set('required', 1),
                    (new Input('名称', 'name', $model['name']))->set('help', '用英文数字')->set('required', 1)
                )
            )
        );
        return $form;
    }

    public function post(
        Request $request,
        Db $db
    ) {
        if (!$model = $db->get('ebcms_scms_model', '*', [
            'id' => $request->post('id'),
        ])) {
            return $this->error('模型不存在~');
        }

        $update = array_intersect_key($request->post(), [
            'title' => '',
            'name' => '',
        ]);

        $db->update('ebcms_scms_model', $update, [
            'id' => $model['id'],
        ]);

        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
