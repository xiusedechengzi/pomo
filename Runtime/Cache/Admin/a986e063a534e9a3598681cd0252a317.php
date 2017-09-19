<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?php echo ($meta_title); ?>|OneThink管理平台</title>
    <link href="/onethink/Public/favicon.ico" type="image/x-icon" rel="shortcut icon">
    <link rel="stylesheet" type="text/css" href="/onethink/Public/Admin/css/base.css" media="all">
    <link rel="stylesheet" type="text/css" href="/onethink/Public/Admin/css/common.css" media="all">
    <link rel="stylesheet" type="text/css" href="/onethink/Public/Admin/css/module.css">
    <link rel="stylesheet" type="text/css" href="/onethink/Public/Admin/css/style.css" media="all">
	<link rel="stylesheet" type="text/css" href="/onethink/Public/Admin/css/<?php echo (C("COLOR_STYLE")); ?>.css" media="all">
     <!--[if lt IE 9]>
    <script type="text/javascript" src="/onethink/Public/static/jquery-1.10.2.min.js"></script>
    <![endif]--><!--[if gte IE 9]><!-->
    <script type="text/javascript" src="/onethink/Public/static/jquery-2.0.3.min.js"></script>
    <script type="text/javascript" src="/onethink/Public/Admin/js/jquery.mousewheel.js"></script>
    <!--<![endif]-->
    
    <script type="text/javascript" src="/onethink/Public/static/uploadify/jquery.uploadify.min.js"></script>

