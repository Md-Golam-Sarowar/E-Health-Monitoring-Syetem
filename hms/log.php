<?php
	$con = mysqli_connect("localhost","root","","form_validation");
	
	$errors = array();
	
	if($_SERVER['REQUEST_METHOD'] == 'POST'){
	
	if(preg_match("/\S+/", $_POST['fname']) === 0){
		$errors['fname'] = "* First Name is required.";
	}
	if(preg_match("/\S+/", $_POST['lname']) === 0){
		$errors['lname'] = "* Last Name is required.";
	}
	if(preg_match("/.+@.+\..+/", $_POST['email']) === 0){
		$errors['email'] = "* Not a valid e-mail address.";
	}
	if(preg_match("/.{8,}/", $_POST['password']) === 0){
		$errors['password'] = "* Password Must Contain at least 8 Chanacters.";
	}
	if(strcmp($_POST['password'], $_POST['confirm_password'])){
		$errors['confirm_password'] = "* Password do not much.";
	}
	
	if(count($errors) === 0){
		$fname = mysqli_real_escape_string($con, $_POST['fname']);
		$lname = mysqli_real_escape_string($con, $_POST['lname']);
		$email = mysqli_real_escape_string($con, $_POST['email']);
		
		$password = hash('sha256', $_POST['password']);
		function createSalt(){
   			$string = md5(uniqid(rand(), true));
    		return substr($string, 0, 3);
		}
		$salt = createSalt();
		$password = hash('sha256', $salt . $password);
		
		$search_query = mysqli_query($con, "SELECT * FROM members WHERE email = '$email'");
		$num_row = mysqli_num_rows($search_query);
		if($num_row >= 1){
			$errors['email'] = "Email address is unavailable.";
		}else{
			$sql = "INSERT INTO members(`fname`, `lname`, `email`, `salt`, `password`) VALUES ('$fname', '$lname', '$email', '$salt', '$password')";
			$query = mysqli_query($con, $sql);
			$_POST['fname'] = '';
			$_POST['lname'] = '';
			$_POST['email'] = '';
			
			$successful = "<h3> You are successfully registered.</h3>";
		}
	}
	}
?>

<!DOCTYPE html>
<html>
<head>
<link type="text/css" rel="stylesheet" href="assets/css/style1.css">
<title></title>
</head>
<body  style="background-color: grey;">
	<div id="container">

	<div class="menu">
	
	<table>
	<tr>
	<td> <a style="color: black; font-size: 30px; text-decoration: none; background-color: orange;" href="../index.html"> Home </a> </td>
	<td> <a style="color: black; font-size: 30px; text-decoration: none; background-color: Yellow;" href="../contact.php"> Contact </a> </td>

	</tr>
	</table>
	
	</div>
	
    	<div class="login">
        <form method="post" action="index4.html">
        	<table>
            	<tr>
                	<td><h1>E-mail</h1></td>
                    <td><h1>Password</h1></td>
                </tr>
                <tr>
                	<td><input type="text" name="login_email" id="login_email"></td>
                    <td><input type="password" name="login_password" id="login_password"></td>
                    <td><input type="submit" name="submit" id="login" value="Login"></td>
                </tr>
                <tr>
                	<td colspan="3"><?php if(isset($_GET['message'])){ echo "<h2>" .$_GET['message']. "</h2>"; } ?></td>
                </tr>
            </table>
        </form>
        </div>
       
            </table>
		</form>
        </div>
        <div class="footer"></div>
    </div>
</body>
</html>
