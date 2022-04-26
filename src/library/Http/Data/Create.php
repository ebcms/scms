<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Data;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Form\Builder;
use DiggPHP\Form\Component\Col;
use DiggPHP\Form\Field\Cover;
use DiggPHP\Form\Field\Hidden;
use DiggPHP\Form\Field\Input;
use DiggPHP\Form\Component\Row;
use DiggPHP\Request\Request;
use DiggPHP\Router\Router;

class Create extends Common
{
    public function get(
        Request $request,
        Router $router
    ) {
        $form = new Builder('添加数据');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Hidden('source_id', $request->get('source_id'))),
                    (new Hidden('pid', $request->get('pid'))),
                    (new Input('标题', 'title'))->set('help', '一般不超过20个字符')->set('attr.required', 1),
                    (new Input('名称', 'name'))->set('help', '英文字符，一般不超过20个字符')->set('attr.required', 1),
                    (new Cover('图标', 'logo', '', $router->build('/ebcms/admin/upload')))
                )
            )
        );
        return $form;
    }

    public function post(
        Request $request,
        Db $db
    ) {
        $values = $db->select('ebcms_scms_data', 'value', [
            'source_id' => $request->post('source_id', 0),
        ]);

        $value = 0;
        for ($i = 0; $i <= count($values); $i++) {
            if (!in_array($i, $values)) {
                $value = $i;
                break;
            }
        }

        $db->insert('ebcms_scms_data', [
            'source_id' => $request->post('source_id', 0),
            'pid' => $request->post('pid', 0),
            'title' => $request->post('title', ''),
            'name' => $request->post('name', ''),
            'value' => $value,
            'logo' => $request->post('logo', ''),
        ]);

        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
