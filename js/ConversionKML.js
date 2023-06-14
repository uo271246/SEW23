class Conversor {
	constructor(){}
	convertir() {
    $.ajax({
      dataType: "xml",
      url: "xml/rutas.xml",
      success: function(data) {
        const rutas = [];
        $(data).find('ruta').each(function() {
          const ruta = new Ruta($(this));
          rutas.push(ruta);
        });

        let kml = '<?xml version="1.0" encoding="UTF-8"?>\n';
        kml += '<kml xmlns="http://www.opengis.net/kml/2.2">\n';
        kml += '<Document>\n';

        rutas.forEach(function(ruta) {
          kml += ruta.generarKML();
        });

        kml += '</Document>\n';
        kml += '</kml>\n';

        console.log(kml);
      },
      error: function(data) {
        console.log(data);
      }
    });
  }
}


class Ruta {
  constructor(xml) {
    this.nombre = $(xml).attr("nombre");
    this.coordenadas = this.getCoordinates($(xml).find('coordenadas'));
    this.hitos = [];
    const self = this;
    $(xml).find('hito').each(function(index, hitoXml) {
      const hito = new Hito($(hitoXml));
      self.hitos[index] = hito;
    });
  }

  generarKML() {
    let kml = '<Placemark>\n';
    kml += `<name>${this.nombre}</name>\n`;
    kml += '<Point>\n';
    kml += `<coordinates>${this.coordenadas}</coordinates>\n`;
    kml += '</Point>\n';
    kml += '</Placemark>\n';
    if (this.hitos.length > 0) {
      const self = this;
      this.hitos.forEach(function(hito) {
        kml += hito.generarKML();
      });
    }
    return kml;
  }

  getCoordinates(xml) {
    const longitud = parseFloat(xml.find('longitud').text());
    const latitud = parseFloat(xml.find('latitud').text());
    const altitud = parseFloat(xml.find('altitud').text());
    return `${longitud},${latitud},${altitud}`;
  }
}

class Hito {
  constructor(xml) {
    this.nombre = $(xml).attr('nombre');
    this.coordenadas = this.getCoordinates($(xml).find('coordenadas'));
  }

  generarKML() {
    let kml = '<Placemark>\n';
    kml += `<name>${this.nombre}</name>\n`;
    kml += '<Point>\n';
    kml += `<coordinates>${this.coordenadas}</coordinates>\n`;
    kml += '</Point>\n';
    kml += '</Placemark>\n';
    return kml;
  }

  getCoordinates(xml) {
    const longitud = parseFloat(xml.find('longitud').text());
    const latitud = parseFloat(xml.find('latitud').text());
    const altitud = parseFloat(xml.find('altitud').text());
    return `${longitud},${latitud},${altitud}`;
  }
}

var conversion = new Conversor();

