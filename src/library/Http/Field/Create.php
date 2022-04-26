<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Field;

use App\Ebcms\Admin\Http\Common;
use DiggPHP\Database\Db;
use DiggPHP\Form\Builder;
use DiggPHP\Form\Component\Col;
use DiggPHP\Form\Component\Html;
use DiggPHP\Form\Field\Hidden;
use DiggPHP\Form\Field\Input;
use DiggPHP\Form\Component\Switchs;
use DiggPHP\Form\Component\Row;
use DiggPHP\Form\Component\SwitchItem;
use DiggPHP\Form\Field\Select;
use DiggPHP\Request\Request;

class Create extends Common
{
    public function get(
        Request $request,
        Db $db
    ) {
        if ($request->get('is_filter') == '1') {
            $form = new Builder('添加筛选字段');
            $form->addItem(
                (new Row())->addCol(
                    (new Col('col-md-9'))->addItem(
                        (new Hidden('model_id', $request->get('model_id'))),
                        (new Hidden('is_filter', $request->get('is_filter'))),
                        (new Input('标题', 'title'))->set('help', '一般不超过20个字符')->set('attr.required', 1),
                        (new Input('名称', 'name'))->set('help', '用英文')->set('attr.required', 1),
                        (new Input('提醒文本', 'help'))->set('help', '不超过255个字符'),
                        (new Switchs('字段类型', 'type', '1'))->addSwitch(
                            (new SwitchItem('模式一', 1))->addItem(
                                new Html('<div>单选录入，单选筛选，支持多层级</div><div>例如：某产品属于哪个品牌、某产品的产地</div>')
                            ),
                            (new SwitchItem('模式二', 2))->addItem(
                                new Html('<div>多选录入，单选筛选，不支持多层级</div><div>例如：某产品适用人群（男士，女士，中老年，小孩子）</div>')
                            ),
                            (new SwitchItem('模式三', 3))->addItem(
                                new Html('<div>多选录入，多选筛选，不支持多层级</div><div>例如：某产品特点（双卡双待、5G、防水、全面屏）</div>')
                            )
                        ),
                        (new Select('数据源', 'source_id', 0, (function () use ($db): array {
                            $res = [];
                            foreach ($db->select('ebcms_scms_source', '*') as $vo) {
                                $res[$vo['id']] = $vo['title'];
                            }
                            return $res;
                        })()))->set('attr.required', 1)->set('help', '此项一经录入，不可更改！')
                    )
                )
            );
            return $form;
        } else {
            $form = new Builder('添加内容字段');
            $form->addItem(
                (new Row())->addCol(
                    (new Col('col-md-9'))->addItem(
                        (new Hidden('model_id', $request->get('model_id'))),
                        (new Hidden('is_filter', $request->get('is_filter'))),
                        (new Input('标题', 'title'))->set('help', '一般不超过20个字符')->set('attr.required', 1),
                        (new Input('名称', 'name'))->set('help', '用英文数字')->set('attr.required', 1),
                        (new Input('提醒文本', 'help'))->set('help', '不超过255个字符'),
                        (new Switchs('字段类型', 'type', 'text'))->addSwitch(
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
                        )
                    )
                )
            );
            return $form;
        }
    }

    public function post(
        Request $request,
        Db $db
    ) {
        if (!$model = $db->get('ebcms_scms_model', '*', [
            'id' => $request->post('model_id')
        ])) {
            return $this->error('模型不存在~');
        }

        $data = [
            'model_id' => $request->post('model_id'),
            'is_filter' => $request->post('is_filter'),
            'title' => $request->post('title'),
            'name' => $request->post('name'),
            'help' => $request->post('help'),
            'type' => $request->post('type'),
            'source_id' => $request->post('source_id', 0),
        ];

        if ($request->post('is_filter', 1)) {
            $fields = $db->select('ebcms_scms_field', 'field', [
                'model_id' => $request->post('model_id'),
                'is_filter' => 1,
            ]);
            for ($i = 0; $i <= 19; $i++) {
                if (!in_array('filter' . $i, $fields)) {
                    $data['field'] = 'filter' . $i;
                    break;
                }
            }
            if (!isset($data['field'])) {
                return $this->error('筛选字段最多20个，请合理规划！');
            }
        } else {
            $data['field'] = $data['name'];
        }

        $db->insert('ebcms_scms_field', $data);

        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }
}
