class PrevisionMeteorologica {

    constructor() {
        this.url = 'https://api.open-meteo.com/v1/forecast?latitude=43.13&longitude=-5.80&daily=temperature_2m_max,temperature_2m_min&timezone=auto';
    }	

    cargarDatos() {
        this.apiCall();
    };

    apiCall() {
        var meteo = this;
        $.ajax({
            dataType: "json",
            url: this.url,
            method: 'GET',
            success: function (data) {
            var html = meteo.cargarTiempo(data);
            $('section').append(html);
            },
            error: function () {
                console.log("Algo ha ido mal");
            }
        });

    }

    cargarTiempo(json) {
        var info ="";
        for(var dia = 0; dia< 7; dia++){
        info += "<p>" + json.daily.time[dia] + " Temperatura min: " + json.daily.temperature_2m_min[dia] + " Temperatura max: " + json.daily.temperature_2m_max[dia] +"</p>";
        }
        return info;
    };
}

var previsionMeteorologica = new PrevisionMeteorologica();
previsionMeteorologica.cargarDatos();
