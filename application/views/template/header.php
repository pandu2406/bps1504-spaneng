<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= $title; ?></title>

    <link rel="icon" href="<?= base_url('assets/img/logo_bps.png'); ?>">

    <!-- Custom fonts for this template-->
    <link href="<?= base_url('assets/'); ?>vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= base_url('assets/'); ?>css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        :root {
            /* --- LIGHT THEME (Default) --- */
            --bg-body: #f8fafc;
            --bg-wrapper: #f8fafc;
            --bg-content: #ffffff;
            --bg-sidebar: #ffffff;
            --bg-topbar: rgba(255, 255, 255, 0.9);
            --bg-footer: #f8fafc;

            --text-main: #2d3748;
            --text-muted: #718096;
            --text-sidebar: #4a5568;
            --text-sidebar-heading: #a0aec0;

            --border-color: #e2e8f0;
            --card-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --glass-border: 1px solid rgba(0, 0, 0, 0.05);

            --primary: #4e73df;
            --primary-light: #ebf8ff;
            /* Light blue for active states */

            --sidebar-width: 14rem;
        }

        [data-theme="dark"] {
            /* --- DARK THEME --- */
            --bg-body: #1a202c;
            /* Safe dark background */
            --bg-wrapper: linear-gradient(135deg, #1a202c 0%, #2d3748 100%);
            --bg-content: rgba(255, 255, 255, 0.05);
            /* Glass card */
            --bg-sidebar: rgba(26, 32, 44, 0.95);
            --bg-topbar: rgba(26, 32, 44, 0.85);
            /* Glass topbar */
            --bg-footer: transparent;

            --text-main: #edf2f7;
            --text-muted: #a0aec0;
            --text-sidebar: #cbd5e0;
            --text-sidebar-heading: #718096;

            --border-color: rgba(255, 255, 255, 0.1);
            --card-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.4);
            --glass-border: 1px solid rgba(255, 255, 255, 0.1);

            --primary: #63b3ed;
            /* Lighter blue for dark mode */
            --primary-light: rgba(99, 179, 237, 0.15);
            /* Glassy active state */
        }

        body {
            font-family: 'Poppins', sans-serif !important;
            background: var(--bg-body);
            color: var(--text-main);
            transition: background 0.3s ease, color 0.3s ease;
        }

        #wrapper {
            background: var(--bg-wrapper);
            min-height: 100vh;
            transition: background 0.3s ease;
        }

        #content-wrapper {
            background: transparent !important;
        }

        /* --- SIDEBAR --- */
        .sidebar {
            background: var(--bg-sidebar) !important;
            border-right: var(--glass-border);
            transition: all 0.3s ease;
        }

        .sidebar .nav-item .nav-link span {
            color: var(--text-sidebar) !important;
            font-weight: 500;
        }

        .sidebar .nav-item .nav-link i {
            color: var(--text-sidebar) !important;
            /* Muted icon */
        }

        /* Hover & Active States */
        .sidebar .nav-item .nav-link:hover {
            color: var(--primary) !important;
            background: var(--primary-light);
            border-radius: 0.5rem;
            margin: 0 0.5rem;
        }

        .sidebar .nav-item .nav-link:hover i {
            color: var(--primary) !important;
        }

        .sidebar .nav-item.active .nav-link {
            background: var(--primary-light) !important;
            color: var(--primary) !important;
            font-weight: 600;
            border-radius: 0.5rem;
            margin: 0 0.5rem;
            box-shadow: none;
            /* Removed hard shadow */
            position: relative;
        }

        /* Little indicator bar for active */
        .sidebar .nav-item.active .nav-link::before {
            content: '';
            position: absolute;
            left: -8px;
            /* Outside the rounded box */
            top: 50%;
            transform: translateY(-50%);
            height: 20px;
            width: 4px;
            background: var(--primary);
            border-radius: 4px;
            display: none;
            /* Optional: Enable if desired */
        }

        .sidebar .nav-item.active .nav-link span,
        .sidebar .nav-item.active .nav-link i {
            color: var(--primary) !important;
        }

        .sidebar .sidebar-heading {
            color: var(--text-sidebar-heading) !important;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .sidebar .sidebar-brand-text {
            color: var(--text-main) !important;
            letter-spacing: 1px;
        }

        .sidebar hr.sidebar-divider {
            border-top: var(--glass-border) !important;
        }

        /* --- TOPBAR --- */
        .topbar {
            background: var(--bg-topbar) !important;
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border-bottom: var(--glass-border) !important;
            transition: background 0.3s ease, border 0.3s ease;
            position: relative;
            /* Ensure stacking context */
            z-index: 1000 !important;
            /* Force above content */
            overflow: visible !important;
            /* Allow dropdowns to spill out */
        }

        .topbar .nav-link,
        .topbar h3,
        .topbar .text-gray-600 {
            color: var(--text-main) !important;
        }

        /* --- CARDS & CONTENT --- */
        .card {
            background-color: var(--bg-content) !important;
            color: var(--text-main);
            border: var(--glass-border);
            border-radius: 1rem;
            box-shadow: var(--card-shadow);
            transition: all 0.3s ease;
        }

        .card-header {
            background-color: transparent !important;
            border-bottom: var(--glass-border);
            color: var(--primary);
            font-weight: 600;
        }

        /* Footer */
        footer.sticky-footer {
            background: var(--bg-footer) !important;
            color: var(--text-muted) !important;
            padding: 2rem 0;
        }

        /* Utils */
        .text-gray-800 {
            color: var(--text-main) !important;
        }

        .text-gray-600 {
            color: var(--text-muted) !important;
        }

        /* Scrollbar */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: transparent;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(156, 163, 175, 0.5);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(156, 163, 175, 0.8);
        }
    </style>

    <!-- Custom styles for this page -->
    <link href="<?= base_url('assets/'); ?>vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">

    <link href="<?= base_url('assets/'); ?>jquery-ui/jquery-ui.css" rel="stylesheet">
    <link href="<?= base_url('assets/'); ?>jquery-ui/jquery-ui.theme.css" rel="stylesheet">
    <link href="<?= base_url('assets/'); ?>jquery-ui/jquery-ui.structure.css" rel="stylesheet">

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css"> -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/frappe-gantt/0.5.0/frappe-gantt.min.css"> -->

    <!-- FullCalendar CSS -->
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.css' rel='stylesheet' />
    <!-- FullCalendar JS -->
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.js'></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Select2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <!-- Custom Theme Support -->
    <link href="<?= base_url('assets/'); ?>css/custom-dark-mode.css" rel="stylesheet">

</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">