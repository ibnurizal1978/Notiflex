<?php  
session_start();
ini_set('display_errors',1);  error_reporting(E_ALL);
require_once "../config.php";
require_once "inc/check_session.php";
$type = Encryption::decode($_POST['type']);

if(!empty($_FILES["excel_file"]))  {  
  $file_array = explode(".", $_FILES["excel_file"]["name"]);  
  if($file_array[1] == "xlsx")  {  
    include("../scripts/PHPExcel/IOFactory.php");  

    $object = PHPExcel_IOFactory::load($_FILES["excel_file"]["tmp_name"]);  
    foreach($object->getWorksheetIterator() as $worksheet)  {  
      $highestRow = $worksheet->getHighestRow();  
      for($row=2; $row<=$highestRow; $row++)  {  

        if($type == 'EMPLOYEE')
        {
          $name     = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(0, $row)->getValue());  
          $position = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(1, $row)->getValue());  
          $email    = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(2, $row)->getValue());  
          $phone    = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(3, $row)->getValue());  

          $query = "INSERT INTO tbl_employee (client_id, name, position, email, phone, type, created_at) VALUES ('".$_SESSION['client_id']."', '".$name."','".$position."','".$email."','".$phone."', '".$type."', UTC_TIMESTAMP())";
        }

        if($type == 'VEHICLE')
        {
          $name     = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(0, $row)->getValue());  
          $vehicle_type = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(1, $row)->getValue());  
          $vehicle_year    = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(2, $row)->getValue());  
          $vehicle_plate    = mysqli_real_escape_string($conn, $worksheet->getCellByColumnAndRow(3, $row)->getValue());  

          $query = "INSERT INTO tbl_employee (client_id, name, vehicle_type, vehicle_year, vehicle_plate, type, created_at) VALUES ('".$_SESSION['client_id']."', '".$name."','".$vehicle_type."','".$vehicle_year."','".$vehicle_plate."', '".$type."', UTC_TIMESTAMP())";
        }
        mysqli_query($conn, $query);  
        }  
      }  

      echo "<script>";
      echo "alert('$data_success'); window.location.href=history.back()";
      echo "</script>";
      exit();
    }else{  
        echo "<script>";
        echo "alert('invalid file'); window.location.href=history.back()";
        echo "</script>";
        exit();  
  }  
} else{
    echo "<script>";
    echo "alert('file is empty'); window.location.href=history.back()";
    echo "</script>";
    exit();  
}
 ?>  