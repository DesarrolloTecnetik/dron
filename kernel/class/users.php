<?php 

	class Users {

		public function user( $id = null, $return = null ) {

			// get UserID global
			global $UserID;
			// conect DB class instance
			$db = new StudiosBIT;

			// conect to class Sessions
			$PDO_INSTANCE = 'SB_CR';
			$session = new PHPSession($PDO_INSTANCE);

			// default data is null
			$data = '';

			if($id != null) {

				$search_user = $db->query("SELECT $return FROM login WHERE userid = :userid");
				$db->bind(':userid', $id);
				$db->execute();

				$su 	= $db->single();
				if($db->rowCount() >= 1) { $data = $su[$return]; } else { $data = null; }

				$db->CloseConnection();

			}

				return $data;
 
		}

		public function session_exist() {

			// get UserID global
			global $UserID;

			// conect DB class instance
			$db = new StudiosBIT;

			// conect to class Sessions
			$PDO_INSTANCE = 'SB_CR';
			$session = new PHPSession($PDO_INSTANCE);

			// default return is false
			$return = false;
			if($UserID != null) {

				$return = true;

			} else {

				$return = false;

			}
			
				return $return;
 
		}

		public function session($id) {

			// get UserID global
			global $UserID;
			// conect DB class instance
			$db = new StudiosBIT;

			// conect to class Sessions
			$PDO_INSTANCE = 'SB_CR';
			$session = new PHPSession($PDO_INSTANCE);

			// conect to class functions
			$CR = new FUN;
			
			// cookie time limit life
			$limit_life = $CR->life_cookie();

			if($id == null || $id == 0) {

				// get now time (datetime)
				$nowtime = $CR->time('datetime');
				// convert datetime to timestamp
				$timestamp = strtotime($nowtime);

				// get session < to timestamp now
				$db->query("SELECT * FROM login_temp WHERE itime < :timeno");
				$db->bind(':timeno', $timestamp);
				$db->execute();

				if($db->rowCount() >= 1) {

					while($bl = $db->single()) {

						$rem = $bl['remember'];

						$time1 = date('Y-m-d H:i:s', $bl['itime']);
						// add limit cookie time
						$time2 = $CR->control_time($time1, '+', 'seconds', $limit_life, 'SH');
						$timestamp2 = strtotime($time2);

						// time2 is < timenow (?)
						if($timestamp2 < $timestamp AND $rem == 0) {

							// convert DATA to no CLOSE SESSION
							/*
							// delete session in DB
							$deleteD = $db->query("DELETE FROM login_temp WHERE userid = :id");
							$CR->deleteSQL('id', $bl['userid'], 'login_temp');
							$session->destroy($bl['userid']);
							unset($_SESSION['id']);
							@session_destroy();
							*/

						}

					}

				}

			} else {

				// search info 
				$db->query("SELECT * FROM login_temp WHERE userid = :usid");
				$db->bind(':usid', $UserID);
				$db->execute();

				$counter = $db->rowCount();
				if($counter >= 1) {

					$ds = $db->single();
					$rem = $ds['remember'];

					$time1 = date('Y-m-d H:i:s', $ds['itime']);
					$time2 = $CR->control_time($time1, '+', 'seconds', $limit_life, 'SH');
					$nowtime = $CR->time('datetime');

					if($nowtime > $time2 AND $rem == 0) {

						// convert DATA to no CLOSE SESSION
						/*
						$session->destroy($UserID);
						unset($_SESSION['id']);
						@session_destroy();
						*/

					}

				} else {

					// convert DATA to no CLOSE SESSION
					/*
					$session->destroy($UserID);
					unset($_SESSION['id']);
					@session_destroy();
					*/
					
				}

			}

		}

	}

    #
        # RUN USER DATA
    #
        
        global $User;
        global $Users;
        $User = new Users();
        $Users = new Users();

?>