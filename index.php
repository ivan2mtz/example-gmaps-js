<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Ejemplo GMaps JS</title>
  <!-- Font Awesome Icons -->
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
  <script src="js/jQuery-2.1.4.min.js"></script>
  <script src="https://maps.google.com/maps/api/js?key=your-key"></script>
  <script type="text/javascript" src="js/gmaps.js"></script>
  <link href="css/bootstrap.min.css" rel="stylesheet" type="text/css" />
  <script src="js/notify.min.js" type="text/javascript"></script>
  <!-- Bootstrap 3.3.2 JS -->
  <script src="js/bootstrap.min.js" type="text/javascript"></script>
  <style>
    #map {
    width: 100%; 
    margin-top: 50px; 
    height: 100vh;
    min-height: 100%;
    max-height: none;
    }
  </style>
  <script type="text/javascript">
    // Redirigir con HTTPS
    if(window.location.protocol != 'https:') {
       location.href =   location.href.replace("http://", "https://");
    }
    var map;
    var markerOrigen;
    $(document).ready(function(){
            map = new GMaps({
        el: '#map',
        lat: 18.4629947,
        lng: -97.39353069999999,
        zoomControl : true,
        panControl : true,
        streetViewControl : true,
        mapTypeControl: true,
        overviewMapControl: true
      });

      $("#latOrigen").val(18.4629947);
      $("#lngOrigen").val(-97.39353069999999);
      markerOrigen = map.addMarker({
          lat: 18.4629947,
          lng: -97.39353069999999,
          title: 'Origen',
          draggable: true,
          infoWindow: {
          content: "Origen",
          },
          dragend: function(e) {
            $("#latOrigen").val(e.latLng.lat());
            $("#lngOrigen").val(e.latLng.lng());
          }
      });

      $("#address_origen").focus();

      $('#geocoding_form_origen').submit(function(e){
        e.preventDefault();
        GMaps.geocode({
          address: $('#address_origen').val().trim(),
          callback: function(results, status){
            if(status=='OK'){
              var latlng = results[0].geometry.location;
              map.setCenter(latlng.lat(), latlng.lng());
              markerOrigen.setPosition({
                lat: latlng.lat(),
                lng: latlng.lng()
              });
              $("#latOrigen").val(latlng.lat());
              $("#lngOrigen").val(latlng.lng());
            }
          }
        });
      });

      $("#ubicacionActual").on("click", function(e){
        e.preventDefault();
        GMaps.geolocate({
          success: function(position){
            map.setCenter(position.coords.latitude, position.coords.longitude);
            markerOrigen.setPosition({
              lat: position.coords.latitude,
              lng: position.coords.longitude,
            });
            $("#latOrigen").val(position.coords.latitude);
            $("#lngOrigen").val(position.coords.longitude);
          },
          error: function(error){
            alert('Geolocation failed: '+error.message);
          },
          not_supported: function(){
            alert("Your browser does not support geolocation");
          },
          always: function(){
            $("#address").focus();
          }
        });
      });

    });
  </script>
</head>
<body>

 <!-- Fixed navbar -->
 <nav class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">GJS</a>
        </div>
        <div id="navbar" class="collapse navbar-collapse">
          <form class="navbar-form navbar-left" method="post" id="geocoding_form_origen">
            <input type="hidden" name="latOrigen" id="latOrigen" value="18.45991186779577">
            <input type="hidden" name="lngOrigen" id="lngOrigen" value="-97.39123913167725">
            <input class="form-control" placeholder="Lugar..." id="address_origen" autofocus autocomplete="off"> 
            <button type="submit" class="btn btn-default">Buscar</button>
            <button type="button" class="btn btn-default" id="ubicacionActual" alt="Ubicación actual."><i class="fa fa-fw fa-map-marker"></i></button>
          </form>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div id="map" style="position: relative; overflow: hidden;"></div>

</body>
</html>