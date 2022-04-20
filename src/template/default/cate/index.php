{include common/header@ebcms/admin}
<div class="container-xxl">
    <div class="my-3">
        <a class="btn btn-primary" href="{echo $router->build('/ebcms/scms/cate/create', ['column_id'=>$request->get('column_id',0),'pid'=>$request->get('pid',0)])}">添加分类</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>标题</th>
                    <th style="width:200px;">管理</th>
                </tr>
            </thead>
            <tbody>
                {if $pcate}
                <tr>
                    <td colspan="2">
                        <a href="{echo $router->build('/ebcms/scms/cate/index', ['column_id'=>$request->get('column_id',0),'pid'=>$pcate['pid']])}">上一级</a>
                    </td>
                </tr>
                {else}
                <tr>
                    <td colspan="2">
                        <a href="{echo $router->build('/ebcms/scms/column/index', ['site_id'=>$column['site_id']])}">上一级</a>
                    </td>
                </tr>
                {/if}
                {foreach $cates as $vo}
                <tr>
                    <td>{$vo.title}</td>
                    <td class="text-nowrap">
                        <a href="{echo $router->build('/ebcms/scms/cate/update', ['id'=>$vo['id']])}">编辑</a>
                        <a href="{echo $router->build('/ebcms/scms/cate/rank', ['id'=>$vo['id'],'type'=>'up'])}">上移</a>
                        <a href="{echo $router->build('/ebcms/scms/cate/rank', ['id'=>$vo['id'],'type'=>'down'])}">下移</a>
                        <a href="{echo $router->build('/ebcms/scms/cate/delete', ['id'=>$vo['id']])}" onclick="return confirm('删除后无法恢复，确定删除？');">删除</a>
                        <a href="{echo $router->build('/ebcms/scms/cate/index', ['column_id'=>$request->get('column_id',0),'pid'=>$vo['id']])}">下级</a>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
{include common/footer@ebcms/admin}