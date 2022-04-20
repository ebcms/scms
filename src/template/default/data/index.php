{include common/header@ebcms/admin}
<div class="container-xxl">
    <div class="my-3">
        <a class="btn btn-primary" href="{echo $router->build('/ebcms/scms/data/create', ['source_id'=>$request->get('source_id',0),'pid'=>$request->get('pid',0)])}">添加数据</a>
    </div>
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>标题</th>
                    <th>名称</th>
                    <th>值</th>
                    <th style="width:200px;">管理</th>
                </tr>
            </thead>
            <tbody>
                {if $pdata}
                <tr>
                    <td colspan="4">
                        <a href="{echo $router->build('/ebcms/scms/data/index', ['source_id'=>$request->get('source_id',0),'pid'=>$pdata['pid']])}">上一级</a>
                    </td>
                </tr>
                {else}
                <tr>
                    <td colspan="4">
                        <a href="{echo $router->build('/ebcms/scms/source/index')}">上一级</a>
                    </td>
                </tr>
                {/if}
                {foreach $datas as $vo}
                <tr>
                    <td>{$vo.title}</td>
                    <td>{$vo.name}</td>
                    <td>{$vo.value}</td>
                    <td class="text-nowrap">
                        <a href="{echo $router->build('/ebcms/scms/data/update', ['id'=>$vo['id']])}">编辑</a>
                        <a href="{echo $router->build('/ebcms/scms/data/rank', ['id'=>$vo['id'],'type'=>'up'])}">上移</a>
                        <a href="{echo $router->build('/ebcms/scms/data/rank', ['id'=>$vo['id'],'type'=>'down'])}">下移</a>
                        <a href="{echo $router->build('/ebcms/scms/data/delete', ['id'=>$vo['id']])}" onclick="return confirm('删除后无法恢复，确定删除？');">删除</a>
                        <a href="{echo $router->build('/ebcms/scms/data/index', ['source_id'=>$request->get('source_id',0),'pid'=>$vo['id']])}">下级</a>
                    </td>
                </tr>
                {/foreach}
            </tbody>
        </table>
    </div>
</div>
{include common/footer@ebcms/admin}