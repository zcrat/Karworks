$(function() {
  const canvas = document.getElementById('canvasfirma1');
  const firma = document.getElementById('canvasfirma2');
  const ctx = canvas.getContext('2d');
  const ctxf = firma.getContext('2d');
  let historial = [];
  let historialf = [];

  window.ajustarCanvas2=function(){
    historial = [];
    historialf = [];
    ajustarCanvas();
    ajustarfirma();
  }
  
  window.addEventListener('resize', cambiarcanvas);

  // Ajustar el tamaño interno del canvas
  function ajustarCanvas() {
    canvas.width = canvas.clientWidth;
    canvas.height = canvas.clientHeight;
  }
  function ajustarfirma(){
    firma.width = firma.clientWidth;
    firma.height = firma.clientHeight;
  }
  canvas.addEventListener('mousedown', (e) => {
    guardarEstado();
    ctx.beginPath();
    ctx.moveTo(e.offsetX, e.offsetY);

    const dibujar = (event) => {
      ctx.lineTo(event.offsetX, event.offsetY);
      ctx.stroke();
    };

    canvas.addEventListener('mousemove', dibujar);

    canvas.addEventListener('mouseup', () => {
      canvas.removeEventListener('mousemove', dibujar);
      ctx.closePath();
    }, { once: true });
  });

  firma.addEventListener('mousedown', (e) => {
    guardarEstadof();
    ctxf.beginPath();
    ctxf.moveTo(e.offsetX, e.offsetY);

    const dibujar = (event) => {
      ctxf.lineTo(event.offsetX, event.offsetY);
      ctxf.stroke();
    };

    firma.addEventListener('mousemove', dibujar);

    firma.addEventListener('mouseup', () => {
      firma.removeEventListener('mousemove', dibujar);
      ctxf.closePath();
    }, { once: true });
  });

  document.getElementById('deshacerfirma1').addEventListener('click', deshacer);
  document.getElementById('deshacerfirma2').addEventListener('click', deshacerfirma);
  document.getElementById('borrarfirma1').addEventListener('click', borrarfirma1);
  document.getElementById('borrarfirma2').addEventListener('click', borrarfirma2);

  function cambiarcanvas() {
    guardarEstado();
    guardarEstadof();
    let ultimoEstado = historial.pop();
    let ultimoEstadof = historialf.pop();
    ajustarCanvas();
    ajustarfirma();
    dibujarImagensinajustar(ultimoEstado);
    dibujarImagensinajustafr(ultimoEstadof);
  }

  function guardarEstado() {
    historial.push(canvas.toDataURL());
  }

  function guardarEstadof() {
    historialf.push(firma.toDataURL());
  }

  function deshacer() {
    if (historial.length > 0) {
      let ultimoEstado = historial.pop();
      dibujarImagensinajustar(ultimoEstado);
    }
  }

  function deshacerfirma() {
    if (historialf.length > 0) {
      let ultimoEstadof = historialf.pop();
      dibujarImagensinajustafr(ultimoEstadof);
    }
  }
  function borrarfirma1() {
    guardarEstado()
    ajustarCanvas();
  }
  function borrarfirma2() {
    guardarEstadof()
    ajustarfirma();
  }

  function dibujarImagen(urlimagen) {
    if(urlimagen){
    let img = new Image();
    img.src = urlimagen;
    img.onload = function () {
      const imgWidth = img.width;
      const imgHeight = img.height;
      const canvasWidth = canvas.clientWidth;
      const canvasHeight = canvas.clientHeight;
      const imgAspectRatio = imgWidth / imgHeight;
      const canvasAspectRatio = canvasWidth / canvasHeight;
      let renderWidth, renderHeight;
      if (imgAspectRatio > canvasAspectRatio) {
        renderWidth = canvasWidth;
        renderHeight = canvasWidth / imgAspectRatio;
      } else {
        renderHeight = canvasHeight;
        renderWidth = canvasHeight * imgAspectRatio;
      }
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      ctx.drawImage(img, 0, 0, renderWidth, renderHeight);
    };
  }else{
    ajustarCanvas();
  }
  }

  function dibujarImagensinajustar(urlimagen) {
    let img = new Image();
    img.src = urlimagen;
    img.onload = function () {
      ctx.clearRect(0, 0, canvas.width, canvas.height);
      ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
    };
  }

  function dibujarImagensinajustafr(urlimagen) {
    let img = new Image();
    img.src = urlimagen;
    img.onload = function () {
      ctxf.clearRect(0, 0, firma.width, firma.height);
      ctxf.drawImage(img, 0, 0, firma.width, firma.height);
    };
  }
window.executedibujarImagen1 = function (img) {
    const url = img + "?v=" + Date.now(); // evitar cache
    dibujarImagensinajustar(url);
};

window.executedibujarImagen2 = function (img) {
    const url = img + "?v=" + Date.now(); // evitar cache
    dibujarImagensinajustafr(url);
};

  
});
