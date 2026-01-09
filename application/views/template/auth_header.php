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
            --font-family: 'Poppins', sans-serif;
            --bg-gradient-dark: linear-gradient(135deg, rgba(30, 58, 138, 0.9) 0%, rgba(118, 75, 162, 0.9) 100%);
            --bg-image: url('<?= base_url('assets/img/bg-login.jpeg'); ?>');

            /* Dark Theme (Default) */
            --card-bg: rgba(255, 255, 255, 0.1);
            --card-border: 1px solid rgba(255, 255, 255, 0.2);
            --text-main: #fff;
            --text-muted: rgba(255, 255, 255, 0.7);
            --input-bg: rgba(255, 255, 255, 0.1);
            --input-border: 2px solid rgba(255, 255, 255, 0.2);
            --input-focus-bg: rgba(255, 255, 255, 0.2);
            --input-focus-border: #fff;
            --btn-bg: #fff;
            --btn-text: #4e73df;
            --brand-color: #fff;
        }

        [data-theme="light"] {
            --bg-gradient-dark: linear-gradient(135deg, rgba(255, 255, 255, 0.85) 0%, rgba(255, 255, 255, 0.85) 100%);
            --card-bg: rgba(255, 255, 255, 0.85);
            /* Frosty white */
            --card-border: 1px solid rgba(255, 255, 255, 0.8);
            --text-main: #333;
            --text-muted: #666;
            --input-bg: rgba(0, 0, 0, 0.05);
            --input-border: 2px solid rgba(0, 0, 0, 0.1);
            --input-focus-bg: #fff;
            --input-focus-border: #4e73df;
            --btn-bg: #4e73df;
            --btn-text: #fff;
            --brand-color: #4e73df;
        }

        body {
            font-family: var(--font-family);
            background: var(--bg-gradient-dark), var(--bg-image);
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.5s ease;
        }

        /* Glass Card */
        .login-card {
            background: var(--card-bg);
            backdrop-filter: blur(15px);
            -webkit-backdrop-filter: blur(15px);
            border: var(--card-border);
            border-radius: 20px;
            width: 100%;
            max-width: 420px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.2);
            animation: bounceIn 0.8s cubic-bezier(0.2, 0.8, 0.2, 1);
            transition: background 0.3s ease;
        }

        /* Floating Input Group */
        .input-group-material {
            position: relative;
            margin-bottom: 1.5rem;
        }

        .input-group-material input {
            width: 100%;
            background: var(--input-bg);
            border: var(--input-border);
            color: var(--text-main);
            padding: 1.2rem 1rem 0.5rem;
            /* Space for label */
            border-radius: 12px;
            font-size: 1rem;
            outline: none;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* AUTOFILL FIX: Force background color via shadow and maintain text color */
        .input-group-material input:-webkit-autofill,
        .input-group-material input:-webkit-autofill:hover,
        .input-group-material input:-webkit-autofill:focus,
        .input-group-material input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0 30px var(--card-bg) inset !important;
            -webkit-text-fill-color: var(--text-main) !important;
            caret-color: var(--text-main);
            transition: background-color 5000s ease-in-out 0s;
        }

        .input-group-material input:focus {
            background: var(--input-focus-bg);
            border-color: var(--input-focus-border);
            box-shadow: 0 0 0 4px rgba(78, 115, 223, 0.2);
            transform: translateY(-2px);
        }

        /* The Label */
        .input-group-material label {
            position: absolute;
            top: 1rem;
            left: 1rem;
            color: var(--text-muted);
            font-size: 1rem;
            pointer-events: none;
            transition: all 0.3s ease;
            margin: 0;
            padding: 0 5px;
            transform-origin: left top;
        }

        /* Floating interaction: Check 'active' class (JS) + focus + placeholder override */
        .input-group-material input:focus~label,
        .input-group-material input:not(:placeholder-shown)~label,
        .input-group-material input.has-val~label {
            top: -0.6rem;
            left: 0.8rem;
            font-size: 0.8rem;
            color: var(--input-focus-border);
            background: var(--card-bg);
            border-radius: 4px;
            padding: 0 4px;
            font-weight: 600;
            z-index: 5;
        }

        /* Show Password Icon */
        .pass-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-muted);
            cursor: pointer;
            z-index: 10;
            transition: color 0.3s;
        }

        .pass-toggle:hover {
            color: var(--text-main);
        }

        /* Buttons */
        .btn-auth {
            background: var(--btn-bg);
            color: var(--btn-text);
            font-weight: 700;
            letter-spacing: 0.5px;
            border: none;
            padding: 0.8rem;
            transition: all 0.3s ease;
        }

        .btn-auth:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.2);
            filter: brightness(1.1);
        }

        /* Text Utilities */
        .text-main-theme {
            color: var(--text-main) !important;
        }

        .text-muted-theme {
            color: var(--text-muted) !important;
        }

        .text-brand {
            color: var(--brand-color) !important;
        }

        /* Theme Toggle */
        .theme-switch {
            position: absolute;
            top: 20px;
            left: 20px;
            z-index: 100;
            background: var(--card-bg);
            backdrop-filter: blur(5px);
            border-radius: 50%;
            width: 45px;
            height: 45px;
            display: flex;
            align-items: center;
            justify-content: center;
            border: var(--card-border);
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        }

        .theme-switch:hover {
            transform: rotate(15deg) scale(1.1);
        }

        .theme-switch i {
            font-size: 1.2rem;
            color: var(--text-main);
        }

        /* Animation Trick to detect Autofill in JS */
        @keyframes onAutoFillStart {
            from {
                opacity: 0.99;
            }

            to {
                opacity: 1;
            }
        }

        /* Animations */
        @keyframes bounceIn {
            0% {
                opacity: 0;
                transform: scale(0.3);
            }

            50% {
                opacity: 1;
                transform: scale(1.05);
            }

            70% {
                transform: scale(0.9);
            }

            100% {
                transform: scale(1);
            }
        }
    </style>


</head>

<body>