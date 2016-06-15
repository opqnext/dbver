<!DOCTYPE html>
<html lang="zh-CN"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <meta name="description" content="">
    <meta name="author" content="">

    <title>版本控制</title>
    
    <link type="text/css" href="<?=STATIC_PATH?>highlight/styles/solarized-dark.css" rel="stylesheet" />
    <link href="<?=STATIC_PATH?>css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        .title{height:50px;line-height: 50px;background:#d9edf7;text-align:center}
        .v_content .row,.col-md-6,.col-md-1{padding: 0px;margin:0px;}
        .v_diff {background:#002a36;color:#9c9c9c;}
        .borRight{width:50%;overflow-x: auto;white-space: nowrap;}
        .colorR{border-right:5px solid #dd40a9;position: absolute; left:50.5%;top:40px;width: 5px;z-index:100;}
        .box{position: relative;}
        .lineRow{line-height: 30px;height:30px;}
        .line1{border-right: 1px solid #004b60;text-align: center;}
        .tableName {margin:0 0 0 10px;padding:0;height:40px;line-height:40px;}
        .v_content .diff-block {margin-bottom:20px;border:1px solid #ccc;}
        .sqlCode {margin-top:10px;}
        pre {border-radius:0;margin:0px;border:0;padding:0;}
        .message{width:1100px;min-height:300px;border-radius: 25px;margin:0 auto;padding:10px;}
        .message div{font-size:24px;text-align:center;margin:100px;}
        .message p{font-size:22px;text-align:center;}
        .text-error{color:red}
    </style>
</head>
<body onload="hljs.initHighlighting()">
<?php $this->navActive='Upgrade';$this->import('nav');?>
<div class="container">
    <?php if($this->diffFormat['old']):?>
    <div>
        <p class="bg-warning text-danger" style="height:35px;line-height:30px;padding-left:10px;font-size:16px;">
            <span>你最近一次同步在：<?=getTimeFormatText($this->upDate)?></span>
            <a href="<?=U("Upgrade", "update")?>" style="float:right;margin-right:20px;">重新同步</a>
        </p>
    </div>
    <div>
        <div class="v_content">
            <?php foreach($this->diffFormat['old'] as $tableName => $diff):?>
                 <div class="row bg-white diff-block box">
                    <h4 class="tableName"><?=$tableName?> <?=$this->diffFormat['tables'][$tableName]?></h4>
                    <div class="col-md-6 borRight v_diff">
                    <?php foreach ($this->diffFormat['old'][$tableName] as $i=>$item):?>
                        <div class="row lineRow">
                            <div class="col-md-1 line1"><?=is_null($this->diffFormat['new'][$tableName][$i]) ? "<span class='text-del'>-</span>":"　"?></div>
                            <div class="col-md-11 <?=is_null($this->diffFormat['new'][$tableName][$i])?'text-del':"text-edit"?>"><?=$item?></div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                    <div class="colorR" style="height:<?=count($this->diffFormat['new'][$tableName])*30?>px;"></div>
                    <div class="col-md-6 borRight v_diff">
                    <?php foreach ($this->diffFormat['new'][$tableName] as $i=>$item):?>
                        <div class="row lineRow">
                            <div class="col-md-1 line1"><?=is_null($this->diffFormat['old'][$tableName][$i]) ? "<span class='text-add'>+</span>":"　"?></div>
                            <div class="col-md-11 <?=is_null($this->diffFormat['old'][$tableName][$i])?'text-add':"text-edit"?>"><?=$item?></div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="sqlCode clearfix">
                       <pre><code class="hljs sql"><?=$this->diffSql[$tableName]?></code></pre>
                    </div>
                 </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php else:?>
    <div class="message bg-info">
        <div class="text-danger">生产环境和版本库一致，你最近一次同步在<?=getTimeFormatText($this->upDate)?>。</div>
        <p><a href="<?=U("Upgrade", "update")?>" class="btn btn-success btn-lg" role="button">立即同步</a></p>
    </div>
    <?php endif;?>
</div>
<script src="<?=STATIC_PATH?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=STATIC_PATH?>highlight/highlight.pack.js"></script>
</body></html>