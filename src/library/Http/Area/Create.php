<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Area;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Scms\Pinyin;
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

class Create extends Common
{
    public function get(
        Request $request,
        Router $router
    ) {
        $form = new Builder('添加区域');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Hidden('site_id', $request->get('site_id'))),
                    (new Hidden('pid', $request->get('pid'))),
                    (new Input('标题', 'title'))->set('help', '一般不超过20个字符，一次录入多个用“|”分割')->set('attr.required', 1),
                    (new Input('名称', 'name'))->set('help', '英文字母，一般不超过20个字符，默认为标题转拼音'),
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
        $data = [];
        if (strpos($request->post('title', ''), '|')) {
            foreach (explode('|', $request->post('title', '')) as $value) {
                if (trim($value)) {
                    $data[] = [
                        'site_id' => $request->post('site_id', 0),
                        'pid' => $request->post('pid', 0),
                        'title' => trim($value),
                        'name' => Pinyin::convert(trim($value)),
                        'logo' => $request->post('logo', ''),
                        'state' => $request->post('state', 1),
                    ];
                }
            }
        } else {
            $data = [
                'site_id' => $request->post('site_id', 0),
                'pid' => $request->post('pid', 0),
                'title' => $request->post('title', ''),
                'name' => $request->post('name', Pinyin::convert(trim($request->post('title', '')))),
                'logo' => $request->post('logo', ''),
                'state' => $request->post('state', 1),
            ];
        }
        $db->insert('ebcms_scms_area', $data);
        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
