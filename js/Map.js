class Mapa {

    constructor() {
        this.mensajeError = "";  
        this.latitud = 43.217912;
        this.longitud = -5.890148;
    }

    error(error) {
        switch (error.code) {
            case error.PERMISSION_DENIED:
                this.mensajeError = "No se han aceptado los permisos"
                break;
            case error.POSITION_UNAVAILABLE:
                this.mensajeError = "Ubicación no disponible"
                break;
            case error.TIMEOUT:
                this.mensajeError = "La petición ha caducado";
                break;
            case error.UNKNOWN_ERROR:
                this.mensajeError = "Error desconocido";
                break;
        }
    }

    escribirDatos() {
        var texto;
        if (this.mensajeError != "")
            texto = "<p>Se ha producido un error: " + this.mensajeError + "</p>";
        else
            texto = "<p>Longitud: " + this.longitud + ", latitud: " + this.latitud + "</p>";
        $('p:last-child').after(texto);
    }

    dibujarMapa() {
        var url = "https://maps.googleapis.com/maps/api/staticmap?";
        var centro = "center=" + this.latitud + "," + this.longitud;
        var zoom = "&zoom=10";
        var tamaño = "&size=800x600";
        var marcador = "&markers=color:red%7Clabel:S%7C" + this.latitud + "," + this.longitud;
        var sensor = "&sensor=false";
        var apiKey = "&key=AIzaSyDEoip53Ju0MUT6UjDVqhRmeOjXb_0YVzU";
        var imagenMapa = url + centro + zoom + tamaño + marcador + sensor + apiKey;
        var texto = "<p><img src=\"" + imagenMapa + "\" alt=\"Mapa estático de Google Maps\"></p>";
        var h2Element = document.getElementsByTagName("h2")[2];
        h2Element.insertAdjacentHTML("afterend",texto);
        //$('p:last-child').after(texto);
        console.log(imagenMapa);
    }
}

var mapa = new Mapa();
mapa.dibujarMapa();