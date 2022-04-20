<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Site;

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
        if (!$site = $db->get('ebcms_scms_site', '*', [
            'id' => $request->get('id'),
        ])) {
            return $this->error('信息不存在~');
        }

        $form = new Builder('修改站点');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Hidden('id', $site['id'])),
                    (new Input('标题', 'title', $site['title']))->set('help', '一般不超过20个字符')->set('required', 1),
                    (new Input('站点地址', 'siteurl', $site['siteurl']))->set('help', '例如：http://xx.com，http://beijin.xx.com，https://www.xx.com/beijin'),
                    (new Cover('图标', 'logo', $site['logo'], $router->build('/ebcms/admin/upload'))),
                    (new Input('跳转地址', 'redirect', $site['redirect']))->set('help', '填写后将跳转到该地址'),
                    (new Radio('状态', 'state', $site['state'], [
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
        if (!$site = $db->get('ebcms_scms_site', '*', [
            'id' => $request->post('id'),
        ])) {
            return $this->error('信息不存在~');
        }

        $update = array_intersect_key($request->post(), [
            'title' => '',
            'siteurl' => '',
            'logo' => '',
            'redirect' => '',
            'state' => '',
        ]);

        $db->update('ebcms_scms_site', $update, [
            'id' => $site['id'],
        ]);

        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
