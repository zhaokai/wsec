<?php
	/**
	 *  shopadd.php  
	 *
	 * @version       v0.01
	 * @create time   2011-8-22
	 * @update time
	 * @author        lujiangxia
	 * @copyright     Copyright (c) JYZX PLS Tech Inc. (http://www.PLS.com)
	 * @informaition
	 */
	require_once("usercheck2.php");
	$act=$_GET['act'];
	
	switch($act)
	{
		case "index":
			$title=sqlReplace($_POST['title']);
			$keywords=HTMLEncode($_POST['keywords']);
			$description=HTMLEncode($_POST['description']);
				
			$sql="update ".WIIDBPRE."_seo set seo_title='".$title."',  seo_keywords='".$keywords."',seo_description='".$description."' where seo_type=1";	
			if(!mysql_query($sql)){
				alertInfo('未知原因保存失败! ',"",1);
			}else{
				alertInfo('保存成功!',"seo.php",0);
			}
			
		break;
		

	}
?>
