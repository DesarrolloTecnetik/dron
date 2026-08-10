<?php 
	
	// mode 0 == sandbox test | mode 1 == live 
	if(PAYPAL_MODE == 1){

		define('PP_BASE_URL', "https://api.paypal.com/v1/");

	} else {

		define('PP_BASE_URL', "https://api.sandbox.paypal.com/v1/");

	}

	// begin paypal express payments
	class paypalExpress {

		/**
			*  
			*  start paypal payment
			*  @var paymentID 		-> ID paypal [paypalid]
			*  @var pid 				-> userID [userid]
			*  @var payerID 			-> id to pay web reference [payerid]
			*  @var paymentToken 	-> generate rand token ID to payment [token] 
			*  
		**/
		public function paypalCheck($paymentID, $usID, $payerID, $paymentToken) {

			// instances other class
			$CR = new FUN;
			$db = new StudiosBIT;

			$curlH 				= curl_init();
			$ppCLIENT 			= PAYPAL_CLIENT;
			$ppSECRET 			= PAYPAL_SECRET;

			curl_setopt($curlH, CURLOPT_URL, PP_BASE_URL.'oauth2/token');
			curl_setopt($curlH, CURLOPT_HEADER, false);
			curl_setopt($curlH, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($curlH, CURLOPT_POST, true);
			curl_setopt($curlH, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($curlH, CURLOPT_USERPWD, $ppCLIENT . ":" . $ppSECRET);
			curl_setopt($curlH, CURLOPT_POSTFIELDS, "grant_type=client_credentials");

			$cData 				= curl_exec($curlH);
			$accessTokenPP 	= null;

			// return data from PayPal (?)
			if(empty($cData)) {

				// no received [return false = error]
				return false;

			} else {

				// json decode data from PP
				$jSON 				= json_decode($cData);
				// create access token PP
				$accessTokenPP 	= $jSON->access_token;
				// reference to BASE connect CURL
				$curl 				= curl_init(PP_BASE_URL.'payments/payment/' . $paymentID);

				// set data to curl headers
				curl_setopt($curl, CURLOPT_POST, 	false);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($curl, CURLOPT_HEADER, false);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array(

					'Authorization: Bearer ' . $accessTokenPP,
					'Accept: application/json',
					'Content-Type: application/xml'

				));

				// save data execute in curl
				$cResponse 		= curl_exec($curl);
				// decode json data response
				$cData 			= json_decode($cResponse);

				// get status order pp	            
				$ppcState 				= $cData->state;
				// get total USD payments
				$ppcUSD 					= $cData->transactions[0]->amount->total;
				// get type of currency [USD or MX or other]
				$ppcCurrency 			= $cData->transactions[0]->amount->currency;
				// get subtotal payment
				$ppcSubtotal 			= $cData->transactions[0]->amount->details->subtotal;
				// name of buyer
				$ppcName 				= $cData->transactions[0]->item_list->shipping_address->recipient_name;
				// email of buyer
				$ppcEmail 				= $cData->payer->payer_info->email;
				// payment ID PP
				$ppcID					= $cData->id;

				// close connections
				curl_close($curlH);
				curl_close($curl);

				// payment succesfull
				if($ppcState == 'approved' && $ppcID == $paymentID) {

					// change payment USD for credits [dracoins] // round -> convert in INT
					$buyINT 			= round($ppcUSD);
					$buyCredits 	= CASH_PER_USD * $buyINT;
					// get datetime
					$itime 			= $CR->time('datetime');
					// valids return
					$ppvalid  			= 1;
					$ppreceived 		= 1;

					// search PayID in datebase
					$searchPaymentID 		= $db->query("SELECT id FROM credits_buy WHERE paypalid = :payID");
					$db->bind(':payID',	$paymentID);
					$db->execute();
					$ppIsDouble 			= $db->rowCount();
					$db->CloseConnection();

					if($ppIsDouble >= 1) {

						// payment is exist [error return]
						return false;

					} else {

						// register log
						$CR->logs('Compra de Dracoins', 'El usuario ha comprado (PP: '.$paymentID.'), <b>'.$buyCredits.'</b> Dracoins a <b>$'.$ppcUSD.'</b>.', $usID);
						// get credits now user
						$userCredits 		= $CR->search_id('userid', $usID, 'login', 'credits');
						// add coins buyed to now credits user
						$userCreditsBuy 	= $userCredits + $buyCredits;
						// update user
						$updateUsers = $db->query("UPDATE login SET credits = :coins1 WHERE userid = :user1");
						$db->bind(':coins1', $userCreditsBuy);
						$db->bind(':user1', $usID);
						$db->execute();
						$db->CloseConnection();
						// register buy in database
						$db->query("INSERT INTO credits_buy (userid, paypalid, currency, token, cash, itime, received, valid, email, usd, payerid) VALUES(:account, :payid, :currency, :token, :cash, :itime, :recev, :valid, :email, :usd, :payer)");
						$db->bind(':account', 	$usID);
						$db->bind(':payid', 		$paymentID);
						$db->bind(':currency', 	$ppcCurrency);
						$db->bind(':token', 		$paymentToken);
						$db->bind(':cash', 		$buyCredits);
						$db->bind(':itime', 		$itime);
						$db->bind(':recev', 		$ppreceived);
						$db->bind(':valid', 		$ppvalid);
						$db->bind(':email', 		$ppcEmail);
						$db->bind(':usd', 		$ppcUSD);
						$db->bind(':payer', 		$payerID);
						$db->execute();
						$db->CloseConnection();
						// return true [correct buy]
						return true;

					}

				} else{

					// return or execute error payment
					return false;

				}

			}

		}
	    
	}

?>