<?php

   require_once '../init.conf';
   $CR->ajaxToken();

   // campos del formulario público de registro
   $name       = !empty($_POST['name'])       ? trim($_POST['name'])       : null;
   $lastname   = !empty($_POST['lastname'])   ? trim($_POST['lastname'])   : null;
   $nacimiento = !empty($_POST['nacimiento']) ? trim($_POST['nacimiento']) : null;
   $country    = !empty($_POST['country'])    ? trim($_POST['country'])   : null;
   $email      = !empty($_POST['email'])      ? trim($_POST['email'])     : null;
   $pass       = !empty($_POST['pass'])       ? $_POST['pass']            : null;
   $pass2      = !empty($_POST['pass2'])      ? $_POST['pass2']           : null;

   $null_array = compact('name', 'lastname', 'nacimiento', 'country', 'email', 'pass', 'pass2');

   if( in_array('', $null_array, true) || in_array(null, $null_array, true) ) {

      echo $CR->updateJS(' alerta("Completa todos los campos para crear tu cuenta.", "danger"); button(true); ');

   } elseif( !filter_var($email, FILTER_VALIDATE_EMAIL) ) {

      echo $CR->updateJS(' alerta("Ingresa un correo electrónico válido.", "danger"); button(true); ');

   } elseif( strlen($pass) < 6 ) {

      echo $CR->updateJS(' alerta("La contraseña debe tener al menos 6 caracteres.", "danger"); button(true); ');

   } elseif( $pass !== $pass2 ) {

      echo $CR->updateJS(' alerta("Las contraseñas no coinciden.", "danger"); button(true); ');

   } else {

      // validar fecha de nacimiento (formato YYYY-MM-DD, real y no futura)
      $birthDT = DateTime::createFromFormat('Y-m-d', $nacimiento);
      $todayDT = new DateTime('now');
      $validBirth = ( $birthDT && $birthDT->format('Y-m-d') === $nacimiento && $birthDT <= $todayDT );

      if( !$validBirth ) {

         echo $CR->updateJS(' alerta("Ingresa una fecha de nacimiento válida.", "danger"); button(true); ');

      } else {

         // ¿ya existe una cuenta con ese correo?
         $existEmail = $CR->count_rows('login', 'WHERE email = :var', 'email', $email);

         if( $existEmail >= 1 ) {

            echo $CR->updateJS(' alerta("Ya existe una cuenta registrada con ese correo.", "danger"); button(true); ');

         } else {

            // generar un "user" corto y único a partir del correo (login acepta usuario O correo)
            $localPart = strstr($email, '@', true);
            $localPart = ($localPart !== false && $localPart !== '') ? $localPart : $email;
            $baseUser  = preg_replace('/[^a-zA-Z0-9_\.\-]/', '', $localPart);
            $baseUser  = substr($baseUser, 0, 25);
            if( $baseUser === '' ) { $baseUser = 'user'; }

            $user = $baseUser;
            $tries = 0;
            while( $CR->count_rows('login', 'WHERE user = :var', 'user', $user) >= 1 ) {
               $tries++;
               $user = substr($baseUser, 0, 25) . rand(100, 999);
               if( $tries > 5 ) { $user = substr($baseUser, 0, 20) . substr(uniqid(), -8); break; }
            }

            // token identificador de cuenta
            $token = md5($user.$email);

            // password con el mismo método usado en account_start.php (login)
            $password = $CR->encripter($pass);

            // rango por defecto para autoregistro: el menor rango existente distinto del de Administrador (9999)
            $db->query("SELECT rank FROM login_rank WHERE rank < 9999 ORDER BY rank ASC LIMIT 1");
            $db->execute();
            $defRankRow  = $db->single();
            $db->CloseConnection();
            $defaultRank = !empty($defRankRow['rank']) ? $defRankRow['rank'] : 1;

            // crear la cuenta
            $db->query("INSERT INTO login (user, pass, rank, token, name, lastname, nacimiento, country, email, ifecha, verified, status) VALUES (:u1, :u2, :u3, :u4, :u5, :u6, :u7, :u8, :u9, :u10, :u11, :u12)");
            $db->bind(':u1',  $user);
            $db->bind(':u2',  $password);
            $db->bind(':u3',  $defaultRank);
            $db->bind(':u4',  $token);
            $db->bind(':u5',  $name);
            $db->bind(':u6',  $lastname);
            $db->bind(':u7',  $nacimiento);
            $db->bind(':u8',  $country);
            $db->bind(':u9',  $email);
            $db->bind(':u10', $date);
            $db->bind(':u11', 1);
            $db->bind(':u12', 1);
            $db->execute();

            $newUserID = $db->lastInsertId();
            $db->CloseConnection();

            $CR->logs('Nuevo registro', "Se registró una nueva cuenta: {$email}.", $newUserID, null, $newUserID);

            // iniciar sesión automáticamente, igual que hace account_start.php al loguear
            $token_rand   = $CR->key('sb', '10');
            $data_token   = $newUserID . $email . $token_rand;
            $sessionToken = $CR->secret($data_token, 'cripte');

            $db->query("INSERT INTO login_temp (userid, itime, ip, token, uagent, remember) VALUES (:us_id, :itime, :ip, :token, :uagent, :remember)");
            $db->bind(':us_id',  $newUserID);
            $db->bind(':itime',  time());
            $db->bind(':ip',     $ip);
            $db->bind(':token',  $sessionToken);
            $db->bind(':uagent', $navegator);
            $db->bind(':remember', 0);
            $db->execute();
            $db->CloseConnection();

            $db->query("UPDATE login SET lastlogin = :laslog, last_ip = :lastip, isonline = 1 WHERE userid = :acc_id");
            $db->bind(':laslog', $datetime);
            $db->bind(':lastip', $ip);
            $db->bind(':acc_id', $newUserID);
            $db->execute();
            $db->CloseConnection();

            $session = new PHPSession($PDO_INSTANCE);
            $session->gc(300);
            $session->write($newUserID, $sessionToken);
            $_SESSION['id'] = $newUserID;

            echo $CR->updateJS(' alerta("¡Cuenta creada con éxito! Ya iniciaste sesión.", "success"); ').$CR->refresh(2, URL.'/inicio');

         }

      }

   }

?>
