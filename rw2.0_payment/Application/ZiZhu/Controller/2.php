<?php  
	try{
		$pdo = new PDO('mysql:host=172.18.1.23;dbname=yq_adrr;charset=utf8','root','trans');
		//定义sql
		$sql = 'select * from district where  upid=?';

		$stmt=$pdo->prepare($sql);

		//执行绑定参数
		$stmt->execute(array($_POST['upid']));

		//获取结果
		$res = $stmt->fetchAll(PDO::FETCH_ASSOC);

		echo json_encode($res);
	}catch(PDOException $e){
		echo $e->getMessage();
	}

	
?>