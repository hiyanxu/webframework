<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="renderer" content="webkit">
        <title>密码修改</title>
        <link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>/Public/Admin/BootStrap/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/css/bootstrap-table.min.css">
		<!--js文件引入-->
		<script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/js/jquery-1.11.1.min.js"></script>		
		<script type="text/javascript" src="<?php echo (WWW_PUB); ?>Public/Admin/BootStrap/js/bootstrap.min.js"></script>
	</head>
	<body>
		<div>
			<div class="modal-body">
				<form id="wt-forms" method="post" tabindex="-1" onsubmit="return false;" class="form-horizontal">
                    <input type="hidden" value="<?php echo ($user_account_id); ?>" name="user_account_id">
                    <div class="form-group">
                        <label class="col-xs-3 control-label">登录账号：</label>
                        <div class="col-xs-8">
                            <input type="text" id="user_account" readonly="readonly" name="user_account" value="<?php echo ($rows[0]['user_account']); ?>" class="form-control input-sm"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">新密码：</label>
                        <div class="col-xs-8">
                            <input type="password" id="user_pwd" name="user_pwd" class="form-control input-sm"> 
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>