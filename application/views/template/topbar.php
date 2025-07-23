<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column" style="background-color: #f8fafc;">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light topbar mb-4 shadow-sm" style="background-color: #e0ecff; border-bottom: 1px solid #c3dafe;">

            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3 text-indigo-600">
                <i class="fa fa-bars"></i>
            </button>

            <h3 class="text-primary font-weight-bold mb-0" style="font-size: 1.25rem;"><?= $title ?></h3>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">

                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle text-primary" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline small text-primary"><?= $user['email']; ?></span>
                        <img class="img-profile rounded-circle border border-light shadow-sm" src="<?= base_url('assets/img/profile/') . $user['image']; ?>" width="36" height="36">
                    </a>

                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in border-0" aria-labelledby="userDropdown" style="background-color: #f0f7ff;">
                        <a class="dropdown-item text-dark" href="<?= base_url('user') ?>">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-primary"></i>
                            My Profile
                        </a>
                        <?php if ($user['role_id'] == 5) : ?>
                            <a class="dropdown-item text-dark" href="<?= base_url('user/editprofilemitra') ?>">
                                <i class="fas fa-user-edit fa-sm fa-fw mr-2 text-primary"></i>
                                Edit Profile
                            </a>
                        <?php else : ?>
                            <a class="dropdown-item text-dark" href="<?= base_url('user/edit') ?>">
                                <i class="fas fa-user-edit fa-sm fa-fw mr-2 text-primary"></i>
                                Edit Profile
                            </a>
                        <?php endif; ?>
                        <a class="dropdown-item text-dark" href="<?= base_url('user/changepassword') ?>">
                            <i class="fas fa-key fa-sm fa-fw mr-2 text-primary"></i>
                            Change Password
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item text-danger" href="<?= base_url('auth/logout'); ?>" data-toggle="modal" data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-danger"></i>
                            Logout
                        </a>
                    </div>
                </li>

            </ul>
        </nav>
        <!-- End of Topbar -->
