<?php
/**
 * 基本参数设置
 *
 * @version       v0.01
 * @create time   2011-5-16
 * @update time   
 * @author        jiangting
 * @copyright     Copyright (c) JYZX PLS Tech Inc. (http://www.PLS.com)
 * @informaition  
 */

	error_reporting(0);    //网站开发必须关闭此处，网站上线必须打开此处
	header("content-type:text/html;charset=utf-8");
	date_default_timezone_set('Asia/Chongqing');
	session_start();
	ob_start();
	

	/**tab标识**/
	$CIRCLE_TAB="";
	$STYLE_TAB="";
	$FOOD_TAB="";
	$orderState=array('新订单','确认订单','商家取消','用户取消','订单已完成','正在备货','修改订单');
	$FOODSPECIALCOUNT=5; //置顶的数量
	$SCORETOTAL=array('','','很不满意','','不太满意','','一般','','比较满意','','非常满意');
	$SCORETEST=array('','','差，或者量很少 ','','不太好，或者量不太够 ','','一般，量也一般','','不错，分量够了','','非常好，分量也足够 ');
	$SCORESPEED=array('','','很慢','','不太快','','速度一般 ','','挺快的','','非常快');
	$columnCircle=array('家庭欢乐送','定制外送','热卖单品','特价活动专区','今日特卖单品');

	$SHOPID=1;

	$SHOPNAME_DDMIN='<JY>网店系统';

	$version='v3.0';
	$updateTime='2013-04-15';
	$subversion='2013001';

	$powered='Powered By <JY>网店系统'.$version;

	

?>