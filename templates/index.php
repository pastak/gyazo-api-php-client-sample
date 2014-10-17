<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8"/>
	<title>Sample</title>
</head>
<body>
<div id='container'>
<h1>Sample</h1>

<?php if(isset($_SESSION['access_token']) && $_SESSION['access_token']){?>
<h2>Choose your image</h2>
<form action='/post' method='post' enctype='multipart/form-data'>
    <input type='file' name='photo'/><br />
    <button>Upload</button>
</form>
<?php }else{ ?>
<h2>Login Gyazo</h2>

<a href='<?php echo $auth_url?>' style='width: 150px; height: 50px; color: white; background: rgb(18, 30, 66); text-decoration: none; display: block; text-align: center;'>
    <span style='position: relative; top: 25%; transform: translateY(-50%);'>Login Gyazo.com</a>
</a>


<?php }?>

</div>
</body>
</html>

