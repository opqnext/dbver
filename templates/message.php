<!DOCTYPE html>
<html lang="zh-CN"><head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>DB版本控制</title>
    <link href="<?=STATIC_PATH?>css/bootstrap.min.css" rel="stylesheet">
    <style type="text/css">
        .message{width:960px;min-height:300px;border-radius: 25px;margin:0 auto;padding:10px;}
        .message div{font-size:24px;text-align:center;margin:100px;}
        .message p{font-size:22px;text-align:center;}
        .text-error{color:red}
    </style>
</head>
<body>
<?php $this->import('nav');?>
<div class="container">
    <?php switch ($this->type) : case "waring":?>
    <div class="message bg-info">
        <div class="text-danger"><?=$this->message?></div>
        <p>
            <?php foreach($this->params as $button):?>
            <a href="<?=$button[1]?>" class="btn btn-success btn-lg" role="button"><?=$button[0]?></a>
            <?php endforeach;?>
        </p>
    </div>
    <?php break;case "info":case "error": ?>
    <script>
    setTimeout(function(){
    	location = "<?=$this->params[0]?>"
    },<?=$this->params[1]*1000?>);
    </script>
    <div class="message bg-info">
        <div class="<?=$this->type=='info'?"text-success":"text-error"?>"><?=$this->message?></div>
        <p><a href="<?=$this->params[0]?>" class="btn btn-success btn-lg" role="button">立即跳转</a></p>
    </div> 
    <?php break; ?>
    <?php endswitch; ?>
</div>
<script src="<?=STATIC_PATH?>js/bootstrap.min.js"></script>
</body></html>