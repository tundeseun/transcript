<!DOCTYPE html>
<html lang="en" data-footer="true" data-override='{"attributes": {"placement": "vertical" }}'>

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <title>Transcript Application | Dashboard</title>
  <meta name="description" content="Login Page" />
  <!-- Favicon Tags Start -->

  <link rel="icon" type="image/png" href="img/favicon/favicon-196x196.png" sizes="196x196" />
  <link rel="icon" type="image/png" href="img/favicon/favicon-96x96.png" sizes="96x96" />
  <link rel="icon" type="image/png" href="img/favicon/favicon-32x32.png" sizes="32x32" />
  <link rel="icon" type="image/png" href="img/favicon/favicon-16x16.png" sizes="16x16" />
  <link rel="icon" type="image/png" href="img/favicon/favicon-128.png" sizes="128x128" />

  <!-- Favicon Tags End -->
  <!-- Font Tags Start -->
  <link rel="preconnect" href="https://fonts.gstatic.com" />
  <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300;400;700&display=swap" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="font/CS-Interface/style.css" />
  <!-- Font Tags End -->
  <!-- Vendor Styles Start -->
  <link rel="stylesheet" href="css/vendor/bootstrap.min.css" />
  <link rel="stylesheet" href="css/vendor/OverlayScrollbars.min.css" />
  <link href="css/sb-admin-2.min.css" rel="stylesheet">

  <!-- Vendor Styles End -->
  <!-- Template Base Styles Start -->
  <link rel="stylesheet" href="css/styles.css" />
  <!-- Template Base Styles End -->

  <link rel="stylesheet" href="css/main.css" />
  <script src="js/base/loader.js"></script>
</head>

<body>
  <div id="root">
    <div id="nav" class="nav-container d-flex">
      <div class="nav-content d-flex">
        <!-- Logo Start -->
        <div class="logo position-relative">
          <a href="Dashboards.Default.html">
            <!-- Logo can be added directly -->
            <!-- <img src="img/logo/logo-white.svg" alt="logo" /> -->

            <!-- Or added via css to provide different ones for different color themes -->
            <div class="img"></div>
          </a>
        </div>
        <!-- Logo End -->



        <!-- User Menu Start -->
        <div class="user-container d-flex">
          <div class="d-flex user position-relative" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <img class="profile" alt="profile" src="img/profile-11.webp" />
            <div class="name">Applicant Name</div>
          </div>
          <div class="dropdown-menu dropdown-menu-end user-menu wide">


          </div>
        </div>
        <!-- User Menu End -->



        <!-- Menu Start -->
        <div class="menu-container flex-grow-1">

          <ul id="menu" class="menu">
            <li>
              <a href="#dashboards" data-href="#">
                <i data-acorn-icon="home" class="icon" data-acorn-size="18"></i>
                <span class="label">Dashboards</span>
              </a>
              <ul id="dashboards">
                <li>
                  <a href="#">
                    <span class="label">Test</span>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <span class="label">Test2</span>
                  </a>
                </li>
                <li>
                  <a href="#">
                    <span class="label">Test3</span>
                  </a>
                </li>
              </ul>
            </li>
            <li>
              <a href="#apps" data-href="#">
                <i data-acorn-icon="screen" class="icon" data-acorn-size="18"></i>
                <span class="label">Apps</span>
              </a>

            </li>
            <li>
              <a href="#pages" data-href="#">
                <i data-acorn-icon="notebook-1" class="icon" data-acorn-size="18"></i>
                <span class="label">Pages</span>
              </a>

            </li>
            <li>
              <a href="#blocks" data-href="#">
                <i data-acorn-icon="grid-5" class="icon" data-acorn-size="18"></i>
                <span class="label">Blocks</span>
              </a>

            </li>
            <li class="mega">
              <a href="#interface" data-href="#">
                <i data-acorn-icon="pocket-knife" class="icon" data-acorn-size="18"></i>
                <span class="label">Interface</span>
              </a>

            </li>
          </ul>
        </div>
        <!-- Menu End -->

        <!-- Mobile Buttons Start -->
        <div class="mobile-buttons-container">
          <!-- Scrollspy Mobile Button Start -->
          <a href="#" id="scrollSpyButton" class="spy-button" data-bs-toggle="dropdown">
            <i data-acorn-icon="menu-dropdown"></i>
          </a>
          <!-- Scrollspy Mobile Button End -->

          <!-- Scrollspy Mobile Dropdown Start -->
          <div class="dropdown-menu dropdown-menu-end" id="scrollSpyDropdown"></div>
          <!-- Scrollspy Mobile Dropdown End -->

          <!-- Menu Button Start -->
          <a href="#" id="mobileMenuButton" class="menu-button">
            <i data-acorn-icon="menu"></i>
          </a>
          <!-- Menu Button End -->
        </div>
        <!-- Mobile Buttons End -->
      </div>
      <div class="nav-shadow"></div>
    </div>
    <!-- Topbar -->
    <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

      <!-- Sidebar Toggle (Topbar) -->
      <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
      </button>

      <!-- Topbar Search -->
      <div id="nav1" onmouseover="closeCity(event, 'Paris')">

        <!--
    <div class="top-logo">

      <img style="width: 30px;" src="img/ui-logo.png" class="logo">

  </div>
