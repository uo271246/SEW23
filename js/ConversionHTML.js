class Conversor {

	constructor(){
  }

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
        
        let html = "<header><h1>Concejo de Riosa</h1></header>\n";
        html += "<nav>\n";
        html +='<a title="Indice" accesskey="i" href="index.html" tabindex="1">Índice</a>\n';
        html +='<a title="Gastronomía" accesskey="g" href="gastronomia.html" tabindex="2">Gastronomía</a>\n';
        html +='<a title="Juego" accesskey="j" href="juego.html" tabindex="3">Juego</a>\n';
        html +='<a title="Meteorología" accesskey="m" href="meteorologia.html" tabindex="4">Meteorología</a>\n';
        html +='<a title="Rutas" accesskey="r" href="rutas.html" tabindex="5">Rutas</a>\n';
        html +='<a title="Reservas" accesskey="s" href="php/reservas.php" tabindex="6">Reservas</a>\n';
        html +='<a title="Login" accesskey="c" href="php/login.php" tabindex="7">Iniciar sesión</a>\n';
        html +='<a title="Registrarse" accesskey="t" href="php/registro.php" tabindex="8">Registro</a>\n';
        html += "</nav>\n";
        html += "<main>\n";
        rutas.forEach(function(ruta) { 
          html += ruta.generarHTML();   
        });
        html += "</main>\n";
        html += '<footer><p>Autor : <em>Pablo Rodríguez Rodríguez</em></p></footer>\n';

        document.body.innerHTML = html;
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
    this.tipo = xml.find('tipo').text();
    this.transporte = xml.find('transporte').text();
    this.fechaInicio = xml.find('fechaInicio').text();
    this.horaInicio = xml.find('horaInicio').text();
    this.duracion = xml.find('duracion').text();
    this.agencia = xml.find('agencia').text();
    this.descripcion = xml.find('descripcion').text();
    this.personasAdecuadas = xml.find('personasAdecuadas').text();
    this.lugarInicio = xml.find('lugarInicio').text();
    this.direccionInicio = xml.find('direccionInicio').text();
    this.coordenadas = this.getCoordinates($(xml).find('coordenadas'));
    this.hitos = [];
    const self = this;
    $(xml).find('hito').each(function(index, hitoXml) {
      const hito = new Hito($(hitoXml));
      self.hitos[index] = hito;
    });
  }

  generarHTML() {
    let html = '<section>\n';
    html += `<h3>Ruta ${this.nombre}</h3>\n`; //h3
    html += `<p>Tipo ${this.tipo}</p>\n`;//tipo
    html += `<p>Transporte  ${this.transporte}</p>\n`;//transporte
    html += `<p>Fecha inicio ${this.fechaInicio}</p>\n`;//tipo
    html += `<p>Hora inicio ${this.horaInicio}</p>\n`;//transporte
    html += `<p>Duracion ${this.duracion}</p>\n`;//tipo
    html += `<p>Agencia ${this.agencia}</p>\n`;//transporte
    html += `<p>Descripcion ${this.descripcion}</p>\n`;//transporte
    html += `<p>Personas adecuadas ${this.personasAdecuadas}</p>\n`;//tipo
    html += `<p>Lugar inicio ${this.lugarInicio}</p>\n`;//transporte
    html += `<p>Direccion inicio ${this.direccionInicio}</p>\n`;//transporte
    html += `<p>Coordenadas inicio ${this.coordenadas} </p>\n`;
    if (this.hitos.length > 0) {
      const self = this;
      this.hitos.forEach(function(hito) {
        html += hito.generarHTML();
      });
    }

    html += '</section>\n'
    return html;
  }

  getCoordinates(xml) {
    const longitud = parseFloat(xml.find('longitud').text());
    const latitud = parseFloat(xml.find('latitud').text());
    const altitud = parseInt(xml.find('altitud').text());
    return `Longitud ${longitud}, Latitud ${latitud}, Altitud ${altitud}`;
  }
}

class Hito {
  constructor(xml) {
    this.nombre = $(xml).attr('nombre');
    this.unidades = $(xml).attr('unidades');
    this.descripcion = xml.find('descripcion').text();
    this.distancia = xml.find('distancia').text();
    this.coordenadas = this.getCoordinates(xml.find('coordenadas'));
    this.xml = xml;
  }

  generarHTML() {
    let html = `<h4>${this.nombre}</h4>\n`;
    html += `<p>Descripcion ${this.descripcion}</p>\n`;//transporte
    html += `<p>Distancia ${this.distancia} ${this.unidades}</p>\n`;
    html += `<p>Coordenadas ${this.coordenadas}</p>\n`;
    html += this.getFotos(this.xml);
    return html;
  }

  getCoordinates(xml) {
    const longitud = parseFloat(xml.find('longitud').text());
    const latitud = parseFloat(xml.find('latitud').text());
    const altitud = parseFloat(xml.find('altitud').text());
    return `Longitud ${longitud}, Latitud ${latitud}, Altitud ${altitud}`;
  }

  getFotos(xml) {
    var fotos = xml.find('fotos');
    let htmlFotos = '';
    fotos.find('foto').each(function() {
      var fotoURL = $(this).text();
      htmlFotos += `<img src="${fotoURL}" alt="Foto">\n`;
    });

    return htmlFotos;
  }
}

class Initializer {
  constructor(conversion){
    this.conversion = conversion;
  }

  iniciar(){
      $(document).ready(function() {
        conversion.convertir();
      });
    
  }
}

var conversion = new Conversor();
var initializer = new Initializer(conversion);
initializer.iniciar();
