{include common/header@ebcms/admin}
<div class="container-xxl">
    <div class="my-3">
        <h1>字段管理</h1>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>标题</th>
                    <th>字段</th>
                    <th>类型</th>
                    <th style="width:200px;">管理</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th colspan="4">
                        <span>筛选字段</span>
                        <a href="{echo $router->build('/ebcms/scms/field/create', ['model_id'=>$model['id'],'is_filter'=>1])}">添加</a>
                    </th>
                </tr>
                <tr>
                    <td class="text-nowrap text-muted">站点id</td>
                    <td class="text-nowrap text-muted">site_id</td>
                    <td class="text-nowrap text-muted"></td>
                    <td class="text-nowrap text-muted">
                        系统字段
                    </td>
                </tr>
                <tr>
                    <td class="text-nowrap text-muted">栏目id</td>
                    <td class="text-nowrap text-muted">column_id</td>
                    <td class="text-nowrap text-muted"></td>
                    <td class="text-nowrap text-muted">
                        系统字段
                    </td>
                </tr>
                <tr>
                    <td class="text-nowrap text-muted">分类id</td>
                    <td class="text-nowrap text-muted">cate_id</td>
                    <td class="text-nowrap text-muted"></td>
                    <td class="text-nowrap text-muted">
                        系统字段
                    </td>
                </tr>
                <tr>
                    <td class="text-nowrap text-muted">地区id</td>
                    <td class="text-nowrap text-muted">area_id</td>
                    <td class="text-nowrap text-muted"></td>
                    <td class="text-nowrap text-muted">
                        系统字段
                    </td>
                </tr>
                <tr>
                    <td class="text-nowrap text-muted">发布人id</td>
                    <td class="text-nowrap text-muted">user_id</td>
                    <td class="text-nowrap text-muted"></td>
                    <td class="text-nowrap text-muted">
                        系统字段
                    </td>
                </tr>
                {foreach $fields as $vo}
                {if $vo['is_filter']==1}
                <tr>
                    <td>{$vo.title}</td>
                    <td>{$vo.name}</td>
                    <td>{$vo.type}</td>
                    <td class="text-nowrap">
                        <a href="{echo $router->build('/ebcms/scms/field/update', ['id'=>$vo['id']])}">编辑</a>
                        <a href="{echo $router->build('/ebcms/scms/field/rank', ['id'=>$vo['id'],'type'=>'up'])}">上移</a>
                        <a href="{echo $router->build('/ebcms/scms/field/rank', ['id'=>$vo['id'],'type'=>'down'])}">下移</a>
                        <a href="{echo $router->build('/ebcms/scms/field/delete', ['id'=>$vo['id']])}" onclick="return confirm('删除后无法恢复，确定删除？');">删除</a>
                        <a href="{echo $router->build('/ebcms/scms/data/index', ['field_id'=>$vo['id']])}">数据设置</a>
                    </td>
                </tr>
                {/if}
                {/foreach}
                <tr>
                    <th colspan="4">
                        <span>内容字段</span>
                        <a href="{echo $router->build('/ebcms/scms/field/create', ['model_id'=>$model['id'],'is_filter'=>0])}">添加</a>
                    </th>
                </tr>
                <tr>
                    <td class="text-nowrap text-muted">标题</td>
                    <td class="text-nowrap text-muted">title</td>
                    <td class="text-nowrap text-muted"></td>
                    <td class="text-nowrap text-muted">
                        系统字段
                    </td>
                </tr>
                {foreach $fields as $vo}
                {if $vo['is_filter']==0}
                <tr>
                    <td>{$vo.title}</td>
                    <td>{$vo.name}</td>
                    <td>{$vo.type}</td>
                    <td class="text-nowrap">
                        <a href="{echo $router->build('/ebcms/scms/field/update', ['id'=>$vo['id']])}">编辑</a>
                        <a href="{echo $router->build('/ebcms/scms/field/rank', ['id'=>$vo['id'],'type'=>'up'])}">上移</a>
                        <a href="{echo $router->build('/ebcms/scms/field/rank', ['id'=>$vo['id'],'type'=>'down'])}">下移</a>
                        <a href="{echo $router->build('/ebcms/scms/field/delete', ['id'=>$vo['id']])}" onclick="return confirm('删除后无法恢复，确定删除？');">删除</a>
                    </td>
                </tr>
                {/if}
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
{include common/footer@ebcms/admin}