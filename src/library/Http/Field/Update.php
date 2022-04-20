<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Field;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Form\Builder;
use DigPHP\Form\Component\Col;
use DigPHP\Form\Component\Html;
use DigPHP\Form\Field\Hidden;
use DigPHP\Form\Field\Input;
use DigPHP\Form\Component\Switchs;
use DigPHP\Form\Component\Row;
use DigPHP\Form\Component\SwitchItem;
use DigPHP\Request\Request;

class Update extends Common
{
    public function get(
        Request $request,
        Db $db
    ) {
        if (!$field = $db->get('ebcms_scms_field', '*', [
            'id' => $request->get('id'),
        ])) {
            return $this->error('字段不存在~');
        }

        $form = new Builder('修改字段');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-9'))->addItem(
                    (new Hidden('id', $field['id'])),
                    (new Input('标题', 'title', $field['title']))->set('help', '一般不超过20个字符')->set('required', 1),
                    (new Input('名称', 'name', $field['name']))->set('help', '用英文')->set('attr.required', 1),
                    (new Input('提示信息', 'help', $field['help']))->set('help', '一般不超过255个字符'),
                    ...(function () use ($field): array {
                        $res = [];
                        if ($field['is_filter']) {
                            $res[] = (new Switchs('字段类型', 'type', $field['type']))->addSwitch(
                                (new SwitchItem('模式一', 1))->addItem(
                                    new Html('<div class="alert alert-warning"><div>单选录入，单选筛选，支持多层级</div><div>例如：某产品属于哪个品牌、某产品的产地</div></div>')
                                ),
                                (new SwitchItem('模式二', 2))->addItem(
                                    new Html('<div class="alert alert-warning"><div>多选录入，单选筛选，不支持多层级</div><div>例如：某产品适用人群（男士，女士，中老年，小孩子）</div></div>')
                                ),
                                (new SwitchItem('模式三', 3))->addItem(
                                    new Html('<div class="alert alert-warning"><div>多选录入，多选筛选，不支持多层级</div><div>例如：某产品特点（双卡双待、5G、防水、全面屏）</div></div>')
                                )
                            );
                        } else {
                            $res[] = (new Switchs('字段类型', 'type', $field['type']))->addSwitch(
                                (new SwitchItem('单行文本', 'text'))->addItem(
                                    new Html('<div class="alert alert-warning">最多支持255个字符</div>')
                                ),
                                (new SwitchItem('多行文本', 'textarea'))->addItem(
                                    new Html('<div class="alert alert-warning">最多支持65536个字符</div>')
                                ),
                                (new SwitchItem('单图上传', 'cover'))->addItem(
                                    new Html('<div class="alert alert-warning">允许上传一张图片</div>')
                                ),
                                (new SwitchItem('多图上传', 'pics'))->addItem(
                                    new Html('<div class="alert alert-warning">允许上传多张图片</div>')
                                ),
                                (new SwitchItem('文件上传', 'files'))->addItem(
                                    new Html('<div class="alert alert-warning">允许上传多个文件</div>')
                                ),
                                (new SwitchItem('可视化编辑器', 'summernote'))->addItem(
                                    new Html('<div class="alert alert-warning">Summernote编辑器</div>')
                                )
                            );
                        }
                        return $res;
                    })()
                )
            )
        );
        return $form;
    }

    public function post(
        Request $request,
        Db $db
    ) {
        if (!$field = $db->get('ebcms_scms_field', '*', [
            'id' => $request->post('id'),
        ])) {
            return $this->error('字段不存在~');
        }

        $update = array_intersect_key($request->post(), [
            'title' => '',
            'name' => '',
            'help' => '',
            'type' => '',
        ]);

        $db->update('ebcms_scms_field', $update, [
            'id' => $field['id'],
        ]);

        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
