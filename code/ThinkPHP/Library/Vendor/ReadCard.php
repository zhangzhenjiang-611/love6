<?php
/*
 * @desc 读卡类
 * @author wangxinghua
 * @final 2019-12-31
 */
class ReadCard{
	/*
	 * @desc 解析读卡信息串
	 * @author wangxinghua
	 * @final 2019-12-31
	 */	
	public static function parse($cardType,$cardInfo)
	{
		$cardInfo = str_replace(array('&quot;','&amp;quot;'),array('',''), $cardInfo);
		$result = array();
		$result['cardType'] = $cardType;

		if($cardType != 3 && !strstr($cardinfo, '<?xml'))
			$cardType = 99;
		switch ($cardType) {
			case '1'://医保卡
			/*
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
			*/
			Vendor("YbMan");
			$info = \YbMan::PersonInfoParse($cardInfo);
			$sex_kv = array('1'=>'男','2'=>'女');
			$result['error'] = $info['error'];
			$result['cardno'] = $info['ic']['card_no'];
			$result['idno'] = $info['ic']['id_no'];
			$result['name'] = $info['ic']['personname'];
			$result['sex'] = $sex_kv[$info['ic']['sex']] ? $sex_kv[$info['ic']['sex']] : '未知';
			$result['birthday'] = date('Y-m-d',strtotime($info['ic']['birthday']));
			$result['address'] = '';
			$result['balance'] = $info['net']['personcount'];
			break;
			case '2'://京医通卡
			/*
			<?xml version="1.0" encoding="utf8" standalone="yes"?> <root version="2.1.1"><state success="true" needconfirm="false"></state><cardinfo><cardsn>20000001630194</cardsn><cardno>98001001630194</cardno><medicalalliancecode>98001001630194</medicalalliancecode><idtype>1</idtype><idno>370404198210160043</idno><name>贾妍章</name><sex>2</sex><birthday>19821016</birthday><nationality>1</nationality><phone>13863271209</phone><recordaddress></recordaddress><presentaddress></presentaddress><balance>0.00</balance><cardstatus>0</cardstatus><lastoptime></lastoptime><lasttradeno></lasttradeno><countryinfo>1</countryinfo><marriageinfo>1</marriageinfo><localityinfo>1</localityinfo><unit></unit><areacode></areacode><contactname>贾妍章</contactname><contactsex>1</contactsex><contactidtype>1</contactidtype><contactidno>370404198210160043</contactidno><contactphone>13863271209</contactphone><contactpresentaddress></contactpresentaddress><deposit>10.00</deposit><photo>V0xmAH4AMgAA/4UeUVFRPnEN1WTzLkeNA6tPimcgRMdiU0/EkivLm/PjLb6JgItNE/YrsloF4S2Q+tx+G6GxRimE1gp8t4Vz8FEzJi9W2HtK/j9YGb+NE9NB+RVRrtVSUVFaPoTqMrABK+iY7jeCBS48duComt38aS62syD5L3mtukwhqI1iHeSqu2fycvrHcacBpNxnQ4lPSaSXkPK86dkFppiM7cd++aAhqm58Z/07lW91dVNd2jZMGlO3gg35It1jewB2Dsp2gmDWydWR15ozb15YNNTLfg6rclz8XbQjLh0IIi8jc3OA0JMvWVwknZK1u8HFG9ZJ4d44KCuM2rvSXV6RdurJCkliZvroAYbwqiSrkP3dAgWoYeK/5Hgp/Yl2BKHLYICTVfUtBOgXD/GPkfGjEKOaP8TkvR80GxsJpOTGozE12kW1OaUIMVQXU010+p3L+ZNTFJCFF7D6FZDNuzhqj/fm5JSIj/vlLsmVeAMcpXP9lhO7V79YBZsOzd2uUXxd2My+lQNJKaEvWdc65OxQHyKZgXrljRqjiqbZLlM/vSEzMZv34IT8iykiT2VsCBrl+QDKj6oB58HdCp8MFVNVVb1Y6Ql9KhzD//YOVW6ztUPlj3nS3n86PUi5XPnvPDfINFT2E+OyOET56cUW4OD0WhCgJusc+bMkV1wgP6ymh1XGaxyDXqzbi+djBQmGCJni1Y/VoQnS+qe5rf3phDeOxn/zc2XA9G1+5DkX5qjyqj9XGT7jCRXl9Qv5FibKRNuwRb5CX2TqhUx9BeZCpFP1JQ6soSFyYLG69udNuEGlEg6wgp0goN0qXUHJzh3dJ5T3U3WqV+iZu2hWIRf2WTOtxBz2R/Ghj9beotOv7Ib0joR5QOUa/ZMl6BrwKoAiUeMQ4/2D9DGrkxNkThdKt3XjFAqvIYJef31JbhR2UwLiey4H5QLV+f3hJfEJksqXZe4IsnBDD99+h+jb26ml3+k1Nmc3BnKEYhFxypgF+Q+Mz/S/LlNDaBcvGD86l+GHOB5sZU0vx4DFHRl3TfN1owh70/ENyWWT2S66uqFxKfuR8S7Wm+FBINWmEQ4UU4yzt0eyZglHogHUraEca6GgKP/9XWmw3nxp6HoJnzivCvZxWeE4LWG8wUyC5l6nuy5dcQxaPjAAakmyQFw5rlGCBul0DUIQeWjscbK8fIwadPQTuWu079jtqQP8PPopa+AsRiyKn2qH8Ww0LCzoJzX5NGGbX1U5dmk6OBD8Mcq66dsu5udaPhwX7UwNWAX9GdBottl9wotPt0/+22G9sf24G1WZbn+wtAnyzTOb4AjYxIbjugTFl509ERCE3Eqo2lRsnCeclay255Fg+Xh4pA==</photo><cardtype>1</cardtype><netinfo><creatcardtime>20171201141036</creatcardtime><creatcardhospcode>H105791</creatcardhospcode><creatcardhospname>中国中医科学院广安门医院</creatcardhospname><islocalblacklist>false</islocalblacklist><reportloststatus>0</reportloststatus><reportlosttime></reportlosttime><reason></reason><accountstatus>1</accountstatus></netinfo></cardinfo></root>;
			*/
			$xml = htmlspecialchars_decode($cardInfo);
			$xml = str_replace("UTF-16","utf8",$xml);
			$xml = str_replace("\\\"", "\"", $xml);

			$doc = simplexml_load_string($xml);
			$state = (array)$doc->state;
			$netinfo = (array)$doc->cardinfo->netinfo;
			$cardinfo = (array)$doc->cardinfo;

			$error = '';
			if($state["@attributes"]['success'] == "true"  && $netinfo['islocalblacklist'] == "false" && $netinfo['reportloststatus'] == "0" )
			{
				if(array_key_exists('error',$state))
				{
					$error = (array)$state['error'];
					$error = $error['@attributes']['info'];
				}
			}
			else
			{
				$error = "读卡失败，请检查卡片是否插反，或去窗口办理！";
			}

			$sex_kv = array('1'=>'男','2'=>'女');
			$result['error'] = $info['error'];
			$result['cardno'] = $cardinfo['cardno'];
			$result['idno'] = $cardinfo['idno'];
			$result['name'] = $cardinfo['name'];
			$result['sex'] = isset($sex_kv[$cardinfo['sex']]) ? $sex_kv[$cardinfo['sex']] : '未知';
			$result['birthday'] = date('Y-m-d',strtotime($cardinfo['birthday']));
			$result['address'] = '';
			$result['balance'] = $cardinfo['balance'];
			break;
			case '3'://身份证
			//陈兴超,男,汉,19850603,河北邢台,230183199011070814,柏乡县公安局,20090324,20190324
			$cardInfo = explode(',', $cardInfo);
			$result['cardno'] = $cardInfo[5];
			$result['idno'] = $cardInfo[5];
			$result['name'] = $cardInfo[0];
			$result['sex'] = $cardInfo[1];
			$result['birthday'] = date('Y-m-d',strtotime($cardInfo[3]));
			$result['address'] = $cardInfo[4];
			$result['balance'] = 0.00;
			break;
			case '4'://就诊卡
			$cardInfo = explode(',', $cardInfo);

			$result['cardno'] = $cardInfo[5];
			$result['idno'] = $cardInfo[5];
			$result['name'] = $cardInfo[0];
			$result['sex'] = $cardInfo[1];
			$result['birthday'] = date('Y-m-d',strtotime($cardInfo[3]));
			$result['address'] = $cardInfo[4];
			$result['balance'] = 0.00;
			break;
			case '99'://多合一读卡器
			$cardInfo = explode(',', $cardInfo);

			$result['cardno'] = $cardInfo[0];
			$result['idno'] = $cardInfo[5];
			$result['name'] = $cardInfo[1];
			$result['sex'] = $cardInfo[2];
			$result['birthday'] = date('Y-m-d',strtotime($cardInfo[4]));
			$result['address'] = $cardInfo[3];
			$result['balance'] = 0.00;
			break;
		}
		//读卡失败，信息处理
		if($result['error'])
		{
			$result['cardno'] = '';
			$result['idno'] = '';
			$result['name'] = '';
			$result['sex'] = '未知';
			$result['birthday'] = '1970-01-01';
			$result['address'] = '';
			$result['balance'] = 0;
		}
		return $result;
	}
}