</head>
<body>
    <!-- 头部 -->
    <div class="header">
        <!-- Logo -->
        <span class="logo"></span>
        <!-- /Logo -->

        <!-- 主导航 -->
        <ul class="main-nav">
            <?php if(is_array($__MENU__["main"])): $i = 0; $__LIST__ = $__MENU__["main"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li class="<?php echo ((isset($menu["class"]) && ($menu["class"] !== ""))?($menu["class"]):''); ?>"><a href="<?php echo (u($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a></li><?php endforeach; endif; else: echo "" ;endif; ?>
        </ul>
        <!-- /主导航 -->

        <!-- 用户栏 -->
        <div class="user-bar">
            <a href="javascript:;" class="user-entrance"><i class="icon-user"></i></a>
            <ul class="nav-list user-menu hidden">
                <li class="manager">你好，<em title="<?php echo session('user_auth.username');?>"><?php echo session('user_auth.username');?></em></li>
                <li><a href="<?php echo U('User/updatePassword');?>">修改密码</a></li>
                <li><a href="<?php echo U('User/updateNickname');?>">修改昵称</a></li>
                <li><a href="<?php echo U('Public/logout');?>">退出</a></li>
            </ul>
        </div>
    </div>
    <!-- /头部 -->

    <!-- 边栏 -->
    <div class="sidebar">
        <!-- 子导航 -->
        
            <div id="subnav" class="subnav">
                <?php if(!empty($_extra_menu)): ?>
                    <?php echo extra_menu($_extra_menu,$__MENU__); endif; ?>
                <?php if(is_array($__MENU__["child"])): $i = 0; $__LIST__ = $__MENU__["child"];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$sub_menu): $mod = ($i % 2 );++$i;?><!-- 子导航 -->
                    <?php if(!empty($sub_menu)): if(!empty($key)): ?><h3><i class="icon icon-unfold"></i><?php echo ($key); ?></h3><?php endif; ?>
                        <ul class="side-sub-menu">
                            <?php if(is_array($sub_menu)): $i = 0; $__LIST__ = $sub_menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$menu): $mod = ($i % 2 );++$i;?><li>
                                    <a class="item" href="<?php echo (u($menu["url"])); ?>"><?php echo ($menu["title"]); ?></a>
                                </li><?php endforeach; endif; else: echo "" ;endif; ?>
                        </ul><?php endif; ?>
                    <!-- /子导航 --><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
        
        <!-- /子导航 -->
    </div>
    <!-- /边栏 -->

    <!-- 内容区 -->
    <div id="main-content">
        <div id="top-alert" class="fixed alert alert-error" style="display: none;">
            <button class="close fixed" style="margin-top: 4px;">&times;</button>
            <div class="alert-content">这是内容</div>
        </div>
        <div id="main" class="main">
            
            <!-- nav -->
            <?php if(!empty($_show_nav)): ?><div class="breadcrumb">
                <span>您的位置:</span>
                <?php $i = '1'; ?>
                <?php if(is_array($_nav)): foreach($_nav as $k=>$v): if($i == count($_nav)): ?><span><?php echo ($v); ?></span>
                    <?php else: ?>
                    <span><a href="<?php echo ($k); ?>"><?php echo ($v); ?></a>&gt;</span><?php endif; ?>
                    <?php $i = $i+1; endforeach; endif; ?>
            </div><?php endif; ?>
            <!-- nav -->
            

            
	<div class="main-title">
		<h2>
			<?php echo ($list['id']?'编辑':'新增'); ?>卡券
		</h2>
	</div>
	<form action="<?php echo U();?>" method="post" class="form-horizontal">
		<div class="form-item">
			<label class="item-label">活动名称：</label>
			<div class="controls">
				<input type="text" class="text input-large" name="name" value="<?php echo ((isset($list["name"]) && ($list["name"] !== ""))?($list["name"]):''); ?>">
			</div>
		</div>
        <div class="form-item">
            <label class="item-label">业态：</label>
            <select name="format" style="width:200px">
                <option value="" >请选择</option>
                <?php if(is_array($format)): foreach($format as $key=>$v): ?><option value="<?php echo ($v['id']); ?>" <?php if(($list["format"]) == $v[id]): ?>selected<?php endif; ?> ><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>
            </select>
        </div>
        <div class="form-item">
            <label class="item-label">颜色：</label>
            <!-- <select name="color" style="width:200px">
                <option value="" >请选择</option>
                <?php if(is_array($color)): foreach($color as $key=>$v): ?><option style="background:rgb(1,118,81)" value="<?php echo ($v['name']); ?>" <?php if(($list["color"]) == $v[name]): ?>selected<?php endif; ?> ><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>
            </select> -->
            <style>
                .form-item .item{
                    width: 8%;
                    display: inline-block;
                    margin-left: 2%;
                }
                .form-item .item>label{
                    display: inline-block;
                    width: 50%;
                    height: 20px;
                }
            </style>
            <div class="colorList">
                <?php if(is_array($color)): $i = 0; $__LIST__ = $color;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$v): $mod = ($i % 2 );++$i;?><span class="item">
                        <input type="radio" name="color" id="<?php echo ($v['name']); ?>" value="<?php echo ($v['name']); ?>" <?php if(($list["color"]) == $v[name]): ?>checked<?php endif; ?> />
                        <label for="<?php echo ($v['name']); ?>" style="background:<?php echo ($v['name']); ?>;"></label><?php echo ($v['name']); ?>
                    </span><?php endforeach; endif; else: echo "" ;endif; ?>
            </div>
           <!--  <div class="selectContainer">
                <div class="container">#196521</div>
                <ul class="select">
                    <li style="background-color: #196521">#196521</li>
                    <li style="background-color: #230191">#196522</li>
                    <li style="background-color: #286590">#196523</li>
                    <li style="background-color: #987521">#196524</li>
                    <li style="background-color: #196521">#196525</li>
                    <li style="background-color: #230191">#196526</li>
                    <li style="background-color: #286590">#196527</li>
                    <li style="background-color: #987521">#196528</li>
                </ul>
            </div> -->
        </div>
        <div class="form-item">
            <label class="item-label">购物中心：</label>
            <select name="mall_id" id="mall" style="width:200px">
                <option value="" >请选择</option>
                <?php if(is_array($mall)): foreach($mall as $key=>$v): ?><option value="<?php echo ($v['id']); ?>" <?php if(($list["mall_id"]) == $v[id]): ?>selected<?php endif; ?> ><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>
            </select>
        </div>
        <div class="form-item">
            <label class="item-label">所属店铺：</label>
            <select name="store_id" id="store" style="width:200px">
                <option value="" >请选择</option>
                <?php if(is_array($store)): foreach($store as $key=>$v): ?><option value="<?php echo ($v['id']); ?>" <?php if(($list["store_id"]) == $v[id]): ?>selected<?php endif; ?> ><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>
            </select>
        </div>
        
        <div class="form-item">
            <label class="item-label">活动类型</label>
            <select class="select_type" name="type" id="select_type" onchange="showtype(this)" style="width:200px">
                <option value="<?php echo ((isset($list["type"]) && ($list["type"] !== ""))?($list["type"]):''); ?>">- 请选择 -</option>
                <?php if($list["type"] == '1'): ?><option value="1" selected="true">现金抵扣券</option>
                    <?php else: ?><option value="1">现金抵扣券</option><?php endif; ?>
                <?php if($list["type"] == '2'): ?><option value="2" selected="true">收费门票</option>
                    <?php else: ?><option value="2">收费门票</option><?php endif; ?>
                <?php if($list["type"] == '3'): ?><option value="3" selected="true">免费体验券</option>
                    <?php else: ?><option value="3">免费体验券</option><?php endif; ?>
            </select>
        </div>
        <div class="form-item">
            <label class="item-label">所属场景：</label>
            <!-- <select name="scene" id="scene" style="width:200px">
                <option value="" >请选择</option>
                <?php if(is_array($scene)): foreach($scene as $key=>$v): ?><option value="<?php echo ($v['name']); ?>" <?php if(($list["scene"]) == $v[name]): ?>selected<?php endif; ?> ><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>
            </select> -->
            <?php if(is_array($scene)): foreach($scene as $key=>$v): ?><input type="checkbox" name="scene[]" id="scene[]" value="<?php echo ($v[id]); ?>" <?php if(in_array($v[id],explode(',',$list[scene]))) echo("checked");?>/><?php echo ($v[name]); ?>
                <!-- <option value="<?php echo ($v['id']); ?>" <?php if(($list["mall_id"]) == $v[id]): ?>selected<?php endif; ?> ><?php echo ($v["name"]); ?></option> --><?php endforeach; endif; ?>
        </div>
        <div class="form-item">
            <label class="item-label">所属类别：</label>
            <!-- <select name="category" id="category" style="width:200px">
                <option value="" >请选择</option>
                <?php if(is_array($category)): foreach($category as $key=>$v): ?><option value="<?php echo ($v['name']); ?>" <?php if(($list["category"]) == $v[name]): ?>selected<?php endif; ?> ><?php echo ($v["name"]); ?></option><?php endforeach; endif; ?>
            </select> -->
            <?php if(is_array($category)): foreach($category as $key=>$v): ?><input type="checkbox" name="category[]" id="category[]" value="<?php echo ($v[id]); ?>" <?php if(in_array($v[id],explode(',',$list[category]))) echo("checked");?>/><?php echo ($v[name]); ?>
                <!-- <option value="<?php echo ($v['id']); ?>" <?php if(($list["mall_id"]) == $v[id]): ?>selected<?php endif; ?> ><?php echo ($v["name"]); ?></option> --><?php endforeach; endif; ?>
        </div>
        <div class="form-item">
            <label class="item-label">私人订制</label>
            <input type="checkbox" name="personal" id="personal" value="1" <?php if($list['personal']) echo("checked");?>/>订制
        </div>
        <div class="form-item">
            <label class="item-label">是否精选</label>
            <input type="checkbox" name="sift" id="sift" value="1" <?php if($list['sift']) echo("checked");?>/>精选
        </div>
        <div class="form-item" id="manjian" >
            <label class="item-label">满减金额</label>
            <div class="controls">
                <p>满&nbsp;<input type="text" class="text input-small" name="price_man" value="<?php echo ((isset($list["price_man"]) && ($list["price_man"] !== ""))?($list["price_man"]):''); ?>"></p>                
                <p>减&nbsp;<input type="text" class="text input-small" name="price_jian" value="<?php echo ((isset($list["price_jian"]) && ($list["price_jian"] !== ""))?($list["price_jian"]):''); ?>"></p>              
            </div>
        </div>
		<div class="form-item">
            <label class="item-label">描述：</label>
            <div class="controls">
                <input type="text" class="text input-large" name="desc" value="<?php echo ((isset($list["desc"]) && ($list["desc"] !== ""))?($list["desc"]):''); ?>">
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">原价：</label>
            <div class="controls">
                <input type="text" class="text input-large" name="cost_price" value="<?php echo ((isset($list["cost_price"]) && ($list["cost_price"] !== ""))?($list["cost_price"]):''); ?>">
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">售价：</label>
            <div class="controls">
                <input type="text" class="text input-large" name="sell_price" value="<?php echo ((isset($list["sell_price"]) && ($list["sell_price"] !== ""))?($list["sell_price"]):''); ?>">
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">销售截止日期：</label>
            <div class="controls">
                <input type="date" class="text input-large" name="sell_time" value="<?php echo ((isset($list["sell_time"]) && ($list["sell_time"] !== ""))?($list["sell_time"]):''); ?>">
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">亮点：</label>
            <div class="textarea input-large">
                <textarea name="edge"><?php echo ((isset($list["edge"]) && ($list["edge"] !== ""))?($list["edge"]):''); ?></textarea>
                <!-- <input type="text" class="text input-large" name="tips" value="<?php echo ((isset($list["tips"]) && ($list["tips"] !== ""))?($list["tips"]):''); ?>"> -->
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">检索关键词：</label>
            <div class="controls">
                <input type="text" class="text input-large" name="search_keys" value="<?php echo ((isset($list["search_keys"]) && ($list["search_keys"] !== ""))?($list["search_keys"]):''); ?>">
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">有效期开始时间：</label>
            <div class="controls">
                <input type="date" class="text input-large" name="valid_start_time" value="<?php echo ((isset($list["valid_start_time"]) && ($list["valid_start_time"] !== ""))?($list["valid_start_time"]):''); ?>">
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">有效期结束时间：</label>
            <div class="controls">
                <input type="date" class="text input-large" name="valid_end_time" value="<?php echo ((isset($list["valid_end_time"]) && ($list["valid_end_time"] !== ""))?($list["valid_end_time"]):''); ?>">
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">卡券发行总数量：</label>
            <p style="color:red;">发行总数量不得低于当前冻结数量与已销售数量的总和：<?php echo(intval($list['all_num']) - intval($list['now_num']) );?></p>
            <div class="controls">
                <input type="text" class="text input-large" name="all_num" value="<?php echo ((isset($list["all_num"]) && ($list["all_num"] !== ""))?($list["all_num"]):''); ?>">
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">每人领取数量限制：</label>
            <div class="controls">
                <input type="text" class="text input-large" name="limit_num" value="<?php echo ((isset($list["limit_num"]) && ($list["limit_num"] !== ""))?($list["limit_num"]):''); ?>">
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">不可用日期：</label>
            <div class="controls">
                <input type="text" class="text input-large" name="unable_date" value="<?php echo ((isset($list["unable_date"]) && ($list["unable_date"] !== ""))?($list["unable_date"]):''); ?>">
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">可用时间段：</label>
            <div class="controls">
                <input type="text" class="text input-large" name="able_time" value="<?php echo ((isset($list["able_time"]) && ($list["able_time"] !== ""))?($list["able_time"]):''); ?>">
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">使用条件：</label>
            <div class="controls">
                <input type="text" class="text input-large" name="requirement" value="<?php echo ((isset($list["requirement"]) && ($list["requirement"] !== ""))?($list["requirement"]):''); ?>">
            </div>
        </div>
        <div class="form-item">
            <label class="item-label">温馨提示：</label>
            <div class="textarea input-large">
                <textarea name="tips"><?php echo ((isset($list["tips"]) && ($list["tips"] !== ""))?($list["tips"]):''); ?></textarea>
                <!-- <input type="text" class="text input-large" name="tips" value="<?php echo ((isset($list["tips"]) && ($list["tips"] !== ""))?($list["tips"]):''); ?>"> -->
            </div>
        </div>
        <div class="form-item">
			<input type="hidden" name="id" value="<?php echo ((isset($list["id"]) && ($list["id"] !== ""))?($list["id"]):''); ?>">
			<button class="btn" id="submit" type="submit" target-form="form-horizontal">确 定</button>
			<button class="btn" onclick="javascript:history.back(-1);return false;">返 回</button>
		</div>
	</form>

        </div>
        <div class="cont-ft">
            <div class="copyright">
                <div class="fl">感谢使用<a href="http://www.onethink.cn" target="_blank">OneThink</a>管理平台</div>
                <div class="fr">V<?php echo (ONETHINK_VERSION); ?></div>
            </div>
        </div>
    </div>
    <!-- /内容区 -->
    <script type="text/javascript">
    (function(){
        var ThinkPHP = window.Think = {
            "ROOT"   : "/onethink", //当前网站地址
            "APP"    : "/onethink/index.php?s=", //当前项目地址
            "PUBLIC" : "/onethink/Public", //项目公共目录地址
            "DEEP"   : "<?php echo C('URL_PATHINFO_DEPR');?>", //PATHINFO分割符
            "MODEL"  : ["<?php echo C('URL_MODEL');?>", "<?php echo C('URL_CASE_INSENSITIVE');?>", "<?php echo C('URL_HTML_SUFFIX');?>"],
            "VAR"    : ["<?php echo C('VAR_MODULE');?>", "<?php echo C('VAR_CONTROLLER');?>", "<?php echo C('VAR_ACTION');?>"]
        }
    })();
    </script>
    <script type="text/javascript" src="/onethink/Public/static/think.js"></script>
    <script type="text/javascript" src="/onethink/Public/Admin/js/common.js"></script>
    <script type="text/javascript">
        +function(){
            var $window = $(window), $subnav = $("#subnav"), url;
            $window.resize(function(){
                $("#main").css("min-height", $window.height() - 130);
            }).resize();

            /* 左边菜单高亮 */
            url = window.location.pathname + window.location.search;
            url = url.replace(/(\/(p)\/\d+)|(&p=\d+)|(\/(id)\/\d+)|(&id=\d+)|(\/(group)\/\d+)|(&group=\d+)/, "");
            $subnav.find("a[href='" + url + "']").parent().addClass("current");

            /* 左边菜单显示收起 */
            $("#subnav").on("click", "h3", function(){
                var $this = $(this);
                $this.find(".icon").toggleClass("icon-fold");
                $this.next().slideToggle("fast").siblings(".side-sub-menu:visible").
                      prev("h3").find("i").addClass("icon-fold").end().end().hide();
            });

            $("#subnav h3 a").click(function(e){e.stopPropagation()});

            /* 头部管理员菜单 */
            $(".user-bar").mouseenter(function(){
                var userMenu = $(this).children(".user-menu ");
                userMenu.removeClass("hidden");
                clearTimeout(userMenu.data("timeout"));
            }).mouseleave(function(){
                var userMenu = $(this).children(".user-menu");
                userMenu.data("timeout") && clearTimeout(userMenu.data("timeout"));
                userMenu.data("timeout", setTimeout(function(){userMenu.addClass("hidden")}, 100));
            });

	        /* 表单获取焦点变色 */
	        $("form").on("focus", "input", function(){
		        $(this).addClass('focus');
	        }).on("blur","input",function(){
				        $(this).removeClass('focus');
			        });
		    $("form").on("focus", "textarea", function(){
			    $(this).closest('label').addClass('focus');
		    }).on("blur","textarea",function(){
			    $(this).closest('label').removeClass('focus');
		    });

            // 导航栏超出窗口高度后的模拟滚动条
            var sHeight = $(".sidebar").height();
            var subHeight  = $(".subnav").height();
            var diff = subHeight - sHeight; //250
            var sub = $(".subnav");
            if(diff > 0){
                $(window).mousewheel(function(event, delta){
                    if(delta>0){
                        if(parseInt(sub.css('marginTop'))>-10){
                            sub.css('marginTop','0px');
                        }else{
                            sub.css('marginTop','+='+10);
                        }
                    }else{
                        if(parseInt(sub.css('marginTop'))<'-'+(diff-10)){
                            sub.css('marginTop','-'+(diff-10));
                        }else{
                            sub.css('marginTop','-='+10);
                        }
                    }
                });
            }
        }();
    </script>
    
