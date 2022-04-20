<?php

declare(strict_types=1);

namespace App\Ebcms\Scms\Http\Content;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Database\Db;
use DigPHP\Pagination\Pagination;
use DigPHP\Request\Request;
use DigPHP\Template\Template;

class Index extends Common
{

    public function get(
        Request $request,
        Template $template,
        Pagination $pagination,
        Db $db
    ) {
        $where = [];
        $where['ORDER'] = [
            'id' => 'DESC',
        ];
        if ($request->get('site_id')) {
            $where['site_id'] = $request->get('site_id');
            if ($request->get('area_id')) {
                $where['area_id'] = $request->get('area_id');
            }
            if ($request->get('column_id')) {
                $where['column_id'] = $request->get('column_id');
                if ($request->get('cate_id')) {
                    $where['cate_id'] = $request->get('cate_id');
                }
            }
        }
        if ($request->get('user_id')) {
            $where['user_id'] = $request->get('user_id');
        }

        if ($q = $request->get('q')) {
            $where['OR'] = [
                'id' => $q,
                'title[~]' => '%' . $q . '%',
                'body[~]' => '%' . $q . '%',
            ];
        }

        $total = $db->count('ebcms_scms_content', $where);

        $page = $request->get('page', 1) ?: 1;
        $page_num = min(100, $request->get('page_num', 20) ?: 20);
        $where['LIMIT'] = [($page - 1) * $page_num, $page_num];
        $where['ORDER'] = [
            'id' => 'DESC'
        ];
        $contents = $db->select('ebcms_scms_content', '*', $where);

        foreach ($contents as &$vo) {
            $vo['user'] = $db->get('ebcms_user_user', '*', [
                'id' => $vo['user_id']
            ]) ?? [];
            $vo['site'] = $db->get('ebcms_scms_site', '*', [
                'id' => $vo['site_id'],
            ]) ?? [];
            $vo['area'] = $db->get('ebcms_scms_area', '*', [
                'id' => $vo['area_id'],
            ]) ?? [];
            $vo['column'] = $db->get('ebcms_scms_column', '*', [
                'id' => $vo['column_id'],
            ]) ?? [];
            $vo['cate'] = $db->get('ebcms_scms_cate', '*', [
                'id' => $vo['cate_id'],
            ]) ?? [];
        }

        $pagination = $pagination->render($page, $total, $page_num);

        return $template->renderFromFile('content/index@ebcms/scms', [
            'contents' => $contents,
            'total' => $total,
            'pagination' => $pagination,
        ]);
    }
}
