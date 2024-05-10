<?php require 'connection.php';

if(!isset($_SESSION['username'])){
    echo '<script>alert ("Please login first") ; window.location.href = "index.php"; </script>';
    exit();
}

$username = $_SESSION['username'];
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
    <?php

    echo '<h1>Welcome ' .$username. '</h1>';

    ?>
    <form action="" method="post">
    <h1>Enter your BMI</h1>
    <label for="kilo">Weight (Kilogram): </label>
    <input type="float" name="weight"><br><br>
    <label for="cm">Height (Centimeter): </label>
    <input type="float" name="height"><br><br>
    <button type="submit" name="submit">Submit</button>
<br><br><br>
    <table border="1" class="table table-dark">
	    <b>
		<th>Food ID</th>
		<th>Food Name</th>
        <th>Food Summary</th>
		<th>Food Calories</th>
		<th>Food Carbohydrates</th>
		<th>Food Picture</th>
    
    <?php

        if(isset($_POST['submit'])){
            $weight = $_POST['weight'];
            $height = $_POST['height'];

            if(empty($weight) || empty($height)){
                echo '<p class="text-danger" >Please enter weight and height.</p>';
            }else{
                $sql = "SELECT * FROM `bmi_users` WHERE `weight` = ? AND `height` = ?";
                $stmt = $conn->prepare($sql);
                $stmt -> bind_param("ss",$weight,$height);
                $stmt->execute();
                $result = $stmt->get_result();
                $row = $result->fetch_assoc();
        }
        $height = $height / 100;
        $division = $weight / ($height * $height);
        $format = number_format ($division, 2);


        $sql = "INSERT INTO `bmi_users`( `weight`, `height`, `bmi`) VALUES (?,?,?)";
        $stmt = $conn->prepare($sql);
        $stmt -> bind_param("sss",$weight,$height,$format);
        $stmt->execute();


          echo "<br>"."<br>".'Your BMI is '. $format."<br>";

          if ($format < 18.5) {
            $sql = "SELECT * FROM recommend WHERE reco_id = 1";
        $result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result -> fetch_assoc()){
			echo "<tr>";
			echo "<td>".$row["reco_id"]."</td>";
			echo "<td>".$row["food_name"]."</td>";
			echo "<td>".$row["food_desc"]."</td>";
			echo "<td>".$row["food_calories"]."</td>";
            echo "<td>".$row["food_carbohydrates"]."</td>";
			echo "<td><img src='data:image/jpeg;base64,".base64_encode($row["food_picture"])."' width='100' height='100'></td>";
			echo "</tr>";
        }
    }
    echo "You're Underweight. Please proceed to food library for recommended food and exercise.";
        } elseif ($format >= 18.5 && $format <= 24.9) {
            $sql = "SELECT * FROM recommend WHERE reco_id = 2";
        $result = $conn->query($sql);
	if($result->num_rows > 0){
		while($row = $result -> fetch_assoc()){
			echo "<tr>";
			echo "<td>".$row["reco_id"]."</td>";
			echo "<td>".$row["food_name"]."</td>";
			echo "<td>".$row["food_desc"]."</td>";
			echo "<td>".$row["food_calories"]."</td>";
            echo "<td>".$row["food_carbohydrates"]."</td>";
			echo "<td><img src='data:image/jpeg;base64,".base64_encode($row["food_picture"])."' width='100' height='100'></td>";
			echo "</tr>";
        }
    }
            echo "You're Normal. Please proceed to food library for recommended food and exercise. ";
        } elseif ($format >= 25 && $format <= 29.9) {
            $sql = "SELECT * FROM recommend WHERE reco_id = 3";
            $result = $conn->query($sql);
        if($result->num_rows > 0){
            while($row = $result -> fetch_assoc()){
                echo "<tr>";
                echo "<td>".$row["reco_id"]."</td>";
                echo "<td>".$row["food_name"]."</td>";
                echo "<td>".$row["food_desc"]."</td>";
                echo "<td>".$row["food_calories"]."</td>";
                echo "<td>".$row["food_carbohydrates"]."</td>";
                echo "<td><img src='data:image/jpeg;base64,".base64_encode($row["food_picture"])."' width='100' height='100'></td>";
                echo "</tr>";
            }
        }
            echo "You're Overweight. Please proceed to food library for recommended food and exercise.";
        } elseif ($format >= 30) {
            echo "You're Obese. Please proceed to food library for recommended food and exercise.";
        } else {
            echo "Invalid input"; // Add this in case $format is not a valid number
        }
        echo "<br>".'<a href="foodlibrary.php">Food Library</a>';
    }
    ?>
    </form>
</table>
</body>
</html>