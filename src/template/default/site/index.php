{include common/header@ebcms/admin}
<div class="container-xxl">
    <div class="my-3">
        <a class="btn btn-primary" href="{echo $router->build('/ebcms/scms/site/create')}">添加站点</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>站点</th>
                    <th>站点地址</th>
                    <th style="width:200px;">管理</th>
                </tr>
            </thead>
            <tbody>
                {foreach $sites as $vo}
                <tr>
                    <td>{$vo.title}{if $vo['redirect']}<span class="text-danger ms-1">[跳转]</span>{/if}</td>
                    <td>{$vo.siteurl}</td>
                    <td class="text-nowrap">
                        <a href="{echo $router->build('/ebcms/scms/site/update', ['id'=>$vo['id']])}">编辑</a>
                        <a href="{echo $router->build('/ebcms/scms/site/delete', ['id'=>$vo['id']])}" onclick="return confirm('删除后无法恢复，确定删除？');">删除</a>
                        <a href="{echo $router->build('/ebcms/scms/site/rank', ['id'=>$vo['id'],'type'=>'up'])}">上移</a>
                        <a href="{echo $router->build('/ebcms/scms/site/rank', ['id'=>$vo['id'],'type'=>'down'])}">下移</a>
                        <a href="{echo $router->build('/ebcms/scms/column/index', ['site_id'=>$vo['id']])}">栏目管理</a>
                        <a href="{echo $router->build('/ebcms/scms/area/index', ['site_id'=>$vo['id']])}">地区管理</a>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
{include common/footer@ebcms/admin}