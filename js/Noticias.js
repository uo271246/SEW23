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
        var h2Element = document.getElementsByTagName("h2")[1];
        h2Element.insertAdjacentHTML("afterend", "<dl></dl>");
        for (var numero in this.numeros) {
            h2Element.insertAdjacentHTML("afterend", "<dt>" + json.results[numero].title + ":</dt>");
            var info = json.results[numero].content;
            h2Element.insertAdjacentHTML("afterend", "<dd>" + info + "</dd>");
        }
    };
}

var noticias = new Noticias();
noticias.cargarDatos();
