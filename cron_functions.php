<?php



function toCloser($n,$x=5) {
    $j = (round($n)%$x === 0) ? round($n) : (round(($n+$x/2)/$x)*$x);
    $r_num = ($j-$n) >= round($x/2) ? ($j-$x) : $j;
	if($r_num=='60'){
	$r_num = $r_num-$x;
	}
	return $r_num;
}


class execute_cronjobs {
        private $func_response;
        private $my_domain;
        private $myDomain;
        private $startMinute;

          public function __construct () {
                    $my_domain = $_SERVER['HTTP_HOST'];
                    if(!$my_domain){ $my_domain = $_SERVER['SERVER_NAME']; }
            $this->myDomain = "https://".$my_domain."/"; // current domain name
            $this->startMinute = (int)date("i"); // current minutes
          }

            public function getArrayRunableTimes(){
                $cron_cycle='5'; // how often this class is accessed in minutes

                        $current_minutes = $this->startMinute; 
                        $current_minutes = (int)round(($current_minutes % $cycle === 0) ? $current_minutes : toCloser($current_minutes, $cron_cycle)); 

                $runable=array();
                $five_min_array=array('0','5','10','15','20','25','30','35','40','45','50','55');
                if (in_array($current_minutes, $five_min_array)) {
                $runable[]='5';
                }
                $ten_min_array=array('0','10','20','30','40','50');
                if (in_array($current_minutes, $ten_min_array)) {
                $runable[]='10';
                }
                $fifteen_min_array=array('0','15','30','45');
                if (in_array($current_minutes, $fifteen_min_array)) {
                $runable[]='15';
                }
                $twenty_min_array=array('0','20','40');
                if (in_array($current_minutes, $twenty_min_array)) {
                $runable[]='20';
                }
                $thirty_min_array=array('0','30');
                if (in_array($current_minutes, $thirty_min_array)) {
                $runable[]='30';
                }
                $hour_array=array('0');
                if (in_array($current_minutes, $hour_array)) {
                $runable[]='0';
                }

            return $runable;
        }

            public function run($file,$run_minutes) {
                $this->setResponse('');
                $runable = $this->getArrayRunableTimes();
                if (in_array($run_minutes, $runable)) {
                    $url = $this->myDomain.$file;
                        $ch = curl_init();
                        curl_setopt_array($ch,array(CURLOPT_URL =>$url,CURLOPT_RETURNTRANSFER =>true,CURLOPT_VERBOSE =>true,CURLOPT_SSL_VERIFYPEER =>false,CURLOPT_SSL_VERIFYHOST =>2));
                        $response = curl_exec($ch);
                            if ($response === false) {
                                $response=curl_error($ch);
                                $this->setResponse('error');
                            }else{
                                $this->setResponse('success');
                            }
                        curl_close($ch);
                }
                return $response;
            }

            public function exec($file,$run_minutes) {
                    $this->run($file,$run_minutes);
                    if($this->getResponse()){
                        $output = $this->getResponse().' running: '.$this->myDomain.$file.'<br>';
                        echo $output;
                    }
            }


            public function setResponse($func_response) { 
                $this->func_response = $func_response; 
            }
            public function getResponse() { 
                return $this->func_response; 
            }


} // EOF class execute_cronjobs

	
?>