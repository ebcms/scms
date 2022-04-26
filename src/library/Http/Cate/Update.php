<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Cate;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Scms\Model\Cate;
use DiggPHP\Database\Db;
use DiggPHP\Form\Builder;
use DiggPHP\Form\Component\Col;
use DiggPHP\Form\Field\Cover;
use DiggPHP\Form\Field\Hidden;
use DiggPHP\Form\Field\Input;
use DiggPHP\Form\Field\Radio;
use DiggPHP\Form\Component\Row;
use DiggPHP\Request\Request;
use DiggPHP\Router\Router;

class Update extends Common
{
    public function get(
        Request $request,
        Router $router,
        Db $db
    ) {
        if (!$cate = $db->get('ebcms_scms_cate', '*', [
            'id' => $request->get('id'),
        ])) {
            return $this->error('栏目不存在~');
        }

        $form = new Builder('修改栏目');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Hidden('id', $cate['id'])),
                    (new Input('标题', 'title', $cate['title']))->set('help', '一般不超过20个字符')->set('required', 1),
                    (new Input('名称', 'name', $cate['name']))->set('help', '用英文数字')->set('required', 1),
                    (new Cover('图标', 'logo', $cate['logo'], $router->build('/ebcms/admin/upload'))),
                    (new Radio('状态', 'state', $cate['state'], [
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
        if (!$cate = $db->get('ebcms_scms_cate', '*', [
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

        $db->update('ebcms_scms_cate', $update, [
            'id' => $cate['id'],
        ]);

        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
