<?php require "../../config/database.php";
session_start();

if(!(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] == 1 && (isset($_SESSION["seller"]) && $_SESSION["seller"] == 1))){
    $error =  "must be logged in to a seller account to upload";
	$_SESSION["error"] = $error;
    header("location: ../../index.php");
    exit;
}

if(isset($_SESSION["error"])){
    $error = $_SESSION["error"];
    echo "<span>$error</span>";
  }
  
  unset($_SESSION["error"]);

$category = mysqli_query($con,"SELECT * FROM `GVWA`.`categories` ORDER BY `categories`.`name` ASC");
#$category = mysqli_fetch_all($category);

#$returnResult = []; //initialise empty array
while($row = $category->fetch_assoc())
{
    $returnResult[] = $row;
}
$category = $returnResult;


include "../../navbar.php";
navigation_bar();
?>


<!DOCTYPE html>
<html>

<head>
<link href="../../css/login.css" rel="stylesheet" type="text/css"> 
</head>

<body>


<div id="form">
<form action="fileUpload.php" method="POST" enctype="multipart/form-data">

<p>
<label> Item Name </label>
<input type="text" id="name" name="name" required/>
</p>

<p>
<label> Item Price </label>
<input type="text" id="price" name="price" required/>
</p>

<div class="search-box">
    <select id="category" name="category" multiple="multiple" required>
        <?php
            if (! empty($category)) {
                 foreach ($category as $key => $value) { 
                    echo '<option value="' . implode("",$category[$key]) . '">' . implode("",$category[$key]) . '</option>';
                 }
             }
        ?>
    </select>
</div>

<p>
<label> Description </label>
<input type="text" id="description" name="description" required/>
</p>

<input type="hidden" id="seller" name="seller" value=<?php $_SESSION['username'];?>/>


<p>
<label> Item image: </label>
<input type="file" name="image" id="image">
</p>

<p>
<input type="submit" id="button" value="submit"/>
</p>
</form>

<form action="../../config/reload.php?page_name">
    <input type="hidden" value=<?php echo __DIR__;?>/fileUpload.php id="page_name" name="page_name"/>
    <input type="submit" value="Reset Item Upload backend" />
</form>

<form action="../../config/reload.php?page_name">
    <input type="hidden" value=<?php echo __FILE__;?> id="page_name" name="page_name"/>
    <input type="submit" value="Reset Item Upload Frontend" />
</form>



</div>