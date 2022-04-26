<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Cate;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Scms\Pinyin;
use DiggPHP\Database\Db;
use DiggPHP\Form\Builder;
use DiggPHP\Form\Component\Col;
use DiggPHP\Form\Field\Cover;
use DiggPHP\Form\Field\Hidden;
use DiggPHP\Form\Field\Input;
use DiggPHP\Form\Field\Radio;
use DiggPHP\Form\Component\Row;
use DiggPHP\Form\Field\Select;
use DiggPHP\Request\Request;
use DiggPHP\Router\Router;

class Create extends Common
{
    public function get(
        Request $request,
        Router $router,
        Db $db
    ) {
        $form = new Builder('添加栏目');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Hidden('column_id', $request->get('column_id'))),
                    (new Hidden('pid', $request->get('pid'))),
                    (new Input('标题', 'title'))->set('help', '一般不超过20个字符，若要同时录入多个，请用“|”分割')->set('attr.required', 1),
                    (new Input('名称', 'name'))->set('help', '用英文数字，不填的话，默认是拼音'),
                    (new Cover('图标', 'logo', '', $router->build('/ebcms/admin/upload'))),
                    (new Select('内容模型', 'model_id', 0, (function () use ($db): array {
                        $res = [];
                        foreach ($db->select('ebcms_scms_model', '*') as $vo) {
                            $res[$vo['id']] = $vo['title'];
                        }
                        return $res;
                    })()))->set('attr.required', 1)->set('help', '此项一经录入，不可更改！'),
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
                    'column_id' => $request->post('column_id', 0),
                    'model_id' => $request->post('model_id', 0),
                    'pid' => $request->post('pid', 0),
                    'title' => trim($value),
                    'name' => Pinyin::convert(trim($value)),
                    'logo' => $request->post('logo', ''),
                    'state' => $request->post('state', '1'),
                ];
            }
        }
        $db->insert('ebcms_scms_cate', $data);
        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
