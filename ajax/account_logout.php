<?php 

   require '../init.conf';
   $CR->ajaxToken();

   // exist session (?)
   if( $UserID <= 0 ) {

      // check sessions expired
      $User->session(0);
      echo $CR->updateJS(' alerta("success message logout", "success"); ').$CR->refresh(2, URL.'/redirect');

   } else {

      // add log
      $CR->logs('log title', 'log message descrip.', $UserID, $serverID);
      // continue delete log in DB
      $session_search = $db->query("DELETE FROM login_temp WHERE userid = :account");
      $db->bind(':account', $UserID);
      $db->execute(); $db->CloseConnection();
      // delete statusd
      $CR->updateData("login", "statusd", "0", "userid", $UserID);
      // session destroys
      $session->destroy($UserID); unset($_SESSION['id']); @session_destroy();
      echo $CR->updateJS(' alerta("success message logout", "success"); ').$CR->refresh(2, URL.'/redirect');

   }

?>