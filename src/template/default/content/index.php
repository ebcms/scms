{include common/header@ebcms/admin}
<div class="container-xxl">
    <div class="my-3">
        <h1>信息管理</h1>
    </div>
    <div class="mb-3">
        <a href="{echo $router->build('/ebcms/scms/content/create')}" class="btn btn-primary">发布</a>
    </div>
    <div class="mb-3">
        <form id="form_2" class="row gy-2 gx-3 align-items-center" action="{echo $router->build('/ebcms/scms/content/index')}" method="GET">

            <div class="col-auto">
                <label class="visually-hidden">站点</label>
                <select class="form-select" name="site_id" onchange="document.getElementById('form_2').submit();">
                    <option {if $request->get('site_id')=='' }selected{/if} value="">不限</option>
                    <?php
                    $sites = $db->select('ebcms_scms_site', '*', [
                        'ORDER' => [
                            'rank' => 'DESC',
                            'id' => 'ASC'
                        ]
                    ]);
                    ?>
                    {foreach $sites as $vo}
                    <option {if $request->get('site_id')==$vo['id']}selected{/if} value="{$vo.id}">{$vo.title}</option>
                    {/foreach}
                </select>
            </div>

            {if $request->get('site_id')}
            <div class="col-auto">
                <label class="visually-hidden">栏目</label>
                <select class="form-select" name="column_id" onchange="document.getElementById('form_2').submit();">
                    <option value="">不限</option>
                    <?php
                    $columns = $db->select('ebcms_scms_column', '*', [
                        'site_id' => $request->get('site_id'),
                        'ORDER' => [
                            'rank' => 'DESC',
                            'id' => 'ASC'
                        ]
                    ]);
                    ?>
                    {foreach $columns as $vo}
                    <option {if $request->get('column_id')==$vo['id']}selected{/if} value="{$vo.id}">{$vo.title}</option>
                    {/foreach}
                </select>
            </div>

            {if $request->get('column_id')}
            <div class="col-auto">
                <label class="visually-hidden">分类</label>
                <select class="form-select" name="cate_id" onchange="document.getElementById('form_2').submit();">
                    <option value="">不限</option>
                    <?php
                    $cates = $db->select('ebcms_scms_cate', '*', [
                        'column_id' => $request->get('column_id'),
                        'ORDER' => [
                            'rank' => 'DESC',
                            'id' => 'ASC'
                        ]
                    ]);
                    ?>
                    {foreach $cates as $vo}
                    <option {if $request->get('cate_id')==$vo['id']}selected{/if} value="{$vo.id}">{$vo.title}</option>
                    {/foreach}
                </select>
            </div>
            {/if}

            {/if}


            <div class="col-auto">
                <label class="visually-hidden">搜索</label>
                <input type="search" class="form-control" name="q" value="{:$request->get('q')}" placeholder="搜索.." onchange="document.getElementById('form_2').submit();">
            </div>
            <input type="hidden" name="page" value="1">
        </form>
    </div>
    <div class="table-responsive mb-3">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th class="text-nowrap">站点</th>
                    <th class="text-nowrap">栏目</th>
                    <th class="text-nowrap">分类</th>
                    <th class="text-nowrap">标题</th>
                    <th class="text-nowrap">发布人</th>
                    <th class="text-nowrap" style="width:200px;">更新时间</th>
                    <th class="text-nowrap" style="width:100px;">管理</th>
                </tr>
            </thead>
            <tbody>
                {foreach $contents as $vo}
                <tr>
                    <td>{$vo['site']['title']??'未知'}</td>
                    <td>{$vo['column']['title']??'未知'}</td>
                    <td>{$vo['cate']['title']??'未知'}</td>
                    <td class="text-nowrap text-truncate" style="max-width: 30em;">{$vo.title}</td>
                    <td class="text-nowrap text-truncate" style="max-width: 30em;">{$vo['user']['nickname']??'-'}</td>
                    <td>{:date('Y-m-d H:i:s', $vo['update_time'])}</td>
                    <td class="text-nowrap">
                        <a href="{echo $router->build('/ebcms/scms/content/update', ['id'=>$vo['id']])}">编辑</a>
                        <a href="{echo $router->build('/ebcms/scms/content/delete', ['id'=>$vo['id']])}" onclick="return confirm('删除后无法恢复，确定删除？');">删除</a>
                        <a href="{echo $router->build('/ebcms/scms/content/create', ['copyfrom'=>$vo['id']])}">复制</a>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
    <nav class="mb-3">
        <ul class="pagination">
            {foreach $pagination as $v}
            {if $v=='...'}
            <li class="page-item disabled"><a class="page-link" href="javascript:void(0);">{$v}</a></li>
            {elseif isset($v['current'])}
            <li class="page-item active"><a class="page-link" href="javascript:void(0);">{$v.page}</a></li>
            {else}
            <li class="page-item"><a class="page-link" href="{echo $router->build('/ebcms/scms/content/index', array_merge($request->get(), ['page'=>$v['page']]))}">{$v.page}</a></li>
            {/if}
            {/foreach}
        </ul>
    </nav>
</div>
{include common/footer@ebcms/admin}