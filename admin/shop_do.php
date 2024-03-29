<?php 
	/**
	 *  shop_do.php  
	 *
	 * @version       v0.01
	 * @create time   2011-8-22
	 * @update time
	 * @author        lujiangxia
	 * @copyright     Copyright (c) JYZX PLS Tech Inc. (http://www.PLS.com)
	 * @informaition
	 */

	require_once("usercheck2.php");
	$act=sqlReplace(trim($_GET['act']));
	switch ($act){
		case "base":
			$name=sqlReplace(trim($_POST['name']));
			
			$address=sqlReplace(trim($_POST['address']));
			if($address=='请输入你的店铺地址如**路**号'){$address='';}
		
			$tel=sqlReplace(trim($_POST['tel']));
			$opentime=sqlReplace(trim($_POST['opentime']));
			$endtime=sqlReplace(trim($_POST['endtime']));
			
			$mainfood=sqlReplace(trim($_POST['mainfood']));
			$discount=empty($_POST['discount'])?'0.00':sqlReplace(trim($_POST['discount']));//折扣
			
			$buycount=empty($_POST['buycount'])?'0':sqlReplace(trim($_POST['buycount']));//购买总数
			$intro=HTMLEncode(trim($_POST['intro']));
			if($intro=='200字以内'){$intro='';}
			
			checkData($name,'店铺名称',1);
			checkData($address,'店铺地址',1);
			checkData($tel,'店铺电话',1);
			checkData($opentime,'店铺营业开始时间',1);
			checkData($endtime,'店铺营业结束时间',1);
			checkData($mainfood,'店铺主营食物',1);
			checkData($intro,'店铺介绍',1);
			$sql="update qiyu_shop set shop_discount='".$discount."',shop_buycount='".$buycount."',shop_name='".$name."',shop_address='".$address."',shop_tel='".$tel."',shop_openstarttime='".$opentime."',shop_openendtime='".$endtime."',shop_intro='".$intro."',shop_status='1',shop_addtime=now(),shop_mainfood='".$mainfood."',shop_type='1' where shop_id=".$QIYU_ID_SHOP;
			if (mysql_query($sql)){
				alertInfo("编辑成功","",1);
				Header("Location: shopadd.php");
			}else
				alertInfo("编辑失败","",1);
		break;
		case "addtime":
			$name=sqlReplace(trim($_POST['name']));
			$t1=sqlReplace(trim($_POST['t1']));
			$s1=sqlReplace(trim($_POST['s1']));
			$t2=sqlReplace(trim($_POST['t2']));
			$s2=sqlReplace(trim($_POST['s2']));
			checkData($name,'时间段名称',1);
			$value1=$t1.":".$s1;
			$value2=$t2.":".$s2;
			
			$sqlStr="insert into qiyu_delivertime(delivertime_name,delivertime_shop,delivertime_starttime,delivertime_endtime) values ('".$name."',".$QIYU_ID_SHOP.",'".$value1."','".$value2."')";
			if (mysql_query($sqlStr)){
				alertInfo("添加成功","",1);
			}else{
				alertInfo("添加失败","",1);
			}
			
		break;
		case "fee":
			$fee=sqlReplace(trim($_POST['fee']));
			$deliverFee=sqlReplace(trim($_POST['deliverFee']));
			$dTime=sqlReplace(trim($_POST['dTime']));
			$isfee=sqlReplace(trim($_POST['isfee']));
			if(empty($fee)){
				if($fee=='0'){
					
				}else{
					alertInfo('起送费不能为空','',1);
				}
			}else{
				if(!is_numeric($fee)){
					alertInfo('起送费必须是数字','',1);
				}
			}
			if(empty($deliverFee)){
				if($deliverFee=='0'){
					
				}else{
					alertInfo('送货费不能为空','',1);
				}
			}else{
				if(!is_numeric($deliverFee)){
					alertInfo('送货费必须是数字','',1);
				}
			}
			
			$sql_select="select deliver_id from qiyu_deliver";
			$rs_select=mysql_query($sql_select);
			$rows_select=mysql_fetch_assoc($rs_select);
			if ($rows_select){
				$sql_str="update qiyu_deliver set deliver_fee=".$deliverFee.",deliver_minfee=".$fee.",deliver_delivertime='".$dTime."',deliver_isfee=".$isfee;
				mysql_query($sql_str);
				alertInfo('费用设置成功','',1);
			}else{
				$sql_str="insert into qiyu_deliver(deliver_fee,deliver_minfee,deliver_delivertime,deliver_shop,deliver_isfee) values(".$deliverFee.",".$fee.",'".$dTime."',".$QIYU_ID_SHOP.",".$isfee.")";
				if (mysql_query($sql_str)){
					alertInfo('费用设置成功','',1);
				}else{
					alertInfo('费用设置失败','',1);
				}	
			}
			
		break;
		case 'savefee':
			$i=trim($_POST['i']);
			for($x=1;$x<=$i;$x++){
				$id=$_POST['id'.$x];
				$deliverfee=$_POST['deliverbycircle_fee'.$x];
				$sendfee=$_POST['deliverbycircle_minfee'.$x];
				$delivertime=$_POST['deliverbycircle_delivertime'.$x];
				$isfee=$_POST['isfee'.$x];
				if($deliverfee=='无'){
					$deliverfee='0';
				}
				if($sendfee=='免费'){
					$sendfee='0';
				}
				
				$sql="update ".WIIDBPRE."_deliverbycircle set deliverbycircle_isfee='".$isfee."',  deliverbycircle_fee=".$deliverfee.",deliverbycircle_minfee=".$sendfee.",deliverbycircle_delivertime='".$delivertime."' where deliverbycircle_id=".$id;	
				if(!mysql_query($sql)){
					alertInfo('未知原因保存失败! ',"",1);
				}
			}
			alertInfo('保存成功!',"",1);
		break;
		case "delfee":
			$id=sqlReplace(trim($_GET['id']));
			$circleid=sqlREplace(trim($_GET['circleid']));
			$sql="delete from qiyu_shopcircle where shopcircle_circle=".$circleid." and shopcircle_shop=".$QIYU_ID_SHOP;//删除商家商圈
			mysql_query($sql);
			$sql="delete from qiyu_deliverbycircle where deliverbycircle_id=".$id;//删除商圈下费用
			mysql_query($sql);
			alertInfo('删除成功','',1);
			
		break;
		case "addstyle":
			$style=sqlReplace(trim($_POST['style']));
			$sqlStr="select * from qiyu_shopstyle where shopstyle_style=".$style." and shopstyle_shop=".$QIYU_ID_SHOP;
			$rs=mysql_query($sqlStr);
			$row=mysql_fetch_assoc($rs);
			if ($row){
				alertInfo('已经添加','',1);
			}else{
				$sql="insert into qiyu_shopstyle(shopstyle_style,shopstyle_shop) values (".$style.",".$QIYU_ID_SHOP.")";
				if(mysql_query($sql)){
					alertInfo('店铺菜系添加成功','',1);
				}else{
					
					alertInfo('店铺菜系添加失败','',1);
				}
			}
		break;
		case 'delstyle':
			$styleid=sqlReplace(trim($_GET['styleid']));
			$sql="delete from qiyu_shopstyle where shopstyle_id=".$styleid;
			if(mysql_query($sql)){
				alertInfo('删除成功','',1);
			}else{
				alertInfo('删除失败','',1);
			}
		break;
		case 'addfoodtype':
			//得到提交的数据，并进行过滤
			$foodtype=sqlReplace(trim($_POST['foodtype']));

			//检验数据的合法性
			checkData($foodtype,'名称',1);
			
			$sql="select count(*) from ".WIIDBPRE."_foodtype where foodtype_name='".$foodtype."' and foodtype_shop=".$QIYU_ID_SHOP;
			$rs=mysql_query($sql);
			$rows=mysql_fetch_assoc($rs);
			if($rows['count(*)']>0){
				alertInfo('菜单大类重复！请另取名字','',1);
			}else{
				$sql = "insert into ".WIIDBPRE."_foodtype(foodtype_name,foodtype_shop) values ('".$foodtype."','".$QIYU_ID_SHOP."')";
				$result=mysql_query($sql);
				if($result){
					alertInfo('菜单大类添加成功',"",1);
				}else{
					alertInfo('未知原因错误，请重试',"",1);
				}
			}
		break;
		case 'delfoodtype':
			$id=sqlReplace(trim($_GET['id']));
			checkData($id,"FYID",0);
			$sql="select * from qiyu_foodtype where foodtype_id=".$id;
			$result=mysql_query($sql);
			$row=mysql_fetch_assoc($result);
			if(!$row)
				alertInfo('您要删除的数据不存在','',1);
			else{
				$sql="select * from qiyu_food where food_foodtype=".$id." and food_shop=".$QIYU_ID_SHOP;
				$rs=mysql_query($sql);
				$row=mysql_fetch_assoc($rs);
				if($row){
					alertInfo("该类下还有菜单，请先删除该菜单明细！",'',1);
				}else{
					$sql="delete from qiyu_foodtype where foodtype_id=".$id;
					if(mysql_query($sql)){
						alertInfo("删除成功",'',1);
					}else{
						alertInfo("删除失败，请重试","",1);
					}
				}
			}
		break;
		case 'savefoodtype':
			$i=trim($_POST['i']);
			for($x=1;$x<=$i;$x++){
				$id=$_POST['id'.$x];
				$foodtypename=$_POST['foodtypename'.$x];
				$order=$_POST['order'.$x];
				checkData($foodtypename,'',1);
				$sql="select * from ".WIIDBPRE."_foodtype where foodtype_id=".$id;
				$result=mysql_query($sql);
				$row=mysql_fetch_assoc($result);
				if(!$row)
					alertInfo('此菜单大类不存在',"",1);
				else{
					$sql2="select count(*) from ".WIIDBPRE."_foodtype where foodtype_name='".$foodtypename."' and foodtype_shop=".$QIYU_ID_SHOP." and foodtype_id<>".$id;
					$res=mysql_query($sql2);
					$count=mysql_fetch_assoc($res);
					if($count['count(*)']>0)
						alertInfo('菜单大类重名，请换一个名称','foodtype.php',0);
					else{
						$sql3="update ".WIIDBPRE."_foodtype set foodtype_name='".$foodtypename."',foodtype_order=".$order." where foodtype_id=".$id;
						if(mysql_query($sql3)){
							//alertInfo('菜单大类修改成功',"",1);
						}else{
							alertInfo('未知错误修改失败，请重试','',1);
						}
					}
				}
			}
			alertInfo('菜单大类修改成功!',"foodtype.php",0);
		break;
		case 'savefood':
			$delid=$_POST['delid'];
		    if(!empty($delid)){
				foreach($delid as $id){				
					$order=$_POST['order'.$id];
					checkData($order,'',1);
					
						$sql2="select count(*) from ".WIIDBPRE."_food where food_id=".$id;
						$res=mysql_query($sql2);
						$count=mysql_fetch_assoc($res);
						if($count['count(*)']>0){
							$sql3="update ".WIIDBPRE."_food set food_order=".$order." where food_id=".$id;
							
							if(mysql_query($sql3)){
								//alertInfo('菜单大类修改成功',"",1);
							}else{
								alertInfo('未知错误修改失败，请重试','',1);
							}
						}else{
							alertInfo('未知错误修改失败，请重试','',1);
						}
					
				}
			}else{
			   alertInfo('请选择!',"food.php",0);
			}
			alertInfo('菜单排序成功!',"food.php",0);
		break;
		case 'addfood':
			//得到提交的数据，并进行过滤
			$name=sqlReplace(trim($_POST['name']));
			$price=sqlReplace(trim($_POST['price']));
			$food_status=sqlReplace(trim($_POST['food_status']));
			$type=sqlReplace(trim($_POST['type']));
			$intro=sqlReplace(trim($_POST['intro']));
			$pic= empty($_POST['upfile1'])?'':sqlReplace(trim($_POST['upfile1']));

			//检验数据的合法性
			checkData($name,'名称',1);
			if($price==''){
				alertInfo('菜的单价不能为空！','',1);
			}
			if(!is_numeric($price)){
				alertInfo('菜的单价必须是数字！','',1);
			}
			if (empty($type)){
				alertInfo('菜的分类不能为空！','foodtype.php',0);
			}
			
			$sql="select count(*) from ".WIIDBPRE."_food where food_name='".$name."' and food_shop=".$QIYU_ID_SHOP;
			$rs=mysql_query($sql);
			$rows=mysql_fetch_assoc($rs);
			if($rows['count(*)']>0){
				alertInfo('货品名称重复！请另取名字','',1);
			}else{				
				$sql2 = "insert into qiyu_food(food_name,food_shop,food_price,food_foodtype,food_intro,food_status,food_order,food_pic) values ('".$name."','".$QIYU_ID_SHOP."','".$price."','".$type."','".$intro."','".$food_status."',999,'".$pic."')";
				$result=mysql_query($sql2);
				if($result){
					$id = mysql_insert_id();//取得刚刚插入数据库的这条记录的自增id  请菜的标签信息插入到菜与标签的关系表里
					if(!empty($_POST['lable'])){
						foreach($_POST['lable'] as $lable){
							$sql3 = "insert into ".WIIDBPRE."_foodbylable(foodbylable_food,foodbylable_foodlable) values ('".$id."','".$lable."')";
							mysql_query($sql3);
						}
					}
					  alertInfo('添加成功',"food.php",0);
				}else{
					alertInfo('未知原因错误，请重试',"",1);
				}
			}
		break;
		case 'editfood':
			$id=sqlReplace(trim($_GET['id']));
			$page=$_GET['page'];
			$name=sqlReplace(trim($_POST['name']));
			$price=sqlReplace(trim($_POST['price']));
			$intro=sqlReplace(trim($_POST['intro']));
			$food_status=sqlReplace(trim($_POST['food_status']));
			$type=sqlReplace(trim($_POST['type']));
			$pic= empty($_POST['upfile1'])?'':sqlReplace(trim($_POST['upfile1']));
			$lable = $_POST['lable'];//修改提交后的标签数组
			$rb=array();
			if (empty($lable)) $lable=array();
			//检验数据的合法性
			checkData($name,'名称',1);
			if($price==''){
				alertInfo('菜的单价不能为空','',1);
			}
			if(!is_numeric($price)){
				alertInfo('菜的单价必须是数字','',1);
			}
			$id=checkData($id,"ID",0);

			$sql="select * from ".WIIDBPRE."_food where food_id=".$id;
			$result=mysql_query($sql);
			$row=mysql_fetch_assoc($result);
			if(!$row){
				alertInfo('此货品名不存在',"",1);
			}else{
					$sql="select * from ".WIIDBPRE."_food where food_name='".$name."' and food_shop=".$QIYU_ID_SHOP." and food_id not in(".$id.") and food_special is NULL";
					$rs=mysql_query($sql);
					$rows=mysql_fetch_assoc($rs);
					if($rows){
						alertInfo('货品名称重复!','',1);
					}else{
						$sql3="update ".WIIDBPRE."_food set food_name='".$name."',food_price='".$price."',food_status='".$food_status."',food_foodtype='".$type."',food_intro='".$intro."',food_pic='".$pic."' where food_id=".$id." and food_shop=".$QIYU_ID_SHOP;
						if(mysql_query($sql3)){
							$sql4 = "select foodbylable_foodlable from ".WIIDBPRE."_foodbylable where foodbylable_food=".$id;
							$lab=mysql_query($sql4);
							$j=0;
							while($rowlab=mysql_fetch_assoc($lab)){
								$j++;
								$rb[$j] = $rowlab['foodbylable_foodlable'];
							}//得到没有修改前菜的标签数组
							$insert = array_diff($lable,$rb);//将修改前后菜的标签数组进行比较取差集 进行插入、删除操作
							$delete = array_diff($rb,$lable);
							if($insert){
								foreach($insert as $in){
									$in_sql = "insert into qiyu_foodbylable(foodbylable_food,foodbylable_foodlable) values ('".$id."','".$in."')";
									mysql_query($in_sql);
								}
							}
							if($delete){
								foreach($delete as $del){
									$del_sql="delete from ".WIIDBPRE."_foodbylable where foodbylable_food=".$id." and foodbylable_foodlable=".$del;
									mysql_query($del_sql);
								}
							}
							alertInfo('菜单明细修改成功',"food.php?page=".$page,0);
						}else{
							alertInfo('未知错误修改失败，请重试','',1);
						}
					}
			}
		break;
		case 'delfood':
			$id=sqlReplace(trim($_GET['id']));
			$id=checkData($id,"ID",0);
			$sql="select * from ".WIIDBPRE."_food where food_id=".$id;
			$result=mysql_query($sql);
			$row=mysql_fetch_assoc($result);
			if(!$row){
				alertInfo('您要删除的货品名不存在','',1);
			}else{
				$sql2="delete from ".WIIDBPRE."_food where food_id=".$id;
				if(mysql_query($sql2)){
					$sql3 = "delete from ".WIIDBPRE."_foodbylable where foodbylable_food=".$id;
					if(mysql_query($sql3)){
						alertInfo('删除成功',"",1);
					}
				}else{
					alertInfo('删除失败，原因SQL出现异常','',1);
				}
					
			}
		break;

		case 'xxdel'://批量删除	
			$delid= $_POST['delid'];
			//echo '<pre>';print_R($delid);die;
			if(!$delid){
				alertInfo('请选择','food.php',0);
			}
			foreach ($delid as $k=>$v){
				$sql="select * from qiyu_food where food_id=".$v;
				$result=mysql_query($sql);
				$row=mysql_fetch_assoc($result);
				if(!$row){
					alertInfo('您要删除的菜品不存在','food.php',0);
				}else{
					$sql2="delete from qiyu_food where food_id=".$v;
					if(!mysql_query($sql2)){
					     alertInfo('删除失败！原因：SQL删除失败。',"",1);
					}
							
				}
			}
			alertInfo('删除成功','food.php',0);
			break;
		
		case "shopTag":
			$tag=sqlReplace(trim($_POST['tag']));
			$spot=empty($_POST['spot'])?0:sqlReplace(trim($_POST['spot']));
			
			$sql="insert into qiyu_shoptag(shoptag_spot,shoptag_shop,shoptag_tag) values (".$spot.",".$QIYU_ID_SHOP.",".$tag.")";
			mysql_query($sql);
			alertInfo('添加成功','',1);
		break;
		case "delTag":
			$id=sqlReplace(trim($_GET['id']));
			
			$sql="select * from qiyu_shoptag where shoptag_id=".$id;
			$rs=mysql_query($sql);
			$row=mysql_fetch_assoc($rs);
			if (!$row)
				alertInfo("非法","",1);
			else{
				$sqlStr="delete from qiyu_shoptag where shoptag_id=".$id;
				if (mysql_query($sqlStr)){
					alertInfo("删除成功","",1);
				}else{
					alertInfo("删除失败","",1);
				}
			}
		break;
		case "topadd":
			$name=sqlReplace(trim($_POST['name']));
			$price1=sqlReplace(trim($_POST['price1']));
			$price2=sqlReplace(trim($_POST['price2']));
			$pic=sqlReplace(trim($_POST['upfile1']));
			
			$sql="select count(food_id) as c from qiyu_food where food_special='1' and food_status='0' and food_shop=".$QIYU_ID_SHOP;
			$rs=mysql_query($sql);
			$row=mysql_fetch_assoc($rs);
			$count=$row['c'];
			if ($count>$FOODSPECIALCOUNT){
				alertInfo("店铺最多有推荐5个推荐菜","shoutop.php",0);
			}else{
				$sqlStr="insert into qiyu_food(food_name,food_shop,food_price,food_special,food_pic,food_oldprice,food_isshow,food_status,food_check) values ('".$name."',".$QIYU_ID_SHOP.",".$price2.",'1','".$pic."',".$price1.",'0','0','0')";
				if (mysql_query($sqlStr)){
					alertInfo("添加成功","shoptop.php",0);
				}else{
					alertInfo("意外出错","",1);
				}
			}
			
		break;
		case "hidetop":
			$id=sqlReplace(trim($_GET['id']));
			$sql="update qiyu_food set food_isshow='1' where food_id=".$id." and food_shop=".$QIYU_ID_SHOP;
			if (mysql_query($sql))
				alertInfo("操作成功","",1);
			else
				alertInfo("操作失败","",1);
		break;
		case "showtop":
			$id=sqlReplace(trim($_GET['id']));
			$sql="update qiyu_food set food_isshow='0' where food_id=".$id." and food_shop=".$QIYU_ID_SHOP;
			if (mysql_query($sql))
				alertInfo("操作成功","",1);
			else
				alertInfo("操作失败","",1);
		break;
		case "topedit":
			$name=sqlReplace(trim($_POST['name']));
			$id=sqlReplace(trim($_GET['id']));
			$price1=sqlReplace(trim($_POST['price1']));
			$price2=sqlReplace(trim($_POST['price2']));
			$pic=sqlReplace(trim($_POST['upfile1']));
			$sql="update qiyu_food set food_name='".$name."',food_pic='".$pic."',food_price=".$price2.",food_oldprice=".$price1." where food_id=".$id;
			if (mysql_query($sql))
				alertInfo("操作成功","shoptop.php",0);
			else
				alertInfo("操作失败","",1);
		break;
		case 'topdel':
			$id=sqlReplace(trim($_GET['id']));
			checkData($id,"ID",0);
			$sql="select * from qiyu_food where food_id=".$id;
			$result=mysql_query($sql);
			$row=mysql_fetch_assoc($result);
			if(!$row){
				alertInfo('您要删除的数据不存在','',1);
			}else{
				$sql2="delete from qiyu_food where food_id=".$id;
				if(mysql_query($sql2)){
					alertInfo('删除成功','',1);
				}else{
					alertInfo('未知原因删除失败，请重试','',1);
				}
			}
			break;
		case "editPass":
			$old=sqlReplace(trim($_POST['old']));
			$new1=sqlReplace(trim($_POST['new1']));
			$new2=sqlReplace(trim($_POST['new2']));
			if ($new1!=$new2) alertInfo("两次输入的密码不同","",1);
			$sql="select * from qiyu_shop where shop_id=".$QIYU_ID_SHOP."";
			$rs=mysql_query($sql);
			$row=mysql_fetch_assoc($rs);
			if ($row){
				$salt=$row['shop_salt'];
				$pw=md5(md5($old).$salt);
				$newpw=md5(md5($new1).$salt);
				if ($pw==$row['shop_password']){
					$sqlStr="update qiyu_shop set shop_password='".$newpw."' where shop_id=".$QIYU_ID_SHOP."";
					if (mysql_query($sqlStr))
						alertInfo("修改成功","",1);
					else
						alertInfo("修改失败","",1);
				
				}else{
					alertInfo("原密码错误","",1);
				}
			}
			
		break;
		case "updatePhone":
			$phone=sqlReplace(trim($_POST['phone']));
			$code=sqlReplace(trim($_POST['code']));
			$sqlStr="select * from qiyu_shop where shop_id=".$QIYU_ID_SHOP."";
			$result = mysql_query($sqlStr);
			$row=mysql_fetch_assoc($result);
			if ($row){
				if ($code==$row['shop_code']){
					$sql="update qiyu_shop set shop_phone='".$phone."',shop_code='' where shop_id=".$QIYU_ID_SHOP."";
					if (mysql_query($sql))
						alertInfo("修改成功","shopupdatephone.php",0);
					else
						alertInfo("修改失败","",1);
				}else{
					alertInfo("验证码错误","",1);
				}
			}
		break;
		case "addspecial";
			//得到提交的数据，并进行过滤
			$sp_id=$QIYU_ID_SHOP;
			$name=sqlReplace(trim($_POST['name']));
			$price=sqlReplace(trim($_POST['price']));
			$oldprice=sqlReplace(trim($_POST['oldprice']));
			$pic=sqlReplace(trim($_POST['upfile1']));
			
			//检验数据的合法性
			checkData($name,'名称',1);
			checkData($price,'优惠价',1);
			checkData($oldprice,'原价',1);
						
			$sql2 = "insert into qiyu_food(food_name,food_shop,food_price,food_oldprice,food_pic,food_special,food_order) values ('".$name."','".$sp_id."','".$price."','".$oldprice."','".$pic."',1,999)";
			echo $sql2;
			$result=mysql_query($sql2);
			if($result){
				alertInfo('添加成功',"foodspecial_list.php",0);
			}else{
				alertInfo('未知原因错误，请重试',"",1);
			}
		break;
		case "editspecial";
			//得到提交的数据，并进行过滤
			$id=sqlReplace(trim($_GET['id']));
			$name=sqlReplace(trim($_POST['name']));
			$price=sqlReplace(trim($_POST['price']));
			$oldprice=sqlReplace(trim($_POST['oldprice']));
			$pic=sqlReplace(trim($_POST['upfile1']));
			
			//检验数据的合法性
			checkData($id,'ID',0);
			checkData($name,'名称',1);
			checkData($price,'优惠价',1);
			checkData($oldprice,'原价',1);
						
			$sql="select food_id from ".WIIDBPRE."_food where food_id=".$id;
			$rs=mysql_query($sql);
			$rows=mysql_fetch_assoc($rs);
			if(!$rows){
				alertInfo('非法操作','',1);
			}else{				
				$sql2 = "update ".WIIDBPRE."_food set food_name='".$name."',food_price='".$price."',food_oldprice='".$oldprice."',food_pic='".$pic."' where food_id=".$id;
				$result=mysql_query($sql2);
				if($result){
					alertInfo('修改成功',"foodspecial_list.php",0);
				}else{
					alertInfo('未知原因错误，请重试',"",1);
				}
			}
		break;
		case "delfoodspecial":
			$id=sqlReplace(trim($_GET['id']));
			checkData($id,'ID',0);
			$sql="select food_id from ".WIIDBPRE."_food where food_id=".$id;
			$rs=mysql_query($sql);
			$rows=mysql_fetch_assoc($rs);
			if(!$rows){
				alertInfo('非法操作','',1);
			}else{				
				$sql2 = "delete from ".WIIDBPRE."_food where food_id=".$id;
				$result=mysql_query($sql2);
				if($result){
					alertInfo('删除成功',"foodspecial_list.php",0);
				}else{
					alertInfo('未知原因错误，请重试',"",1);
				}
			}
		break;
		case "savefoodspecail":
			$i=trim($_POST['i']);
			for($x=1;$x<=$i;$x++){
				$id=$_POST['id'.$x];
				$order=$_POST['order'.$x];
				$sql="update ".WIIDBPRE."_food set food_order=".$order." where food_id=".$id;	
				if(!mysql_query($sql)){
					alertInfo('未知原因保存失败! ',"foodspecial_list.php",0);
				}
			}
			alertInfo('保存排序成功!',"",1);
		break;
		case "card1":
			$upfile1=sqlReplace(trim($_POST['upfile']));
			checkData($upfile1,'营业执照',1);

			$sql="update qiyu_shop set shop_certpic='".$upfile1."',shop_certtime=now() where shop_id=".$QIYU_ID_SHOP;
			if (mysql_query($sql)){
				alertInfo("提交成功","shopcard.php",0);
			}else{
				alertInfo("提交失败","",1);
			}
		break;
		case "card2":
			$upfile2=sqlReplace(trim($_POST['upfile']));
			checkData($upfile2,'卫生许可证',1);

			$sql="update qiyu_shop set shop_licensepic='".$upfile2."',shop_licensetime=now() where shop_id=".$QIYU_ID_SHOP;
			if (mysql_query($sql)){
				alertInfo("提交成功","shopcard.php",0);
			}else{
				alertInfo("提交失败","",1);
			}
		break;
	}
	

	
?>