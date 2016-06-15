<!DOCTYPE html>
<html lang="zh-CN"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>数据库字典</title>
    <link href="<?=STATIC_PATH?>css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        table td{text-align: left;}
        .Info{background-color: #fff;border: 1px solid #ccc;border-radius: 4px;margin: 0 0 10px;padding:0 9px;}
    </style>
</head>
<body>
<?php $this->import('nav');?>
<div class="container">
    <div class="starter-template Info">
        <h3 class="text-danger">PHP Fatal error: <?=$this->e->getMessage()?></h3>
        <p class="text-danger"><?=$this->e->getFile()?> [Line:<?=$this->e->getLine()?>]</p>
        <pre><b>Stack trace:</b>
<?=$this->e->getTraceAsString()?></pre>
        <p class="text-warning">程序出现错误请检查环境或联系程序维护人！</p>
    </div>
</div><!-- /.container -->
<script src="<?=STATIC_PATH?>js/bootstrap.min.js"></script>
</body></html>