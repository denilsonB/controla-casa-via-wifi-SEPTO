<?php //condição para deixar os botões iguais ao seus valores no banco de dados
  include('recebe.php');
  $checkedUltraSsom = $sensorUltrassom->getEstado()=='L' ? 'checked' : '';
  $checkedLdr = $resistorLdr->getEstado()=='L' ? 'checked' : '';
  $checkedLedLdr = $ledLdr->getEstado()=='L' ? 'checked' : '';
  $checkedMicrofone = $microfone->getEstado()=='L' ? 'checked' : '';
?>
<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="Author" content="Denilson Bulhões">
    <meta charset="UTF-8"/>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
</head>
<body>
<div class="words" contenteditable>
</div>
<div id="demo">
  <h2>Casa inteligente</h2>
  <h3><i>Segurança</i></h3>
  <div>
  <label class="switch">
        <input type="checkbox" id="botaoUltrassom" name="botaoUltrassom" onclick="ativaSeguranca()" value="yes" <?php  echo $checkedUltraSsom; ?>> 
        <span class="slider round"></span>
  </label>
 </div>
<h3>LDR</h3>
 <div>
  <label class="switch">
        <input type="checkbox" id="botaoLdr" name="botaoLdr" onclick="ativaLdr()" value="yes" <?php  echo $checkedLdr; ?>> 
        <span class="slider round"></span>
  </label>
 </div>
 <h3>Luz de casa</h3>
 <div>
 <label class="switch">
        <input type="checkbox" id="botaoLedLdr" name="botaoLedLdr" onclick="ativaLedLdr()" value="yes" <?php  echo $checkedLedLdr; ?>> 
        <span class="slider round"></span>
 </label>
 </div>
 <h3>Microfone</h3>
 <div>
 <label class="switch">
        <input type="checkbox" id="botaoMicrofone" name="botaoMicrofone" onclick="ativaMicrofone()" value="yes" <?php  echo $checkedMicrofone; ?>> 
        <span class="slider round"></span>
 </label>
 </div>
 <div>
        <button type="button"  style="width: 80px;height: 50px; margin-top:30px" ><i class="fa fa-microphone" id="micIcon" style="font-size:30px; color: red;"></i></button>
  </div>
</div>
<script>//função de reconhecimento de voz
    window.SpeechRecognition = window.SpeechRecognition || window.webkitSpeechRecognition;//verificar se é o chrome ou firefox que está sendo usado, até o momento os outros navegadores não dão suporte a está função
    var recognition = new SpeechRecognition();
    recognition.interimResults = true;//reconhecer a cada palavra dita, sem está linha iria esperar o microfone não receber entrada para escrever tudo que foi dito


    $(document).ready(function(){
    $("button").mouseup(function(){
        console.log("botão não pressionado");
        //alert("F5");
        document.location.reload(true);
    });
      $("button").mousedown(function(){//quando o botão do microfone é pressionado

        let p = document.createElement('p');
        const words = document.querySelector('.words');
        words.append(p);


        recognition.addEventListener('result', e => {
            console.log(e.results);
            const transcript = Array.from(e.results)
            .map(result => result[0])
            .map(result => result.transcript)
            .join('')

            p.textContent = transcript;
            var xhttp = new XMLHttpRequest();

            if(transcript.includes('Ligar alarme')){
                words.append("alarme LIGADO");
                xhttp.open("GET", "https://casainteligentesepto.000webhostapp.com/envia.php?id=1&estado=L", true);
                xhttp.send();
            }            
            if(transcript.includes('Desligar alarme')){
                words.append("alarme DESLIGADO");
                xhttp.open("GET", "https://casainteligentesepto.000webhostapp.com/envia.php?id=1&estado=D", true);
                xhttp.send();
            }

            if(transcript.includes('Ligar resistor')){
                words.append("LDR LIGADO");
                xhttp.open("GET", "https://casainteligentesepto.000webhostapp.com/envia.php?id=2&estado=L", true);
                xhttp.send();
            }            
            if(transcript.includes('desligar resistor')){
                words.append("LDR DESLIGADO");
                xhttp.open("GET", "https://casainteligentesepto.000webhostapp.com/envia.php?id=2&estado=D", true);
                xhttp.send();
            }

            if(transcript.includes('Ligar luz')){
                words.append("LED LIGADO");
                xhttp.open("GET", "https://casainteligentesepto.000webhostapp.com/envia.php?id=3&estado=L", true);
                xhttp.send();
            }            
            if(transcript.includes('Desligar luz')){
                words.append("LED DESLIGADO");
                xhttp.open("GET", "https://casainteligentesepto.000webhostapp.com/envia.php?id=3&estado=D", true);
                xhttp.send();
            }
            if(transcript.includes('ligar microfone')){
                words.append("microfone LIGADO");
                xhttp.open("GET", "https://casainteligentesepto.000webhostapp.com/envia.php?id=4&estado=L", true);
                xhttp.send();
            }            
            if(transcript.includes('desligar microfone')){
                words.append("microfone DESLIGADO");
                xhttp.open("GET", "https://casainteligentesepto.000webhostapp.com/envia.php?id=4&estado=D", true);
                xhttp.send();
            }
            console.log(transcript);
        });
        recognition.addEventListener('end', recognition.start);

        document.getElementById("micIcon").style.color = 'green';
        recognition.start();
      });
    });

</script>
<script>
function ativaSeguranca() {
  var checkBox = document.getElementById("botaoUltrassom");

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     //document.getElementById("demo").innerHTML = this.responseText;
    }
  };
  if (checkBox.checked == true){
    xhttp.open("GET", "https://casainteligentesepto.000webhostapp.com/envia.php?id=1&estado=L", true);
    xhttp.send();
  }else{
    xhttp.open("GET", "https://casainteligentesepto.000webhostapp.com/envia.php?id=1&estado=D", true);
    xhttp.send();
  }
}
function ativaLdr() {
  var checkBox = document.getElementById("botaoLdr");

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    // document.getElementById("demo").innerHTML = this.responseText;
    }
  };
  if (checkBox.checked == true){
    xhttp.open("GET", "https://casainteligentesepto.000webhostapp.com/envia.php?id=2&estado=L", true);
    xhttp.send();
  }else{
    xhttp.open("GET", "https://casainteligentesepto.000webhostapp.com/envia.php?id=2&estado=D", true);
    xhttp.send();
  }
}
function ativaLedLdr() {
  var checkBox = document.getElementById("botaoLedLdr");

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    // document.getElementById("demo").innerHTML = this.responseText;
    }
  };
  if (checkBox.checked == true){
    xhttp.open("GET", "https://casainteligentesepto.000webhostapp.com/envia.php?id=3&estado=L", true);
    xhttp.send();
  }else{
    xhttp.open("GET", "https://casainteligentesepto.000webhostapp.com/envia.php?id=3&estado=D", true);
    xhttp.send();
  }
}
function ativaMicrofone() {
  var checkBox = document.getElementById("botaoMicrofone");

  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    // document.getElementById("demo").innerHTML = this.responseText;
    }
  };
  if (checkBox.checked == true){
    xhttp.open("GET", "https://casainteligentesepto.000webhostapp.com/envia.php?id=4&estado=L", true);
    xhttp.send();
  }else{
    xhttp.open("GET", "https://casainteligentesepto.000webhostapp.com/envia.php?id=4&estado=D", true);
    xhttp.send(null);
  }
}
</script>
</body>
</html>		