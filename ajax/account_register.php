<?php
      
   require '../init.conf';
   $CR->ajaxToken();

   $user = !empty( $_POST['user'] ) ? $_POST['user'] : null;
   $pass = !empty( $_POST['pass'] ) ? $_POST['pass'] : null;
   $rank = !empty( $_POST['rank'] ) ? $_POST['rank'] : null;
   $name = !empty( $_POST['name'] ) ? $_POST['name'] : null;
   $lastname = !empty( $_POST['lastname'] ) ? $_POST['lastname'] : null;
   $phone = !empty( $_POST['phone'] ) ? $_POST['phone'] : null;
   $email = !empty( $_POST['email'] ) ? $_POST['email'] : null;

   $null_array = compact('user', 'pass', 'rank', 'name', 'email', 'lastname');
   if( in_array('', $null_array) ) { echo $CR->updateJS(' alerta("Completa los campos marcados con *.", "danger"); button(true); '); } else {

      // exist username
      $existName = $CR->count_rows('login', 'WHERE user = :var', 'user', $user);
      if( $existName >= 1 ) { echo $CR->updateJS(' alerta("El nombre de Usuario ya está siendo utilizado.", "danger"); button(true); '); } else {

         // generate token
         $cripte = $user.$email;
         $token = md5($cripte);

         // password encripter
         $password = $CR->encripter($pass);

         // verified exist rank
         $exRank = $CR->count_rows("login_rank", "WHERE rank = :var", "id", $rank);
         if( $exRank >= 1 ) {

            // create and register log
            $db->query("INSERT INTO login (user, pass, rank, token, name, lastname, ifecha, phone, email ) VALUES(:u1, :u2, :u3, :u4, :u5, :u6, :u7, :u8, :u9)");
            $db->bind(":u1", $user);
            $db->bind(":u2", $password);
            $db->bind(":u3", $rank);
            $db->bind(":u4", $token);
            $db->bind(":u5", $name);
            $db->bind(":u6", $lastname);
            $db->bind(":u7", $date);
            $db->bind(":u8", $phone);
            $db->bind(":u9", $email);
            $db->execute();
            $lastID = $db->lastInsertId();
            $db->CloseConnection();
            $CR->logs( "Alta Personal", "Se ha dado de alta nuevo Personal: {$user}.", $UserID, $serverID, $lastID );
            echo $CR->updateJS(' alerta("Se dio de Alta el Personal.", "success"); $("#formPA")[0].reset(); ');

         } else { echo $CR->updateJS(' alerta("Error en el cargo seleccionado.", "danger"); button(true); '); }

      }

   }

?>