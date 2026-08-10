<?php 

	require '../init.conf';

	$image = !empty($_FILES['inputBacheo']) ? $_FILES['inputBacheo'] : null;
	$auten = !empty($_POST['auten']) ? $_POST['auten'] : null;
	$nameImage = $image['name'];

	// upload image
	$nameFile = 'bache_'.rand(0, 99).'_'.date("ymdhis");
	$typeFiles  = array('image/jpg', 'image/jpeg', 'image/png', 'image/HEIC', 'image/*');
	// Proccess Image
	$handle = new upload($image);
	$handle->allowed = $typeFiles; 
	$handle->file_max_size = 10000000; 
	$handle->file_overwrite = true;
	$handle->file_new_name_body = $nameFile;
	$handle->image_resize = false;
	$newPath = PATH.'/assets/images/baches/';
	$handle->process($newPath);
	// verified Proccess
	if( $handle->file_is_image ) {

		if( $handle->processed ) {

			// get data and new name imageFile
			$img_ext = $handle->file_src_name_ext;
			$nameImage = $nameFile.'.'.$img_ext;
			$errorImage = false;

		} else { $errorImage = true; }

	} else { $errorImage = true; }

	// finish Process
	if( $errorImage == false ) {

		// update Data
		$db->query("INSERT INTO baches (auten, image) VALUES(:r1, :r2)");
		$db->bind(":r1", $auten);
		$db->bind(":r2", $nameImage);
		$db->execute();
		$lastID = $db->lastID();
		$db->CloseConnection();

	} else { echo $CR->updateJS(' alerta("Ocurrió un error al cargar la imagen, intenta nuevamente.", "danger"); button(true); modalX(); '); }


	// search image
	$imageF = $CR->searchBy("baches", "id", $lastID, "image");

?>

<div class="modalSB">
	
	<div class="buttonX"> <i class="fa fa-times"></i> </div>

	<div class="contentModal">
		
		<div class="imagen">
			
			<img src="<?php echo URL ?>/assets/images/baches/<?php echo $imageF ?>">

		</div>

		<div class="googleMaps">
			
			<button class="btn btn-secondary black" id="updateUbi">Usar mi ubicación</button>
			<fieldset class="gllpLatlonPicker col-md-12 col-sm-12">

            <div class="gllpMap" id="Mapa">Cargando mapa</div>
            <div class="pac-card" id="pac-card">

            	<div id="pac-container">

            		<input id="pac-input" class="form-control googleInput" type="text" placeholder="Ingresa la dirección..." />

            	</div>

            </div>

		   	<div id="infowindow-content">

					<span id="place-name" class="title"></span><br />
					<span id="place-address"></span>

				</div>
            
            <input type="hidden" class="gllpLatitude" value="19.4326077"/>
            <input type="hidden" class="gllpLongitude" value="-99.133208"/>
            <input type="hidden" class="gllpZoom" value="10"/>

         </fieldset>

		</div>

		<button id="cancel" onclick="modalX()" class="btn btn-danger">Cancelar</button>
		<button id="saved" class="btn btn-secondary floatRight">Enviar mi reporte</button>

	</div>

</div>

<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA_ydFQiAhgKWK6a3IFjr-DUjiNC2W1iL8&libraries=geometry,places" defer async loading=async></script>
<script type="text/javascript">

	setTimeout(function() { 
            
      var headID = document.getElementsByTagName("head")[0];         
      var newScript = document.createElement('script');
      newScript.type = 'text/javascript';
      newScript.src = '<?php echo URL ?>/assets/js/map.js';
      headID.appendChild(newScript);

   }, 1000);
	
	$(document).ready(function () {

		// En caso de no poder geolocalizar hay que tener un mensaje de error (o acción)
		function error() { alert('No se puede obtener tu ubicación actual'); }
		// Comprobamos si el navegador soporta la geolocalización
		if(navigator.geolocation) { navigator.geolocation.getCurrentPosition(localizacion, error); } else { alert('Navegador NO soporta geolocalización'); }

	});

	$(".buttonX").on("click", function(x) { modalX(); });

	function modalX() {

		$(".modalSB").fadeOut(1000);
		$(".loadermodals").html("");

	}

	$("#saved").on("click",function(e) {

		modalX();
		alerta("Gracias por tu reporte, pronto iniciaremos su revisión.", "success");

	});

	$('#UbicacionPersonal').click(function () {

		if(navigator.geolocation) { navigator.geolocation.getCurrentPosition(localizacion, error); } else { alert('Navegador NO soporta geolocalización'); }
		latitudReal = posicion.coords.latitude;
		longitudReal = posicion.coords.longitude;
		var markerPosicionReal = new google.maps.Marker({
		position: { lat: latitudReal, lng: longitudReal }, zoom: 18, title: "Mi actual ubicación" });
		markerPosicionReal.setMap(map);
		map.setCenter(markerPosicionReal.getPosition());

	});

	function localizacion(posicion) {

		var latitud = 17.9236717;
		var longitud = -94.8909297;

		// Generamos el mapa que muestre y cual será el punto central
		var map = new google.maps.Map(document.getElementById('Mapa'), { center: { lat: latitud, lng: longitud }, zoom: 14 });

		// Generamos el marcadores para señalar una posición
		var markerMiPosicion = new google.maps.Marker({

			position: { lat: latitud, lng: longitud }, title: "Ubicación CDMX" }

		);

		$('#UbicacionPersonal').click(function () {

			latitudReal = posicion.coords.latitude;
			longitudReal = posicion.coords.longitude;
			var markerPosicionReal = new google.maps.Marker({
				position: {
					lat: latitudReal,
					lng: longitudReal
				}, zoom: 18,
				title: "Mi actual ubicación"
			});
			markerPosicionReal.setMap(map);
			map.setCenter(markerPosicionReal.getPosition());

		});


		// Mostramos los marcadores en el mapa.
		markerMiPosicion.setMap(map);


	}

</script>