<?
session_start();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>示例1</title>
<?
$action=$_GET["action"];
if ($action=='add')
{
		$Code=trim($_POST["TextBox2"]);
		if(strlen($Code)>1 and $Code==$_SESSION["code"])
			print( "<script>alert('通证成功')</script>");
		else
			print( "<script>alert('未通证成功')</script>");
		unset($_SESSION['code']);
}
?>
<script src="js/jquery.js" language="javascript" type="text/javascript"></script>
<script type="text/javascript">
function get(obj) {
    var partten = /^\d{10,13}$/;
    if (!partten.test(document.getElementById("TextBox1").value)) {
        alert('请输入正确的手机号码');
        return;
    }
  obj.disabled = true;
  $.ajax({
      url: "sms.php",
      type: "Post",
      data: "Tel=" + $("#TextBox1").val(),

	 success: function(msg) {
          obj.disabled = false;
          if (msg == "ok") {
              alert("生成成功!请等侍短信提示。")
              return;
          }
          if (msg == "error") {
              alert("生成失败!请联系管理员")
              return;
          }
          alert(msg);
      }
  })
  
}
</script>
</head>

<body>
<label>
<form id="form1" name="form1" method="post" action="s.php?action=add">
您手机号码：
<input name="TextBox1" type="text" id="TextBox1" />       
<input id="Button1" type="button" value="获取短信验证码" onClick="get(this)" />
<br />
<br />
验证码：
<input name="TextBox2" type="text" id="TextBox2" /> 
</label>
<br />
<br />
<label>
<input type="submit" name="Submit" value=" 确 定 " />
</label>
</form>
<p>&nbsp;</p>
</body>
</html>
