{include common/header@ebcms/admin}
<div class="container-xxl">
    <div class="my-3">
        <a class="btn btn-primary" href="{echo $router->build('/ebcms/scms/area/create', ['site_id'=>$request->get('site_id',0),'pid'=>$request->get('pid',0)])}">添加区域</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>标题</th>
                    <th>名称</th>
                    <th style="width:200px;">管理</th>
                </tr>
            </thead>
            <tbody>
                {if $parea}
                <tr>
                    <td colspan="3">
                        <a href="{echo $router->build('/ebcms/scms/area/index', ['site_id'=>$request->get('site_id',0),'pid'=>$parea['pid']])}">上一级</a>
                    </td>
                </tr>
                {else}
                <tr>
                    <td colspan="3">
                        <a href="{echo $router->build('/ebcms/scms/site/index')}">上一级</a>
                    </td>
                </tr>
                {/if}
                {foreach $areas as $vo}
                <tr>
                    <td>{$vo.title}</td>
                    <td>{$vo.name}</td>
                    <td class="text-nowrap">
                        <a href="{echo $router->build('/ebcms/scms/area/update', ['id'=>$vo['id']])}">编辑</a>
                        <a href="{echo $router->build('/ebcms/scms/area/rank', ['id'=>$vo['id'],'type'=>'up'])}">上移</a>
                        <a href="{echo $router->build('/ebcms/scms/area/rank', ['id'=>$vo['id'],'type'=>'down'])}">下移</a>
                        <a href="{echo $router->build('/ebcms/scms/area/delete', ['id'=>$vo['id']])}" onclick="return confirm('删除后无法恢复，确定删除？');">删除</a>
                        <a href="{echo $router->build('/ebcms/scms/area/index', ['site_id'=>$request->get('site_id',0),'pid'=>$vo['id']])}">下级</a>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
{include common/footer@ebcms/admin}