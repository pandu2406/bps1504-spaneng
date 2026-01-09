<!-- Sidebar -->
<!-- Sidebar -->
<ul class="navbar-nav sidebar accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center py-3" href="<?= base_url() ?>">
        <div class="sidebar-brand-icon">
            <img src="<?= base_url('assets/img/spaneng.png') ?>" alt="Logo SPANENG" style="width: 40px; height: 40px;">
        </div>
        <div class="sidebar-brand-text mx-2">SPANENG</div>
    </a>

    <hr class="sidebar-divider">

    <?php
    $role_id = $this->session->userdata('role_id');
    $queryMenu = "SELECT `user_menu`.`id`, `menu`
                  FROM `user_menu` 
                  JOIN `user_access_menu` ON `user_menu`.`id` = `user_access_menu`.`menu_id`    
                  WHERE `user_access_menu`.`role_id` = $role_id
                  ORDER BY CASE WHEN `user_menu`.`menu` = 'Admin' THEN 0 ELSE 1 END, `user_access_menu`.`menu_id` ASC";
    $menu = $this->db->query($queryMenu)->result_array();
    ?>

    <!-- Looping Menu -->
    <?php foreach ($menu as $m):
        // Icon Mapping
        $iconMap = [
            'Admin' => 'fas fa-fw fa-tachometer-alt',
            'User' => 'fas fa-fw fa-users',
            'Menu' => 'fas fa-fw fa-bars',
            'Utility' => 'fas fa-fw fa-cogs',
            'Master' => 'fas fa-fw fa-database'
        ];
        $menuIcon = $iconMap[$m['menu']] ?? 'fas fa-fw fa-folder';

        $menuId = $m['id'];
        $querySubMenu = "SELECT * FROM user_sub_menu WHERE menu_id = $menuId AND is_active = 1";
        $subMenu = $this->db->query($querySubMenu)->result_array();

        $isActiveMenu = false;
        foreach ($subMenu as $sm) {
            if ($title == $sm['title']) {
                $isActiveMenu = true;
                break;
            }
        }
        ?>
        <li class="nav-item <?= $isActiveMenu ? 'active' : ''; ?>">
            <a class="nav-link <?= $isActiveMenu ? '' : 'collapsed'; ?>" href="#" data-toggle="collapse"
                data-target="#menu<?= $m['id']; ?>" aria-expanded="<?= $isActiveMenu ? 'true' : 'false'; ?>"
                aria-controls="menu<?= $m['id']; ?>">
                <i class="<?= $menuIcon; ?>"></i>
                <span><?= $m['menu']; ?></span>
            </a>

            <div id="menu<?= $m['id']; ?>" class="collapse <?= $isActiveMenu ? 'show' : ''; ?>"
                aria-labelledby="heading<?= $m['id']; ?>" data-parent="#accordionSidebar">
                <div class="collapse-inner rounded py-2 collapse-inner-glass">
                    <?php foreach ($subMenu as $sm): ?>
                        <a class="collapse-item <?= ($title == $sm['title']) ? 'active' : ''; ?>"
                            href="<?= base_url($sm['url']); ?>">
                            <i class="<?= $sm['icon']; ?> mr-2"></i> <?= $sm['title']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </li>
    <?php endforeach; ?>

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline mt-4">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>