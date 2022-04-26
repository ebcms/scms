<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Source;

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
        if (!$source = $db->get('ebcms_scms_source', '*', [
            'id' => $request->get('id'),
        ])) {
            return $this->error('数据源不存在~');
        }

        $form = new Builder('修改数据源');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Hidden('id', $source['id'])),
                    (new Input('标题', 'title', $source['title']))->set('help', '一般不超过20个字符')->set('required', 1)
                )
            )
        );
        return $form;
    }

    public function post(
        Request $request,
        Db $db
    ) {
        if (!$source = $db->get('ebcms_scms_source', '*', [
            'id' => $request->post('id'),
        ])) {
            return $this->error('数据源不存在~');
        }

        $update = array_intersect_key($request->post(), [
            'title' => '',
        ]);

        $db->update('ebcms_scms_source', $update, [
            'id' => $source['id'],
        ]);

        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
