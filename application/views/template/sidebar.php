<!-- Sidebar -->
<ul class="navbar-nav sidebar sidebar-light accordion shadow-sm" id="accordionSidebar" style="background: linear-gradient(180deg, #eaf4ff, #d4e9ff); border-right: 1px solid #cce0f5;">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center py-3" href="<?= base_url() ?>">
        <div class="sidebar-brand-icon">
            <img src="<?= base_url('assets/img/spaneng.png') ?>" alt="Logo SPANENG" style="width: 50px; height: 50px;">
        </div>
        <div class="sidebar-brand-text mx-2" style="font-weight: bold; color: #003366;">SPANENG</div>
    </a>

    <hr class="sidebar-divider">

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

        $isActiveMenu = false;
        foreach ($subMenu as $sm) {
            if ($title == $sm['title']) {
                $isActiveMenu = true;
                break;
            }
        }
    ?>
        <li class="nav-item <?= $isActiveMenu ? 'active-menu' : ''; ?>">
            <a class="nav-link d-flex align-items-center <?= $isActiveMenu ? '' : 'collapsed'; ?>" href="#" data-toggle="collapse" data-target="#menu<?= $m['id']; ?>" aria-expanded="<?= $isActiveMenu ? 'true' : 'false'; ?>" aria-controls="menu<?= $m['id']; ?>">
                <i class="fas fa-fw fa-folder text-primary"></i>
                <span class="ml-2 text-dark"><?= $m['menu']; ?></span>
            </a>

            <div id="menu<?= $m['id']; ?>" class="collapse <?= $isActiveMenu ? 'show' : ''; ?>" aria-labelledby="heading<?= $m['id']; ?>" data-parent="#accordionSidebar">
                <div class="collapse-inner rounded bg-white shadow-sm px-2 py-1">
                    <?php foreach ($subMenu as $sm) : ?>
                        <a class="collapse-item <?= ($title == $sm['title']) ? 'active' : ''; ?>" href="<?= base_url($sm['url']); ?>">
                            <i class="<?= $sm['icon']; ?> mr-2 text-primary"></i> <?= $sm['title']; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
        </li>
    <?php endforeach; ?>

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline mt-3 mb-2">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>

<!-- Custom CSS -->
<style>
    .sidebar {
        font-family: 'Segoe UI', sans-serif;
    }

    .sidebar-brand-text {
        font-size: 1.2rem;
    }

    .nav-item a.nav-link:hover {
        background-color: #d0e7ff;
        color: #003366;
        transition: all 0.2s;
    }

    .collapse-item {
        font-size: 0.95rem;
        border-radius: 8px;
        padding: 6px 16px;
        margin-bottom: 4px;
        color: #003366;
        white-space: normal !important;
        word-wrap: break-word;
    }

    .collapse-item:hover {
        background-color: #cce5ff;
        color: #002244;
        font-weight: 500;
    }

    .collapse-item.active {
        background-color: #b3d7ff;
        color: #002244 !important;
        font-weight: 600;
        border-left: 4px solid #3399ff;
    }

    .nav-item.active-menu > a.nav-link {
        border-left: 4px solid #0066cc;
        background-color: #d4e9ff;
        font-weight: bold;
        color: #003366;
    }

    .sidebar-divider {
        border-top: 1px solid #cce0f5;
    }

    .collapse-inner {
        max-width: 100%;
    }

    .sidebar .nav-link {
        white-space: normal;
        line-height: 1.2;
        padding-top: 8px;
        padding-bottom: 8px;
    }
</style>
