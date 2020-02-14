<?php
include "conecta_banco.php";
$id = $_GET["id"];
$estado = $_GET["estado"];

$sql = "UPDATE sensores SET estado='$estado' WHERE id_sensor='$id'";
$result = $conn->query($sql);



$conn->close();
?>