<?php
/**
 * ALIPAY API: alipay.ebpp.config.province.search request
 *
 * @author auto create
 * @since 1.0, 2014-06-12 17:16:53
 */
class AlipayEbppConfigProvinceSearchRequest
{
	/** 
	 * 业务类型
	 **/
	private $orderType;
	
	/** 
	 * 子业务类型
	 **/
	private $subOrderType;

	private $apiParas = array();
	private $terminalType;
	private $terminalInfo;
	private $prodCode;
	private $apiVersion="1.0";
	
	public function setOrderType($orderType)
	{
		$this->orderType = $orderType;
		$this->apiParas["order_type"] = $orderType;
	}

	public function getOrderType()
	{
		return $this->orderType;
	}

	public function setSubOrderType($subOrderType)
	{
		$this->subOrderType = $subOrderType;
		$this->apiParas["sub_order_type"] = $subOrderType;
	}

	public function getSubOrderType()
	{
		return $this->subOrderType;
	}

	public function getApiMethodName()
	{
		return "alipay.ebpp.config.province.search";
	}

	public function getApiParas()
	{
		return $this->apiParas;
	}

	public function getTerminalType()
	{
		return $this->terminalType;
	}

	public function setTerminalType($terminalType)
	{
		$this->terminalType = $terminalType;
	}

	public function getTerminalInfo()
	{
		return $this->terminalInfo;
	}

	public function setTerminalInfo($terminalInfo)
	{
		$this->terminalInfo = $terminalInfo;
	}

	public function getProdCode()
	{
		return $this->prodCode;
	}

	public function setProdCode($prodCode)
	{
		$this->prodCode = $prodCode;
	}

	public function setApiVersion($apiVersion)
	{
		$this->apiVersion=$apiVersion;
	}

	public function getApiVersion()
	{
		return $this->apiVersion;
	}

}
