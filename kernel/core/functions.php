<?php 

   #
      # FUNCTIONS FOR USER
   #

      class FUN {

         /**
            *  
            *  redirection with header
            *  @var location -> url or path to redirect
            *  
         **/
         public function header( $location ) {

         	// check send to Login Page
         	if( strpos($location, "login") !== false ) { $_SESSION['gologout'] = $this->url(); }

         	// redirection
            $send_header = header("Location: ".$location."");
            return $send_header; exit;

         }

         /**
            *  
            *  get user login or visit
            *  @var action -> no = dont need session | on = need session user 
            *  @var option -> null = no other action | re = method refresh HTML
            *  
         **/
         public function needLogin( $action = "yes", $option = null ) {

            global $UserID;
            $instance = new Users();
            $user = $instance->session_exist();
            $ins = new FUN;

            // check userID is != 0
            if( $user === true ) { $login = 1; } else { $login = 0; }
            if( $action == 'no' AND $login == 1 ) {

               $actUrl = URL."/inicio";
               if($option == null) { $ins->header($actUrl); } else { $ins->header($option); }

            } elseif( $action == 'yes' AND $login == 0 ) {

               $actUrl = URL."/login";
               if( $option == null ) { $ins->header($actUrl); } else { $ins->header($option); }

            } elseif( $action == "unlock" ) {

               $actUrl = URL."/login";
               // need account unlocked and session
               if( $UserID >= 1 ) {

                  $accL = $instance->user($UserID, "statusd");
                  if( $accL >= 2 ) { $this->header(URL."/lockscreen"); }

               } else { $this->header($actUrl); }

            }

         }

         /**
            *  
            *  execute function JS with time interval 
            *  @var function -> name function jquery or javascrip
            *  @var time -> time in seconds #default 0s
            *  
         **/
         public function updateJS( $function = null, $time = 0 ) {

            // create script JS
            $updateJS = ' <script> setTimeout( function( u ) { '.$function.' }, '.$time.' ); </script> ';
            return $updateJS;

         }

         /**
            *  
            *  generate rand keygen 
            *  @var limit -> max lenght to keygen
            *  
         **/
         public function key( $kid = 'CR', $limit = 7 ) {

            // divide limit longer key
            $limit = $limit - 2;  // delete 2 letter for the first key (CR)
            $limit = $limit / 2;  // divide in 2 for the two keys (letters and numbers)

            // first key init
            $keygen_ini   = $kid;
            $init_keygen  = 'ABCDEFGHIJKLMNOPQRSTUVXWZ';
            $max_init = strlen($init_keygen) - 1;

            // generate first key letters
            for($i = 0; $i < $limit; $i ++) {

               $keygen_ini .= $init_keygen[mt_rand(0, $max_init)];

            }

            $keygen   = $keygen_ini;
            $pattern  = '1234567890';
            $max_sec  = strlen($pattern) - 1;

            // generate second key numbers
            for($i = 0; $i < $limit; $i ++) {

               $keygen .= $pattern[mt_rand(0, $max_sec)];

            }

            return $keygen;

         }

         /**
            *  
            *  extract initials letters in differents chains
            *  @var char -> chain text separete for space ### EX: a b c
            *  
         **/
         public function initals($char) {

            // separe vars for spaces
            $char = explode(' ', $char);
            // count vars 
            $count  = count($char);
            // default vars
            $init = 0;

            // generate vars individual
            for($i = 1; $i < $count; $i ++) {

               $init .= substr($char[$i], 0, 1);

            }

            return $init;

         }

         /**
            *  
            *  reset all inputs inside form
            *  @var div -> is the id o class the form  ### EX:   .div  or  #div
            *  
         **/
         public function clear_form($div) {

            $clear = ' 
               <script>

                  $(document).ready(function() {

                     $("'.$div.'")[0].reset();

                  });

               </script>
            ';

            return $clear;

         }

         /**
            *  
            *  control to the time, add or subtract time in seconds
            *  @var timenow -> time from wich the operation will be made  ### EX:  timenow   or   1997-03-17
            *  @var opp    -> type of operation, default is + (add time)     ### EX:  + or - (to subtract)
            *  @var type   -> type time, for conver in seconds                ### EX:  minutes or hours or days
            *  @var time -  > te value to the time, depend of type             ### EX:  integer numbers (20 or 100)
            *  @var format -> format to print time final, default is no hours   ### EX:  SH (print H:i:s) or NH (dont print H:i:s)
            *  
         **/
         public function control_time( $timenow = null, $opp = '+', $type = "seconds", $time = 1, $format = 'NH') {

            // defined type control 
            if($opp != '+') {

               // declared operation of subtraction
               $opp = '-';

            }

            // if type is different to seconds -> convert in seconds
            if($type == 'minutes') {

               $seconds = $time * 60;

            } elseif($type == 'hours') {

               $seconds = $time * 60 * 60;

            } elseif($type == 'days') {

               $seconds = $time * 24 * 60 * 60;

            } else {

               $seconds = $time;

            }

            // realize operation
            $timesecond = strtotime(''.$opp.' '.$seconds.' seconds', strtotime($timenow));

            // convert format
            if($format == 'NH') {

               // no hours : minutes : seconds
               $timesecond = date('Y-m-d', $timesecond);

            } elseif($format == 'SH') {

               // add hours : minutes : seconds
               $timesecond = date('Y-m-d H:i:s', $timesecond);

            } else {

               // add hours : minutes : seconds and print hours
               $timesecond = date('H:i', $timesecond);

            }

            return $timesecond;

         }

         /**
            *  
            *  get real IP to user client or user visit
            *  
         **/
         public function ip() {

            // ip default is null
            $ipaddress = null;

            if(getenv('HTTP_CLIENT_IP')) {

               $ipaddress = getenv('HTTP_CLIENT_IP');

            } elseif(getenv('HTTP_X_FORWARDED_FOR')) {

               $ipaddress = getenv('HTTP_X_FORWARDED_FOR');

            } elseif(getenv('HTTP_X_FORWARDED')) {

               $ipaddress = getenv('HTTP_X_FORWARDED');

            } elseif(getenv('HTTP_FORWARDED_FOR')) {

               $ipaddress = getenv('HTTP_FORWARDED_FOR');

            } elseif(getenv('HTTP_FORWARDED')) {

               $ipaddress = getenv('HTTP_FORWARDED');

            } elseif(getenv('REMOTE_ADDR')) {

               $ipaddress = getenv('REMOTE_ADDR');

            } else {

               // ip is unknow
               $ipaddress = '?';

            }

            // if IP localhost (?)
            if($ipaddress == '::1') {

               $ipaddress = '127.0.0.1';

            }

            // return real IP
            return $ipaddress;

         }

         /**
            *  
            *  get data to IP now, for GeoPlugin Free
            *  @var ip     -> get IP mediant class function ip() 
            *  @var data   -> data return, default is IP                ### EX: country  state   region  city   timezone   address   default   money
            *  
         **/
         public function dataip( $ip = null, $data = "default" ) {

            // run get IP function
            $instance = new FUN;

            // detect other IP
            if($ip != null) {

               $ip = $ip;

            } else {

               $ip = $instance->ip();

            }

            // get info of geoplugin
            $purpose = $data; 
            $deep_detect  = true;
            $output  = null;

            if(filter_var($ip, FILTER_VALIDATE_IP) === FALSE) {

               $ip = $ip;
               if( $deep_detect ) {

                  if(filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP)) {

                     $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

                  }

                  if(filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP)) {

                     $ip = $_SERVER['HTTP_CLIENT_IP'];

                  }

               }

            }

            // clean data get return
            $purpose  = str_replace(array("name", "\n", "\t", " ", "-", "_"), null, strtolower(trim($purpose)));
            // define data return support for the plugin
            $support  = array("country", "countrycode", "state", "region", "city", "timezone", "address", "default", "money");
            // change continent code for continent name
            $continents = array(
               "AF" => "África",
               "AN" => "Antártida",
               "AS" => "Asia",
               "EU" => "Europa",
               "OC" => "Oceanía",
               "SA" => "América Sur",
               "NA" => "América Norte",
            );

            // filter IP and support data
            if(filter_var($ip, FILTER_VALIDATE_IP) && in_array($purpose, $support)) {

               // get data from API GeoPlugin
               $ipdat = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=" . $ip));

               if(@strlen(trim($ipdat->geoplugin_countryCode)) == 2) {

                  // get data return = support vars
                  switch($purpose) {

                     case "timezone":

                        $output = @$ipdat->geoplugin_timezone;
                        break;

                     case "money": 

                        $output = @$ipdat->geoplugin_currencyCode;
                        break;

                     case "address":

                        $address = array($ipdat->geoplugin_countryName);

                        if(@strlen($ipdat->geoplugin_regionName) >= 1) {

                           $address[] = $ipdat->geoplugin_regionName;

                        }

                        if(@strlen($ipdat->geoplugin_city) >= 1) {

                           $address[] = $ipdat->geoplugin_city;

                        }

                        $output = implode(", ", array_reverse($address));
                        break;

                     case "city":

                        $output = @$ipdat->geoplugin_city;
                        break;

                     case "state":

                        $output = @$ipdat->geoplugin_regionName;
                        break;

                     case "region":

                        $output = @$ipdat->geoplugin_regionName;
                        break;

                     case "country":

                        $output = @$ipdat->geoplugin_countryName;
                        break;

                     case "countrycode":

                        $output = @$ipdat->geoplugin_countryCode;
                        break;

                     case "lat":

                        $output = @$ipdat->geoplugin_latitude;
                        break;

                     case "lon":

                        $output = @$ipdat->geoplugin_longitude;
                        break;

                     case "default": 

                        $output = $ip;
                        break;

                  }

               }

            }

            return $output;

         }

         /**
            *  
            *  detect user agent plataform client
            *  @var so     -> if is null, return user agent if is different null return S.O     ### EX:  navegator('SO') = 'Windows X'
            *  
         **/
         public function navegator($so = null) {

            // get user agent client
            $nav = $_SERVER['HTTP_USER_AGENT'];
            $plataform = $nav;

               if($so != null) {

                  $plataforms = array(

                  '/windows nt 10/i'    =>  'Windows 10',
                  '/windows nt 6.3/i'   =>  'Windows 8.1',
                  '/windows nt 6.2/i'   =>  'Windows 8',
                  '/windows nt 6.1/i'   =>  'Windows 7',
                  '/windows nt 6.0/i'   =>  'Windows Vista',
                  '/windows nt 5.2/i'   =>  'Windows Server 2003/XP x64',
                  '/windows nt 5.1/i'   =>  'Windows XP',
                  '/windows xp/i'  =>  'Windows XP',
                  '/windows nt 5.0/i'   =>  'Windows 2000',
                  '/windows me/i'  =>  'Windows ME',
                  '/win98/i'   =>  'Windows 98',
                  '/win95/i'   =>  'Windows 95',
                  '/win16/i'   =>  'Windows 3.11',
                  '/macintosh|mac os x/i' =>  'Mac OS X',
                  '/mac_powerpc/i' =>  'Mac OS 9',
                  '/linux/i'   =>  'Linux',
                  '/ubuntu/i'  =>  'Ubuntu',
                  '/iphone/i'  =>  'iPhone',
                  '/ipod/i'    =>  'iPod',
                  '/ipad/i'    =>  'iPad',
                  '/android/i' =>  'Android',
                  '/blackberry/i'  =>  'BlackBerry',
                  '/webos/i'   =>  'Mobile'

               );

               foreach($plataforms as $plataform => $pattern) {

                  if(preg_match($plataform, $nav)) {

                     return $pattern;

                  } else {

                     return 'Plataforma desconocida';

                  }

               }

            }

            return $plataform;

         }

         /**
            *  
            *  encryption of any char
            *  @var cripter     -> text to encripter
            *  
         **/
         public function encripter($cripter = "null") {

            $special  = htmlspecialchars($cripter);
            $base = base64_encode($special);
            $entities = htmlentities($base);
            $md  = md5($entities);
            $sha = sha1($md);
            $asl = addslashes($sha);
            $convert  = convert_uuencode($asl);
            // return the chain cript
            return $convert;

         }

         /**
            *  
            *  refresh website or redirection with meta HTML
            *  @var seconds     -> time in seconds to execute meta      # EX: null = 0s 
            *  @var url         -> url or path to redirect              # EX: null = refresh
            *  
         **/
         public function refresh($seconds = 3, $url = null) {

            // default time to execute
            if($seconds == null) { $seconds = 0; } else { $seconds = $seconds * 1000; }
            // refresh or redirection (?)
            $ree = '<script> url = "'.$url.'"; setTimeout(function() { $(location).attr(\'href\', url); }, '.$seconds.'); </script>';
            return $ree;

         }

         /**
            *  
            *  send mail web
            *  @var to        -> email to send mail 
            *  @var name      -> name of the email to 
            *  @var subject   -> subject or title mail
            *  @var message   -> message content mail
            *  
         **/
         public function mailer( $to = null, $name = null, $subject = null, $message = null, $mailes = null) {
            
            // begin Class PHPMailer
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            // text charset
            $mail->CharSet = 'UTF-8';
            // set mailer to use SMTP
            $mail->IsSMTP();
            // specify main and backup server
            $mail->Host = "mail.pin-credit.com";
            // turn on SMTP authentication
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = 'ssl';
            // specify main and backup server
            $mail->Port = 465;
            // SMTP username
            $mail->Username = "solicitudes@pin-credit.com";
            // SMTP password
            $mail->Password = 'PinCredit$100';
            // mail "from"   -> contact@mydomain.com
            $mail->From = "solicitudes@pin-credit.com";
            // title "Asunto"   -> "Title content"          
            $mail->FromName = TITLE." ".SLOGAN;
            $mail->AddAddress($to, $name);
            /** detect copy CC **/
            if( $mailes != null ) {

               $explode = explode(',', $mailes);
               $countex = count($explode);
               if($countex >= 1) { for($i = 0; $i < $countex; $i ++) { $mail->addCC($explode[$i]); } }

            }
            // set word wrap to 50 characters
            $mail->WordWrap = 50;
            // set email format to HTML
            $mail->IsHTML(true);
            $mail->Subject = $subject;
            $mail->Body  = $message;
            $mail->SMTPDebug  = 0;
            /** return response **/
            if(!$mail->Send()) { return false; } else { return true; }

         }

         /**
            *  
            *  prepare html message for mail send
            *  @var h2                -> title (h2) 
            *  @var message           -> content message (p)
            *  @var href              -> url for the (a)
            *  @var a                 -> label to (a)
            *  
         **/
         public function mail_message($h1, $message) {

            // change span datas
            $message = str_replace('class="h1EMAIL"', 'class="h1EMAIL" style="font-size: 12px !important; padding: 05px 0px;display: block;position: relative;text-transform: uppercase;padding-bottom: 4px !important; text-align: center; margin: 4px auto;"', $message);
            $message = str_replace('<span>', '<span style="font-size: 14px;line-height: 22px;display: block;margin: 10px auto;position: relative;color: rgba( 21, 21, 21, 0.8 );">', $message);
            $message = str_replace('class="button"', 'class="button" style="text-decoration: none;position: relative;display: block;margin: 20px auto;text-align: center;background: rgba( 30, 30, 30, 1 );text-decoration: none;color: rgba( 255, 255, 255, 1 );font-weight: bold;text-transform: uppercase;cursor: pointer;padding: 14px 0px; margin-bottom: 0px; border-radius: 2px"', $message);
            $message = str_replace('<a class="aEmail"', '<a class="aEmail" style="color: rgba( 21, 21, 21, 1 )"', $message);
            $message = str_replace('class="inc"', 'style="background: rgba( 100, 31, 96, 1 ); color: white; padding: 3px 4px !important; border-radius: 2px !important;"', $message);
            $message = str_replace('class="smallText"', 'style="font-size: 13px !important; color: rgba( 100, 31, 96, 1 ); margin: 10px auto !important; display: block;"', $message);

            $logo = "";
            $logo = ''.URL.'/img/logos/logo-medium.png';

            $message = '

               <!DOCTYPE html>
               <html>

                  <head> 

                  	<meta charset="utf-8">
                  	<style> .button:hover { background: #dd003e !important; } </style>

                  </head>
   
                  <body style="font-family: Helvetica, Arial, sans-serif">
               
                     <div class="warper" style="max-width: 628px;margin: 12px auto;position: relative;padding: 10px 20px;background: rgba( 239, 238, 237, 1 );">

                        <header style="padding: 20px 0px;position: relative;text-align: center;">

                           <div class="logo" style="background: transparent;padding: 10px 0px;cursor: pointer;background: #ed1169;position: relative;margin: 0px 0px;display: block;"> 

                              <a href="'.URL.'/inicio" style="text-decoration: none; "> <img src="'.$logo.'" alt="Logo - '.TITLE.'" style="margin: 0px auto;display: block; padding: 10px 0px;"> </a> 

                           </div>

                           <div class="headBox" style="background: rgba( 255, 255, 255, 1 ); padding: 10px 0px; padding-top: 20px;">

                              <div class="content" style="padding: 18px 10px;text-align: left;display: block;">

                                 <div class="box" style="position: relative;display: block; max-width: 90%;margin: 0px auto;">

                                    <h1 style="color: rgba( 255, 255, 255, 1 );font-size: 32px; text-align: center; padding: 20px 0px; font-size: 18px;padding: 10px 0px; padding-top: 5px; color: rgba( 21, 21, 21, 1 ) !important;">'.$h1.'</h1>
                                    <p style="margin: 15px auto; display: block; text-align: center;">'.$message.'</p>

                                 </div>

                              </div>

                           </div>

                        </header>

                        <footer style="background: transparent;padding: 5px 0px;margin: 5px auto;position: relative;display: block;max-width: 60%;text-align: center;">

                           <div class="f1" style="max-width: 98%;position: relative;display: block;margin: 0px auto;color: rgba( 85, 85, 85, 1 );font-size: 11px;">

                           	Todos los derechos reservados a <b style="color: #ed1169">&copy;'.TITLE.'</b> '.date('Y').'.
                           	
                           	<br> 
                           	
                           	<small style="padding-top: 6px; display: block;">

                           		<a style="color: #ed1169;" href="'.URL.'/oops/suscripcion" title="Cancelar Suscripción">Cancelar Suscripción</a> 

                           	</small>

                           </div>

                        </footer>

                     </div>

                  </body>

               </html>

            ';

            return $message;

         }

         /**
            *  
            *  get time server in different formats
            *  @var format     -> format return type time     #EX: 'datetime' | 'date' | 'time'
            *  @var nosecs     -> null = show H:i:s  || 'no' = show H:i
            *  
         **/
         public function time($format, $nosecs = null) {

            $time_server  = getdate();

            $Y   = $time_server['year'];
            $m   = $time_server['mon'];
            $d   = $time_server['mday'];
            $H   = $time_server['hours'];
            $i   = $time_server['minutes'];
            $s   = $time_server['seconds'];

            if($i <= 9) { $i = '0'.$i; } else { $i = $i; }
            if($m <= 9) { $m = '0'.$m; } else { $m = $m; }

            if($format == 'datetime') {

               // return date time
               $time = date('Y-m-d H:i:s');

            } elseif($format == 'date') {

               // return date
               $time = date('Y-m-d');

            } elseif($format == 'time') {

               // return time hours
               if($nosecs != null) { $time = date('H:i'); } else { $time = date('H:i:s'); }

            } else {

               // default
               $time = date('Y-m-d');

            }

            return $time;

         }

         /**
            *  
            *  get time server in different formats
            *  @var table     -> name to tabla in DB
            *  @var id        -> id unique for the search
            *  @var return    -> data return - name column (value)
            *  
         **/
         public function table($table, $id, $return, $from = '*') {

            $instance = new StudiosBIT;
            $data = $instance->query("SELECT $from FROM $table WHERE id = :id1");
            $instance->bind('id1', $id);
            $instance->execute();
            $dd = $instance->single();
            if($instance->rowCount() >= 1) { $d  = $dd[$return]; } else { $d = null; }
            return $d;

         }

         /**
            *  
            *  convert date in speak spanish
            *  @var time     -> time in format 'date'
            *  
         **/
         public function date_speak($time, $return = null) {

            global $keyLANG;
            $datetime = strtotime($time);

            if($return == null) { $new_date = date('d F, Y', $datetime); } else { $new_date = date($return, $datetime); }

            $dias  = array("Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado");
            $meses = array("de Enero", "de Febrero", "de Marzo", "de Abril", "de Mayo", "de Junio", "de Julio", "de Agosto", "de Septiembre", "de Octubre", "de Noviembre", "de Diciembre");

            $days  = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");
            $months  = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

            if($keyLANG == 'es') {

               $new_date = str_replace($days, $dias, $new_date);
               $new_date = str_replace($months, $meses, $new_date);

            }

            // change lang "de" -> "of"
            if($return != null) { $new_date = str_replace('de ', '', $new_date); }
            return $new_date;

         }

         /**
            *  
            *  convert datetime in speak spanish
            *  @var date     -> datetime in format 'datetime'
            *  
         **/
         public function time_speak($date, $horas = null, $timeF = null) {

            // default in datetime
            $format_default = "Y-m-d H:i:s";

            if(stristr($date, '-') || stristr($date, ':') || stristr($date, '.') || stristr($date, ', ')) {

               if(stristr($date, '[')) {

                  $explorer_date    =   explode('[', $date);
                  $date    =   trim($explorer_date[0]);
                  $format  =   str_replace(']', '', $explorer_date[1]);

               } else {

                  $format = $format_default;

               }

               $date = str_replace("-", " ", $date);
               $date = str_replace(":", " ", $date);
               $date = str_replace(".", " ", $date);
               $date = str_replace(",", " ", $date);

               $number = explode(" ", $date);

               $format = str_replace("-"," ", $format);
               $format = str_replace(":"," ", $format);
               $format = str_replace("."," ", $format);
               $format = str_replace(","," ", $format);

               $format = str_replace("d", "j", $format);
               $format = str_replace("m", "n", $format);
               $format = str_replace("G", "H", $format);

               $letter = explode(" ", $format);

               $relation[$letter[0]] = $number[0];
               $relation[$letter[1]] = $number[1];
               $relation[$letter[2]] = $number[2];
               $relation[$letter[3]] = $number[3];
               $relation[$letter[4]] = $number[4];
               $relation[$letter[5]] = $number[5];

               $date = mktime($relation['H'], $relation['i'], $relation['s'], $relation['n'], $relation['j'], $relation['Y']);

            }

            $timenow = time();
            if( $timeF != null ) { $timenow = strtotime($timeF); }
            $ht = $timenow - $date;

            if( $horas != null ) {

               if($ht >= 3570) {

                  // datetime hours
                  $hc = round($ht / 3600);
                  if($hc > 1) { $s = "s"; }
                  $datetime = ''.$hc.' hr'.@$s;

               } if($ht < 3570) {

                  // datetime minutes
                  $hc = round($ht / 60);
                  if($hc > 1) { $s = "s"; }
                  $datetime = ''.$hc.' min'.@$s;

               } if($ht <= 60) {

                  // datetime seconds < 1 minute
                  $datetime = ''.$ht.' s';

               }

               if($ht <= 10) {

                  // datetime seconds < 10s is now
                  $datetime = "Ahora mismo";

               }

            } else {

               if($ht >= 2116800) {

                  // datetime past
                  $day = date('d', $date);
                  $month    = date('n', $date);
                  $year = date('Y', $date);
                  $hour = date('H', $date);
                  $minute   = date('i', $date);
                  $months_  = array('', 'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre');
                  $datetime = 'El '.$day.' de '.$months_[$month].' del '.$year.'';

               } if($ht < 30242054.045) {

                  // datetime month
                  $hc = round($ht / 2629743.83);
                  if($hc > 1) { $s = "es"; }
                  $datetime = 'Hace '.$hc.' mes'.@$s;

               } if($ht < 2116800) {

                  // datetime weakends
                  $hc = round($ht / 604800);
                  if($hc > 1) { $s = "s"; }
                  $datetime = 'Hace '.$hc.' semana'.@$s;

               } if($ht < 561600) {

                  // datetime days or yesterday
                  $hc = round($ht / 86400);
                  if($hc == 1) { $datetime = "Ayer"; } if($hc == 2) { $datetime = "Anteayer"; } if($hc > 2) { $datetime = 'Hace '.$hc.' días'; }

               } if($ht < 84600) {

                  // datetime hours
                  $hc = round($ht / 3600);
                  if($hc > 1) { $s = "s"; }
                  $datetime = 'Hace '.$hc.' hora'.@$s;
                  if($ht > 4200 && $ht < 5400) {  $datetime = 'Hace más de una hora'; }

               } if($ht < 3570) {

                  // datetime minutes
                  $hc = round($ht / 60);
                  if($hc > 1) { $s = "s"; }
                  $datetime = 'Hace '.$hc.' minuto'.@$s;

               } if($ht <= 60) {

                  // datetime seconds < 1 minute
                  $datetime = 'Hace '.$ht.' segundos';

               }

               if($ht <= 10) {

                  // datetime seconds < 10s is now
                  $datetime = "Ahora mismo";

               }

            }

            return $datetime;

         }

         /**
            *  
            *  get difference between two datetimes
            *  @var time           -> the first datetime
            *  @var timesecond     -> second datetime
            *  @var seconds        -> this defined if return speak time or in seconds    #EX:  's' -> return time in seconds "60"
            *  
         **/
         public function time_between($time, $timesecond, $seconds = null) {

         	$thetime = $time;
            if($seconds == null) {

               $start_date   = new DateTime($time);
               $since_start  = $start_date->diff(new DateTime($timesecond));

               $days = $since_start->days;
               $years    = $since_start->y;
               $months   = $since_start->m;
               $hours    = $since_start->h;
               $minutes  = $since_start->i;
               $seconds  = $since_start->s;

               if($days != '0') {

                  if($hours != '0') { $thetime = $days.' días'; } else { $thetime = $days.' días '; }

               } elseif($hours != '0') {

                  $thetime = $hours.' hrs '.$minutes.' mins';

               } elseif($minutes != '0') {

                  $thetime = $minutes.' mins';

               } elseif($seconds != '0') {

                  $thetime = $seconds.' s';

               }

            } else {

               $thetime = strtotime($timesecond) - strtotime($time);

            }

            return $thetime;

         }
         
         /**
            *  
            *  change number in formato decimals (limit 2)
            *  @var value           -> get the number value
            *  
         **/
         public function money($value = 0, $nozeros = null) {

         	if( $value == null OR is_numeric($value) == false ) { $value = 0; } 
            $money = @number_format($value, '2', '.', ',');
            if($money == null) { $money = '0.00'; }
            if($nozeros != null) { $money = number_format($value, '0', '.', ','); }

            return $money;

         }

         /**
            *  
            *  use special cripter (and decripte) for strings secrets
            *  @var string           -> the chain use (letter, numbers, etc)
            *  @var convert          -> define if cripte or decripte the string         #EX: 'cripte' return cripter string  |  'decripte' return decripter string
            *  
         **/
         public function secret($string, $convert = 'cripte') {

            // define default key primary
            $key = 'sb_c';
            $result = '';

            if($convert == 'cripte') {

               for($i = 0; $i < strlen($string); $i++) {

                  $char = substr($string, $i, 1);
                  $keychar  = substr($key, ($i % strlen($key)) -1, 1);
                  $char = chr(ord($char) + ord($keychar));
                  $result     .= $char;

               }

               $keygen = base64_encode($result);
               $keygen = str_replace('+', '', $keygen);

            } else {

               $string = base64_decode($string);
               for($i = 0; $i < strlen($string); $i++) {

                  $char = substr($string, $i, 1);
                  $keychar  = substr($key, ($i % strlen($key)) -1, 1);
                  $char = chr(ord($char) - ord($keychar));
                  $result     .= $char;

               }

               $keygen = $result;

            }

            return $keygen;

         }

         /**
            *  
            *  get url the page web
            *  @var mode           -> if null return URL complet | if
            *  @var http           -> define base url web                               #EX: 'http' | 'https'
            *  
         **/
         public function url($mode = null, $http = 'http://') {

         	// verified is HTTPS in domain
         	if( strpos(URL, "https") !== false ) { $http = "https://"; } else { $http = "http://"; }

            if( $mode == null ) {

               $host = $_SERVER["HTTP_HOST"];
               $url  = $_SERVER["REQUEST_URI"];
               $URLLI  = $http.$host.$url;

            } elseif( $mode == 'domain' ) {

               $url  = URL;
               $url = str_replace("http://", "", $url);
               $url = str_replace("https://", "", $url);
               $URLLI = $url;

            } elseif( $mode == 'hash' ) {

               $url  = $_SERVER['REQUEST_URI'];
               $URLLI  = $url;

            } elseif( $mode == 'hashL' ) {

               // get last hash
               $url  = $_SERVER['REQUEST_URI'];
               $eU = explode("/", $url);
               $cU = count($eU) - 1;
               $URLLI  = $eU[$cU];

            } else {

               $host = $_SERVER["HTTP_HOST"];
               $url  = $_SERVER["REQUEST_URI"];
               $URLLI  = $url;

            }

            return $URLLI;

         }

         /**
            *  
            *  get subfije of number for speak_numer
            *  @var xx           -> the subfije
            *  
         **/
         public function number_sub($xx) {

            $xx  = trim($xx);
            $xstrlen  = strlen($xx);

            if($xstrlen == 1 || $xstrlen == 2 || $xstrlen == 3) { $xsub = ""; }
            if($xstrlen == 4 || $xstrlen == 5 || $xstrlen == 6) { $xsub = "Mil"; }

            return $xsub;

         }

         /**
            *  
            *  convert the number "num" to speak Spanish
            *  @var xcifra           -> the num convert
            *  
         **/
         public function speak_number($xcifra) {

            // begins instances
            $instance = new FUN;

            $xarray = array(0 => "Cero",
               1 => "Un", "Dos", "Tres", "Cuatro", "Cinco", "Seis", "Siete", "Ocho", "Nueve", "Diez", "Once", "Doce", "Trece", "Catorce", "Quince", "Diesciseis", "Diescisiete", "Dieciocho", "Diecinueve", "Veinti", 
               30 => "Treinta", 40 => "Cuarenta", 50 => "Cincuenta", 60 => "Sesenta", 70 => "Setenta", 80 => "Ochenta", 90 => "Noventa",
               100 => "Ciento", 200 => "Doscientos", 300 => "Trescientos", 400 => "Cuatrocientos", 500 => "Quinientos", 600 => "Seiscientos", 700 => "Setecientos", 800 => "Ochocientos", 900 => "Novecientos"
            );

            $xcifra   = trim($xcifra);
            $xlength  = strlen($xcifra);
            $xpos_punto = strpos($xcifra, ".");
            $xaux_int = $xcifra;
            $xdecimales = "00";

            if(!($xpos_punto === false)) {

               if($xpos_punto == 0) {

                  $xcifra   = "0" . $xcifra;
                  $xpos_punto = strpos($xcifra, ".");

               }

               $xaux_int = substr($xcifra, 0, $xpos_punto);              // get int
               $xdecimales = substr($xcifra . "00", $xpos_punto + 1, 2);   // get decimals

            }

            $XAUX = str_pad($xaux_int, 18, " ", STR_PAD_LEFT);
            $xcadena  = "";

            for($xz = 0; $xz < 3; $xz ++) {

               $xaux = substr($XAUX, $xz * 6, 6);
               $xi  = 0;
               $xlimite  = 6; // star counter XI / 6 is the limit decimal
               $xexit    = true; // control to while

               while($xexit) {

                  if($xi == $xlimite) { break; }

                  $x3digitos  = ($xlimite - $xi) * - 1;                       // star 3 decimal / init in left
                  $xaux = substr($xaux, $x3digitos, abs($x3digitos));   // get centenaas

                  for($xy = 1; $xy < 4; $xy ++) { 

                     switch($xy) {

                        case 1: 

                           if(substr($xaux, 0, 3) < 100) { 

                              // if it is less than <99 nothing is done and it continues

                           } else {

                              $key = (int) substr($xaux, 0, 3);

                              if(TRUE === array_key_exists($key, $xarray)) {  

                                 $xseek  = $xarray[$key];
                                 $xsub = $instance->number_sub($xaux); 
                                 if(substr($xaux, 0, 3) == 100) { $xcadena = " " . $xcadena . " CIEN " . $xsub; } else { $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub; }
                                 $xy = 3; 

                              } else { 

                                 $key = (int) substr($xaux, 0, 1) * 100;
                                 $xseek    = $xarray[$key]; 
                                 $xcadena  = " " . $xcadena . " " . $xseek;

                              } 

                           } 

                           break;

                        case 2: 

                           if(substr($xaux, 1, 2) < 10) {

                              // if it is less 9 nothing is done

                           } else {

                              $key = (int) substr($xaux, 1, 2);

                              if(TRUE === array_key_exists($key, $xarray)) {

                                 $xseek  = $xarray[$key];
                                 $xsub = $instance->number_sub($xaux);
                                 if(substr($xaux, 1, 2) == 20) { $xcadena = " " . $xcadena . " VEINTE " . $xsub; } else { $xcadena = " " . $xcadena . " " . $xseek . " " . $xsub; }
                                 $xy = 3;

                              } else {

                                 $key  = (int) substr($xaux, 1, 1) * 10;
                                 $xseek  = $xarray[$key];
                                 if(20 == substr($xaux, 1, 1) * 10) { $xcadena = " " . $xcadena . " " . $xseek; } else { $xcadena = " " . $xcadena . " " . $xseek . " Y "; }

                              } 

                           } 

                           break;

                        case 3: 

                           if(substr($xaux, 2, 1) < 1) { 

                              // if the unit is zero (0) nothing is done

                           } else {

                              $key = (int) substr($xaux, 2, 1);
                              $xseek    = $xarray[$key]; 
                              $xsub = $instance->number_sub($xaux);
                              $xcadena  = " " . $xcadena . " " . $xseek . " " . $xsub;

                           } 

                           break;

                     } 

                  } 

                  $xi = $xi + 3;

               } 

               if(substr(trim($xcadena), -5, 5) == "ILLON") { 

                  // if it is MILLON or BILLION -> "de" is added
                  $xcadena.= " De";

               }

               if(substr(trim($xcadena), -7, 7) == "ILLONES") {

                  // if it is MILLIONS or BILLIONS -> "de" is added
                  $xcadena.= " De";

               }

               if(trim($xaux) != "") {

                  switch($xz) {

                     case 0:

                        if(trim(substr($XAUX, $xz * 6, 6)) == "1") { $xcadena.= "Un billon "; } else { $xcadena.= " Billones "; }
                        break;

                     case 1:

                        if(trim(substr($XAUX, $xz * 6, 6)) == "1") { $xcadena.= "Un millon "; } else { $xcadena.= " Millones "; }
                        break;

                     case 2:

                        // zero $
                        if($xcifra < 1) { $xcadena = ' Cero Pesos '.$xdecimales.'/100 M.N. '; }
                        // only $
                        if($xcifra >= 1 && $xcifra < 2) { $xcadena = ' Un Peso '.$xdecimales.'/100 M.N. '; }
                        // more $
                        if($xcifra >= 2) { $xcadena.= 'Pesos '.$xdecimales.'/100 M.N.'; }
                        break;
                  
                  } 

               } 

               // delete space in "Veinti"
               $xcadena = str_replace("Veinti ", "Veinti", $xcadena);
               // delete spaces
               $xcadena = str_replace("  ", " ", $xcadena);
               // detele double sub
               $xcadena = str_replace("Un Un", "Un", $xcadena);
               // delete double spaces                          
               $xcadena = str_replace("  ", " ", $xcadena);
               // the legends are corrected
               $xcadena = str_replace("Billon De Millones", "Billon De", $xcadena);
               $xcadena = str_replace("Billones De Millones", "Billones De", $xcadena);
               $xcadena = str_replace("De Un", "Un", $xcadena);
            
            }

            return trim($xcadena);

         }

         /**
            *  
            *  convert positive number to negative
            *  @var num           -> num to convert
            *  
         **/
         public function number_negative($num) {

            $negative = $num * (-1);
            return $negative;

         }

         /**
            *  
            *  generate day or month speak in spanish
            *  @var date           -> date time
            *  @var return         -> defined what is back      #EX:   'd' -> day   |  'm'  -> month  | 'nd'  -> number of day   |  'null'  -> return all complete
            *  
         **/
         public function day_speak($date, $return) {

            $date = substr($date, 0, 10);
            $numberDay  = date('d', strtotime($date));

            $day = date('l', strtotime($date));
            $month    = date('F', strtotime($date));
            $year = date('Y', strtotime($date));

            $days_ES  = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
            $days_EN  = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
            $name_day = str_replace($days_EN, $days_ES, $day);

            $month_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
            $month_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
            $name_month   = str_replace($month_EN, $month_ES, $month);

            if($return == 'd') {

               return $name_day;

            } elseif($return == 'm') {

               return $name_month;

            } elseif($return == 'nd') {

               return $numberDay;

            } else {

               return $name_day .' '. $numberDay .' de '. $name_month .' '. $year;

            }

         }

         /**
            *  
            *  generate alert in notifications
            *  @var message           -> text in alert
            *  @var type              -> type of alert   #EX:  'danger'  |  'warning'  |  'info'   |   'success'
            *  
         **/
         public function alert( $message, $type, $noremove = "false", $sb = null, $ida = null ) {

            // instances
            $instance = new FUN;
            // generate rand number
            $id_alert = $instance->key('AL', 5);
            $typeE = $icon = null;
            $title = "Notificación";
            $disaper = null;

            // create icon and type color
            if( $type == "danger" ) { $color = "notification-danger stack-bottomright"; $typeE = "type: 'error',"; $title = "Alerta"; }
            else if( $type == "info" ) { $color = "stack-bottomright"; $typeE = "type: 'info',"; $title = "¡Atención!"; }
            else if( $type == "success" ) { $color = "stack-bottomright"; $typeE = "type: 'success',"; $title = "¡Enhorabuena!"; $icon ="icon: 'fa fa-check',"; }
            else if( $type == "warning" ) { $color = "stack-bottomright"; $title = "Alerta"; }
            else { $color = " notification-dark stack-bottomright"; $icon = "icon: 'fa fa-user',"; }

            // extern alert
            if( $sb != null ) { $sb = "alertSB"; }

            // remove automatic (?)
            if( $noremove == "true" ) { $disaper = "delay: 2500, hide: true,"; }

            // generate alert [html]
            $alerta = " 

               <script>

                  var stack_bar_bottom = {'dir1': 'up', 'dir2': 'right', 'spacing1': 0, 'spacing2': 0};
                  var notice = new PNotify({

                     title: '".$title."',
                     text: '".$message."',
                     ".$typeE."
                     addclass: '".$color." ".$id_alert." click-2-close',
                     ".$icon."
                     hide: false,
                     buttons: { closer: true, sticker: false },
                     stack: stack_bar_bottom,
                     ".$disaper."

                  });

                  $('.".$id_alert."').click(function() { $(this).remove(); });

               </script>

            ";

            return $alerta;

         }

         /**
            *  
            *  filter of bad words (database)
            *  @var string           -> text to clean or detected
            *  
         **/
         public function filter_words($string, $return = null) {

            // instances
            $db = new StudiosBIT;
            $bad = false;

            $censured = array();
            $create_array = $db->query("SELECT word FROM login_filters");
            $i = 1;

            while($fil = $db->single()) { 

               $censured[$i] = $fil['word'];
               $i ++;

            }  

            foreach($censured as $badword) {

               preg_match("/".$badword."/i", $string, $matches);
               if(count($matches) > 0) {

                  $bad = true;
                  if($return != null) { $bad = $badword; }

               }

            }

            return $bad;

         }

         /**
            *  
            *  filter to email verified
            *  @var email           -> get email text
            *  
         **/
         public function filter_email($email) {

            if(filter_var($email, FILTER_VALIDATE_EMAIL)) { $emaild = true;} else { $emaild = false; }
            return $emaild;

         }

         /**
            *  
            *  filter uppercase, check exist one uppercase
            *  @var string           -> text to clean or detected
            *  
         **/
         public function filter_upper($string) {

            if(preg_match("/[A-Z]/", $string)) { $upper = true; } else { $upper = false; }
            return $upper;

         }

         /**
            *  
            *  filter lower case, check exist one lowercase 
            *  @var string           -> text to clean or detected
            *  
         **/
         public function filter_lower($string) {

            if(preg_match("/[a-z]/", $string)) { $upper = true; } else { $upper = false; }
            return $upper;

         }

         /**
            *  
            *  filter number, check exist number in string
            *  @var string           -> text to clean or detected
            *  
         **/
         public function filter_number($string) {

            if(preg_match("/[0-9]/", $string)) { $upper = true; } else { $upper = false; }
            return $upper;

         }

         /**
            *  
            *  insert in database logs of actions in web
            *  @var title                -> title log 
            *  @var descrip              -> descrip to log
            *  @var user                 -> user ID (session on)         #EX :  0 is null user (no session)
            *  
         **/
         public function logs( $title, $descrip, $user = 0, $serverid =  null, $idaction = null ) {

            // instances
            $instance = new FUN;
            $db = new StudiosBIT;
            // get vars to instances
            $ip = $instance->ip();
            $itime  = $instance->time('date');
            $ihours = $instance->time('time');
            // get serverID

            $insert = $db->query("INSERT INTO logs (title, descrip, user, itime, ihours, ip, server, idaction ) VALUES( :title, :descrip, :user, :itime, :ihours, :ip, :server, :action )");
            $db->bind(':title', $title);
            $db->bind(':descrip', $descrip);
            $db->bind(':user', $user);
            $db->bind(':itime', $itime);
            $db->bind(':ihours', $ihours);
            $db->bind(':ip', $ip);
            $db->bind(':server', $serverid);
            $db->bind(':action', $idaction);

            $db->execute();
            $db->CloseConnection();

         }

         /**
            *  
            *  filter space, detect space in string
            *  @var var                -> string text 
            *  
         **/
         public function filter_space($var) {

            // define false (default)
            $space = false;
            if(strpos($var, ' ')) { $space = true; } else { $space = false; }
            return $space;

         }

         /**
            *  
            *  get value to life time of cookies
            *  
         **/
         public function life_cookie() {

            // instances
            $db = new StudiosBIT;
            $get_val = $db->query("SELECT time_cookie FROM config");
            $data  = $db->single();

            $life  = $data['time_cookie'];
            return $life;

         }

         /**
            *  
            *  get total users (chars) online in server
            *  
         **/
         public function is_online() {

            // instances
            $db = new StudiosBIT;

            // search users on
            $charson = $db->query("SELECT userid FROM login WHERE online = :on_value");
            $db->bind(':on_value', 1);
            $db->execute();

            $cU = $db->rowCount();
            $db->CloseConnection();

            return $cU;

         }

         /**
            *  
            *  count results where sentence SQL
            *  @var table              -> name of table
            *  @var sentence           -> if null default   |  != null = WHERE data = data
            *  @var from               -> if all (*) default   |  != null = especific data from table
            *  
         **/
         public function count_rows($table, $sentence = null, $from = "*", $var = null) {

            // instances
            $db = new StudiosBIT;

            // search query prepared
            $db->query("SELECT $from FROM $table $sentence");
            if( $var != null ) {
               
               $db->bind(':var', $var);

            } $db->execute();

            $count = $db->rowCount();
            $db->CloseConnection();

            return $count;

         }

         /**
            *  
            *  get data of tables in DB with WHERE sentence
            *  @var ids              -> defined name of column in table 
            *  @var id               -> defined val to column name 
            *  @var table            -> defined name of table 
            *  @var return           -> defined name column to return and print data 
            *  
         **/
         public function search_id($ids, $id, $table, $return, $other = null) {

            // instances
            $db = new StudiosBIT;
            $rT = null;
            
            // search query prepared
            $db->query("SELECT $return FROM $table WHERE $ids = :id $other");
            $db->bind(':id', $id);
            $db->execute();

            $sI = $db->single();
            if( $db->rowCount() >= 1 ) { $rT = $sI[$return]; }
            $db->CloseConnection();

            return $rT;

         }

         /**
            *  
            *  return number in K or M
            *  @var n              -> number (int)
            *  @var precision      -> define is add zero
            *  
         **/
         public function K($n, $precision = 1) {

            if($n < 900) {

               // 0 - 900
               $n_format = number_format($n, $precision);
               $suffix = '';

            } elseif($n < 900000) {

               // 0.9k-850k
               $n_format = number_format($n / 1000, $precision);
               $suffix = 'k';

            } elseif($n < 900000000) {

               // 0.9m-850m
               $n_format = number_format($n / 1000000, $precision);
               $suffix = 'm';

            } elseif($n < 900000000000) {

               // 0.9b-850b
               $n_format = number_format($n / 1000000000, $precision);
               $suffix = 'b';

            } else {

               // 0.9t+
               $n_format = number_format($n / 1000000000000, $precision);
               $suffix = 't';

            }

            if($precision > 0) {

               $dotzero  = '.' . str_repeat('0', $precision);
               $n_format = str_replace($dotzero,'',$n_format);

            }

            return $n_format . $suffix;

         }

         /**
            *  
            *  return number in format 
            *  @var value              -> number (int)
            *  @var max                -> define decimals count
            *  
         **/
         public function number($value, $max = 0) {

            $value = number_format($value, $max, '.', ',');
            return $value;

         }

         /**
            *  
            *  search all files in folder path
            *  @var path              -> define url to acces folder in host
            *  
         **/
         public function files_folder($path) {

            if(is_dir($path)) {

               if($dir = opendir($path)) {

                  $data = '';
                  while(($file = readdir($dir)) !== false) {

                     if($file != '.' && $file != '..' && $file != '.htaccess') { $data .= $file.','; }

                  }

                  closedir($dir);

               }

            }

            return $data;

         }

         /**
            *  
            *  close session and delete login log in DB
            *  @var userid              -> id User to logout
            *  @var return              -> return is null for now
            *  
         **/
         public function logout($userid, $return = null) {

            // instances
            $db = new StudiosBIT;
            global $session;

            if( $userid >= 1 ) {

               // delete log in DB
               $session_search = $db->query("DELETE FROM login_attemp WHERE userid = :account");
               $db->bind(':account', $userid);
               $db->execute();
               $db->CloseConnection();

               // update session user id
               $db->query("UPDATE login SET isonline = :on1 WHERE userid = :on2");
               $db->bind(':on1', 0);
               $db->bind(':on2', $userid);
               $db->execute();
               $db->CloseConnection();

               // close session
               $session->destroy( $userid );
               unset($_SESSION['id']);
               @session_destroy();

               return true;

            } else { return false; }

         } 

         /**
            *  
            *  check call is ajax or not [security files]
            *  
         **/
         public function ajaxToken() {
             
            // instances
            $CR   = new FUN;   
            // response text [error page]  
            $RT   = 'errorAjax';
            $RTE  = $CR->secret($RT);

            // check is call ajax
            if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') { } else {

               //  dont is ajax
               header("Location: ".URL."/oops/".$RTE."");
               die;

            }

         }

         /**
            *  
            *  delete sentence by SQL prepared (:v1)
            *  
         **/
         public function deleteSQL($identify, $id, $table) {

            // instances
            $db = new StudiosBIT;

            $deleteSQL = $db->query("DELETE FROM $table WHERE $identify = :v1");
            $db->bind(':v1', $id);
            $db->execute();
            $db->CloseConnection();

         }

         /**
            *  
            *  count back to Textarea with bbcode
            *  
         **/
         public function countbbcode($bbcode, $return = null) {

            $countbbcodeIn = strlen($bbcode);
            $deleteBbcode  = preg_replace('/\[[^\]]*\]/', '', $bbcode);
            $countbbcodeOut = strlen($deleteBbcode);

            if($return == null) {

               return $countbbcodeOut;

            } elseif($return == 'countIn') {

               return $countbbcodeIn;

            } elseif($return == 'out') {

               return $deleteBbcode;

            } else {

               return $bbcode;

            }

         }

         /**
            *  
            *  filter names input
            *  
         **/
         public function filterName($var) {

            $permitidos = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-";
            for($i = 0; $i < strlen($var); $i++) {

               if(strpos($permitidos, substr($var, $i, 1)) === false) { $return = false; } else { $return = true; }

            }

            return $return;

         }

         /**
            *  
            *  update data by SQL sentence prepared
            *  
         **/
         public function updateConfig($c1, $v1) {

            $db = new StudiosBIT;
            $updates = $db->query("UPDATE config SET $c1 = :v1");
            $db->bind(':v1', $v1);
            $db->execute();
            $db->CloseConnection();

            return true;

         }

         public function updateData($table, $c1, $v1, $c2, $v2) {

            $db = new StudiosBIT;

            $updates = $db->query("UPDATE $table SET $c1 = :v1 WHERE $c2 = :v2");
            $db->bind(':v1', $v1);
            $db->bind(':v2', $v2);
            $db->execute();
            $db->CloseConnection();

            return true;

         }

         public function searchBy($table, $col1, $id, $return, $other = null) {

            // instances
            $db = new StudiosBIT;
            $rT = null;
            
            // search query prepared
            $db->query("SELECT $return FROM $table WHERE $col1 = :id $other");
            $db->bind(':id', $id);
            $db->execute();

            $sI = $db->single();
            if( $db->rowCount() >= 1 ) { $rT = $sI[$return]; }
            $db->CloseConnection();

            return $rT;

         }

         /**
            *  
            *  update table direct by SQL sentence prepared
            *  
         **/
         public function updateSQL($table, $c1, $v1, $c2, $v2, $other = null) {

            // instance to DB
            $db = new StudiosBIT;
            $updateSQL = $db->query("UPDATE $table SET $c1 = :v1 WHERE $c2 = :v2 $other");
            $db->bind(':v1', $v1);
            $db->bind(':v2', $v2);
            $db->execute();
            $db->CloseConnection();

            return true;

         }

         /**
            *  
            *  clean name for images 
            *  
         **/
         public function nameforImage($name) {

            // replace : to datetime with -
            $clean1 = str_replace(':', '-', $name);
            $clean1 = str_replace('.', '-', $clean1);
            // replace spaces with -
            $clean2 = str_replace(' ', '-', $clean1);

            // return name clean
            return $clean2;

         }

         /**
            *  
            *  verified user login complete SB
            *  
         **/
         public function verified($user) {

            // instances
            $db = new StudiosBIT;
            $CR  = new FUN;

            if( $user >= 1 ) {

               // session // check user exist in db
               $searchUser = $db->query("SELECT userid, isonline, verified FROM login WHERE userid = :usID");
               $db->bind(':usID', $user);
               $db->execute();
               $usCount = $db->rowCount();

               if( $usCount >= 1 ) {

                  // search user data
                  $usD = $db->single();
                  $db->CloseConnection();

                  // get data user
                  $isOn = $usD['isonline'];
                  $isAc = $usD['verified'];

                  // is account verified (?)
                  if( $isAc == 1 ) {

                     // account verified // isOnline (?)
                     if( $isOn == 1 ) {

                        // is online // check user banned (?)
                        $banned = $CR->count_rows('login_banned', 'WHERE userid = :var', 'userid', $user);
                        if( $banned >= 1 ) {

                           // user is banned (close sesion)
                           $db->query("DELETE FROM login_temp WHERE userid = :user1");
                           $db->bind(':user1', $user);
                           $db->execute();
                           $db->CloseConnection();

                           // close session
                           $session->destroy($user);
                           unset($_SESSION['id']);
                           @session_destroy();
                           return false;

                        } else {

                           // user dont banned // account is verified complete
                           return true;

                        }

                     } else {

                        // is offline
                        return false;

                     }

                  } else {

                     // account no verified
                     return false;

                  }

               } else {

                  // dont exist userID                     
                  return false;

               }

            } else {

               // no session
               return false;

            }

         }

         /**
            *  
            *  bbcode function complete SB
            *  
         **/
         public function bbcode($text) {

            // instances
            $parser = new \SBBCodeParser\Node_Container_Document();

            $return = $parser->parse($text)
            ->detect_links()
            ->detect_emails()
            ->get_html();

            // change somewhere [color] with error bbcode
            $findColor = '~\[color=(.*?)\](.*?)\[/color\]~s';
            $restColor = '<span style="color:$1;">$2</span>';
            $return = preg_replace($findColor, $restColor, $return);

            // remove all br exceded
            //$return = str_replace('<br />', '<div class="blockBR"></div>', $return);
            $return = preg_replace('#(?:<br\s*/?>\s*?){2,}#', '<div class="blockBR"></div>', $return);

            // replace [color] with error now
            $return = preg_replace('~\[color=(.*?)\]~s', '', $return);
            $return = preg_replace('~\[/color\]~s', '', $return);
            // change http for https
            $return = str_replace('http://', 'https://', $return);

            return $return;

         }

         /**
            *  
            *  bbcode function youtube embed
            *  
         **/
         public function youtube($embed) {

            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $embed, $eID);
            return $eID[1];

         }
        
         /**
            *  
            *  register data by SQL sentence prepared
            *  
         **/
         public function addSQL($query) {

            $db = new StudiosBIT;
            $db->query($query);
            $db->execute();
            $db->CloseConnection();

         }

         /**
            *  
            *  defined title HTML documents
            *  
         **/
         public function Page($title) { echo '<title> '.$title.' </title>'; }

         /**
            *  
            *  found number between ranged
            *  
         **/
         public function ranged($number, $ranged) { return (in_array($number, explode(',', $ranged))) ? true : false; }

         /** 
            *  
            * clean tildes of words
            *
         **/
         public function tildes( $cadena ) {

            $cadena = str_replace(
               array('á', 'à', 'ä', 'â', 'ª', 'Á', 'À', 'Â', 'Ä'),
               array('a', 'a', 'a', 'a', 'a', 'A', 'A', 'A', 'A'),
               $cadena
            );

            $cadena = str_replace(
               array('é', 'è', 'ë', 'ê', 'É', 'È', 'Ê', 'Ë'),
               array('e', 'e', 'e', 'e', 'E', 'E', 'E', 'E'),
               $cadena 
            );

            $cadena = str_replace(
               array('í', 'ì', 'ï', 'î', 'Í', 'Ì', 'Ï', 'Î'),
               array('i', 'i', 'i', 'i', 'I', 'I', 'I', 'I'),
               $cadena 
            );

            $cadena = str_replace(
               array('ó', 'ò', 'ö', 'ô', 'Ó', 'Ò', 'Ö', 'Ô'),
               array('o', 'o', 'o', 'o', 'O', 'O', 'O', 'O'),
               $cadena 
            );

            $cadena = str_replace(
               array('ú', 'ù', 'ü', 'û', 'Ú', 'Ù', 'Û', 'Ü'),
               array('u', 'u', 'u', 'u', 'U', 'U', 'U', 'U'),
               $cadena 
            );

            $cadena = str_replace(
               array('ñ', 'Ñ', 'ç', 'Ç'),
               array('n', 'N', 'c', 'C'),
               $cadena
            );

            return $cadena;

         }

         /** 
            *  
            * create url frendly SEO 
            *
         **/
         public function urlSEO($url) {

            // instance
            $CR = new FUN;
            $url  = $CR->tildes($url);
            $slug = preg_replace('/[^A-Za-z0-9-]+/','-', $url);

            $cslugs = explode('-', $slug);
            if($cslugs >= 4) {

               $slug = $cslugs[0].'-'.$cslugs[1].'-'.$cslugs[2].'-'.$cslugs[3];

            }

            $slug = strtolower($slug);
            return $slug;

         }

         /** 
            *  
            * meta data SEO
            *
         **/
         public function SEO( $pageTitle = TITLE, $image = null, $imageFirst = null, $url = null, $description = null, $type = "website", $title = null, $keywords = null ) {

				global $timeISO;
         	// image is null (?)
         	if( $image == null ) { $image = URL."/assets/images/seo/default.jpg"; }
         	if( $imageFirst == null ) { $imageFirst = URL."/assets/images/seo/default.jpg"; }
         	// url is null (?)
         	if( $url == null ) { $url = $this->url()."/"; $url = rtrim($url, "/"); }
         	// description is null (?)
         	if( $description == null ) { $description = SLOGAN; }
         	// whats type is (?) : website, article
         	if( $type == "article" ) {

         		// get data restaurante by ID
         		$dataid = 1;
         		$descriptionRest = null;
         		$titleRest = null;
         		$imageRest = null;

         	} else {

         		$dataid = null;
         		$descriptionRest = null;
         		$titleRest = null;
         		$imageRest = URL."/assets/images/seo/default.jpg";

         	}
         	// format time published: 2021-09-25T14:58:21+00:00
         	$timePublished = $timeISO;
         	$timeModified = $timeISO;
         	// logo 518px
         	$logo = URL."/assets/images/seo/logo.jpg";
         	// slogan short
         	$slogan = SLOGAN;
         	// title page section
         	if( $title == null ) { $title = TITLE; }

         	// generate data SEO
            $data = '

					<!-- Website optimized with ~ SB SEO ~ -->
					<title> '.$pageTitle.' </title>
					<meta name="apple-mobile-web-app-capable" content="yes">
					<meta name="description" content="'.$description.'" />
					<link rel="canonical" href="'.$url.'" />
					<meta property="og:locale" content="es_ES" />
					<meta property="og:type" content="'.$type.'" />
					<meta property="og:title" content="'.$title.'" />
					<meta property="og:description" content="'.$description.'" />
					<meta property="og:url" content="'.$url.'" />
					<meta property="og:site_name" content="'.TITLE.'" />
					<meta property="article:publisher" content="'.FACEBOOK.'" />
					<meta property="article:author" content="'.FACEBOOK.'" />
					<meta property="article:published_time" content="'.$timePublished.'" />
					<meta property="article:modified_time" content="'.$timeModified.'" />
					<meta property="og:image" content="'.$image.'" />
					<meta property="og:image:width" content="1460" />
					<meta property="og:image:height" content="730" />
					<meta name="twitter:card" content="summary_large_image" />
					<meta name="twitter:title" content="'.$title.'" />
					<meta name="twitter:image" content="'.$image.'" />
					<meta name="twitter:creator" content="'.TWITTERUSER.'" />
					<meta name="twitter:site" content="'.TWITTERUSER.'" />
					<meta name="twitter:label1" content="Por" />
					<meta name="twitter:data1" content="'.TITLE.'" />
					<meta name="twitter:label2" content="Tiempo de lectura" />
					<meta name="twitter:data2" content="3 minutos" />

					<script type="application/ld+json" class="sb-schema-graph">

						{"@context":"https://schema.org","@graph":[{"@type":"Organization","@id":"'.URL.'","name":"'.TITLE.'/#organization","url":"'.URL.'/","sameAs":["'.FACEBOOK.'","'.INSTAGRAM.'","'.TWITTER.'"],"logo":{"@type":"ImageObject","@id":"'.URL.'/#logo","inLanguage":"es","url":"'.$logo.'","contentUrl":"'.$logo.'","width":580,"height":580,"caption":"'.TITLE.'"},"image":{"@id":"'.URL.'/#logo"}},{"@type":"WebSite","@id":"'.URL.'/#website","url":"'.URL.'/","name":"'.TITLE.'","description":"'.$description.'","publisher":{"@id":"'.URL.'/#organization"},"potentialAction":[{"@type":"SearchAction","target":{"@type":"EntryPoint","urlTemplate":"'.URL.'/search/?s={search_term_string}"},"query-input":"required name=search_term_string"}],"inLanguage":"es"},{"@type":"ImageObject","@id":"'.$url.'#primaryimage","inLanguage":"es","url":"'.$image.'","contentUrl":"'.$image.'","width":1460,"height":730,"caption":"'.TITLE.'"},{"@type":"WebPage","@id":"'.$url.'#webpage","url":"'.$url.'","name":"'.$title.'","isPartOf":{"@id":"'.URL.'/#website"},"primaryImageOfPage":{"@id":"'.$url.'#primaryimage"},"datePublished":"'.$timePublished.'","dateModified":"'.$timeModified.'","description":"'.$description.'","breadcrumb":{"@id":"'.$url.'#breadcrumb"},"inLanguage":"es","potentialAction":[{"@type":"ReadAction","target":["'.$url.'"]}]},{"@type":"BreadcrumbList","@id":"'.$url.'#breadcrumb","itemListElement":[{"@type":"ListItem","position":1,"name":"Inicio","item":"'.URL.'"},{"@type":"ListItem","position":2,"name":"Restaurantes","item":"'.URL.'/restaurantes"},{"@type":"ListItem","position":3,"name":"'.$title.'"}]},{"@type":"Article","@id":"'.$url.'#article","isPartOf":{"@id":"'.$url.'#webpage"},"author":{"@id":"'.URL.'/"},"headline":"'.$title.'","datePublished":"'.$timePublished.'","dateModified":"'.$timeModified.'","mainEntityOfPage":{"@id":"'.$url.'#webpage"},"wordCount":4010,"commentCount":0,"publisher":{"@id":"'.URL.'/#organization"},"image":{"@id":"'.$url.'#primaryimage"},"thumbnailUrl":"'.$imageFirst.'","keywords":['.$keywords.'],"articleSection":["'.$slogan.'"],"inLanguage":"es","potentialAction":[{"@type":"CommentAction","name":"Comment","target":["'.$url.'#responder"]}]},{"@type":"Person","@id":"'.URL.'/","name":"'.TITLE.'","image":{"@type":"ImageObject","@id":"'.URL.'/#personlogo","inLanguage":"es","url":"'.$imageRest.'","contentUrl":"'.$imageRest.'","caption":"'.$titleRest.'"},"description":"'.$descriptionRest.'","sameAs":["'.FACEBOOK.'"}]}

					</script>
					<!-- / ~ SB SEO ~ -->

					';

            return $data;

         }

         /** 
            *  
            * is user Rank Admin (?)
            *
         **/
         public function userAdmin($usID, $rankmin = 9999) {

            // instances
            $CR = new FUN;
            $User = new Users;

            // verified exist session admin
            if($CR->verified($usID) === true) {

               // require rank user
               $crRank = $User->user($usID, 'rank');
               if($crRank == $rankmin) { return true; } else { return false; }

            } else { return false; }

         }

         public function needAdmin( $usID, $rankmin ) {

            $User = new Users;
            if( $this->userAdmin( $usID, $rankmin ) === false ) { $this->header(URL."/inicio"); } 

         }

         // search permission accepts
         public function powers($rank, $pow) {

            // this rank is CEO ADMIN
            if($rank == 9999) { $powf = 1; } else {

               $permisos = $this->table('login_rank', $rank, 'permissions');
               $pow = str_replace(' ', '', $pow);
               $powe = explode(',', $pow);
               $powc = count($powe);
               $powf = 0; 
               for($i = 0; $i < $powc; $i ++) { if(in_array($powe[$i], explode(',', $permisos))) { $powf ++; } if($powe[$i] == 'all') { $powf = 1; } }

            } return $powf;

         }

         // verified lock screen is active
         public function Lockscreen($userid) {

            $User = new Users;
            if( $userid >= 1 && $User->user( $userid, "locked" ) == 1 ) { $this->header(URL."/locked"); } else { return true; }

         }

         // generate random hex color
         public function randomHex( $hashtag = false ) {

            $chars = '77B5FE'; $color = '#';
            for ( $i = 0; $i < 6; $i ++ ) { $color .= $chars[rand(0, strlen($chars) - 1)]; }
            if( $hashtag == false ) { $color = str_replace("#", "", $color); }
            return $color;

         }

         // pagination for results
         public function pagination( $url, $pageCurrent, $totalPages, $search = null ) {

            // default vars
            $link = ""; $page = $pageCurrent; $limit = 6;
            // get page initial
            $pageInit = 1;
            $initPage = $url.$pageInit.$search;
            $pageEnd = $totalPages;
            $endPage = $url.$pageEnd.$search;
            // get page prev
            $pagePrev = $page - 1;
            if( $pagePrev <= 0 ) { $pagePrev = 1; }
            $prevPage = $url.$pagePrev.$search;
            // get page next
            $pageNext = $page + 1;
            if( $pageNext >= $totalPages ) { $pageNext = $totalPages; }
            $nextPage = $url.$pageNext.$search;

            // show link to pagination
            if( $totalPages >= 1 && $page <= $totalPages ) {

               $counter = 1; $link = "";

               for( $x = $page; $x <= $totalPages; $x ++ ) {

                  if( $counter < $limit ) {

                     $pageXX = $url.$x.$search;
                     if( $page == $x ) { 

                        $link .= '<li class="page-item active"><a class="page-link">'.$x.'</a></li>';

                     } else {

                        $link .= '<li class="page-item"><a class="page-link" href="'.$pageXX.'">'.$x.'</a></li>';

                     }

                     $counter ++;

                  }

               }

            }

            $pagePrint = '

               <div class="row mb-3">

                  <div class="col-12">

                     <div class="text-right">

                        <ul class="pagination pagination-split mt-0 float-right">

                           <li class="page-item"> <a class="page-link" href="'.$initPage.'" aria-label="Primera"> <span aria-hidden="true">««</span> <span class="sr-only">Primera</span> </a> </li>

                           <li class="page-item"> <a class="page-link" href="'.$prevPage.'" aria-label="Anterior"> <span aria-hidden="true">«</span> <span class="sr-only">Anterior</span> </a> </li>

                              '.$link.'

                           <li class="page-item"> <a class="page-link" href="'.$nextPage.'" aria-label="Siguiente"> <span aria-hidden="true">»</span> <span class="sr-only">Siguiente</span> </a> </li>

                           <li class="page-item"> <a class="page-link" href="'.$endPage.'" aria-label="Última"> <span aria-hidden="true">»»</span> <span class="sr-only">Última</span> </a> </li>

                        </ul>

                     </div>

                  </div>

               </div>

            ';

            return $pagePrint;

         }

         // remove characters of var
         function removeOn( $text, $action = "numeric" ) {

            if( $action == "numeric" ) {

               $text = preg_replace('([^0-9])', '', $text);

            } elseif( $action == "alfanumeric" ) {

               $text = preg_replace('([^A-Za-z0-9])', '', $text);

            } elseif( $action == "text" ) { 

               $text = preg_replace('([^A-Za-z])', '', $text);

            } elseif( $action == "email" ) {

               $text = preg_replace('([^A-Za-z0-9 \._\-@])', '', $text);

            } else { $text = $text; }

            return $text;

         }

         // return error div for webApp
         public function responseError( $h5, $p ) {

         	$r = '<div class="responseError">
      						
							<div class="spinner-border text-secondary" role="status"> <span class="sr-only">Loading...</span> </div>
							<div class="container-fluid mt-4 bg-dark text-white pd0 br2">

								<div class="row">

									<div class="container">

										<div class="row">

											<div class="col align-self-center">

												<div class="text-center py-3">

													<h5 class="mb-0">'.$h5.'</h5>
													<p class="small">'.$p.'</p>

												</div>
											</div>

										</div>

									</div>

								</div>

							</div>

						</div>';

				return $r;

         }

         public function getDay15( ) {

         	$dayToday = date("d");
				if( $dayToday >= 1 AND $dayToday <= 14 ) {

					$nextDay = 15;
					$nextFecha = date("Y-m-").$nextDay;

				} elseif( $dayToday >= 15 AND $dayToday <= 29 ) {

					$nextDay = 30;
					$nextFecha = date("Y-m-").$nextDay;

				} else {

					$nextDay = 15;
					$addmonth = $this->control_time($date, "+", "days", 1, "NH");
					$addM = explode("-", $addmonth);
					$nextFecha = $addM[0]."-".$addM[1]."-".$nextDay;

				}

				return $nextFecha;

         }

         public function addressGoogle( $address ) {

         	// null default
         	$geo = null;
         	$address = urlencode($address);
         	if( $address != null ) {
					
					$distance_data = file_get_contents( 'https://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&key='.GOOGLEAPI);
					$Data = json_decode($distance_data);
					if( $Data != null ) {

						if( $Data->status != "ZERO_RESULTS" ) {

							$Lat = $Data->results[0]->geometry->location->lat;
							$Lon = $Data->results[0]->geometry->location->lng;
							$geo = $Lat."|".$Lon;

						} else { $geo = null; }

					} else { $geo = null; }

				}

				return $geo;

         }

         public function distanceGoogle( $lat1, $lon1, $lat2, $lon2, $return ) {

				$distance_data = file_get_contents( 'https://maps.googleapis.com/maps/api/distancematrix/json?&origins='.$lat1.','.$lon1.'&destinations='.$lat2.','.$lon2.'&key='.GOOGLEAPI);
				$Data = json_decode($distance_data);

				$Dstatus = $Data->rows[0]->elements[0]->status;

				if( $Dstatus == "OK" ) {

					$disSB = $Data->rows[0]->elements[0]->distance->text;
					$minSB = $Data->rows[0]->elements[0]->duration->text;

					if( $return == "km" ) {

						$DR = str_replace(' mi', '', $disSB);  

					} elseif( $return == "time" ) {

						$DR = $minSB; 

					} elseif( $return == "ambas" ) {

						$DRK = str_replace(' mi', '', $disSB);  
						$DR = $DRK." <b class='dgMin'>".$minSB."</b>"; 

					} else { $DR = "N/A"; }

				} else { $DR = "Error en el cálculo de distancias."; }
				
				return $DR;

         }

         public function googleAPI( $lat, $lng, $return = "formatted_address" ) {

         	// simple functions google
            function check_status($jsondata) { if( $jsondata["status"] == "OK" ) { return true; } else { return false; } }
            function google_getCountry($jsondata) { return Find_Long_Name_Given_Type("country", $jsondata["results"][0]["address_components"]); }
            function google_getProvince($jsondata) { return Find_Long_Name_Given_Type("administrative_area_level_1", $jsondata["results"][0]["address_components"], false); }
            function google_getPolitical($jsondata) { return Find_Long_Name_Given_Type("political", $jsondata["results"][0]["address_components"]); }
            function google_getCity($jsondata) { return Find_Long_Name_Given_Type("locality", $jsondata["results"][0]["address_components"]); }
            function google_getStreet($jsondata) { return  Find_Long_Name_Given_Type("route", $jsondata["results"][0]["address_components"]). ' ' . Find_Long_Name_Given_Type("street_number", $jsondata["results"][0]["address_components"]); }
            function google_getPostalCode($jsondata) { return Find_Long_Name_Given_Type("postal_code", $jsondata["results"][0]["address_components"]); }
            function google_getCountryCode($jsondata) { return Find_Long_Name_Given_Type("country", $jsondata["results"][0]["address_components"], true); }
            function google_getAddress($jsondata) { return $jsondata["results"][0]["formatted_address"]; }
            function Find_Long_Name_Given_Type($type, $array, $short_name = false) { foreach( $array as $value) { if( in_array($type, $value["types"]) ) { if($short_name) { return $value["short_name"]; } else { return $value["long_name"]; } } }  }

            $url = 'https://maps.googleapis.com/maps/api/geocode/json?latlng='.trim($lat).','.trim($lng).'&key='.GOOGLEAPI.'&sensor=false';
            // Make the HTTP request
            $data = @file_get_contents($url);
            // Parse the json response
            $jsondata = json_decode($data,true);
            // If the json data is invalid, return empty array
            if( !check_status($jsondata) ) { return array(); }
            $address = array(

               'country' => google_getCountry($jsondata),
               'province' => google_getProvince($jsondata),
               'city' => google_getCity($jsondata),
               'street' => google_getStreet($jsondata),
               'postal_code' => google_getPostalCode($jsondata),
               'country_code' => google_getCountryCode($jsondata),
               'formatted_address' => google_getAddress($jsondata),
               'political' => google_getPolitical($jsondata),

            );

            return $address[$return];

         }

         public function sendAlert( $name, $descrip, $userid = null, $serverid =  null ) {

            $db = new StudiosBIT;
            global $date;
            global $time;
            global $serverID;
            global $UserID;

            if( $userid == null ) { $userid = 0; }
            if( $serverid == null ) { $serverid = $serverID; }

            // add register
            $db->query("INSERT INTO login_alerts (userid, name, descrip, serverid, idate, itime ) VALUES( :v1, :v2, :v3, :v4, :v5, :v6 )");
            $db->bind(':v1', $userid);
            $db->bind(':v2', $name);
            $db->bind(':v3', $descrip);
            $db->bind(':v4', $serverid);
            $db->bind(':v5', $date);
            $db->bind(':v6', $time);
            $db->execute();
            $db->CloseConnection();

         }

         public function needRank( $rank, $minrank, $goto = null ) {

         	if( $goto == null ) { $goto = URL."/inicio"; }
         	if( $rank != $minrank ) { $this->header($goto); }

         }

         public function viewGoogle( $lat, $lon, $zoom = "18", $action = null ) {

         	$gM = null;
         	if( $action == null ) { $gM = 'https://www.google.com.mx/maps/dir/'.$lat.','.$lon.'/@'.$lat.','.$lon.','.$zoom.'z'; }
         	return $gM;

         }

         // send response jSON 
         public function sendResponse($resp_code, $data, $message) {

				return json_encode(array('code'=>$resp_code,'message'=>$message,'data'=>$data));

			}

         // validate ESTADO
         public function isMXSTATE($state) {    

            $mxStates = [         
               'AS','BS','CL','CS','DF','GT',         
               'HG','MC','MS','NL','PL','QR',         
               'SL','TC','TL','YN','NE','BC',         
               'CC','CM','CH','DG','GR','JC',         
               'MN','NT','OC','QT','SP','SR',         
               'TS','VZ','ZS'     
            ];  

            if(in_array(strtoupper($state),$mxStates)){ return true; }  else { return false; }

         }

         // validate SEXO CURP
         public function isSEXCURP($sexo) {
            
            $sexoCurp = ['H','M'];
            if( in_array(strtoupper($sexo), $sexoCurp) ) { return true; } else { return false; }
         
         }

         // get long name Sexo
         public function sexo($sexo) {
            
            switch ($sexo) {

               case 'H':
                  return "Hombre";
                  break;

               case 'M':
                  return "Mujer";
                  break;
               
               default:
                  return "Hombre";
                  break;
                  
            }
         
         }

         // validate CURP
         public function CURP( $curp ) { 

            if(strlen( $curp ) == 18 ) { 

               $letras   = substr($curp, 0, 4);
               $numeros  = substr($curp, 4, 6);         
               $sexo = substr($curp, 10, 1);
               $mxState  = substr($curp, 11, 2); 
               $letras2  = substr($curp, 13, 3); 
               $homoclave  = substr($curp, 16, 2);

               if(ctype_alpha($letras) && ctype_alpha($letras2) && ctype_digit($numeros) && ctype_digit($homoclave) && $this->isMXSTATE($mxState) && $this->isSEXCURP($sexo)) { return true; } else { return false; }

            } else { return false;  } 

         }

         // get model 
         public function vLive() {

            global $ACAP;

            $body = "";
            $url = VERIDAS."/xpressid/api/v2/alive";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [ 'apikey:'.$ACAP ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
            curl_setopt($ch, CURLOPT_POSTFIELDS, $body);

            // non proxy
            curl_setopt($ch, CURLOPT_PROXY, "");
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

            $response = curl_exec($ch);
            $vs = json_decode( $response, false );
            print($response);

            curl_close($ch);

         }

         // post get access token
         public function vDocument( $url = null, $return = null ) {

            global $ACAP;

            // https://localhost/supremacorte/registro
            $url = VERIDAS."/xpressid/api/v2/token";
            $data = '
            {

               "data": {

                  "platform": "web",
                  "operationMode": "idv",
                  "flowSetup": {

                     "core": { "confirmProcess": false },
                     "stages": ["document", "qr", "selfie" ],
                     "options": {

                        "document": { "captures": [ {"documentTypes":["MX_ID"]} ] },

                        "qr": {

                           "redirectionUrl": "https://192.168.1.117/supremacorte/registro",
                           "setup": {

                              "logo": { "show": false },
                              "backgroundColor": "#181c33",
                              "containerBox": { "show": false },
                              "title": {

                                 "text": "Puedes continuar desde tu dispositivo móvil",
                                 "textColor": "#FFFFFF"

                              },

                              "subtitle": {

                                 "text": "Para una mejor experiencia, te recomendamos completar el proceso desde tu teléfono móvil escaneando el código QR.",
                                 "textColor": "#b1b1b1"

                              },

                              "buttons": {

                                 "continue": {

                                    "text": "Continuar en el escritorio",
                                    "backgroundColor": "#181c33",
                                    "textColor": "#FFFFFF",
                                    "borderColor": "#FFFFFF",
                                    "states": {

                                       "hover": { "backgroundColor": "#ffca35", "textColor": "#000000", "borderColor": "#000000" },
                                       "active": { "backgroundColor": "#FFFFFF", "textColor": "#1452a1", "borderColor": "#1452a1" }

                                    }

                                 }

                              }

                           }

                        }

                     }

                  }

               }

            }';

            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'apikey:'.$ACAP.'';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            // non proxy
            //curl_setopt($ch, CURLOPT_PROXY, "");
            //curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $vs = json_decode( $response, false );

            if( $return != null ) {

               return $vs->$return;

            } else {
               
               return $response;

            }

            // close curl connection
            curl_close($ch);

         }

         // post compare selfie base64 
         public function vSelfie( $image = null, $return = null ) {

            global $ACAP;

            $url = VERIDAS."/xpressid/api/v2/token";
            $data = '
            {
               "data": {
                  "platform": "web",
                  "operationMode": "authentication",
                  "flowSetup": {
                     "stages": [

                     "selfie"
                     ],
                     "core": {

                         "contextualData": {
                             "validationId": "number"
                         },
                        "selfieImage": "'.$image.'"
                        }
                     }
                  }
               }';

            $headers = array();
            $headers[] = 'Content-Type: application/json';
            $headers[] = 'apikey:'.$ACAP.'';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers );
            curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

            $response = curl_exec($ch);
            $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $vs = json_decode( $response, false );

            if( $return != null ) {

               return $vs->$return;

            } else {
               
               return $response;

            }

            // close curl connection
            curl_close($ch);

         }

         // get ids
         public function vData( $id = null, $return = null ) {

            global $ACAP;

            $ch = curl_init();
            curl_setopt_array($ch, array(

               CURLOPT_URL => 'https://api-work.eu.veri-das.com/validas/v1/validation/'.$id.'/selfie',
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_ENCODING => '',
               CURLOPT_MAXREDIRS => 10,
               CURLOPT_TIMEOUT => 0,
               CURLOPT_FOLLOWLOCATION => true,
               CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
               CURLOPT_CUSTOMREQUEST => 'GET',
               CURLOPT_HTTPHEADER => array(
                  'protocol: https',
                  'apikey: '.$ACAP
               ),

            ));

            $response = curl_exec($ch);
            return $response;

            // close curl connection
            curl_close($ch);

         }

         // get result score
         public function vAuten( $id = null, $return = null ) {

            global $ACAP;

            $ch = curl_init();
            curl_setopt_array($ch, array(

               CURLOPT_URL => 'https://api-work.eu.veri-das.com/validas/v1/authentication/'.$id.'/scores',
               CURLOPT_RETURNTRANSFER => true,
               CURLOPT_ENCODING => '',
               CURLOPT_MAXREDIRS => 10,
               CURLOPT_TIMEOUT => 0,
               CURLOPT_FOLLOWLOCATION => true,
               CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
               CURLOPT_CUSTOMREQUEST => 'GET',
               CURLOPT_HTTPHEADER => array(
                  'protocol: https',
                  'apikey: '.$ACAP
               ),

            ));

            $response = curl_exec($ch);
            return $response;

            // close curl connection
            curl_close($ch);

         }

         public function lastID($table, $from = "id") {

            // instances
            $db = new StudiosBIT;
            $rT = null;
            
            // search query prepared
            $db->query("SELECT $from FROM $table ORDER BY id DESC LIMIT 1");
            $sI = $db->single();
            if( $db->rowCount() >= 1 ) { $rT = $sI[$from]; } else { $rT = 0; }
            $db->CloseConnection();

            return $rT;

         }

         // generate rand username
         public function randUser( $string ) {

            $nrRand = rand(0, 100);
            $string = strtolower($string);
            return preg_replace('/^(.+)\s(.{2}).+$/', '$1$2', $string, 1) . $nrRand;

         }

      }
        
   #
      # RUN FUNCTIONS USER
   #

      global $CR;
      $CR = new FUN();

?>