-->
        <img style="width: 30px;" src="img/ui-logo.png" class="logo">
        <div class="topbar-divider d-none d-sm-block"></div>
        <img style="width: 40px; margin-right:1rem;margin-bottom:0.3rem; " src="img/logo.png" class="logo">
        <h1 class="h3 m-0 font-weight-100 text-primary" style="">Transcript Processing Application</h1>
        <!--
  <div class="top-logo">

      <img style="width: 50px;" src="img/logo.png" class="logo">

  </div>
-->
      </div>
      <!-- Topbar Navbar -->
      <ul class="navbar-nav ml-auto">


        <!-- Nav Item - User Information -->
        <li class="nav-item dropdown no-arrow">
          <form method="post" class=" nav-link"><button class="trash logout" type="submit" name="logout"><i class="fas fa-sign-out-alt"></i>Logout</button></form>

        </li>

      </ul>

    </nav>


    <main>

      <div class="container">
        <!-- Title and Top Buttons Start -->

        <div class="page-title-container">
          <div class="row">
            <!-- Title Start -->
            <div class="col-12 col-md-7">
              <h1 class="mb-0 pb-0 display-4" id="title">Dashboard</h1>
              <nav class="breadcrumb-container d-inline-block" aria-label="breadcrumb">

              </nav>
            </div>
            <!-- Title End -->
          </div>
        </div>
        <!-- Title and Top Buttons End -->

        <!-- Content Start -->
        <div class="card mb-2">
          <div class="card-body h-100">
            Welcome
          </div>
        </div>
        <!-- Content End -->
      </div>
    </main>

  </div>



  <!-- Vendor Scripts Start -->
  <script src="js/vendor/jquery-3.5.1.min.js"></script>
  <script src="js/vendor/bootstrap.bundle.min.js"></script>
  <script src="js/vendor/OverlayScrollbars.min.js"></script>
  <script src="js/vendor/autoComplete.min.js"></script>
  <script src="js/vendor/clamp.min.js"></script>

  <script src="icon/acorn-icons.js"></script>
  <script src="icon/acorn-icons-interface.js"></script>

  <!-- Vendor Scripts End -->

  <!-- Template Base Scripts Start -->
  <script src="js/base/helpers.js"></script>
  <script src="js/base/globals.js"></script>
  <script src="js/base/nav.js"></script>
  <script src="js/base/search.js"></script>
  <script src="js/base/settings.js"></script>
  <!-- Template Base Scripts End -->
  <!-- Page Specific Scripts Start -->

  <script src="js/common.js"></script>
  <script src="js/scripts.js"></script>
  <!-- Page Specific Scripts End -->
</body>

</html>