<?php
namespace Home\Controller;
use Think\Controller;
class IndexController extends Controller
{
    public function index()
    {
        $url = "http://192.168.1.218:11289/KWSService.asmx?wsdl";
        $client = new \SoapClient($url);
        dump($client->__getFunctions());
        //$server = new \SoapServer($url);
        //dump($server);
        //echo C('USERNAME');
        //  $user = M('User');
        // dump($user->select());
        // pp($_SERVER);
        //var_dump(defined('__PUBLIC'));  //检测常量是否存在  不是常量
        //echo THINK_VERSION;
        //echo U('show',array('uid'=>1,'username'=>'aa'),'',true);die();
        //$wish = M('Wish') ->select();
        //var_dump($wish);die();
        //$this->assign('wish',M('Wish') ->select())->display();
    }

    public function test()
    {
        header('Content-Type:text/html; charset=utf-8');//设置编码方式UTF-8
        ini_set('soap.wsdl_cache_enabled', '0');//关闭缓存
        $url = 'http://192.168.1.218:11289/KWSService.asmx?wsdl';
        $client = new \SoapClient($url);
        //dump($client);die;
        $card_no = '320502198611062528';
        $card_code = 4;
        $zzj_id = '00';
        $xmls = "101";
        $xml = "<request><partner></partner><sign></sign><timestamp></timestamp><operid>" . $zzj_id . "</operid><password></password><kfsdm></kfsdm><params><cardno>" . $card_no . "</cardno><cardtype>" . $card_code . "</cardtype><czyh>" . $zzj_id . "</czyh></params></request>";
        $xmlData = array(
            'TranCode' => $xmls,
            'InXml' => $xml,
        );

        //$data = array($xmls,$xml);
        try {
            $result = $client->WebBusiness($xmlData);
            $arr = object_array($result);
            dump($result);

        } catch (Exception $e) {
            print_r($e->getMessage());

        }

        exit;
        $array = get_object_vars($result);
        $str = $array['SendLeadsResult'];
        $arr = json_decode($str, true);
        if ($arr['Success'] == '1') {
            return json_encode(1);
        } else {
            return json_encode(0);
        }
    }

    public function arrayToXml($arr)
    {
        $xml = "<xml>";
        foreach ($arr as $key => $val) {
            if (is_numeric($val)) {
                $xml .= "<" . $key . ">" . $val . "</" . $key . ">";
            } else {
                $xml .= "<" . $key . "><![CDATA[" . $val . "]]></" . $key . ">";
            }
        }
        $xml .= "</xml>";
        return $xml;
    }

    public function handle()
    {
        // var_dump(IS_POST);
        // pp(I('post.'));
        //echo I('username');
        if (!IS_POST) {
            $this->error('页面不存在');
        } else {
            $data = array(
                'username' => I('post.username', ''),
                'content' => I('post.content', ''),
                'time' => time()
            );
            // $id = M('Wish')->data($data)->add();
            $id = M('Wish')->add($data);
            if ($id) {
                $this->success('发布成功', 'index');
            } else {
                $this->error('发布失败');
            }
        }
    }

    public function qiantai()
    {
    }

    //
    public function sortArr()
    {
        $arr = array(
            array(
                'fee' => '168',
                'date' => '2020-05'
            ),
            array(
                'fee' => '77',
                'date' => '2020-03'
            ),
            array(
                'fee' => '33',
                'date' => '2020-02'
            )
        );

        $date_month = array_column($arr, 'date');
        $new_arr = array();
        for ($i = 1; $i <= 12; $i++) {
            /* if ($i <=9) {
                 if(!in_array(date('Y').'-0'.$i,$date_month)) {
                     $arr[] = array(
                         'fee'  => 0,
                         'date'  => date('Y').'-0'.$i
                     );
                 }
             } else {
                 if(!in_array(date('Y').'-0'.$i,$date_month)) {
                     $arr[] = array(
                         'fee'  => 0,
                         'date'  => date('Y').'-'.$i
                     );
                 }
             }*/
            if ($i <= 9) {
                $new_arr[] = array();
            }

        }
        echo "<pre>";
        print_r($arr);
    }

    public function setArr()
    {
        $arr = array(
            array(1, 1, 1, 1, 0, 0, 1, 1),
            array(1, 0, 1, 1, 1, 0, 0, 1),
            array(0, 1, 1, 1, 0, 1, 1, 0),
            array(1, 1, 1, 0, 0, 1, 1, 0),

        );
        for ($i = 0; $i < count($arr); $i++) {
            for ($j = 0; $j < 8; $j++) {
                for ($k = 1; $k < count($arr); $k++) {
                    if ($arr[$i][$j] == 1 && $arr[$i + $k][$j] == 1) {
                        echo '1' . "<br>";
                    } else {
                        echo '0' . "<br>";
                    }
                }


            }
        }
    }

    /* public function json() {
         $data = [
             'aa' => '12',
             'bb' => '123'
         ];
         echo json_encode($data);
     }*/

    public function jsom()
    {
        $res = $this->json();
        print_r($res);
    }

    public function merge()
    {
        $arr = array(
            'DATA' => array(
                'REGISTERQUERY' => 25,
                'FEEQUERY' => 26,
                'NEWPATQUERY' => 27
            ),
            'DATASTATISTICS' => array(
                'NEWPATSTATISTICS' => '33',
                'FEESTATISTICS' => '32',
                'REGISTERSTATISTICS' => '31',
                'DATASTATISTICS' => '29',
            )
        );

      $arr['DATA'] = array_merge($arr['DATA'],$arr['DATASTATISTICS']);
      unset($arr['DATASTATISTICS']);
      echo "<pre>";
      print_r($arr);

    }

}