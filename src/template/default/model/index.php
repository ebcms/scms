{include common/header@ebcms/admin}
<div class="container-xxl">
    <div class="my-3">
        <a class="btn btn-primary" href="{echo $router->build('/ebcms/scms/model/create')}">添加模型</a>
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
                {foreach $models as $vo}
                <tr>
                    <td>{$vo.title}</td>
                    <td class="text-nowrap">
                        <a href="{echo $router->build('/ebcms/scms/model/update', ['id'=>$vo['id']])}">编辑</a>
                        <a href="{echo $router->build('/ebcms/scms/model/delete', ['id'=>$vo['id']])}" onclick="return confirm('删除后无法恢复，确定删除？');">删除</a>
                        <a href="{echo $router->build('/ebcms/scms/field/index', ['model_id'=>$vo['id']])}">字段管理</a>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
{include common/footer@ebcms/admin}