<script type="text/javascript" charset="utf-8">
	//导航高亮
	highlight_subnav('<?php echo U('index');?>');

    function showtype(obj) {
        $('input[name="price_man"]').attr('disabled',true);
        $('input[name="price_jian"]').attr('disabled',true);
        var s_type = $(obj).val(); 
        switch(s_type)
        {
        case '0':
            break;
        case '1': 
            $('#manjian').css('display','block');
            $('input[name="price_man"]').attr('disabled',false);
            $('input[name="price_jian"]').attr('disabled',false);
            break;
        case '2':
            $('#manjian').css('display','none');
            break;
        case '3': 
            $('#manjian').css('display','none');
            break;
        }
    }

    
</script>
<script type="text/javascript">
    $(function(){
        /*二级联动*/
        $('#mall').change(function() {
            var objectModel = {};
            var site_id = $(this).val();
            var type = $(this).attr('id');
            objectModel[type] =site_id;
            console.log(objectModel); 
            $.ajax({
                cache:false,
                type:"POST",
                url:"<?php echo U('Pass/selectStore');?>",
                dataType:"json",
                data:objectModel,
                // timeout:30000,
                error:function() {
                    alert('ajax请求失败');
                },
                success:function(data){
                    console.log(data);
                    $("#store").empty();
                    var count = data.length;
                    var i = 0;
                    var b="<option value=''>选择店铺</option>";
                    for(i=0;i<count;i++) {
                        b+="<option value='"+data[i].id+"'>"+data[i].name+"</option>";
                    }
                    $("#store").append(b);
                }
            });
            $.ajax({
                cache:false,
                type:"POST",
                url:"<?php echo U('Pass/selectPackage');?>",
                dataType:"json",
                data:objectModel,
                // timeout:30000,
                error:function() {
                    alert('ajax请求失败');
                },
                success:function(data){
                    console.log(data);
                    
                }
            });
        });
    })
</script>

</body>
</html>