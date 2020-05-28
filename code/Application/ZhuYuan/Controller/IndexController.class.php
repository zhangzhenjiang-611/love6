<?php
namespace ZhuYuan\Controller;
use Think\Controller;
class IndexController extends CommonController {
	public function index()
	{
		$this->display();
	}
	//查询患者信息接口
	private function his_patient_info($patient_id = '',$jyt_no = '',$sfz_no = '',$yb_no = '')
	{
		$url = C('HIS_HOST_URL').'/patient_info';
		$xml = '<?xml version="1.0" encoding="utf8"?>';
		$xml .= ' <request>';
		$xml .= '   <patient_id>'.$patient_id.'</patient_id>';
		$xml .= '   <jyt_no>'.$jyt_no.'</jyt_no>';
		$xml .= '   <sfz_no>'.$sfz_no.'</sfz_no>';
		$xml .= '   <yb_no>'.$yb_no.'</yb_no>';
		$xml .= ' </request>';
		$response = curl_http($url,$xml,'xml');
		$response = simplexml_load_string($response);
		return $response;
	}
	//住院充值保存接口
	private function his_charge_save($patient_id,$indate,$incnt,$pos = '',$union = '')
	{
		$url = C('HIS_HOST_URL').'/charge_save';
		$xml = '<?xml version="1.0" encoding="utf8"?>';
		$xml .= ' <request>';
		$xml .= '   <patient_id>'.$patient_id.'</patient_id>';
		$xml .= '   <indate>'.$indate.'</indate>';
		$xml .= '   <incnt>'.$incnt.'</incnt>';
		$xml .= '   <pos>'.$pos.'</pos>';
		$xml .= '   <union>'.$union.'</union>';
		$xml .= ' </request>';
		$response = curl_http($url,$xml,'xml');
		$response = simplexml_load_string($response);
		return $response;
	}
	//查询患者信息ajax方法
	public function ajaxPatientInfo()
	{
		$patient_id = I('post.patient_id');
		$jyt_no = I('post.jyt_no');
		$sfz_no = I('post.sfz_no');
		$yb_no = I('post.yb_no');
		$response = $this->his_patient_info($patient_id,$jyt_no,$sfz_no,$yb_no);
		$this->ajaxReturn($response,'JSON');	
	}
	//充值保存ajax方法
	public function ajaxChargeSave()
	{
		$zzj_id = I('post.zzj_id');
		$patient_id = I('post.patient_id');
		$indate = I('post.indate');
		$incnt = I('post.incnt');
		$paytype = I('post.paytype');
		$transactionAmount = I('post.transactionAmount');
		$payCode = I('post.payCode');
		print_r($_POST);
		if($paytype == 'pos')
		{
			$pos = $this->pay($patient_id,$transactionAmount,$payCode,$zzj_id);
			$pos['errCode'] = '00';
			if($pos['errCode'] == '00')
			{
				$pos = json2xml($pos);
				$response = $this->his_charge_save($patient_id,$indate,$incnt,$pos,'');
				if($response['code'] == '00')
				{

				}
				else
				{

				}
			}
			else
			{

			}
		}
		else if($paytype == 'union')
		{
			$response = $this->his_charge_save($patient_id,$indate,$incnt,'',$union);
		}
		$this->ajaxReturn($response,'JSON');	
	}
	//pos激活方法
	private function active()
	{
		vendor('.UnionPos');	
		$UnionPos = new \UnionPos();
		$response = $UnionPos->active();
	}
	//pos支付方法
	private function pay($patient_id,$transactionAmount,$payCode,$zzj_id)
	{
		vendor('.UnionPos');	
		$UnionPos = new \UnionPos();
		$post = array();
		$post['transactionAmount'] = $transactionAmount*100;
        $post['transactionCurrencyCode'] = 156;
        $post['merchantOrderId'] = $zzj_id.date('YmdHis').mt_rand(100000,999999);
        $post['merchantRemark']="自助终端患者{$patient_id}充值{$transactionAmount}";
        $post['payMode'] = 'CODE_SCAN';

        $post['payCode'] = $payCode;
        $post['imitCreditCard'] = 1;
		$UnionPos->pay($post);
		$pos['param'] = $UnionPos->param;
		$pos['return'] = $UnionPos->return;
		return $pos;
	}
}