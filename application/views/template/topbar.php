<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column" style="background-color:  #e6e6e6;">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow" style="background-color:  #b3b3b3;">

            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <h3 style="color: #003366;"><?= $title ?></h3>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto" style="color: #003366;">

                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline text" style="color: #003366;"><?= $user['email']; ?></span>
                        <img class="img-profile rounded-circle" src="<?= base_url('assets/img/profile/') . $user['image']; ?>">
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown" style="background-color: #e6e6e6;">
                        <a class=" dropdown-item" href="<?= base_url('user') ?>">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text"></i>
                            My Profile
                        </a>
                        <?php if ($user['role_id'] == 5) : ?>
                            <a class="dropdown-item" href="<?= base_url('user/editprofilemitra') ?>">
                                <i class="fas fa-user-edit fa-sm fa-fw mr-2 text"></i>
                                Edit Profile
                            </a>
                        <?php else : ?>
                            <a class="dropdown-item" href="<?= base_url('user/edit') ?>">
                                <i class="fas fa-user-edit fa-sm fa-fw mr-2 text"></i>
                                Edit Profile
                            </a>

                        <?php endif; ?>
                        <a class="dropdown-item" href="<?= base_url('user/changepassword') ?>">
                            <i class="fas fa-key fa-sm fa-fw mr-2 text"></i>
                            Change Password
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?= base_url('auth/logout'); ?>" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text"></i>
                            Logout
                        </a>
                    </div>
                </li>

            </ul>

        </nav>
        <!-- End of Topbar -->