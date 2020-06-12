<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('admin_paging'))
{
	function paging($page,$num_items,$maxitem,$attrib="",$pre='<div class="text-center">',$post='</div>')
	{
		if($attrib==""){
			$attrib["href"] = "#";
		}
		echo $pre;
		($page<=1?$page=1:false);
		$limit = ($page*$maxitem)-$maxitem;
 		if(($num_items%$maxitem)==0){
			$lastpage=($num_items/$maxitem);
		}else{
			$lastpage=($num_items/$maxitem)-(($num_items%$maxitem)/$maxitem)+1;
		}
		$i = 0;
		if(is_array($attrib)){
			foreach ($attrib as $prop => $value) {
				if($i==0){
					$attr = $prop.'="'.$value.'"';
				}else{
					$attr .=" ".$prop.'="'.$value.'"';
				}
				$i++;
			}
		}else{
			$attr = "";
		}
		$maxpage = 3;
		echo '
		<ul class="pagination prints">
		';
		$cnt=0;
		if($page>1){
			$back=$page-1;
			echo '<li><a '.$attr.' id="1">&laquo;&laquo;</a></li>';	
			echo '<li><a '.$attr.' id="'.$back.'">&laquo;</a></li>';	
			for($i=($page-$maxpage);$i<$page;$i++){
				if($i>0){
					echo "<li><a $attr id='$i'>$i</a></li>";	
				}
				$cnt++;
				if($cnt==$maxpage){
					break;
				}
			}
		}
		
		$cnt=0;
		for($i=$page;$i<=$lastpage;$i++){
			$cnt++;
			if($i==$page){
				echo '<li class="active"><a>'.$i.'</a></li>';	
			}else{
				echo '<li><a '.$attr.' id="'.$i.'">'.$i.'</a></li>';	
			}
			if($cnt==$maxpage){
				break;
			}
		}
		
		$cnt=0;
		for($i=($page+$maxpage);$i<=$lastpage;$i++){
			$cnt++;
			echo '<li><a '.$attr.' id="'.$i.'">'.$i.'</a></li>';	
			if($cnt==$maxpage){
				break;
			}
		}
		if($page!=$lastpage&&$num_items>0){
			$next=$page+1;
			echo '<li><a '.$attr.' id="'.$next.'">&raquo;</a></li>';
			echo '<li><a '.$attr.' id="'.$lastpage.'">&raquo;&raquo;</a></li>';
		}
		echo "</ul>";

		echo $post;	
		# code...
	}

	function sms_status($server_response)
	{
		if($server_response['status'] == "ok"){
			return "Success! Message is now on queue and will be sent soon.";
		}else{
			return $server_response['errors'][0];
		}
	}

	function send_sms($mobile_number='',$message='',$apicode='', $send_request='')
	{
		// if(ENVIRONMENT=='development'){
		// 	return 0;
		// }
		$data["number"] =  $mobile_number;
		$data["message"] =  $message;
		$data["apicode"] =  $apicode;
		$data = http_build_query($data);
		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_URL => $send_request,
			CURLOPT_RETURNTRANSFER => TRUE,
			CURLOPT_SSL_VERIFYPEER => FALSE,
			CURLOPT_POSTFIELDS => $data,
		));
		$result = curl_exec($curl);
		$statusCode = curl_getinfo($curl);
		if($result == null){
			return [
				'status_code' => "error",
				'errors' => [
					"General Error"
				]
			];
		}
		$result = json_decode($result, true);
		return $result;
		curl_close($curl);
		if($result['status']=="ok"){
			$result['status_code'] = 0;
		}else{
			$result['status_code'] = 1;
		}
		return $result;
	}

	function age($birthdate)
	{
		$birthdate = date("m/d/Y",$birthdate);
		$birthdate = explode("/", $birthdate);
		$age = (date("md", date("U", mktime(0, 0, 0, $birthdate[0], $birthdate[1], $birthdate[2]))) > date("md")
			? ((date("Y") - $birthdate[2]) - 1)
			: (date("Y") - $birthdate[2]));
		return $age;
	}
}