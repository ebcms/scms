<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Content;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Router\Router;
use DigPHP\Form\Builder;
use DigPHP\Form\Component\Col;
use DigPHP\Form\Field\Checkbox;
use DigPHP\Form\Field\Cover;
use DigPHP\Form\Field\Files;
use DigPHP\Form\Field\Hidden;
use DigPHP\Form\Field\Textarea;
use DigPHP\Form\Field\Input;
use DigPHP\Form\Field\Pics;
use DigPHP\Form\Field\Select;
use DigPHP\Form\Field\Summernote;
use DigPHP\Form\Component\Row;
use DigPHP\Request\Request;
use DigPHP\Template\Template;
use Exception;

class Create extends Common
{
    public function get(
        Request $request,
        Router $router,
        Template $template,
        Db $db
    ) {
        if ($content = $db->get('ebcms_scms_content', '*', [
            'id' => $request->get('copyfrom', 0),
        ])) {
            if (!$site = $db->get('ebcms_scms_site', '*', [
                'id' => $content['site_id'],
            ])) {
                return $this->error('数据错误，无法复制改信息，请删除该信息~');
            }
            if (!$column = $db->get('ebcms_scms_column', '*', [
                'id' => $content['column_id'],
            ])) {
                return $this->error('数据错误，无法复制改信息，请删除该信息~');
            }
            if (!$cate = $db->get('ebcms_scms_cate', '*', [
                'id' => $content['cate_id'],
            ])) {
                return $this->error('数据错误，无法复制改信息，请删除该信息~');
            }
        } else {
            $content = [];
            if (!$site = $db->get('ebcms_scms_site', '*', [
                'id' => $request->get('site_id'),
            ])) {
                return $template->renderFromFile('content/create@ebcms/scms');
            }
            if (!$column = $db->get('ebcms_scms_column', '*', [
                'id' => $request->get('column_id'),
            ])) {
                return $template->renderFromFile('content/create@ebcms/scms');
            }
            if (!$cate = $db->get('ebcms_scms_cate', '*', [
                'id' => $request->get('cate_id'),
            ])) {
                return $template->renderFromFile('content/create@ebcms/scms');
            }
        }

        if (!$model = $db->get('ebcms_scms_model', '*', [
            'id' => $cate['model_id'],
        ])) {
            return $this->error('模型不存在~');
        }
        $fields = $db->select('ebcms_scms_field', '*', [
            'model_id' => $model['id'],
            'ORDER' => [
                'rank' => 'DESC',
                'id' => 'ASC',
            ],
        ]);
        $areas = $db->select('ebcms_scms_area', '*', [
            'site_id' => $site['id'],
            'ORDER' => [
                'id' => 'ASC',
            ],
        ]);
        $cates = $db->select('ebcms_scms_cate', '*', [
            'column_id' => $column['id'],
            'ORDER' => [
                'rank' => 'DESC',
                'id' => 'ASC',
            ],
        ]);

        $form = new Builder('发布内容');
        $form->addItem(
            (new Row())->addCol(
                (new Col('col-md-3'))->addItem(
                    (new Hidden('site_id', $site['id'])),
                    (new Hidden('column_id', $column['id'])),
                    (new Hidden('cate_id', $cate['id'])),
                    (new Select('地区', 'area_id', $content['area_id'] ?? 0, (function () use ($areas): array {
                        $to_level = function (array $data): array {
                            $res = [];
                            $level_fun = function (array $data, $pid, $level, &$res) use (&$level_fun) {
                                foreach ($data as $vo) {
                                    if ($vo['pid'] == $pid) {
                                        $vo['_level'] = $level;
                                        $res[] = $vo;
                                        $level_fun($data, $vo['id'], $level + 1, $res);
                                    }
                                }
                            };
                            $level_fun($data, 0, 0, $res);
                            return $res;
                        };
                        $res = [];
                        $res[0] = '不限';
                        foreach ($to_level($areas) as $vo) {
                            $res[$vo['id']] = str_repeat('ㅤ', $vo['_level']) . $vo['title'];
                        }
                        return $res;
                    })()))->set('attr.required', 1),
                    ...(function () use ($content, $fields, $db): array {
                        $res = [];
                        foreach ($fields as $vo) {
                            if ($vo['is_filter'] == 1) {
                                $datas = $db->select('ebcms_scms_data', '*', [
                                    'source_id' => $vo['source_id']
                                ]);
                                if (in_array($vo['type'], ['1'])) {
                                    $res[] = (new Select($vo['title'],  $vo['field'], $content[$vo['field']] ?? '', (function () use ($datas): array {
                                        $res = [];
                                        foreach ($datas as $tmp) {
                                            $res[$tmp['value']] = $tmp['title'];
                                        }
                                        return $res;
                                    })()))->set('help', $vo['help']);
                                } elseif (in_array($vo['type'], ['2', '3'])) {
                                    $res[] = (new Checkbox($vo['title'], $vo['field'], (function () use ($content, $vo): array {
                                        $res = [];
                                        $strs = array_reverse(str_split(decbin($content[$vo['field']]) . ''));
                                        foreach ($strs as $key => $value) {
                                            if ($value) {
                                                $res[] = pow(2, $key);
                                            }
                                        }
                                        return $res;
                                    })(), (function () use ($datas): array {
                                        $res = [];
                                        foreach ($datas as $tmp) {
                                            $res[pow(2, $tmp['value'])] = $tmp['title'];
                                        }
                                        return $res;
                                    })()))->set('help', $vo['help']);
                                } else {
                                }
                            }
                        }
                        return $res;
                    })()
                ),
                (new Col('col-md-9'))->addItem(
                    (new Input('标题', 'title', $content['title'] ?? ''))->set('help', '一般不超过80个字符')->set('attr.required', 'required'),
                    (new Pics('图片介绍', 'pics', json_decode($content['pics'] ?? '[]', true), $router->build('/ebcms/admin/upload'))),
                    (new Textarea('信息详情', 'detail', $content['detail'] ?? ''))->set('attr.rows', 10),
                    ...(function () use ($content, $fields, $router): array {
                        $res = [];
                        $extra = json_decode($content['extra'] ?? '[]', true);
                        foreach ($fields as $vo) {
                            if ($vo['is_filter'] != 1) {
                                switch ($vo['type']) {
                                    case 'text':
                                        $res[] = (new Input($vo['title'], 'extra[' . $vo['field'] . ']', $extra[$vo['field']] ?? ''))->set('help', $vo['help']);
                                        break;

                                    case 'textarea':
                                        $res[] = (new Textarea($vo['title'], 'extra[' . $vo['field'] . ']', $extra[$vo['field']] ?? ''))->set('help', $vo['help']);
                                        break;

                                    case 'cover':
                                        $res[] = (new Cover($vo['title'], 'extra[' . $vo['field'] . ']', $extra[$vo['field']] ?? '', $router->build('/ebcms/ucenter-web/upload')))->set('help', $vo['help']);
                                        break;

                                    case 'pics':
                                        $res[] = (new Pics($vo['title'], 'extra[' . $vo['field'] . ']', $extra[$vo['field']] ?? [], $router->build('/ebcms/ucenter-web/upload')))->set('help', $vo['help']);
                                        break;

                                    case 'files':
                                        $res[] = (new Files($vo['title'], 'extra[' . $vo['field'] . ']', $extra[$vo['field']] ?? [], $router->build('/ebcms/ucenter-web/upload')))->set('help', $vo['help']);
                                        break;

                                    case 'summernote':
                                        $res[] = (new Summernote($vo['title'], 'extra[' . $vo['field'] . ']', $extra[$vo['field']] ?? '', $router->build('/ebcms/ucenter-web/upload')))->set('help', $vo['help']);
                                        break;

                                    default:
                                        # code...
                                        break;
                                }
                            }
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
        if (!$column = $db->get('ebcms_scms_column', '*', [
            'id' => $request->post('column_id'),
        ])) {
            return $this->error('栏目不存在~');
        }

        $content = [
            'site_id' => $request->post('site_id'),
            'column_id' => $request->post('column_id'),
            'cate_id' => $request->post('cate_id'),
            'area_id' => $request->post('area_id'),
            'create_time' => time(),
            'update_time' => time(),
            'content_id' => $this->createContentId(),
            'title' => $request->post('title'),
            'pics' => json_encode($request->post('pics', []), JSON_UNESCAPED_UNICODE),
            'detail' => $request->post('detail'),
            'extra' => json_encode($request->post('extra', []), JSON_UNESCAPED_UNICODE),
        ];
        for ($i = 0; $i < 20; $i++) {
            if ($request->has('post.filter' . $i)) {
                $content['filter' . $i] = array_sum((array)$request->post('filter' . $i));
            }
        }
        $db->insert('ebcms_scms_content', $content);

        return $this->success('操作成功！', 'javascript:history.go(-2)');
    }

    private function createContentId($lenght = 13)
    {
        if (function_exists("random_bytes")) {
            $bytes = random_bytes(intval(ceil($lenght / 2)));
        } elseif (function_exists("openssl_random_pseudo_bytes")) {
            $bytes = openssl_random_pseudo_bytes(intval(ceil($lenght / 2)));
        } else {
            throw new Exception("no cryptographically secure random function available");
        }
        return substr(bin2hex($bytes), 0, $lenght);
    }
}
