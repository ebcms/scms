{include common/header@ebcms/admin}
<div class="container-xxl">
    <div class="my-3">
        <a class="btn btn-primary" href="{echo $router->build('/ebcms/scms/source/create', ['site_id'=>$request->get('site_id')])}">添加数据源</a>
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
                <tr>
                    <th colspan="2">
                        <a href="{echo $router->build('/ebcms/scms/site/index')}">上一级</a>
                    </th>
                </tr>
                {foreach $sources as $vo}
                <tr>
                    <td>{$vo.title}</td>
                    <td class="text-nowrap">
                        <a href="{echo $router->build('/ebcms/scms/source/update', ['id'=>$vo['id']])}">编辑</a>
                        <a href="{echo $router->build('/ebcms/scms/source/delete', ['id'=>$vo['id']])}" onclick="return confirm('删除后无法恢复，确定删除？');">删除</a>
                        <a href="{echo $router->build('/ebcms/scms/data/index', ['source_id'=>$vo['id']])}">数据管理</a>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
{include common/footer@ebcms/admin}