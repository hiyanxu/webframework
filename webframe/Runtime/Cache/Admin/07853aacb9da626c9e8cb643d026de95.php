<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="renderer" content="webkit">
        <title>Laravel</title>
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
                    <input type="hidden" name="role_id" value="<?php echo ($role_id); ?>" >
                    <div class="form-group">
                        <label class="col-xs-3 control-label">角色名称：</label>
                        <div class="col-xs-8">
                            <input type="text" name="role_name" value="<?php echo ($row[0]['role_name']); ?>" class="form-control input-sm"> 
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">角色描述：</label>
                        <div class="col-xs-8">
                            <textarea cols="30" rows="5" name="role_desc">
                            <?php echo ($row[0]["role_desc"]); ?>
                            </textarea>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>