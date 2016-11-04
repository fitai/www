<?php
namespace MyApp;
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

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

function isJSON($string){
   return is_string($string) && is_array(json_decode($string, true)) && (json_last_error() == JSON_ERROR_NONE) ? true : false;
}

class Chat implements MessageComponentInterface {
    protected $clients;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }
	
	// custom function called elsewhere in this class whenever you want to close the server.
    protected function close() {
        call_user_func($this->clients);
    }

    public function onOpen(ConnectionInterface $conn) {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);

        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
		if (isJSON($msg)) {
			//Turn JSON message into array
			$data = json_decode($msg, true);
			$sendMsg = "";
			
			//Rep Counter
			$repCount = $data['rep_count'];
			if ($repCount == null)
				$repCount = 0;
			
			//Velocity Calculations
			$vArray = $data['content']['v_rms'];
			$velocity = mmmr($vArray, 'mean');

			//Power Calculations
			$pArray = $data['content']['p_rms'];
			$power = mmmr($pArray, 'mean');
			//$sendMsg = sprintf('Velocity Avg: %s<br>Power Avg: %s<br>', $velocity, $power);
			
			//Active State
			$active = $data['header']['active'];
			if ($active == false) :
				$activeMsg = "Off";
			elseif ($active == true) :
				$activeMsg = "On";	
			endif;
			
			//Athlete ID
			$athleteID = $data['header']['athlete_id'];
			
			//Build Array to send to listener
			$sendMsg = array("velocity"=>$velocity, "power"=>$power, "repCount"=>$repCount, "active"=>$activeMsg, "athleteID"=>$athleteID);
			$sendMsg = json_encode($sendMsg);
		} else
			$sendMsg = $msg;
		
        $numRecv = count($this->clients) - 1;
        echo sprintf('Test: Connection %d sending message "%s" to %d other connection%s' . "\n"
            , $from->resourceId, $sendMsg, $numRecv, $numRecv == 1 ? '' : 's');

        foreach ($this->clients as $client) {
            if ($from !== $client) {
                // The sender is not the receiver, send to each client connected
				
                $client->send($sendMsg);
            }
        }
    }

    public function onClose(ConnectionInterface $conn) {
        // The connection is closed, remove it, as we can no longer send it messages
        $this->clients->detach($conn);

        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";

        $conn->close();
    }

}

