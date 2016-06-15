<!DOCTYPE html>
<html lang="zh-CN"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>数据库字典</title>
    <link href="<?=STATIC_PATH?>css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        table td{text-align: left;}
        .tableInfo{background-color: #fff;border: 1px solid #ccc;border-radius: 4px;margin: 0 0 10px;padding:0 9px;}
        <?php if($this->commitInfo): ?>
        .tableInfo {background-color: #F0FaF6}
        <?php endif; ?>
    </style>
</head>
<body>
<?php $this->navActive='Index';$this->import('nav');?>
<?php if($this->commitInfo):?>
<div class="container">
    <div class="starter-template bg-white" style="padding:10px;margin-bottom:10px;border: 1px solid #ccc;border-radius: 4px;">
        <p><h3><?=$this->commitInfo['message']?></h3></p>
        <p>作者：<a href="mailto:<?=$this->commitInfo['email']?>?subject=Re<<?=$this->commitInfo['id']?>>: <?=$this->commitInfo['message']?>" class="text-success" title="<?=$this->commitInfo['email']?>"><?=$this->commitInfo['name']?></a> 在 <?=getTimeFormatText($this->commitInfo['dateline'])?> 调整</p>
    </div>
</div>
<?php endif; ?>
<div class="container">
    <div class="starter-template ">
        <?php foreach($this->details as $tableName=>$item):?>
        <div class="tableInfo  bg-white">
            <h3><?=$tableName?> <?=$item['info'][2]?></h3>
            <table class="table table-bordered table-hover table-striped">
                <thead>
                <tr>
                    <th class="bg-primary text-center" style="width:200px;">字段名</th>
                    <th class="bg-primary text-center" style="width:120px;">数据类型</th>
                    <th class="bg-primary text-center" style="width:80px;">允许非空</th>
                    <th class="bg-primary text-center" style="width:120px;">默认值</th>
                    <th class="bg-primary text-center" style="width:80px;">自动自增</th>
                    <th class="bg-primary text-center">描述</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($item['fields'] as $name=>$info):?>
                <tr>
                    <td><?=$name?></td>
                    <td><?=explode(" ",$info[0])[0]?></td>
                    <td><?=$info[2]?></td>
                    <td><?=$info[3]===''?"''":$info[3]?></td>
                    <td><?=$info[4] ? "是":""?></td>
                    <td><?=$info[5]?></td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endforeach;?>
    </div>

</div><!-- /.container -->
<script src="<?=STATIC_PATH?>js/bootstrap.min.js"></script>
</body></html>