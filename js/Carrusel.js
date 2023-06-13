class Carrusel {

    constructor() {
    	this.arraySrc = ['multimedia/angliru.png','multimedia/aldea.png','multimedia/aramo.png','multimedia/arroxines.png','multimedia/poblado_minero.png'];
    	this.arrayAlt = ['El Angliru','Hotel Spa la aldea soñada','Sierra del Aramo','Río',"Poblado minero"];
    	this.counter = 0;
    }	

    getNext(){
        this.counter++;
        if(this.counter > 4){
            this.counter = 0;
        }
        document.getElementsByTagName('img')[0].src = this.arraySrc[this.counter];
        document.getElementsByTagName('img')[0].alt = this.arrayAlt[this.counter];
    }

    getPrevious(){
        this.counter--;
        if(this.counter < 0){
            this.counter = 4;
        }
        document.getElementsByTagName('img')[0].src = this.arraySrc[this.counter];
        document.getElementsByTagName('img')[0].alt = this.arrayAlt[this.counter];
    }

}

var carrusel = new Carrusel();
