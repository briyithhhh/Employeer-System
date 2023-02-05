<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "urbeadminsystembdd";

// Create connection
$conn = mysqli_connect($servername, $username, $password);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create database if not exists
$create_db_sql = "CREATE DATABASE IF NOT EXISTS $dbname";
if (mysqli_query($conn, $create_db_sql)) {
    echo "Database created successfully or already exists.<br>";
} else {
    echo "Error creating database: " . mysqli_error($conn) . "<br>";
}

// Connect to the database
$conn = mysqli_connect($servername, $username, $password, $dbname);

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Create table if not exists
$create_table_sql = "CREATE TABLE IF NOT EXISTS users (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(30) NOT NULL,
    lastname VARCHAR(30) NOT NULL,
    age INT(3) NOT NULL,
    civil_status VARCHAR(30) NOT NULL,
    sex VARCHAR(10) NOT NULL,
    salary VARCHAR(30) NOT NULL
)";

if (mysqli_query($conn, $create_table_sql)) {
    echo "Table created successfully or already exists.<br>";
} else {
    echo "Error creating table: " . mysqli_error($conn) . "<br>";
}

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $lastname = $_POST['lastname'];
    $age = $_POST['age'];
    $civil_status = $_POST['civil_status'];
    $sex = $_POST['sex'];
    $salary = $_POST['salary'];

    $data = [
        'name' => $name,
        'lastname' => $lastname,
        'age' => $age,
        'civil_status' => $civil_status,
        'sex' => $sex,
        'salary' => $salary,
    ];

    // Insert data into database
    $sql = "INSERT INTO users (name, lastname, age, civil_status, sex, salary) 
    VALUES ('$name', '$lastname', '$age', '$civil_status', '$sex', '$salary')";
    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }
}

// Select data from database
$sql = "SELECT * FROM users";
$result = mysqli_query($conn, $sql);

// Get Total Female Users
$total_female_users = mysqli_query($conn, "SELECT COUNT(*) FROM users WHERE sex = 'Female'");
$total_female_users = mysqli_fetch_array($total_female_users)[0];

// Get Total Married Male Users with salary more than 2500$
$total_married_male_users = mysqli_query($conn, "SELECT COUNT(*) FROM users WHERE civil_status = 'Married' AND sex = 'Male' AND salary = 'More than 2500$'");
$total_married_male_users = mysqli_fetch_array($total_married_male_users)[0];

// Get Total Widow Female Users with salary between 1000$ and 2500$
$total_widow_female_users = mysqli_query($conn, "SELECT COUNT(*) FROM users WHERE civil_status = 'Widow' AND sex = 'Female' AND salary = 'Between 1000$ and 2500$'");
$total_widow_female_users = mysqli_fetch_array($total_widow_female_users)[0];

// Get average age of male users
$average_age_of_male_users = mysqli_query($conn, "SELECT AVG(age) FROM users WHERE sex = 'Male'");
$average_age_of_male_users = mysqli_fetch_array($average_age_of_male_users)[0];

// Print data in table
echo "<table>";
echo "<tr>";
echo "<th>Name</th>";
echo "<th>Last Name</th>";
echo "<th>Age</th>";
echo "<th>Civil Status</th>";
echo "<th>Sex</th>";
echo "<th>Salary</th>";
echo "</tr>";

if (mysqli_num_rows($result) > 0) {
    // Output data for each row
    while($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>" . $row["name"] . "</td>";
        echo "<td>" . $row["lastname"] . "</td>";
        echo "<td>" . $row["age"] . "</td>";
        echo "<td>" . $row["civil_status"] . "</td>";
        echo "<td>" . $row["sex"] . "</td>";
        echo "<td>" . $row["salary"] . "</td>";
        echo "</tr>";
    }
} else {
    echo "0 results";
}

echo "</table>";

mysqli_close($conn);
?>

<form action="" method="post">
    <div>
        <label for="name">Name:</label>
        <input type="text" id="name" name="name">
    </div>
    <div>
        <label for="lastname">Last Name:</label>
        <input type="text" id="lastname" name="lastname">
    </div>
    <div>
        <label for="age">Age:</label>
        <input type="number" id="age" name="age">
    </div>
    <div>
        <label for="civil_status">Civil Status:</label>
        <select id="civil_status" name="civil_status">
            <option value="Married">Married</option>
            <option value="Single">Single</option>
            <option value="Widow">Widow</option>
        </select>
    </div>
    <div>
        <label for="sex">Sex:</label>
        <select id="sex" name="sex">
            <option value="Male">Male</option>
            <option value="Female">Female</option>
        </select>
    </div>
    <div>
        <label for="salary">Salary:</label>
        <select id="salary" name="salary">
            <option value="Less than 1000$">Less than 1000$</option>
            <option value="Between 1000$ and 2500$">Between 1000$ and 2500$</option>
            <option value="More than 2500$">More than 2500$</option>
        </select>
    </div>
    <div>
        <input type="submit" name="submit" value="Submit">
    </div>

    <ul>
	    <li>
            <p>Womens: <?php echo $total_female_users?></p>
	    </li>
	    <li>
            <p>Married male and gain more than 2500$: <?php echo $total_married_male_users?></p>
	    </li>
	    <li>
            <p>Widow Female and gain between 1000$ - 2500$: <?php echo $total_widow_female_users?></p>
	    </li>
	    <li>
            <p>Male average y/o: <?php echo (int) $average_age_of_male_users?></p>
	    </li>
    </ul>
</form>

<style>
    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
    }

    th {
        background-color: #ddd;
    }

    label {
        font-weight: bold;
    }

    input[type="text"], select {
        width: 100%;
        padding: 12px;
        margin-bottom: 20px;
        box-sizing: border-box;
        border: 2px solid #ccc;
        border-radius: 4px;
    }

    input[type="submit"] {
        width: 100%;
        background-color: #4CAF50;
        color: white;
        padding: 14px 20px;
        margin-top: 20px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    ul {
        list-style-type: none;
        padding: 0;
    }
</style>

