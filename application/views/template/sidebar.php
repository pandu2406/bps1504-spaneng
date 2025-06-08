<!-- Sidebar -->
<ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar" style="background-color: #00264d;">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= base_url() ?>">
        <div class="sidebar-brand-icon">
        <img src="<?= base_url('assets/img/spaneng.png') ?>" alt="Logo SPANENG" style="width: 60px; height: 60px;">
        </div>
        <div class="sidebar-brand-text mx-3">SPANENG</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Query Menu -->
    <?php
    $role_id = $this->session->userdata('role_id');
    $queryMenu = "SELECT `user_menu`.`id`, `menu`
                  FROM `user_menu` 
                  JOIN `user_access_menu` ON `user_menu`.`id` = `user_access_menu`.`menu_id`    
                  WHERE `user_access_menu`.`role_id` = $role_id
                  ORDER BY `user_access_menu`.`menu_id` ASC";
    $menu = $this->db->query($queryMenu)->result_array();
    ?>

    <!-- Looping Menu -->
    <?php foreach ($menu as $m) :
        $menuId = $m['id'];
        $querySubMenu = "SELECT * FROM user_sub_menu WHERE menu_id = $menuId AND is_active = 1";
        $subMenu = $this->db->query($querySubMenu)->result_array();

        // Cek apakah salah satu submenu aktif
        $isActiveMenu = false;
        foreach ($subMenu as $sm) {
            if ($title == $sm['title']) {
                $isActiveMenu = true;
                break;
            }
        }
    ?>
        <li class="nav-item">
            <a class="nav-link <?= $isActiveMenu ? '' : 'collapsed'; ?>" href="#" data-toggle="collapse" data-target="#menu<?= $m['id']; ?>" aria-expanded="<?= $isActiveMenu ? 'true' : 'false'; ?>" aria-controls="menu<?= $m['id']; ?>">
                <i class="fas fa-fw fa-folder"></i>
                <span><?= $m['menu']; ?></span>
            </a>

            <div id="menu<?= $m['id']; ?>" class="collapse <?= $isActiveMenu ? 'show' : ''; ?>" aria-labelledby="heading<?= $m['id']; ?>" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <?php foreach ($subMenu as $sm) : ?>
                        <a class="collapse-item <?= ($title == $sm['title']) ? 'active' : ''; ?>" href="<?= base_url($sm['url']); ?>">
                            <i class="<?= $sm['icon']; ?>"></i> <?= $sm['title']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </li>
    <?php endforeach; ?>

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->
