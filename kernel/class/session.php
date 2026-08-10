<?php
    
    #
        # CLASS TO SESSION CONTROL USER
    #
        
        class PHPSession {
            
            // private name table sessions logs
            private $session_table;
            // private encrypt keygen
            private $enckey;
            // private session ID
            private $sessionid;
            
            // contruct of class
            public function __construct( $pdo_instance ) {

                $instance = new FUN();

                // defined PDO_INSTANCE
                //$this->db = $pdo_instance;
                // defined name table session log
                $this->session_table = "login_temp";
                // defined method hash encryp
                //$this->session_hash = "sha256";
                // defined time life cookie  || default is '86400' -> 1 day
                //$this->timeout = $instance->life_cookie();
                // defined string to keygen 
                $this->enckey = 'sessions_studiosBIT';

                // defined functions store session at nivel user
                /*
                    
                    session_set_save_handler(

                        array($this, 'open'),
                        array($this, 'close'),
                        array($this, 'read'),
                        array($this, 'write'),
                        array($this, 'destroy'),
                        array($this, 'gc')

                    );

                */

                // prevents unexpected effects when using objects such as storage managers
                register_shutdown_function('session_write_close');

                return true;
                
            }
            
            /**
                *  
                *  start session 
                * @var session_name      -> defined name to session or false is default PHP sessions   || default is "false"
                * @var secure            -> defined if used HTTPS in website || default is "false"
                *  
            **/
            public function start_session($session_name = false, $secure = false) {

                // user agent browser
                $ua = $_SERVER['HTTP_USER_AGENT'];

                // detect bot pattners
                $botpattern = "(";

                    $botpattern .= "(bing|yandex|mj12|google|cc|org_|msn|cliqz|twitter)bot";
                    $botpattern .= "|ezooms|yahoo|google|robot|spider|crawl(er)?|synapse|cmradar|java|facebook|wordpress";

                $botpattern .= ")";

                // detected bot or user agent is null (empty)
                if(preg_match("/$botpattern/i", $ua) || empty($ua)) {

                    // return false
                    return false;

                }

                // only use http  || default is "true"
                $httponly = true;

                // if is HTTPS in website
                if(!empty($_SERVER['HTTPS'])) {

                    // change @secure to "true"
                    $secure = true;

                }

                // declared session method hash
                if(in_array($this->session_hash, hash_algos())) {

                    // generate sha256 and is more stronger session ID
                    ini_set('session.hash_function', $this->session_hash);

                }

                if(@$_SESSION['id'] == null) { 

                    // defined how bits almacened en character
                    ini_set('session.hash_bits_per_character', 5);
                    // defined user only cookies in session
                    ini_set('session.use_only_cookies', 1);
                    // defined max time life session
                    ini_set('session.gc_maxlifetime', $this->timeout); 
                    // defined max time life cookie
                    ini_set("session.cookie_lifetime", $this->timeout);
                    // defined probability the process in sessions expired (garbage collection)
                    ini_set('session.gc_probability', 1);
                    // defined probability the process in sessions expired (garbage collection) and inicialited in each session start
                    ini_set('session.gc_divisor', 1);

                    // get params to cookies PHP
                    $cookieParams = session_get_cookie_params();
                    // change param to cookies  | time life | path | domain | secure = false | httponly = false
                    session_set_cookie_params($this->timeout, '/', $cookieParams["domain"], $secure, $httponly);

                }

                // declared and set session ID
                if($this->sessionid) {

                    // set session now id
                    session_id($this->sessionid);

                }

                // session name dont´s null 
                if($session_name !== false) {

                    // change name to session (@session_name)
                    session_name(hash('sha256', $session_name));

                }

                // start session
                session_start();
                // regenerate sessions
                session_regenerate_id(true);  

                // return true
                return true;

            }
            
            /**
                *  
                *  set session userID
                * @var sessId      -> is userID session
                *  
            **/
            public function setSessionId($sessId) {

                // set data session ID
                $this->sessionid = $sessId;

            }
            
            /**
                *  
                *  open function
                *  
            **/
            public function open() {

                // return true
                return true;

            }
            
            /**
                *  
                *  function close sessions
                *  
            **/
            public function close() {

                // return true
                return true;

            }
            
            /**
                *  
                *  read stored data in session for userID
                * @var id      -> is userID
                *  
            **/
            public function read( $id ) {

                // default data is null
                $data = '';
                
                // instaces
                $db = new StudiosBIT;
                $CR = new FUN;

                // get data to login
                $db->query("SELECT data, uagent, ip FROM {$this->session_table} WHERE userid = :id");
                $db->bind(':id', $id);

                // get data query
                $r_sess = $db->single();

                // if different to null or empty
                if(!empty( $r_sess )) {

                    // decript data get
                    $data = unserialize($this->decrypt($r_sess['data']));
                    // rewrite
                    $this->write($id, $data);

                    // check same ip and same uagent
                    if($r_sess['uagent'] != $CR->navegator()) {

                        // $this->destroy($id);

                    }
                
                }

                // close connection to DB
                $db->CloseConnection();

                // return data and decript
                return $data;

            }
            
          
            /**
                *  
                *  insert data session in DB
                * @var id      -> is userID
                * @var data    -> is data value session
                *  
            **/
            public function write($id, $data) {

                // instace to database connect
                $db = new StudiosBIT;
                $CR = new FUN;

                // get timestamp
                $time = time();

                // execute query db  |  count exist other session with same ID (user)
                $query = $db->query("SELECT * FROM {$this->session_table} WHERE userid = :id");
                $db->bind(':id', $id);
                $db->execute();

                // get data query
                $count_usid = $db->rowCount();

                // close connection to DB
                $db->CloseConnection();
                $data = $this->encrypt(serialize($data));
                $ip = $CR->ip();
                $nav = $CR->navegator();

                // convert DATA to no CLOSE SESSION
                $time = 99999999;
                $data = 1;
                $ip = 1;
                $nav = 1;

                // if no user session same ID
                if($count_usid <= 0) {

                    // insert new session
                    $query = $db->query("INSERT INTO {$this->session_table} (userid, itime, data, ip, uagent) VALUES (:userid, :itime, :data, :ip, :uagent)");
                    $db->bind(':userid', $id);
                    $db->bind(':itime', $time);
                    $db->bind(':data',  $data);
                    $db->bind(':ip',    $ip);
                    $db->bind(':uagent', $nav);
                    $db->execute();

                    // close connection to DB
                    $db->CloseConnection();

                } else {

                    // update last session user         
                    $qery = $db->query("UPDATE {$this->session_table} SET itime = :itime, data = :data, ip = :ip WHERE userid = :userid");
                    $db->bind(':userid', $id);
                    $db->bind(':itime', $time);
                    $db->bind(':data', $data);
                    $db->bind(':ip', $ip);
                    $db->execute();

                    // close connection to DB
                    $db->CloseConnection();

                }

                // return exit true
                return true;

            }
            
            /**
                *  
                *  destroy session user in DB
                * @var id      -> is userID
                *  
            **/
            public function destroy($id) {

                // instance to database connect
                $db = new StudiosBIT;

                // delete all session user
                $query = $db->query("DELETE FROM {$this->session_table} WHERE userid = :id");
                $db->bind(':id', $id);
                $db->execute();
                $db->CloseConnection();

                // change isOnline
                $changeOn = $db->query("UPDATE login SET isonline = :on3 WHERE userid = :id1");
                $db->bind(':on3', 0);
                $db->bind(':id1', $id);
                $db->execute();
                $db->CloseConnection();

                return true;

            }
            
            /**
                *  
                *  delete all sessions expireds
                * @var max      -> time to limit expired
                *  
            **/
            public function gc($max) {

                // instance database connect
                $db = new StudiosBIT;
                $CR = new FUN;

                // rest time max to timestamp now
                $time_max = time() - ($max * 2);

                // search sessions actives
                $db->query("SELECT userid FROM {$this->session_table} WHERE itime < :it1 AND remember = 0");
                $db->bind(':it1', $time_max);
                $db->execute();

                if($db->rowCount() >= 1) {

                    while($saData = $db->single()) {

                        $sausID = $saData['userid'];
                        $CR->deleteSQL('userid', $sausID, $this->session_table);
                        $CR->logs('Cierre sesión forzoso', 'Se cerró la sesión por inactividad del usuario.', $sausID);

                    }

                    $db->CloseConnection();

                }

                /* 

                    // old method sql // delete all sessions expireds
                    $query = $db->query("DELETE FROM {$this->session_table} WHERE itime < :itime AND remember = 0");
                    $db->bind(':itime', $time_max);
                    $db->execute();
                    $db->CloseConnection();

                */

                // return true 
                return true;

            }
            
            /**
                *  
                *  function encrypt session data value
                * @var data      -> string to encrypt
                *  
            **/
            private function encrypt($data) {

                // define method hash
                $key = hash("sha1", $this->enckey);

                // encrypte whit openSSL method and keys
                $encrypted = openssl_encrypt($data, "aes-256-ecb", $key, 0);

                // return data encrypt
                return $encrypted;

            }
            
            /**
                *  
                *  function decrypt session data value
                * @var data      -> string to decryp
                *  
            **/
            private function decrypt($data) {

                // default method for crypte use now to decrypte
                $key = hash("sha1", $this->enckey);

                // decrypte data session with same openSSL method
                $decrypted = openssl_decrypt($data, "aes-256-ecb", $key, 0);

                // return data decrypte
                return $decrypted;

            }
            
        }

?>