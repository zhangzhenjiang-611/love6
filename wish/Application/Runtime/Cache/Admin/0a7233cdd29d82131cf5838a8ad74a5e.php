<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Title</title>
    <link rel="stylesheet" href="/Public/admin/Css/public.css" />
    <script type="text/javascript" src="/Public/Js/jquery-1.7.2.min.js"></script>
</head>
<body>
<form action="<?php echo U('addRoleHandle');?>" method="post">
      <table class="table">
          <tr >
              <th colspan="2">添加角色</th>
          </tr>
          <tr>
              <td align="center">角色名称：</td>
              <td>
                  <input type="text" name="name" />
              </td>
          </tr>
          <tr>
              <td align="center">角色描述：</td>
              <td>
                  <input type="text" name="remark" />
              </td>
          </tr>
          <tr>
              <td align="center">是否开启：</td>
              <td>
                  <input type="radio" name="status" value="1" checked="checked" /> &nbsp;开启&nbsp;
                  <input type="radio" name="status" value="0" /> &nbsp;关闭&nbsp;
              </td>
          </tr>
          <tr>
              <td colspan="2" align="center">
                  <input type="submit" value="保存" />
              </td>
          </tr>
      </table>
</form>
</body>
</html>