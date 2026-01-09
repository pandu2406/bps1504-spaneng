<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">

    <!-- Main Content -->
    <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light topbar mb-4 static-top shadow-sm">

            <!-- Sidebar Toggle (Topbar) -->
            <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                <i class="fa fa-bars"></i>
            </button>

            <!-- Page Title -->
            <h3 class="font-weight-bold mb-0 ml-2" style="font-size: 1.25rem;"><?= $title ?></h3>

            <!-- Topbar Navbar -->
            <ul class="navbar-nav ml-auto">

                <!-- Nav Item - Clock -->
                <li class="nav-item mx-1 d-none d-md-flex align-items-center">
                    <div id="digital-clock" class="font-weight-bold small mr-2"></div>
                </li>

                <script>
                    function updateClock() {
                        const now = new Date();
                        const options = { weekday: 'short', year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
                        const timeString = now.toLocaleDateString('id-ID', options);
                        document.getElementById('digital-clock').innerText = timeString;
                    }
                    setInterval(updateClock, 1000);
                    window.onload = updateClock;
                </script>

                <!-- Nav Item - Theme Toggle (Switch Style) -->
                <li class="nav-item mx-1 d-flex align-items-center">
                    <label class="theme-switch-wrapper" title="Toggle Dark/Light Mode">
                        <input type="checkbox" id="theme-checkbox">
                        <div class="slider round">
                            <i class="fas fa-sun sun-icon"></i>
                            <i class="fas fa-moon moon-icon"></i>
                        </div>
                    </label>

                    <style>
                        /* Switch Wrapper */
                        .theme-switch-wrapper {
                            display: flex;
                            align-items: center;
                            margin-bottom: 0;
                            position: relative;
                            width: 60px;
                            height: 30px;
                            cursor: pointer;
                        }

                        /* Hide Default Checkbox */
                        .theme-switch-wrapper input {
                            opacity: 0;
                            width: 0;
                            height: 0;
                        }

                        /* Slider Track */
                        .slider {
                            position: absolute;
                            cursor: pointer;
                            top: 0;
                            left: 0;
                            right: 0;
                            bottom: 0;
                            background-color: #ccc; /* Light Mode Track */
                            transition: .4s;
                            border-radius: 34px;
                            display: flex;
                            align-items: center;
                            justify-content: space-between;
                            padding: 0 5px;
                            box-shadow: inset 0 2px 5px rgba(0,0,0,0.2);
                        }

                        /* Active Track (Dark Mode) */
                        [data-theme="dark"] .slider {
                            background-color: #2d3748; /* Dark Mode Track */
                            box-shadow: inset 0 2px 5px rgba(0,0,0,0.5);
                        }
                        
                        /* Slider Knob */
                        .slider:before {
                            position: absolute;
                            content: "";
                            height: 24px;
                            width: 24px;
                            left: 3px;
                            bottom: 3px;
                            background-color: white;
                            transition: .4s;
                            border-radius: 50%;
                            box-shadow: 0 2px 5px rgba(0,0,0,0.3);
                            z-index: 2;
                        }

                        /* Move Knob when Checked (Dark Mode) */
                        input:checked + .slider:before {
                            transform: translateX(30px);
                            background-color: #4e73df; /* Brand Color Knob */
                        }

                        /* Icons */
                        .sun-icon {
                            color: #f6e05e;
                            z-index: 1;
                            font-size: 14px;
                            margin-left: 4px;
                            opacity: 0;
                            transition: opacity 0.3s;
                        }

                        .moon-icon {
                            color: #cbd5e0;
                            z-index: 1;
                            font-size: 12px;
                            margin-right: 4px;
                            opacity: 1;
                            transition: opacity 0.3s;
                        }

                        /* Icon Visibility Logic */
                        input:checked + .slider .sun-icon {
                            opacity: 1; /* Show Sun in Dark Mode (as inactive option) or just showing current state? Usually switch shows "Pre-Dark" -> "Sun/Moon" 
                            Actually, usually: 
                            Unchecked (Light): Show Moon on right (to switch to dark), Knob on left.
                            Checked (Dark): Knob on right, Show Sun on left (to switch to light). 
                            */
                        }
                        
                        input:checked + .slider .moon-icon {
                            opacity: 0; 
                        }
                        
                        /* Let's refine icons:
                           Unchecked (Light): Knob Left. Moon Icon visible on Right (Target).
                           Checked (Dark): Knob Right. Sun Icon visible on Left (Target).
                        */
                    </style>

                    <!-- Script for Switch Logic -->
                    <script>
                        document.addEventListener("DOMContentLoaded", function () {
                            const checkbox = document.getElementById('theme-checkbox');
                            const html = document.documentElement;

                            console.log("Switch Theme Init...");

                            // 1. Sync Initial State
                            let savedTheme = localStorage.getItem('theme');
                            if (!savedTheme) {
                                savedTheme = 'light';
                                localStorage.setItem('theme', 'light');
                            }

                            // Apply to HTML
                            html.setAttribute('data-theme', savedTheme);

                            // Sync Checkbox (Checked = Dark, Unchecked = Light)
                            if (savedTheme === 'dark') {
                                checkbox.checked = true;
                            } else {
                                checkbox.checked = false;
                            }

                            console.log("Initial Theme:", savedTheme);

                            // 2. Change Listener
                            checkbox.addEventListener('change', function () {
                                if (this.checked) {
                                    // Switch to Dark
                                    html.setAttribute('data-theme', 'dark');
                                    localStorage.setItem('theme', 'dark');
                                    console.log("Switched to Dark");
                                } else {
                                    // Switch to Light
                                    html.setAttribute('data-theme', 'light');
                                    localStorage.setItem('theme', 'light');
                                    console.log("Switched to Light");
                                }
                            });
                        });
                    </script>
                </li>

                <!-- Nav Item - Alerts -->
                <li class="nav-item dropdown no-arrow mx-1">
                    <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-bell fa-fw"></i>
                        <!-- Counter - Alerts -->
                        <span class="badge badge-danger badge-counter" id="notif-count"></span>
                    </a>
                    <!-- Dropdown - Alerts -->
                    <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="alertsDropdown">
                        <h6 class="dropdown-header border-0">
                            Notification Center
                        </h6>
                        <div id="notif-list">
                            <!-- Dynamic Content -->
                            <a class="dropdown-item d-flex align-items-center" href="#">
                                <div class="mr-3">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-spinner fa-spin text-white"></i>
                                    </div>
                                </div>
                                <div>
                                    <span class="font-weight-bold">Loading...</span>
                                </div>
                            </a>
                        </div>
                        <a class="dropdown-item text-center small text-gray-500" href="#" id="mark-all-read">Mark all as
                            Read</a>
                    </div>
                </li>

                <!-- Fetch Notification Script (Minified logic) -->
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        // fetchNotifications(); // Disabled to prevent console errors
                        // setInterval(fetchNotifications, 15000);
                        document.getElementById('mark-all-read').addEventListener('click', function (e) {
                            e.preventDefault();
                            // fetch('<?= base_url('notification/mark_all_read') ?>').then(() => { fetchNotifications(); });
                        });
                    });
                    function fetchNotifications() {
                        return; // Disabled
                        /*
                        fetch('<?= base_url('notification/get_latest') ?>')
                            .then(response => response.json())
                            .then(data => {
                                const badge = document.getElementById('notif-count');
                                if (data.count > 0) { badge.innerText = data.count; badge.style.display = 'inline-block'; }
                                else { badge.style.display = 'none'; }
                                const list = document.getElementById('notif-list');
                                list.innerHTML = '';
                                if (data.notifications.length === 0) {
                                    list.innerHTML = `<a class="dropdown-item d-flex align-items-center" href="#"><div class="mr-3"><div class="icon-circle bg-secondary"><i class="fas fa-bell-slash text-white"></i></div></div><div><span class="font-weight-light">No new notifications</span></div></a>`;
                                } else {
                                    data.notifications.forEach(notif => {
                                        const item = document.createElement('a');
                                        item.className = 'dropdown-item d-flex align-items-center';
                                        item.href = notif.link;
                                        item.onclick = function () { fetch('<?= base_url('notification/mark_read/') ?>' + notif.id); };
                                        if (notif.is_read == 0) item.style.backgroundColor = 'var(--primary-light)';
                                        item.innerHTML = `<div class="mr-3"><div class="icon-circle bg-${notif.color}"><i class="${notif.icon} text-white"></i></div></div><div><div class="small text-gray-500">${notif.time}</div><span class="${notif.is_read == 0 ? 'font-weight-bold' : ''}">${notif.title}</span><div class="small text-gray-600">${notif.message}</div></div>`;
                                        list.appendChild(item);
                                    });
                                }
                            });
                        */
                    }
                </script>

                <div class="topbar-divider d-none d-sm-block"></div>

                <!-- Nav Item - User Information -->
                <li class="nav-item dropdown no-arrow">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <span class="mr-2 d-none d-lg-inline small font-weight-bold"><?= $user['email']; ?></span>
                        <img class="img-profile rounded-circle"
                            src="<?= base_url('assets/img/profile/') . $user['image']; ?>">
                    </a>
                    <!-- Dropdown - User Information -->
                    <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                        aria-labelledby="userDropdown">
                        <a class="dropdown-item" href="<?= base_url('user') ?>">
                            <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                            My Profile
                        </a>
                        <a class="dropdown-item" href="<?= base_url('user/edit') ?>">
                            <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                            Edit Profile
                        </a>
                        <a class="dropdown-item" href="<?= base_url('user/changepassword') ?>">
                            <i class="fas fa-key fa-sm fa-fw mr-2 text-gray-400"></i>
                            Change Password
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="<?= base_url('auth/logout'); ?>" data-toggle="modal"
                            data-target="#logoutModal">
                            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                            Logout
                        </a>
                    </div>
                </li>

            </ul>
        </nav>
        <!-- End of Topbar -->