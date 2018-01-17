<!DOCTYPE html>
<html lang="en">

<title>兑换记录</title>
{include file='index/header'}

<body id="record" class="flexv">
<div id="common-header" class="flex">
    <a href="javascript:history.back()" class=" cBtn back"><i class="iconfont icon-back"></i></a>
    <h1>兑换记录</h1>
</div>
<div class="flexitemv scroll main">
    {volist name='list' id='v'}
    <div class="flex record-item">
        {neq name='v.iu_indentid' value=''}<a href="{:url('Indent/indentDetail',['id'=>$v.iu_indentid])}" class="flexitem centerv">{/neq}
            <div class="flexitemv centerh intro">
                <p>付款推广积分兑换{eq name='v.iu_mean' value='1'}商品{else /}提现{/eq}</p>
                <span>{$v.createtime|date='Y-m-d H:i:s',###}</span>
            </div>
            <div class="flexv center text">
                <p>{$v.iu_integral}</p>
                <span>{eq name='v.iu_status' value='1'}兑换成功{else /}兑换失败{/eq}</span>
            </div>
            {neq name='v.iu_indentid' value=''}<i class="iconfont icon-go"></i>{/neq}
        </a>
    </div>
    {/volist}
</div>
<script src="https://s22.cnzz.com/z_stat.php?id=1270643347&web_id=1270643347" language="JavaScript"></script>
</body>
{js href="https://cdn.bootcss.com/jquery/2.0.0/jquery.min.js"}
<script>
    $('a').last().hide();
</script>
</html>