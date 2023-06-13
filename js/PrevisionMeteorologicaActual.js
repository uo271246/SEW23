class PrevisionMeteorologicaActual {

    constructor() {
        this.url = 'https://api.openweathermap.org/data/2.5/weather?lat=43.217912&lon=-5.890148&units=metric&lang=es&APPID=f2467b8d8b345f52a3a37868429dc64f'
    }	
    cargarDatos() {
        $('body').append("<dl></dl>");
            this.apiCall();
    };

    cargarTiempo(json) {
        var info ="El tiempo es de " + json.weather[0].description + '\n';
        //$('p').append(info);     
        info += "La temperatura es de " + json.main.temp + '\n';
        //$('p').append(info);
        info += "Humedad " + json.main.humidity + '\n';
        //$('p').append(info);
        var h2Element = document.getElementsByTagName("h2")[3];
        h2Element.insertAdjacentHTML("afterend",info);
    };

    apiCall() {
        var meteo = this;
        $.ajax({
            dataType: "json",
            url: this.url,
            method: 'GET',
            success: function (data) {
                meteo.cargarTiempo(data);
            },
            error: function () {
                console.log("Algo ha ido mal");
            }
        });

    }
}

var previsionMeteorologicaActual = new PrevisionMeteorologicaActual();
previsionMeteorologicaActual.cargarDatos();
