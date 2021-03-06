<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Column;

use App\Ebcms\Admin\Http\Common;
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

class Create extends Common
{
    public function get(
        Request $request,
        Router $router,
        Db $db
    ) {
        if (!$site = $db->get('ebcms_scms_site', '*', [
            'id' => $request->get('site_id'),
        ])) {
            return $this->error('站点不存在!');
        }

        $form = new Builder('添加栏目');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Hidden('site_id', $site['id'])),
                    (new Input('标题', 'title'))->set('help', '一般不超过20个字符')->set('attr.required', 1),
                    (new Input('名称', 'name'))->set('help', '用英文数字')->set('attr.required', 1),
                    (new Cover('图标', 'logo', '', $router->build('/ebcms/admin/upload'))),
                    (new Radio('状态', 'state', 1, [
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
        $db->insert('ebcms_scms_column', [
            'site_id' => $request->post('site_id', 0),
            'title' => $request->post('title', ''),
            'name' => $request->post('name', ''),
            'logo' => $request->post('logo', ''),
            'state' => $request->post('state', '1'),
        ]);
        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
