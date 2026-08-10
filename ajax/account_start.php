<?php
      
   require '../init.conf';
   $CR->ajaxToken();

   $email = !empty( $_POST['user'] ) ? $_POST['user'] : null;
   $pass = !empty( $_POST['pass'] ) ? $_POST['pass'] : null;
   $remember = !empty( $_POST['remember'] ) ? $_POST['remember'] : "off";
   $password = $CR->encripter($pass);

   // is null vars (?)
   $null_array = compact('email', 'pass');
   if( in_array('', $null_array) ) { echo $CR->updateJS(' alerta("error null fields", "danger"); button(true); '); } else {
         
      // check email exist (?)
      $existEmail = $CR->count_rows('login', 'WHERE user = :var', 'user', $email);

      if( $existEmail <= 0 ) { echo $CR->updateJS(' alerta("error dont exist user/mail", "danger"); button(true); '); } else {

         // get data user 
         $userData = $db->query("SELECT pass, userid, user, verified, email, name, server FROM login WHERE user = :us1");
         $db->bind(':us1', $email);
         $db->execute(); $usData = $db->single(); $db->CloseConnection();

         $usPass  = $usData['pass'];
         $usId    = $usData['userid'];
         $usVal   = $usData['verified'];
         $usEmail = $usData['email'];
         $usName  = $usData['name'];
         $usServ  = $usData['server'];

         // account verified (?)
         if( $usVal >= 1 ) {

            // user banned (?)
            $userBanned = $db->query("SELECT itimeid, itime, ihours FROM login_banned WHERE userid = :usb1");
            $db->bind(':usb1', $usId);
            $db->execute();
            $banCount = $db->rowCount();
               
            if( $banCount >= 1 ) {

               // user find
               $banData = $db->single();
               $db->CloseConnection();

               // data banned
               $banTimeID = $banData['itimeid'];
               $banTime1  = $banData['itime'];
               $banTime2  = $banData['ihours'];
               $banTimedate = $banTime1.' '.$banTime2;
               $banPerm = 0;

               // get data banned
               $searchDaysBan = $CR->table('login_banned_times', $banTimeID, 'itime_days');
               $isPermanent   = $CR->table('login_banned_times', $banTimeID, 'ispermanent');
               
               // is permanent (?)
               if( $isPermanent >= 1 ) { $banUser = 1; $banPerm = 1; } else {

                  // add days to datetime [return datetime]
                  $finalBanTime  = $CR->control_time($banTimedate, '+', 'days', $searchDaysBan, 'SH');
                  // is bannen now?
                  if( $datetime > $finalBanTime ) {

                     // finalizo ban 
                     $banUser = 0;
                     $deleteBanned = $db->query("DELETE FROM login_banned WHERE userid = :usb2 AND itimeid = :itim3");
                     $db->bind(':usb2', $usId);
                     $db->bind(':itim3', $banTimeID);
                     $db->execute(); $db->CloseConnection();
                     $CR->logs('log title desban', 'log message', $usId, $usServ);

                  } else { $banUser = 1; $banTimeRest = $CR->time_between($finalBanTime, $datetime); }

               }

            } else { $banUser  = 0; }

            if( $banUser >= 1 ) {
               
               if( $banPerm >= 1 ) { echo $CR->updateJS(' alerta("alert message ban perm", "danger", 2000); button(true); '); } else {

                  $enchantVar = ''.$banTimeRest.'|'.$email.'';
                  $cripteBan1 = $CR->secret($enchantVar, 'cripte'); $cripteBan1 = base64_encode($cripteBan1);
                  echo $CR->updateJS(' alerta("Cuenta bloqueada, desbloqueo en '.$banTimeRest.'.", "danger"); button(true); ');

               }

            } else {

               // check attemp fails
               $users_attemp = $db->query("SELECT userid, itime FROM login_attemp WHERE userid = :user_id AND itime = :idate");
               $db->bind(':user_id', $usId);
               $db->bind(':idate', $date);
               $db->execute(); $attemp_count = $db->rowCount(); $db->CloseConnection();

               if( $attemp_count > LIMIT_LOGINS ) { echo $CR->updateJS(' alerta("error message exced limit", "danger"); button(true); '); } else {

                  // attemp is null (?)
                  if($attemp_count <= 0) { $attemp_count = 0; }
                  $attemp_rest = LIMIT_LOGINS - $attemp_count;
                  // user exist & check password coincidence
                  if($password != $usPass) {

                     // insert attemp fail
                     $insert_attemp = $db->query("INSERT INTO login_attemp (userid, itime, ihours) VALUES(:useri, :itime, :ihours)");
                     $db->bind(':useri', $usId);
                     $db->bind(':itime', $datetime);
                     $db->bind(':ihours', $time);
                     $db->execute(); $db->CloseConnection();

                     $CR->logs('Contraseña incorrecta', 'El usuario ha fallado la conexión a la cuenta: #'.$attemp_count.'.', $usId, $usServ);
                     echo $CR->updateJS(' alerta("La contraseña ingresada es incorrecta.", "danger"); button(true); ');

                  } else {

                     // create token 
                     $token_rand = $CR->key('sb', '10');
                     $data_token = $usId . $email . $token_rand;
                     $token      = $CR->secret($data_token, 'cripte');

                     // defined maxlife // add 60 minutes
                     if( $remember == "on" ) { $min60 = "99999999"; } else { $min60 = 6000; }
                     $max_life   = $CR->control_time($datetime, '+', 'seconds', $min60, 'SH');

                     // check exit in login_temp
                     $searchSessionUser = $db->query("SELECT userid, id FROM login_temp WHERE userid = :usid");
                     $db->bind(':usid', $usId);
                     $db->execute(); $countSessionOn = $db->rowCount();

                     // session user exist 
                     if( $countSessionOn >= 1 ) {

                        $bC  = $db->single(); $bcGet = $bC['id']; $db->CloseConnection();
                        // delete login_temp accountID
                        $deleteB = $db->query("DELETE FROM login_temp WHERE id = :idB");
                        $db->bind(':idB', $bcGet);
                        $db->execute(); $db->CloseConnection();

                     }

                     // default remember
                     if( $remember == "on" ) { $remember = 1; } else { $remember = 0; }
                     // delete fails attemps
                     $deleteFail = $db->query("DELETE FROM login_attemp WHERE userid = :us3 AND itime = :dat3");
                     $db->bind(':us3', $usId);
                     $db->bind(':dat3', $date);
                     $db->execute(); $db->CloseConnection();
                     
                     // password is correct & insert attemp succes
                     $insert_succes = $db->query("INSERT INTO login_temp (userid, itime, ip, token, uagent, remember) VALUES(:us_id, :itime, :ip, :token, :uagent, :remember)");
                     $db->bind(':us_id', $usId);
                     $db->bind(':itime', time());
                     $db->bind(':ip', $ip);
                     $db->bind(':token', $token);
                     $db->bind(':uagent', $navegator);
                     $db->bind(':remember', $remember);
                     $db->execute(); $db->CloseConnection();               

                     // update last login 
                     $update_login = $db->query("UPDATE login SET lastlogin = :laslog, last_ip = :lastip, isonline = :ison3 WHERE userid = :acc_id");
                     $db->bind(':laslog', $datetime);
                     $db->bind(':lastip', $ip);
                     $db->bind(':acc_id', $usId);
                     $db->bind(':ison3', 1);
                     $db->execute(); $db->CloseConnection();   

                     // begin class session
                     $session = new PHPSession($PDO_INSTANCE);
                     $session->gc(300);
                     // create session
                     $session->write($usId, $token);
                     $_SESSION['id'] = $usId;

                     // change Status LoockScreen
                     $CR->updateData("login", "status", 1, "userid", $usId);
                     $CR->updateData("login", "statusd", 1, "userid", $usId);

                     // goLogout is null
                     if( $goLogout == null ) { $goURL = URL."/redirect"; } else { $goURL = $_SESSION['gologout']; }

                     // add log
                     $CR->logs('Inicio de sesión', 'El usuario se ha identificado correctamente.', $usId, $usServ);
                     echo $CR->updateJS(' alerta("Conexión exitosa, en segundos serás redireccionado.", "success"); ').$CR->refresh(2, $goURL);

                  }

               }

            }

         } else { echo $CR->updateJS(' alerta("error account validate", "danger"); button(true); '); }

      }

   }

?>