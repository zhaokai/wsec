<?php
	/**
	 *  userorder.ajax.php  修改默认地址 修改电话  添加新地址
	 *
	 * @version       v0.01
	 * @create time   2011-8-6
	 * @update time
	 * @author        lujiangxia
	 * @copyright     Copyright (c) JYZX PLS Tech Inc. (http://www.PLS.com)
	 * @informaition
	 */
	require_once("usercheck.php");
	$act=sqlReplace(trim($_GET['act']));
	date_default_timezone_set('PRC');
	switch ($act){
		case "checkOpen":
				$day_str=date("Y-m-d");
				
				$time_now=strtotime(date("H:i:s"));
				$night=strtotime('16:00:00');
				$morning=strtotime('09:00:00');
				if ($time_now>=$night || $time_now<$morning)
					echo "N";
				else
					echo "S";
		break;
	
	}

	
?>