<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<body>

  <!-- Header -->
  <div class="site-header">
    <nav
      class="navbar container flex-row align-items-center justify-content-between pt-3 px-md-4 color-white bottom-wave box-shadow">

      <button id="navToggle" class="custom-toggle navbar-toggler" type="button">
        <i class="text-white fas fa-bars"></i>
      </button>

      <a class="my-0 mr-md-auto mb-sm-2 logo-wrapper" href="<?php echo base_url(); ?>" aria-label="Product">
        <img class="logo mx-auto" src="<?php echo base_url(); ?>assets/img/Rottnest-Fast-Ferries_logo.png" alt="Rottnest Fast Ferries Logo">
      </a>

      <div class="dropdown">
        <a class="p-2 text-white dropdown-toggle" href="#" data-toggle="dropdown" aria-haspopup="true"
          aria-expanded="false"><i class="ml-2 mr-2 fas fa-sign-in-alt d-none d-sm-inline-block"></i>Log in</a>
        <div class="dropdown-menu dropdown-menu-right text-dark" aria-labelledby="navbarDropdown">
          <a class="dropdown-item text-dark" href="#"><i class="fas fa-user mr-2"></i>Customer Login</a>
          <a class="dropdown-item text-dark" href="#"><i class="demo-icon agent-icon mr-2"></i>Agent Login</a>
        </div>
      </div>

      <!-- Navigation-->
      <div id="navbarNav" class="sidenav">
        <button class="closeBtn"><i class="fas fa-times"></i></button>
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class=" p-2 text-white nav-item nav-link " href="#"><i
                class="ml-2 mr-2 icon demo-icon gift-card-icon"></i>Gift Cards</a>
          </li>
        </ul>
      </div>
    </nav>
  </div>