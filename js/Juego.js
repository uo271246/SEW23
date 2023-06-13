class Juego {

    spans = document.getElementsByTagName("span");
    p = document.getElementsByTagName("p");
    form = document.getElementsByTagName("form")[0];
    

    constructor() {
    	this.preguntas = ['¿Como se llama el concejo?',
        '¿Cuál es el más alejado del concejo?',
        '¿Cual es el restaurante mejor valorado?',
        '¿Cual es el mejor plato de carne?',
        '¿Cual es el mejor plato de verduras?',
        '¿Cuál es el mejor plato de legumbres?',
        '¿Cuál es la mejor feria gastronómica?',
        '¿Cuál es la ruta que empieza en La Vega?',
        '¿Cuál es la ruta que empieza en Calle Aldea la Collada?',
        '¿Cuál es la ruta que empieza en Carretera RI-6?'];
    	this.correctas = ['a','d','b','e','a','c','a','a','b','c'];
        this.respuestasA = ['Riosa','Proaza','El Puente','Escalopines','Parrillada de verduras','Fabada','Feria del caballo','Angliru','Angliru','Angliru'];
        this.respuestasB = ['Nueva York','Oviedo','El Hogar','Cachopo de jamon','Berenjena asada','Cocido','Feria de año nuevo','Collada','Collada','Collada'];
        this.respuestasC = ['Oviedo','Pola de Lena','El nuevo Lleron Riosa','Chuleton','Pimientos rellenos','Pote','Feria andaluza','Minas','Minas','Minas',];
        this.respuestasD = ['Aller','Gijón','La perdiz','Cordero','Pimientos del padrón','Lentejas','Feria del Rosario','Del oso','Del oso','Del oso'];
        this.respuestasE = ['Mieres','Mieres','Casa Yoli','Callos','Salchicha vegana','Frejoles','Feria de Navidad','De las xanas','De las xanas','De las xanas'];
    	this.respondidas = [];
    	this.contador = 0;
    }	

    getQuestion(){
        //Pregunta
       this.p[0].textContent = this.preguntas[this.contador];
       //Respuestas
       this.spans[0].textContent = this.respuestasA[this.contador];
       this.spans[1].textContent = this.respuestasB[this.contador];
       this.spans[2].textContent = this.respuestasC[this.contador];
       this.spans[3].textContent = this.respuestasD[this.contador];
       this.spans[4].textContent = this.respuestasE[this.contador];
    }

    getNextQuestion(){
        var inputs = document.getElementsByTagName('input');
        for (var i = 0; i < inputs.length; i++) {
        var input = inputs[i];
        if (input.checked) {
            this.seleccion = input;
            break;
        }
        }
        if(this.seleccion){
            if(this.contador < 10){
            this.respondidas[this.contador]=this.seleccion.value;
            this.contador++;
            this.getQuestion();
        }
            if (this.contador == 10) {
                this.puntuacion = this.checkAnswer();
                this.showMark();
            }
        }

    }

    checkAnswer() {
            var puntos = 0;
            for(var index = 0; index < 10; index++){
                if(this.respondidas[index] === this.correctas[index]){
                    puntos++;
                }
            }
            return puntos;
    }

    showMark() {
        this.p[1].textContent = 'Puntuación: ' + this.puntuacion;
    }

}

var juego = new Juego();
juego.getQuestion();
