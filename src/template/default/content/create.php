{include common/header@ebcms/admin}
<div class="container-xxl">
    <div class="my-3">
        <h1>添加信息</h1>
    </div>
    <div class="mb-3">
        <form id="form_2" class="row gy-2 gx-3 align-items-center" action="{echo $router->build('/ebcms/scms/content/create')}" method="GET">
            <?php
            $sites = $db->select('ebcms_scms_site', '*', [
                'ORDER' => [
                    'rank' => 'DESC',
                    'id' => 'ASC'
                ]
            ]);
            ?>
            <div class="col-auto">
                <label class="visually-hidden">站点</label>
                <select class="form-select" name="site_id" onchange="document.getElementById('form_2').submit();">
                    <option value="">请选择</option>
                    {foreach $sites as $vo}
                    <option {if $request->get('site_id')==$vo['id']}selected{/if} value="{$vo.id}">{$vo.title}</option>
                    {/foreach}
                </select>
            </div>

            {if $request->get('site_id')}
            <?php
            $columns = $db->select('ebcms_scms_column', '*', [
                'site_id' => $request->get('site_id'),
                'ORDER' => [
                    'rank' => 'DESC',
                    'id' => 'ASC'
                ]
            ]);
            ?>
            <div class="col-auto">
                <label class="visually-hidden">栏目</label>
                <select class="form-select" name="column_id" onchange="document.getElementById('form_2').submit();">
                    <option value="">请选择</option>
                    {foreach $columns as $vo}
                    <option {if $request->get('column_id')==$vo['id']}selected{/if} value="{$vo.id}">{$vo.title}</option>
                    {/foreach}
                </select>
            </div>

            {if $request->get('column_id')}
            <?php
            $cates = $db->select('ebcms_scms_cate', '*', [
                'column_id' => $request->get('column_id'),
                'ORDER' => [
                    'rank' => 'DESC',
                    'id' => 'ASC'
                ]
            ]);
            ?>
            <div class="col-auto">
                <label class="visually-hidden">分类</label>
                <select class="form-select" name="cate_id" onchange="document.getElementById('form_2').submit();">
                    <option value="">请选择</option>
                    {foreach $cates as $vo}
                    <option {if $request->get('cate_id')==$vo['id']}selected{/if} value="{$vo.id}">{$vo.title}</option>
                    {/foreach}
                </select>
            </div>
            {/if}

            {/if}
        </form>
    </div>
</div>
{include common/footer@ebcms/admin}