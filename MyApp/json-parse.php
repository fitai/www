<?php

function isJSON($string){
   return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}

//Mean, Median, Mode, Range function
function mmmr($array, $output = 'mean'){ 
    if(!is_array($array)){ 
        return FALSE; 
    }else{ 
        switch($output){ 
            case 'mean': 
                $count = count($array); 
                $sum = array_sum($array); 
                $total = $sum / $count; 
            break; 
            case 'median': 
                rsort($array); 
                $middle = round(count($array) / 2); 
                $total = $array[$middle-1]; 
            break; 
            case 'mode': 
                $v = array_count_values($array); 
                arsort($v); 
                foreach($v as $k => $v){$total = $k; break;} 
            break; 
            case 'range': 
                sort($array); 
                $sml = $array[0]; 
                rsort($array); 
                $lrg = $array[0]; 
                $total = $lrg - $sml; 
            break; 
        } 
        return $total; 
    } 
} 

$msg = '{ "header": { "u_id": 1, "lift_id": 1}, "content": {"v_rms": [0, 1, 2, 3, 4], "p_rms": [5, 6, 7, 8, 9] } }';	
$sendMsg = "no";	

if (isJSON($msg)) {
			//Turn JSON message into array
			$data = json_decode($msg, true);
			$sendMsg = "";
			
			//Velocity Calculations
			$vArray = $data['content']['v_rms'];
			$velocity = mmmr($vArray, 'mean');

			//Power Calculations
			$pArray = $data['content']['p_rms'];
			$power = mmmr($pArray, 'mean');
			$sendMsg = sprintf('Velocity Avg: %s<br>Power Avg: %s<br>', $velocity, $power);
}
echo $sendMsg;
?>