<?php

include '../db/database.php';

$sql= "SELECT * FROM product LEFT JOIN product_type ON product.prodTypeID = product_type.prodTypeID";

$result = mysqli_query($link,$sql);

$productArray = array();
while ($row = mysqli_fetch_assoc($result)) {
    $productArray[] = $row;
}
echo json_encode($productArray);
