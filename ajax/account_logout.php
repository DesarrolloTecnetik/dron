<?php

   require_once '../init.conf';

   // solo hacemos trabajo de cierre de sesión si en verdad había una sesión activa
   if( $UserID >= 1 ) {

      // log de la acción
      $CR->logs('Cierre de sesión', 'El usuario cerró sesión manualmente.', $UserID, $serverID);

      // elimina los tokens de sesión (login_temp) y marca isonline = 0
      $session->destroy($UserID);

   }

   // limpiar sesión PHP de todas formas (por si acaso quedó algo colgado)
   unset($_SESSION['id']);
   @session_destroy();

   // redirección directa; el toast "Sesión cerrada" se dispara en inicio.php vía ?logout=1
   header('Location: ' . URL . '/inicio?logout=1');
   exit;

?>
