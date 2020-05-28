<?php
/*
 * @desc 医保交互处理类
 * @author wangxinghua
 * @final 2019-12-31
 */
class YbMan{
	/*
	 * @desc 解析医保返回的卡信息xml串
	 * @param $xml
	   例如:
	    医保
		<?xml version="1.0" encoding="utf-16" standalone="yes" ?>
		<root version="2.003">
			<output name="输出部分">
				<ic>
					<card_no name="社保卡卡号"/>107765716008</card_no>
					<ic_no name="卡号">10776571600s</ic_no>
					<id_no name="公民身份号码">110101194808074025</id_no>
					<personname name="姓名">xxxx</personname>
					<sex name="性别">男</sex>
					<birthday name = "出生日期">19480807</birthday>
					<fromhosp name="转诊医院编码">xx</fromhosp>
					<fromhospdate name="转诊时限"></fromhospdate>
					
					<fundtype name="险种类型:老少,居民,职工,公疗"></fundtype>
					<spechospcode name="特殊管理病种定点医疗机构代码（肝移植病人门诊用）"></spechospcode>
					<isyt name="预提人员标示"></isyt>
					<jclevel name="军残等级"></jclevel>
					<hospflag name="在院标示"></hospflag>
				</ic>
				<net>
					<persontype name="参保人员类别"></persontype>
					<net name="是否在红名单"></net>
					<isspecifiedhosp name="是否本人定点医院"></isspecifiedhosp>
					<personcount name="个人帐户余额"></personcount>
					<chroniccode name="慢病编码">1,2,3</chroniccode>
				</net>
			</output>
				<state success="true">
				<error no="1" info="读卡失败,请插入社保卡!" />
				<error no="2" info="该卡片已经挂失,不能使用!" />
				<warning no="1" info="与医保中心通讯中断,不能取得个人账户,定点医疗机构等信息,请联系网络管理员查看网络运行状况" />
			</state>
		</root>;
		<?xml version="1.0" encoding="utf8" standalone="yes"?>
		<root version="1.00.0409">
			<state success="true"></state>
			<output>
				<ic>
					<card_no>100354047008</card_no>
					<ic_no>10035404700S</ic_no>
					<id_no>110103195809181268</id_no>
					<personname>宋美珍</personname>
					<sex>2</sex>
					<birthday>19580918</birthday>
					<fromhosp></fromhosp>
					<fromhospdate>18991230</fromhospdate>
					<fundtype>3</fundtype>
					<isyt>0</isyt>
					<jclevel>0</jclevel>
					<hospflag>0</hospflag>
				</ic>
				<net>
					<persontype>61</persontype>
					<isinredlist>true</isinredlist>
					<isspecifiedhosp>1</isspecifiedhosp>
					<ischronichosp>false</ischronichosp>
					<personcount>0.00</personcount>
					<chroniccode></chroniccode>
				</net>
			</output>
		</root>
	 * @author wangxinghua
	 * @final 2019-12-31
	 */	
	public static function PersonInfoParse($xml)
	{
		$xml = htmlspecialchars_decode($xml);
		$xml = str_replace(array("gb2312","utf-16"),array("utf8","utf8"),$xml);
		$xml = simplexml_load_string($xml);

		$info = array();
		$info['error'] = "";
		$info['ic'] = (array)$xml->output->ic;
	    $info['net'] = (array)$xml->output->net;
	    $info['state'] = (array)$xml->state;

		if($info['state']["@attributes"]['success'] == "true")
		{
			if(array_key_exists('error',$info['state']))
			{
				$error = (array)$info['state']['error'];
				$info['error'] = $error['@attributes']['info'];
			}
		}
		else
		{
			$info['error'] = "读卡失败，请检查卡片是否插反，或去窗口办理！";
		}
		return $info;
	}
	/*
	 * @desc 拼接医保费用分解需要的xml串
	 * @author wangxinghua
	 * @final 2019-12-31
	 */	
	public static function DivideInfoParam($param, $business_type)
	{
		$SENDER = 'SHJSDWNR00002.100019';//融威首信认证序号
		switch ($business_type)
		{
			case 'zzjf'://自助缴费
				$result = ''.
			    '<?xml version="1.0" encoding="utf-16" standalone="yes" ?>' .
                '<root version="2.003" sender="'. $SENDER .'">' .
                   '<input>' .
                      '<tradeinfo>' .
                          '<curetype>17</curetype>' .
                          '<illtype>0</illtype>' .
                          '<feeno></feeno>' .
                          '<operator>'.$param['zzj_code'].'</operator>' .
                      '</tradeinfo>' .
                      '<recipearray>'; 
                      $feeitem = '';
                      	foreach($param['data'] as $ok=>$o)
                      	{
                      		$recipedate = date('YmdHis',strtotime($o['orderDate'].$o['orderTime']));
                      		$result .= ''.
                      		'<recipe>' .
                              '<diagnoseno>'.$ok.'</diagnoseno>' .
                              '<recipeno>'.$o['orderNo'].'</recipeno>' .
                              '<recipedate>'.$recipedate.'</recipedate>' .
                              '<diagnosename></diagnosename>' .
                              '<diagnosecode></diagnosecode>' .
                              '<medicalrecord></medicalrecord>' .
                              '<sectioncode></sectioncode>' .
                              '<sectionname></sectionname>' .
                              '<hissectionname></hissectionname>' .
                              '<drid></drid>' .
                              '<drname></drname>' .
                              '<recipetype>1</recipetype>' .
                              '<billstype>1</billstype>' .
                            '</recipe>';

                            foreach ($o['orderItems'] as $ik=>$i)
                            {
                            	$feeitem .= '<feeitem itemno="'.$i['itemNo'].'" recipeno="'.$o['orderNo'].'" hiscode="'.$i['itemNo'].'_'.$ik.'" itemname="'.$i['itemName'].'" itemtype="0" unitprice="'.$i['itemPrice'].'" count="'.$i['itemNumber'].'" fee="'.$i['itemFee'].'" dose="" specification="" unit="'.$i['itemUnit'].'" howtouse="" dosage="" packaging="" minpackage="" conversion="" days=""/>';
                            }
                        }

            			$result .= ''.
            		  '</recipearray>' .
                      '<feeitemarray>';
                  				$result .= $feeitem;
   		   				$result .= ''.
   		   			 '</feeitemarray>' .
                  '</input>' .
               '</root>';
				break;
			case 'zzgh'://自助挂号
			case 'yygh'://预约挂号
			case 'yyqh'://预约取号
				$recipeno = $param['data']['dsNo'] ? $param['data']['dsNo'] : $param['data']['apNo'];
				$recipedate = $param['data']['serviceDate'] ? str_replace('-', '', $param['data']['serviceDate']).' '.date('His') : date('Ymd His');
				$fee = $param['data']['serviceFee'];
				$hiscode = '';//HIS项目代码(药品、诊疗项目或服务设施编码)
				$itemname= '';//HIS项目名称(本医院项目名称)
				$result = '<?xml version="1.0" encoding="utf-16" standalone="yes" ?>' .
                    '<root version="2.003" sender="'. $SENDER .'">' .
                      '<input>' .
                          '<tradeinfo>' .
                              '<curetype>17</curetype>' .
                              '<illtype>0</illtype>' .
                              '<feeno></feeno>' .
                              '<operator>'.$param['zzj_code'].'</operator>' .
                          '</tradeinfo>' .
                          '<recipearray>' .
                              '<recipe>' .
                                  '<diagnoseno>1</diagnoseno>' .
                                  '<recipeno>'.$recipeno.'</recipeno>' .
                                  '<recipedate>'.$recipedate.'</recipedate>' .
                                  '<diagnosename></diagnosename>' .
                                  '<diagnosecode></diagnosecode>' .
                                  '<medicalrecord></medicalrecord>' .
                                  '<sectioncode></sectioncode>' .
                                  '<sectionname></sectionname>' .
                                  '<hissectionname></hissectionname>' .
                                  '<drid></drid>' .
                                  '<drname></drname>' .
                                  '<recipetype>1</recipetype>' .
                                  '<billstype>1</billstype>' .
                              '</recipe>' .
                          '</recipearray>' .
                          '<feeitemarray>' .
                             '<feeitem      itemno="0"      recipeno="'.$recipeno.'" hiscode="'.$hiscode.'"   itemname="'.$itemname.'"      itemtype="1"      unitprice="'.$fee.'"     count="1"      fee="'.$fee.'"     dose=""      specification=""      unit="人次"      howtouse=""      dosage=""      packaging=""      minpackage=""      conversion=""      days=""       />' .
                          '</feeitemarray>' .
                      '</input>' .
                   '</root>';
				break;
		}
		return $result;
	}
	/*
	 * @desc 解析医保返回的费用分解结果xml串
	 * @param $xml
	   <?xml version="1.0" encoding="utf-16" standalone="yes" ?>
		<root version="2.003">
		<output name="输出部分">
			<tradeinfo>
				<tradeno name = "交易流水号">0111000309030100116b</tradeno>
				<feeno 	 name="收费单据号">xxxxx</feeno>
			</tradeinfo>
			<feeitemarray>
				<feeitem  itemno="0" recipeno="" hiscode="sw001" itemcode="101110003507201990000000000000000" itemname="松万" itemtype="0" unitprice="7.5" count="100" fee="750" feein="0" feeout="0" selfpay2="0" usedate="20081001" state="0" fix_flag="0"   fee_type="" preferentialfee="0" preferentialscale="0" bornflag="false"/>
				<feeitem  itemno="1" recipeno="" hiscode="slhl001" itemcode="101110003509900990000000000000000" itemname="三氯化铝溶液" itemtype="0" unitprice="5.5" count="100" fee="550" feein="0" feeout="0" selfpay2="0" usedate="20081101" state="0" fix_flag="0"   fee_type="" preferentialfee="0" preferentialscale="0" bornflag="false"/>
			</feeitemarray>
			<sumpay>
				<feeall name="费用总金额">0</feeall>
				<fund name="基金支付"></fund>
				<cash name="现金支付"></cash >
				<personcountpay name="个人帐户支付"> </personcountpay>
				<personcount 	name="个人账户余额"></personcount>
			</sumpay>

			<payinfo>
				<mzfee 		name="">0</mzfee>
				<mzfeein 	name="">0</mzfeein>
				<mzfeeout 	name="医保外费用">0</mzfeeout>
				<mzpayfirst name="本次付起付线">0</mzpayfirst>
				<mzselfpay2	name="">0</mzselfpay2>
				<mzbigpay	name="">0</mzbigpay>
				<mzbigselfpay name="">0</mzbigselfpay>
				<mzoutofbig name="">0</mzoutofbig>
				<bcpay    	name="补充保险支付"></bcpay>
				<jcbz  		name="军残补助保险支付"></jcbz>
				<jspay      name="计划生育支付"></jspay >
		    </payinfo>
			<medicatalog>
				<medicine name="西药">0</medicine>
				<tmedicine name="中成药">0</tmedicine>
				<therb name="中草药">0</therb>
				<examine name="常规检查">0</examine>
				<ct >0</ct>
				<mri name="核磁">0</mri>
				<ultrasonic name="b超">0</ultrasonic>
				<oxygen name="输氧费">0</oxygen>
				<operation name="手术费">0</operation>
				<treatment name="治疗费">0</treatment>
				<xray name="放射">0</xray>
				<labexam name="化验">0</labexam>
				<bloodt name="输血费">0</bloodt>
				<orthodontics name="正畸费">0</orthodontics>
				<prosthesis name="镶牙费">0</prosthesis>
				<psychometry name="心理测试">0</psychometry>
				<forensic_expertise name="司法鉴定">0</forensic_expertise>
				<material name="材料费">0</material>
				<other name="其他项目">0</other>
			</medicatalog>
		</output>
		<state>
			<error   no="0" info="读卡失败,请插入社保卡!" />
			<error   no="1" info="该卡片已经挂失,不能使用!" />
			<warning no="0" info="与医保中心通讯中断,不能取得个人账户,定点医疗机构等信息,请联系网络管理员查看网络运行状况" />
		</state>	
	    </root>
	 * @author wangxinghua
	 * @final 2019-12-31
	 */	
	public static function DivideResultParse($xml)
	{
		$xml = htmlspecialchars_decode($xml);
		$xml = str_replace(array("gb2312","utf-16"),array("utf8","utf8"),$xml);
		$xml = simplexml_load_string($xml);

		$result = array();
		$result['error'] = "";

		$result['payinfo'] = (array)$xml->output->payinfo;
		$result['tradeinfo'] = (array)$xml->output->sumpay;
		$result['feeitemarray'] = (array)$xml->output->feeitemarray;
		$result['sumpay'] = (array)$xml->output->sumpay;
		$result['state'] = (array)$xml->state;

		if($result['state']["@attributes"]['success'] == "true")
		{
			if(array_key_exists('error',$result['state']))
			{
				$error = (array)$result['state']['error'];
				$result['error'] = $error['@attributes']['info'];
			}
		}
		else
		{
			$result['error'] = "医保分解失败，请重试或去窗口处理！";
		}
		return $result;
	}

