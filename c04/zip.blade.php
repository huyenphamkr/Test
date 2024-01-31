<html>
<head>
<style>
body {
	padding: 5px 500px;
	font-size: 13px;
    background-color: deepskyblue;
	font-family:serif;
}
h2 {
	margin-bottom: 20px;
	font-family: fantasy;
    font-size: 40px;
}
h3{
	color:green;
}
.container,.container2{
	padding: 10px;
	border: 3px solid black;
	width: 250px;
	color: black;
	box-shadow: rgba(0, 0, 0, 0.1) 0px 0px 8px;
}
input{
	padding: 15px;
}
input[type=submit]{
	width: 100px; 
	height: 50px;
	background-color: #474E69; 
	color: #FFF;
	border-radius: 5px;
}
input[type=checkbox] {
font-size:20px;	
}
input[type=file]{
    display: block;
    width: auto;
    font-size: 11px;
    margin-top: 30px;
    border: 3px solid black;
    display: block;
    margin-bottom: 5px;
}
</style>
</head>
<body>
<h2>Upload Files To Create Zip</h2>
<div class="container">
<form method="POST" action="" enctype="multipart/form-data">
<input type="file" name="userfile[]" multiple />
<input type="submit" name="sub" value="UPLOAD" >
</form>
</div>
</body>
</html>
<?php
function reArrayFiles($file_post)
{
	$file_ary=array();
	$file_count=count($file_post['name']);
	$file_keys=array_keys($file_post);
	for($i=0;$i<$file_count;$i++)
	{
		foreach($file_keys as $keys)
		{
			$file_ary[$i][$keys]=$file_post[$keys][$i];
		}
	}
	return $file_ary;
}
if(isset($_POST['sub']))
{
$file_array=reArrayFiles($_FILES['userfile']);
echo"<h2>Select file to zip</h2>
	<div class='container2'>";
echo'<form action="" method="POST">';
for($i=0;$i<count($file_array);$i++)
{
$extensions=array('jpg','img','png','jpeg','docx','xsl','txt','pdf','exe','doc','csv');
$file_ext=explode('.',$file_array[$i]['name']);
$file_ext=end($file_ext);
if(in_array($file_ext,$extensions))
{
	move_uploaded_file($file_array[$i]['tmp_name'],"".$file_array[$i]['name']);
	echo'<input type="checkbox" name="zip_list[]" value="'.$file_array[$i]['name'].'"><label>'.$file_array[$i]['name'].'</label><br>';
}
else
	echo "can't upload the file";
}
echo'<input type="submit" name="zip" value="CREATE" >
</form></div>';
} 
?>
<?php
if(isset($_POST['zip']))
{
	$zip = new ZipArchive;
	if ($zip->open('newzip.zip', ZipArchive::CREATE) === TRUE)
	{
		if(!empty($_POST['zip_list']))
		{
			foreach($_POST['zip_list'] as $selected)
			{
				$zip->addFile($selected);
			}
			echo"<h3>Selected files are successfully zipped</h3>";

			//folder
			// foreach($_POST['zip_list'] as $key => $selected)
			// {
			// 	$zip->addFile($selected, $key.'/'.$selected);
			// }
		}
		else
		{
			echo"Select atleast one";
		}
	$zip->close();
    // file will download ad this name
    $demo_name = "newzip.zip";
    $zip_file_name_with_location = "newzip.zip";
    header('Content-type: application/zip');  
    header('Content-Disposition: attachment; filename="'.$demo_name.'"');  
    readfile($zip_file_name_with_location); // auto download
    //if you wnat to delete this zip file after download
    unlink($zip_file_name_with_location);
	}
}
?>

https://vinasupport.com/zip-va-unzip-file-va-folder-trong-php/
php artisan cache:clear 


php artisan optimize:clear

Route::get('/clear', function() {
	//Clear route cache
	\Artisan::call('route:cache');

	//Clear config cache
	\Artisan::call('config:cache');

	// Clear application cache
	\Artisan::call('cache:clear');

	// Clear view cache
	\Artisan::call('view:clear');

	// Clear cache using reoptimized class
	\Artisan::call('optimize:clear');
	
	return 'cache cleared';
});

CACHE_DRIVER=null