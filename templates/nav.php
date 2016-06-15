<?php
use lib\Config;
?>
<style type="text/css">
.nav .logoText{height:52px;line-height: 52px;font-size: 24px; padding-right: 100px;color:#fff;}
.nav li{margin-right: 30px;}
.nav a{color:#fff;font-size:16px;}
.nav>li>a:hover{background:#006699 !important;}
.nav>li.active{background:#006699 !important;}
.navbar{border-radius:0}
.select_box{float: right;padding-top: 8px;}
.select_box .dropdown{margin-left: 20px;float: left;}
</style>
<script src="<?=STATIC_PATH?>js/jquery1.10.2.js"></script>
<nav class="navbar" style="background: #148CCA !important;">
    <div class="container">
        <div id="navbar" >
            <ul class="nav navbar-nav">
                <li class="logoText">DB版本控制</li>
                <li class="<?=$this->navActive=='Index'?'active':''?>"><a href="<?=U('Index', 'index');?>">DB字典</a></li>
                <li class="<?=$this->navActive=='Version'?'active':''?>"><a href="<?=U('Version', 'index')?>">版本控制</a></li>
                <li class="<?=$this->navActive=='Log'?'active':''?>"><a href="<?=U('Log', 'index');?>">历史记录</a></li>
                <?php if (Config::$config['UPGRADE']): ?>
                <li class="<?=$this->navActive=='Upgrade'?'active':''?>"><a href="<?=U('Upgrade', 'diff');?>">升级对比</a></li>
                <?php endif; ?>
            </ul>
            <?php if(Config::$config['SERVERS']):?>
            <div class="select_box">
                <div class="dropdown">
                  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    <?=Config::$config['SERVER_NAME'];?>
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <?php foreach(Config::$config['SERVERS'] as $key=>$item):?>
                    <li><a href="<?=U("Index","index", array('server'=>$key))?>"><?=$item['NAME']?></a></li>
                    <?php endforeach; ?>
                  </ul>
                </div>
                <div class="dropdown">
                  <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <?=Config::$config['ENVS_NAME'];?>
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                    <?php foreach (Config::$config['SERVER_ENVS'] as $key=>$item):?>
                    <li><a href="<?=U("Index","index", array("env"=>$key)) ?>"><?=$item['NAME']?></a></li>
                    <?php endforeach;?>
                  </ul>
                </div>
            </div>
            <?php endif;?>
        </div>
    </div>
</nav>