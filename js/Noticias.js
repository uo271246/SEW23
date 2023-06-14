class Noticias {

    constructor() {
        this.url =  'https://newsdata.io/api/1/news?apikey=pub_2381176253f8cb8ceab71fecb615509bf7be7&country=es&language=es';
        this.numeros = [0,1,2,3,4];
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
                meteo.cargarNoticia(data);
            },
            error: function () {
                console.log("Algo ha ido mal");
            }
        });

    }

    cargarNoticia(json) {
        var ps = document.getElementsByTagName("p");
    var contador = 0;
    for (var i = 0; i < this.numeros.length; i++) {
        ps[i + 1].textContent = json.results[contador].title + ": " + json.results[contador].content;
        contador++;
    }
    };
}

var noticias = new Noticias();
noticias.cargarDatos();
