<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <a class="navbar-brand" href="dashbord.php">Home</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNavDropdown">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" href="members.php">Adherents</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="categories.php?do=Categorie">Categories</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="livre.php?do=Manage">Livres</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="livre.php?do=Reservation">Reservations</a>
      </li>
      <li class="nav-item dropdown nav navbar-nav navbar-right">
        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          Oussama
        </a>
        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
          <a class="dropdown-item" href="members.php?do=Edit&id=1">Edit profile</a>
          <a class="dropdown-item" href="../user">Bibliotheque</a>
          <a class="dropdown-item" href="../user/logout.php">Logout</a>
        </div>
      </li>
    </ul>
  </div>
</nav>