	/*
	 * @desc 解析医保返回的交易确认xml串
	 * @param $xml
		<?xml version="1.0" encoding="utf16" standalone="yes" ?> 
		<root version="2.003">
			<state success="true">
				<error /> 
				<warning no="0" info="与医保中心通讯中断,不能取得个人帐户,定点医疗机构等信息,请联系网络管理员查看网络运行状况" /> 
			</state>
			<output name="输出部分">
				<personcountaftersub name="本次结算后个人帐户余额">2.00</personcountaftersub> 
				<certid name="个人数字证书ID" /> 
				<sign name="交易签名结果" /> 
			</output>
		</root>
	 * @author wangxinghua
	 * @final 2019-12-31
	 */	
	public static function TradeResultParse($xml)
	{
		$xml = htmlspecialchars_decode($xml);
		$xml = str_replace(array("gb2312","utf-16"),array("utf8","utf8"),$xml);
		$xml = simplexml_load_string($xml);

		$result = array();
		$result['error'] = "";

		$result['state'] = (array)$xml->state;

		if($result['state']["@attributes"]['success'] == "true")
		{
			if(array_key_exists('error',$result['state']))
			{
				$error = (array)$result['state']['error'];
				$result['error'] = $error['@attributes']['info'];
			}
		}
		else
		{
			$result['error'] = "医保交易确认失败，请重试或去窗口处理！";
		}
		return $result;
	}
}