<?php
	/**
	 *  userquit.php  
	 *
	 * @version       v0.01
	 * @create time   2011-8-6
	 * @update time
	 * @author        lujiangxia
	 * @copyright     Copyright (c) JYZX PLS Tech Inc. (http://www.PLS.com)
	 * @informaition
	 */
	require_once("include/dbconn.php");
	if($_COOKIE['QIYUCHECK']=='yes'){
		
	}else{
		setcookie("QIYUUSER","",time()-1);
	}
	setcookie("QIYUVERD","",time()-1);
	session_unset();
	session_destroy();
	Header("Location:index.php");
?>