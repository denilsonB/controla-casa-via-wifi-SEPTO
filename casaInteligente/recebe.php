<?php
include "conecta_banco.php";
include "sensor.php";

$mostrar = isset($_GET["mostrar"]) ? $_GET["mostrar"] : 0;

$sql = "SELECT id_sensor,estado,nome FROM sensores";
$result = $conn->query($sql);

$list = array();

while($row = $result->fetch_assoc()){
    array_push($list,$row["id_sensor"]);
    array_push($list,$row["estado"]);
    array_push($list,$row["nome"]);
}

//while ($row = $result->fetch_assoc()) {
//    echo $row['estado'];
//}

$sensorUltrassom = new Sensor($list[0],$list[1],$list[2]);
$resistorLdr = new Sensor($list[3],$list[4],$list[5]);
$ledLdr = new Sensor($list[6],$list[7],$list[8]);
$microfone = new Sensor($list[9],$list[10],$list[11]);

if ($mostrar){
    echo $sensorUltrassom->getEstado() ;
    echo $resistorLdr->getEstado();
    echo $ledLdr->getEstado();
    echo $microfone->getEstado();
}

$conn->close();
?>