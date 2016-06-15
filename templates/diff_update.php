<?php 
use lib\Config;
?>
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
        .tableName {margin:0 10px 0 10px;padding:0;height:40px;line-height:40px;}
        .tableName spna{float:left;}
        .tableName a{float:right;}
        .v_content .diff-block {margin-bottom:20px;border:1px solid #ccc;}
        .sqlCode {margin-top:10px;}
        .sqlCode .my_sql{display:none;}
        pre {border-radius:0;margin:0px;border:0;padding:0;}
    </style>
</head>
<body onload="hljs.initHighlighting()">
<?php $this->navActive='Version';$this->import('nav');?>
<div class="container">
    <?php if($this->diffData['clash']): ?>
    <div>
        <p class="bg-warning text-danger" style="height:35px;line-height:35px;padding-left:10px;font-size:16px;">
            <span>远程版本和你本地版本存在冲突，默认采用远程版本库修改，请注意确认！</span>
        </p>
    </div>
    <?php endif; ?>
    <div>
        <div class="v_content">
            <?php foreach($this->diffData['remote'] as $tableName => $diff):?>
                 <div class="row bg-white diff-block box">
                    <h4 class="tableName">
                        <span><?=$tableName?> <?=$this->diffData['tables'][$tableName]['name']?></span>
                        <?php if($this->diffData['tables'][$tableName]['clash']): ?>
                        <a href="" data-table="<?=$tableName?>" class="clash text-del" >使用我的</a>
                        <?php endif; ?>
                    </h4>
                    <div class="col-md-6 borRight v_diff">
                    <?php foreach ($this->diffData['remote'][$tableName] as $i=>$item):?>
                        <div class="row lineRow">
                            <div class="col-md-1 line1"><span class='text-<?=Config::$config[$item[1]]?>'><?=$item[1]?></span></div>
                            <div class="col-md-11 text-<?=$item[2]?>"><?=$item[0]?></div>
                        </div>
                    <?php endforeach;?>
                    </div>
                    <div class="colorR"></div>
                    <div class="col-md-6 borRight v_diff">
                    <?php foreach ($this->diffData['local'][$tableName] as $i=>$item):?>
                        <div class="row lineRow">
                            <div class="col-md-1 line1"><span class='text-<?=Config::$config[$item[1]]?>'><?=$item[1]?:"　"?></span></div>
                            <div class="col-md-11 text-<?=$item[2]?>"><?=$item[0]?></div>
                        </div>
                    <?php endforeach; ?>
                    </div>
                    <div class="clearfix"></div>
                    <div class="sqlCode clearfix">
                       <div class="my_sql"><pre><code class="hljs sql"><?=$this->diffData['my_sql'][$tableName]?></code></pre></div>
                       <div class="it_sql"><pre><code class="hljs sql"><?=$this->diffData['it_sql'][$tableName]?></code></pre></div>
                    </div>
                 </div>
            <?php endforeach; ?>
            <p class="text-center"><a href="" data-url="<?=U("Version", "change")?>" class="btn btn-success btn-lg changeData">确认更新</a></p>
        </div>
    </div>
</div>
<script src="<?=STATIC_PATH?>js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?=STATIC_PATH?>highlight/highlight.pack.js"></script>
<script type="text/javascript">
	$(function(){
		$(".colorR").each(function(){
			$(this).css('height',$(this).next(".borRight").find('.lineRow').height() * $(this).next(".borRight").find('.lineRow').length);
		});

		var clashSolve = {};
		$(".changeData").click(function(){
			var tables = [];
			var i = 0;
			for(table in clashSolve){
				tables[i] = table;
				i++;
			}
			if(tables.length>0){
				$(this).attr("href", $(this).attr("data-url")+"&tables="+tables.join(','));
			}else{
				$(this).attr("href", $(this).attr("data-url"));
			}
		});
		
		$(".clash").click(function(){
			var name = $(this).attr("data-table");
			var mySqlCode = $(this).parents('.diff-block').find(".sqlCode .my_sql");
			var ItSqlCode = $(this).parents('.diff-block').find(".sqlCode .it_sql");
			if(clashSolve[name]){
				delete clashSolve[name];
				$(this).text("使用我的");

				if($(this).parents('.diff-block').find(".sqlCode .it_sql code").text()==""){
					$(this).parents('.diff-block').find(".sqlCode").css("display", "none");
				}else{
					$(this).parents('.diff-block').find(".sqlCode").css("display", "block");
				}
				
				ItSqlCode.css("display", "block");
				mySqlCode.css("display", "none");
			}else{
				clashSolve[name] = true;
				$(this).text("使用它的");

				if($(this).parents('.diff-block').find(".sqlCode .my_sql code").text()==""){
					$(this).parents('.diff-block').find(".sqlCode").css("display", "none");
				}else{
					$(this).parents('.diff-block').find(".sqlCode").css("display", "block");
				}
				mySqlCode.css("display", "block");
				ItSqlCode.css("display", "none");
			}

			return false;
		})
	})
</script>
</body></html>