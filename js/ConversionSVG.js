class Conversion {
constructor() {
}

verDatos(datos) {
var cabecera = '<?xml version="1.0" encoding="UTF-8"?> <svg xmlns="http://www.w3.org/2000/svg">';
    var altitud;
    var lineTag = '';
    $(datos)
        .find("ruta")
        .each(function () {
            var nombre = '<title>' + $(this).attr("nombre") + '</title>';
            var xAnterior = 0;
            var yAnterior = 0;
            $(this).find("hito").each(function () {
                altitud = $(this).find("coordenadas").find("altitud").text();
                lineTag += '<line x1="' + xAnterior + '" y1="' + yAnterior + '" x2="' + (xAnterior + 200) + '" y2="' + altitud + '" style="stroke:blue; stroke-width:7"/>';
                yAnterior = altitud;
                xAnterior += 200;
            }
            );
            console.log(cabecera + nombre + lineTag + '</svg>');
        });
};

cargar() {
    $.ajax({
        dataType: "xml",
        url: "xml/rutas.xml",
        success: function (data) {
            conversion.verDatos(data);
        },
        error: function (data) {
            console.log(data);
        }
    });
}

}

var conversion = new Conversion();
