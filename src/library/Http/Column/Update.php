<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Column;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Form\Builder;
use DigPHP\Form\Component\Col;
use DigPHP\Form\Field\Cover;
use DigPHP\Form\Field\Hidden;
use DigPHP\Form\Field\Input;
use DigPHP\Form\Field\Radio;
use DigPHP\Form\Component\Row;
use DigPHP\Request\Request;
use DigPHP\Router\Router;

class Update extends Common
{
    public function get(
        Request $request,
        Router $router,
        Db $db
    ) {
        if (!$column = $db->get('ebcms_scms_column', '*', [
            'id' => $request->get('id'),
        ])) {
            return $this->error('栏目不存在~');
        }

        $form = new Builder('修改栏目');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Hidden('id', $column['id'])),
                    (new Input('标题', 'title', $column['title']))->set('help', '一般不超过20个字符')->set('required', 1),
                    (new Input('名称', 'name', $column['name']))->set('help', '用英文数字')->set('required', 1),
                    (new Cover('图标', 'logo', $column['logo'], $router->build('/ebcms/admin/upload'))),
                    (new Radio('状态', 'state', $column['state'], [
                        '1' => '启用',
                        '2' => '停用',
                    ]))
                )
            )
        );
        return $form;
    }

    public function post(
        Request $request,
        Db $db
    ) {
        if (!$column = $db->get('ebcms_scms_column', '*', [
            'id' => $request->post('id'),
        ])) {
            return $this->error('栏目不存在~');
        }

        $update = array_intersect_key($request->post(), [
            'title' => '',
            'name' => '',
            'logo' => '',
            'state' => '',
        ]);

        $db->update('ebcms_scms_column', $update, [
            'id' => $column['id'],
        ]);

        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
