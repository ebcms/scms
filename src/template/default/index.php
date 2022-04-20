{include common/header@ebcms/ucenter-web}
{if $companys}
<div class="container-xxl mt-3">
    <div class="fs-1 mb-3">商户平台</div>
    {foreach $companys as $company}
    <div class="mb-3 bg-light">
        <div class="d-flex gap-3 mb-3 p-3 position-relative">
            <div>
                <img src="{$company.logo}" style="max-width:200px;max-height:200px;">
            </div>
            <div>
                <dl>
                    <dt>商户名称</dt>
                    <dd>{$company.title}</dd>
                    <dt>统一社会信用代码</dt>
                    <dd>{$company.business_code}</dd>
                    <dt>主营产品</dt>
                    <dd>{$company.business_product}</dd>
                    <dd>
                        <a href="{echo $router->build('/ebcms/b2b/ucenter/company/index', ['company_id'=>$company['id']])}" class="text-decoration-none stretched-link">管理</a>
                    </dd>
                </dl>
            </div>
        </div>
    </div>
    {/foreach}
</div>
{else}
<div class="container-xxl">
    <a href="{echo $router->build('/ebcms/b2b/ucenter/company/create')}" class="btn btn-primary">暂未开通商户平台，立即开通</a>
</div>
{/if}
{include common/footer@ebcms/ucenter-web}