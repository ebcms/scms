<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Site;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Form\Builder;
use DigPHP\Form\Component\Col;
use DigPHP\Form\Field\Cover;
use DigPHP\Form\Field\Input;
use DigPHP\Form\Field\Radio;
use DigPHP\Form\Component\Row;
use DigPHP\Request\Request;
use DigPHP\Router\Router;

class Create extends Common
{
    public function get(
        Router $router
    ) {
        $form = new Builder('添加站点');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Input('标题', 'title'))->set('help', '一般不超过20个字符，若要同时录入多个，请用“|”分割')->set('attr.required', 1),
                    (new Input('站点地址', 'siteurl'))->set('help', '例如：http://xx.com，http://beijin.xx.com，https://www.xx.com/beijin'),
                    (new Cover('图标', 'logo', '', $router->build('/ebcms/admin/upload'))),
                    (new Input('跳转地址', 'redirect'))->set('help', '重定向地址，填写后将重定向到该地址'),
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
        $data = [];
        foreach (explode('|', $request->post('title', '')) as $value) {
            if (trim($value)) {
                $data[] = [
                    'title' => trim($value),
                    'siteurl' => $request->post('siteurl', ''),
                    'logo' => $request->post('logo', ''),
                    'redirect' => $request->post('redirect', ''),
                    'state' => $request->post('state', '1'),
                ];
            }
        }
        $db->insert('ebcms_scms_site', $data);
        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
