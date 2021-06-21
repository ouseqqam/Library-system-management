<!DOCTYPE html>

<html>
	<head>
		<meta charset = "UTF-8" />
		<title><?php getTitle() ?></title>
		
		<link rel="stylesheet" type="text/css" href="layout/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="layout/css/fronts.css">
		<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/fontawesome.min.css">
		
	</head>

	<body>


		<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
			<a class="navbar-brand" href="index.php">Home</a>
			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="navbarNavDropdown">
				<ul class="navbar-nav">
					<li class="nav-item">
									<?php

										$cat = getCat();
										setCat($cat);

									?>
					</li>
				<?php 
					if (isset($_SESSION['user']) || isset($_SESSION['email']))
					{ 
				?>
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						<?php
							if (isset($_SESSION['user']))
								putUser($_SESSION['user']);
							else if (isset($_SESSION['email']))
							putUser($_SESSION['email']);
							
						?>
						</a>
						<div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
						<a class="dropdown-item" href="profile.php?do=Edit">Edit profile</a>
						<?php
						if (isset($_SESSION['user']))
						{
							echo "<a class='dropdown-item' href='emprinte.php'>reservation</a>";
						}?>
						<?php
						if (isset($_SESSION['email']))
						{
							echo "<a class='dropdown-item' href='../admin/dashbord.php'>go to admin side</a>";
						}?>
						<a class="dropdown-item" href="logout.php">Logout</a>
						</div>
					</li>
					<?php
					}
					else
					{
					?>
					<li class="nav-item">
						<div class="container">
						<a class=" log nav-link" href="login.php">Login</a>
						<a class="sign nav-link" href="signup.php">/signup</a>
						</div>
						
     				</li>
					<?php
					}
					?>
				</ul>
			</div>
		</nav>