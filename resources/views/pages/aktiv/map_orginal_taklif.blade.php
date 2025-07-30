<!DOCTYPE html>
<html lang="uz">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvestUz - Ўзбекистон Республикаси Инвестиция харитаси</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Roboto:wght@300;400;500;700&display=swap"
        rel="stylesheet">

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />

    <!-- CSS will be included separately -->
    <style>
        /* CSS Variables for Government Theme */
        :root {
            --color-primary: #3b82f6;
            --color-primary-dark: #2563eb;
            --color-error: #e11d48;
            --color-success: #10b981;
            --color-warning: #f59e0b;
            --color-info: #3b82f6;

            --color-text-dark: #1e293b;
            --color-text-medium: #475569;
            --color-text-light: #64748b;
            --color-text-lighter: #94a3b8;

            --color-bg-primary: #ffffff;
            --color-bg-secondary: #f8fafc;
            --color-bg-tertiary: #e2e8f0;
            --color-bg-quaternary: #cbd5e1;

            --color-border: #e2e8f0;
            --color-border-light: #f1f5f9;

            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.06);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);

            --border-radius-sm: 6px;
            --border-radius-md: 8px;
            --border-radius-lg: 12px;

            --spacing-xs: 8px;
            --spacing-sm: 12px;
            --spacing-md: 16px;
            --spacing-lg: 20px;
            --spacing-xl: 24px;
            --spacing-2xl: 32px;
        }

        /* Base Styles */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        body,
        html {
            margin: 0;
            padding: 0;
            font-family: 'Inter', 'Roboto', -apple-system, BlinkMacSystemFont, 'Segoe UI', system-ui, sans-serif;
            font-weight: 400;
            line-height: 1.5;
            color: var(--color-text-dark);
            background: var(--color-bg-secondary);
            height: 100vh;
            overflow: hidden;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* Top Notice Bar */
        .top-notice-bar {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            color: white;
            padding: var(--spacing-sm) 0;
            font-size: 14px;
            font-weight: 500;
            text-align: center;
            box-shadow: var(--shadow-sm);
        }

        .top-notice-bar .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--spacing-lg);
            display: flex;
            justify-content: center;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .top-notice-bar i {
            opacity: 0.9;
        }

        /* Header */
        .app-header {
            background: var(--color-bg-primary);
            border-bottom: 1px solid var(--color-border);
            box-shadow: var(--shadow-sm);
            position: relative;
            z-index: 100;
        }

        .header-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: var(--spacing-lg);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .app-logo {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
        }

        .logo-emblem {
            width: 48px;
            height: 48px;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            border-radius: var(--border-radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
            box-shadow: var(--shadow-md);
            transition: transform 0.2s ease;
        }

        .logo-emblem:hover {
            transform: scale(1.05);
        }

        .logo-text {
            display: flex;
            flex-direction: column;
        }

        .app-title {
            font-size: 20px;
            font-weight: 700;
            color: var(--color-text-dark);
            line-height: 1.2;
            margin: 0;
        }

        .app-subtitle {
            font-size: 14px;
            color: var(--color-text-medium);
            font-weight: 400;
            margin: 0;
        }

        /* Language Switcher */
        .lang-switcher {
            display: flex;
            gap: 4px;
            background: var(--color-bg-secondary);
            border-radius: var(--border-radius-sm);
            padding: 4px;
            border: 1px solid var(--color-border);
        }

        .lang-btn {
            padding: var(--spacing-sm) var(--spacing-md);
            border: none;
            background: transparent;
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            color: var(--color-text-medium);
            transition: all 0.2s ease;
            position: relative;
        }

        .lang-btn:hover {
            background: var(--color-bg-tertiary);
            color: var(--color-text-dark);
        }

        .lang-btn.active {
            background: var(--color-primary);
            color: white;
            box-shadow: var(--shadow-sm);
        }

        /* Navigation */
        .app-navigation {
            background: var(--color-bg-secondary);
            border-bottom: 1px solid var(--color-border);
            padding: var(--spacing-sm) 0;
        }

        .nav-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 var(--spacing-lg);
            display: flex;
            gap: var(--spacing-xl);
            align-items: center;
        }

        .nav-item {
            color: var(--color-text-medium);
            text-decoration: none;
            font-weight: 500;
            font-size: 14px;
            padding: var(--spacing-sm) var(--spacing-md);
            border-radius: var(--border-radius-sm);
            transition: all 0.2s ease;
            position: relative;
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
        }

        .nav-item:hover {
            color: var(--color-primary);
            background: var(--color-bg-primary);
            transform: translateY(-1px);
        }

        .nav-item.active {
            color: var(--color-primary);
            background: var(--color-bg-primary);
            box-shadow: var(--shadow-sm);
        }

        /* Main Content */
        .main-content {
            height: calc(100vh - 140px);
            position: relative;
        }

        #map {
            height: 100%;
            width: 100%;
            z-index: 1;
        }

        /* Map Controls */
        .map-controls {
            position: absolute;
            top: var(--spacing-lg);
            right: var(--spacing-lg);
            z-index: 1000;
            background: var(--color-bg-primary);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            padding: var(--spacing-lg);
            min-width: 280px;
            border: 1px solid var(--color-border-light);
            animation: fadeInRight 0.3s ease-out;
        }

        .controls-title {
            font-size: 16px;
            font-weight: 600;
            color: var(--color-text-dark);
            margin: 0 0 var(--spacing-md) 0;
            padding-bottom: var(--spacing-sm);
            border-bottom: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
        }

        .map-control-btn {
            width: 100%;
            padding: var(--spacing-md);
            background: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: var(--border-radius-md);
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: var(--spacing-sm);
            transition: all 0.2s ease;
            margin-bottom: var(--spacing-sm);
            color: var(--color-text-dark);
        }

        .map-control-btn:last-child {
            margin-bottom: 0;
        }

        .map-control-btn:hover {
            background: var(--color-bg-primary);
            border-color: var(--color-primary);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .map-control-btn.active {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            color: white;
            border-color: var(--color-primary);
            box-shadow: var(--shadow-md);
        }

        .map-control-btn.auction-active {
            background: linear-gradient(135deg, var(--color-error) 0%, #dc2626 100%);
            color: white;
            border-color: var(--color-error);
        }

        .control-content {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .count-badge {
            background: var(--color-primary);
            color: white;
            border-radius: 20px;
            padding: 4px var(--spacing-sm);
            font-size: 12px;
            font-weight: 600;
            min-width: 24px;
            text-align: center;
            box-shadow: var(--shadow-sm);
        }

        .map-control-btn.active .count-badge {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Map Style Controls */
        .map-style-controls {
            position: absolute;
            top: var(--spacing-lg);
            left: var(--spacing-lg);
            z-index: 1000;
            background: var(--color-bg-primary);
            border-radius: var(--border-radius-lg);
            box-shadow: var(--shadow-lg);
            padding: var(--spacing-lg);
            min-width: 200px;
            border: 1px solid var(--color-border-light);
            animation: fadeInLeft 0.3s ease-out;
        }

        .style-control-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--color-text-dark);
            margin: 0 0 var(--spacing-md) 0;
            text-align: center;
            padding-bottom: var(--spacing-sm);
            border-bottom: 1px solid var(--color-border);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-xs);
        }

        .style-btn {
            width: 100%;
            padding: var(--spacing-sm) var(--spacing-md);
            background: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-xs);
            margin-bottom: var(--spacing-xs);
            transition: all 0.2s ease;
            text-align: center;
            color: var(--color-text-dark);
        }

        .style-btn:last-child {
            margin-bottom: 0;
        }

        .style-btn:hover {
            background: var(--color-bg-primary);
            border-color: var(--color-primary);
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .style-btn.active {
            background: var(--color-primary);
            color: white;
            border-color: var(--color-primary);
            box-shadow: var(--shadow-sm);
        }

        /* Stats Panel */
        .stats-panel {
            background: var(--color-bg-secondary);
            border: 1px solid var(--color-border);
            border-radius: var(--border-radius-md);
            padding: var(--spacing-md);
            margin-top: var(--spacing-lg);
        }

        .stats-title {
            font-size: 14px;
            font-weight: 600;
            color: var(--color-text-dark);
            margin-bottom: var(--spacing-sm);
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--spacing-xs);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-sm);
            font-size: 12px;
        }

        .stats-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: var(--spacing-xs);
            background: var(--color-bg-primary);
            border-radius: var(--border-radius-sm);
        }

        .stats-label {
            color: var(--color-text-medium);
            font-weight: 500;
        }

        .stats-value {
            font-weight: 600;
            color: var(--color-text-dark);
        }

        /* Modal Styles */
        .info-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.4);
            backdrop-filter: blur(4px);
            display: none;
            z-index: 2000;
            justify-content: center;
            align-items: center;
            padding: var(--spacing-lg);
            animation: fadeIn 0.3s ease-out;
        }

        .info-modal.show {
            display: flex;
        }

        .modal-content {
            background: var(--color-bg-primary);
            border-radius: var(--border-radius-lg);
            max-width: 700px;
            max-height: 85vh;
            width: 100%;
            overflow: hidden;
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--color-border-light);
            animation: slideIn 0.3s ease-out;
        }

        .modal-header {
            padding: var(--spacing-xl);
            border-bottom: 1px solid var(--color-border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-primary-dark) 100%);
            color: white;
        }

        .modal-header h2 {
            margin: 0;
            font-size: 18px;
            font-weight: 600;
        }

        .modal-close-btn {
            background: none;
            border: none;
            color: white;
            font-size: 24px;
            cursor: pointer;
            padding: var(--spacing-xs);
            width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: var(--border-radius-sm);
            transition: all 0.2s ease;
        }

        .modal-close-btn:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .modal-body {
            padding: var(--spacing-xl);
            overflow-y: auto;
            max-height: calc(85vh - 80px);
        }

        /* Enhanced Popup Styles */
        .leaflet-popup-content {
            margin: var(--spacing-md) var(--spacing-lg);
            max-width: 350px;
            font-family: inherit;
        }

        .popup-header {
            font-size: 16px;
            font-weight: 600;
            color: var(--color-text-dark);
            margin-bottom: var(--spacing-sm);
            padding-bottom: var(--spacing-xs);
            border-bottom: 1px solid var(--color-border);
        }

        .popup-info {
            margin: var(--spacing-xs) 0;
            font-size: 14px;
            color: var(--color-text-medium);
        }

        .popup-info strong {
            color: var(--color-text-dark);
            font-weight: 500;
        }

        .popup-buttons {
            margin-top: var(--spacing-md);
            display: flex;
            gap: var(--spacing-xs);
            flex-wrap: wrap;
        }

        .popup-btn {
            padding: var(--spacing-xs) var(--spacing-md);
            background: var(--color-primary);
            color: white;
            border: none;
            border-radius: var(--border-radius-sm);
            cursor: pointer;
            font-size: 12px;
            font-weight: 500;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 4px;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-sm);
        }

        .popup-btn:hover {
            background: var(--color-primary-dark);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        .popup-btn.details {
            background: var(--color-success);
        }

        .popup-btn.download {
            background: var(--color-warning);
        }

        .popup-btn.external {
            background: var(--color-error);
        }

        /* Section Styles */
        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin: var(--spacing-lg) 0 var(--spacing-md);
            padding-bottom: var(--spacing-sm);
            border-bottom: 2px solid var(--color-border);
            color: var(--color-text-dark);
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
        }

        .section-title:first-child {
            margin-top: 0;
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: var(--spacing-lg);
        }

        .details-table td {
            padding: var(--spacing-sm) 0;
            color: var(--color-text-medium);
            font-size: 14px;
            border-bottom: 1px solid var(--color-border-light);
        }

        .details-table td:first-child {
            font-weight: 500;
            width: 40%;
            color: var(--color-text-dark);
            display: flex;
            align-items: center;
            gap: var(--spacing-xs);
        }

        .details-table td:last-child {
            color: var(--color-text-dark);
        }

        /* Badge Styles */
        .badge {
            display: inline-block;
            padding: 4px var(--spacing-sm);
            border-radius: var(--border-radius-sm);
            font-size: 12px;
            font-weight: 500;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-success {
            background-color: #ecfdf5;
            color: var(--color-success);
            border: 1px solid #d1fae5;
        }

        .badge-warning {
            background-color: #fffbeb;
            color: var(--color-warning);
            border: 1px solid #fed7aa;
        }

        .badge-info {
            background-color: #eff6ff;
            color: var(--color-info);
            border: 1px solid #dbeafe;
        }

        .badge-error {
            background-color: #fef2f2;
            color: var(--color-error);
            border: 1px solid #fecaca;
        }

        .badge-renovation {
            background-color: #faf5ff;
            color: #8b5cf6;
            border: 1px solid #e9d5ff;
        }

        .badge-investment {
            background-color: #f0f9ff;
            color: #0ea5e9;
            border: 1px solid #bae6fd;
        }

        .badge-auction {
            background-color: #fff7ed;
            color: #ea580c;
            border: 1px solid #fed7aa;
        }

        /* Document Links */
        .document-link {
            display: flex;
            align-items: center;
            color: var(--color-primary);
            text-decoration: none;
            padding: var(--spacing-md);
            border-radius: var(--border-radius-md);
            background: var(--color-bg-secondary);
            margin-bottom: var(--spacing-xs);
            font-size: 14px;
            font-weight: 500;
            border: 1px solid var(--color-border);
            transition: all 0.2s ease;
        }

        .document-link i {
            margin-right: var(--spacing-sm);
        }

        .document-link:hover {
            background: var(--color-bg-primary);
            border-color: var(--color-primary);
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }

        /* Loading Indicator */
        .loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 3000;
            flex-direction: column;
            gap: var(--spacing-lg);
        }

        .spinner {
            width: 50px;
            height: 50px;
            border: 4px solid var(--color-border);
            border-top: 4px solid var(--color-primary);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .loading-text {
            color: var(--color-text-medium);
            font-weight: 500;
            font-size: 16px;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Toast Notifications */
        .toast {
            position: fixed;
            bottom: var(--spacing-xl);
            left: 50%;
            transform: translateX(-50%);
            background: var(--color-text-dark);
            color: white;
            padding: var(--spacing-md) var(--spacing-xl);
            border-radius: var(--border-radius-md);
            z-index: 4000;
            box-shadow: var(--shadow-xl);
            font-weight: 500;
            max-width: 500px;
            text-align: center;
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            animation: slideUp 0.3s ease-out;
        }

        .toast.info {
            background: var(--color-primary);
        }

        .toast.warning {
            background: var(--color-warning);
        }

        .toast.error {
            background: var(--color-error);
        }

        .toast.success {
            background: var(--color-success);
        }

        /* Mobile Responsive */
        @media (max-width: 992px) {
            .header-content {
                padding: var(--spacing-md);
            }

            .app-title {
                font-size: 18px;
            }

            .app-subtitle {
                font-size: 13px;
            }

            .nav-content {
                padding: 0 var(--spacing-md);
                gap: var(--spacing-md);
            }

            .map-controls,
            .map-style-controls {
                position: relative;
                top: auto;
                left: auto;
                right: auto;
                margin: var(--spacing-md);
                min-width: auto;
            }

            .main-content {
                height: calc(100vh - 120px);
            }
        }

        @media (max-width: 768px) {
            .top-notice-bar {
                font-size: 13px;
                padding: var(--spacing-xs) 0;
            }

            .header-content {
                flex-direction: column;
                gap: var(--spacing-md);
                align-items: flex-start;
            }

            .app-logo {
                width: 100%;
                justify-content: center;
            }

            .lang-switcher {
                align-self: center;
            }

            .nav-content {
                flex-wrap: wrap;
                justify-content: center;
                gap: var(--spacing-sm);
            }

            .main-content {
                height: calc(100vh - 160px);
            }

            .modal-content {
                margin: var(--spacing-md);
                max-height: calc(100vh - 32px);
            }

            .modal-header,
            .modal-body {
                padding: var(--spacing-lg);
            }

            .map-controls {
                position: fixed;
                bottom: var(--spacing-md);
                left: var(--spacing-md);
                right: var(--spacing-md);
                top: auto;
                min-width: auto;
            }

            .map-style-controls {
                position: fixed;
                top: var(--spacing-md);
                left: var(--spacing-md);
                right: auto;
                min-width: 150px;
            }
        }

        /* Custom Marker Styles */
        .custom-marker {
            border-radius: 50%;
            box-shadow: var(--shadow-md);
        }

        .auction-marker-container {
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        /* Focus States for Accessibility */
        .lang-btn:focus,
        .nav-item:focus,
        .map-control-btn:focus,
        .style-btn:focus,
        .popup-btn:focus,
        .document-link:focus,
        .modal-close-btn:focus {
            outline: 2px solid var(--color-primary);
            outline-offset: 2px;
        }

        /* Animation Effects */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes fadeInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: scale(0.9) translateY(-20px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateX(-50%) translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateX(-50%) translateY(0);
            }
        }

        @keyframes pulse {

            0%,
            100% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.05);
            }
        }

        .logo-emblem:hover {
            animation: pulse 0.6s ease-in-out;
        }

        /* Leaflet popup custom styling */
        .leaflet-popup-content-wrapper {
            border-radius: var(--border-radius-md);
            box-shadow: var(--shadow-xl);
        }

        .leaflet-popup-tip {
            background: white;
        }

        /* Smooth transitions for all interactive elements */
        * {
            transition: color 0.2s ease, background-color 0.2s ease, border-color 0.2s ease, transform 0.2s ease;
        }

        /* Print styles */
        @media print {

            .top-notice-bar,
            .app-header,
            .app-navigation,
            .map-controls,
            .map-style-controls,
            .info-modal {
                display: none !important;
            }

            .main-content {
                height: 100vh;
            }
        }
    </style>
</head>

<body>
    <!-- Top Notice Bar -->
    <div class="top-notice-bar">
        <div class="container">
            <i class="fas fa-info-circle"></i>
            <span>Ўзбекистон Республикаси расмий инвестиция харитаси - Барча маълумотлар ҳақиқий вақтда
                янгиланади</span>
        </div>
    </div>

    <!-- Header -->
    <header class="app-header">
        <div class="header-content">
            <div class="app-logo">
                <div class="logo-emblem">
                    <i class="fas fa-map-marked-alt"></i>
                </div>
                <div class="logo-text">
                    <h1 class="app-title">ИнвестУз</h1>
                    <p class="app-subtitle">Ўзбекистон Республикаси Инвестиция харитаси</p>
                </div>
            </div>
            <div class="lang-switcher">
                <button class="lang-btn active" data-lang="uz">УЗ</button>
                <button class="lang-btn" data-lang="ru">RU</button>
                <button class="lang-btn" data-lang="en">EN</button>
            </div>
        </div>
    </header>

    <!-- Navigation -->
    <nav class="app-navigation">
        <div class="nav-content">
            <a href="#" class="nav-item active" data-nav="map">
                <i class="fas fa-map"></i> Харита
            </a>
            <a href="#" class="nav-item" data-nav="stats">
                <i class="fas fa-chart-bar"></i> Статистика
            </a>
            <a href="#" class="nav-item" data-nav="projects">
                <i class="fas fa-building"></i> Лойиҳалар
            </a>
            <a href="#" class="nav-item" data-nav="auctions">
                <i class="fas fa-gavel"></i> Аукционлар
            </a>
            <a href="#" class="nav-item" data-nav="reports">
                <i class="fas fa-file-alt"></i> Ҳисоботлар
            </a>
            <a href="#" class="nav-item" data-nav="contact">
                <i class="fas fa-phone"></i> Алоқа
            </a>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="main-content">
        <!-- Map Container -->
        <div id="map"></div>

        <!-- Map Style Controls -->
        <div class="map-style-controls">
            <div class="style-control-title">
                <i class="fas fa-layer-group"></i> Харита турлари
            </div>
            <button class="style-btn" data-style="osm">
                <i class="fas fa-map"></i> Стандарт
            </button>
            <button class="style-btn" data-style="satellite">
                <i class="fas fa-satellite"></i> Сунъий йўлдош
            </button>
            <button class="style-btn active" data-style="hybrid">
                <i class="fas fa-layer-group"></i> Гибрид
            </button>
        </div>

        <!-- Map Controls -->
        <div class="map-controls">
            <div class="controls-title">
                <i class="fas fa-cog"></i> Харита бошқаруви
            </div>

            <!-- Regular Data Control -->
            <button id="regular-count-btn" class="map-control-btn" style="cursor: default;">
                <div class="control-content">
                    <i class="fas fa-building"></i>
                    <span>API + KMZ маълумотлар</span>
                </div>
                <span class="count-badge">0</span>
            </button>

            <!-- JSON Data Control -->
            <button id="toggle-json-btn" class="map-control-btn active">
                <div class="control-content">
                    <i class="fas fa-database"></i>
                    <span>JSON маълумотлар</span>
                </div>
                <span class="count-badge">0</span>
            </button>

            <!-- Auction Control -->
            <button id="toggle-auction-btn" class="map-control-btn">
                <div class="control-content">
                    <i class="fas fa-gavel"></i>
                    <span>Аукционлар</span>
                </div>
                <span class="count-badge">0</span>
            </button>

            <!-- DOP KMZ Control -->
            <button id="toggle-dop-kmz-btn" class="map-control-btn active">
                <div class="control-content">
                    <i class="fas fa-file-archive"></i>
                    <span>DOP KMZ файллар</span>
                </div>
                <span class="count-badge">0</span>
            </button>

            <!-- Statistics Panel -->
            <div class="stats-panel">
                <div class="stats-title">
                    <i class="fas fa-chart-pie"></i> Статистика
                </div>
                <div class="stats-grid">
                    <div class="stats-item">
                        <span class="stats-label">Жами:</span>
                        <span class="stats-value" id="total-count">0</span>
                    </div>
                    <div class="stats-item">
                        <span class="stats-label">Фаол:</span>
                        <span class="stats-value" id="active-count">0</span>
                    </div>
                    <div class="stats-item">
                        <span class="stats-label">DOP KMZ:</span>
                        <span class="stats-value" id="dop-count">0</span>
                    </div>
                    <div class="stats-item">
                        <span class="stats-label">Аукцион:</span>
                        <span class="stats-value" id="auction-count">0</span>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Loading Indicator -->
    <div id="loading" class="loading">
        <div class="spinner"></div>
        <div class="loading-text">Маълумотлар юкланмоқда...</div>
    </div>

    <!-- Info Modal -->
    <div id="info-modal" class="info-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 id="modal-title">Маълумотлар</h2>
                <button class="modal-close-btn" id="modal-close">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <div class="modal-body" id="modal-body">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- JavaScript Libraries -->
    <!-- Leaflet JS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

    <!-- KMZ Support -->
    <script src="https://unpkg.com/jszip@3.10.1/dist/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/togeojson/0.16.0/togeojson.min.js"></script>

    <!-- Main Application Script -->
    <script>
        // InvestUz Map Application - Complete JavaScript
        'use strict';

        // Application Configuration
        const APP_CONFIG = {
            apiBaseUrl: (() => {
                const hostname = window.location.hostname;
                const port = window.location.port;
                const protocol = window.location.protocol;
                return hostname === 'localhost' || hostname === '127.0.0.1' ?
                    `${protocol}//${hostname}${port ? ':' + port : ''}` :
                    `${protocol}//${hostname}${port ? ':' + port : ''}`;
            })(),
            dopKmzFiles: [
                'ALL_RENOVATION_AREA_368_303_230525.kmz',
                'SER-10_Кумарик_1,92 га.kmz',
                'YAK-11_Ракатбоши-6_0,63 га.kmz',
                'YASH-44_Бойкурган-4_1,75 га.kmz'
            ],
            defaultCenter: [41.311, 69.279],
            defaultZoom: 11
        };

        // Application State
        const AppState = {
            map: null,
            currentLang: 'uz',
            currentNav: 'map',
            mapStyle: 'hybrid',
            isLoading: false,

            // Data containers
            markers: [],
            polygons: {},
            kmzLayers: {},
            auctionMarkers: [],
            jsonDataMarkers: [],

            // Clusters
            markerCluster: null,
            auctionCluster: null,
            jsonDataCluster: null,

            // Visibility states
            visibility: {
                jsonData: true,
                auction: false,
                dopKmz: true
            },

            // Counts
            counts: {
                regular: 0,
                auction: 0,
                jsonData: 0,
                kmz: 0,
                dopKmz: 0
            },

            // Map layers
            mapLayers: {
                osm: null,
                satellite: null,
                hybridBase: null,
                hybridLabels: null,
                currentLayer: 'hybrid'
            },

            // Modal state
            modal: {
                isOpen: false,
                title: '',
                content: ''
            }
        };

        // Utility Functions
        const Utils = {
            /**
             * Extract coordinates from Google Maps and Yandex URLs
             */
            extractCoordinatesFromUrl(url) {
                if (!url) return null;

                try {
                    const decodedUrl = decodeURIComponent(url);

                    // Handle Yandex Maps URLs
                    if (decodedUrl.includes('yandex.uz/maps') || decodedUrl.includes('yandex.com/maps')) {
                        const llMatch = decodedUrl.match(/ll=([^&]+)/);
                        if (llMatch) {
                            const coords = llMatch[1].split(',');
                            if (coords.length === 2) {
                                const lng = parseFloat(coords[0]);
                                const lat = parseFloat(coords[1]);
                                if (lat >= 39 && lat <= 43 && lng >= 68 && lng <= 71) {
                                    return [lat, lng];
                                }
                            }
                        }
                    }

                    // Handle Google Maps URLs - Multiple patterns
                    const patterns = [
                        /@(-?\d+\.?\d*),(-?\d+\.?\d*),?\d*z?/, // @ pattern
                        /(-?\d+\.\d{4,}),(-?\d+\.\d{4,})/, // Direct coordinates
                        /!3d(-?\d+\.?\d*)!4d(-?\d+\.?\d*)/ // !3d/!4d pattern
                    ];

                    for (const pattern of patterns) {
                        const match = decodedUrl.match(pattern);
                        if (match) {
                            const lat = parseFloat(match[1]);
                            const lng = parseFloat(match[2]);
                            if (lat >= 39 && lat <= 43 && lng >= 68 && lng <= 71) {
                                return [lat, lng];
                            }
                        }
                    }

                    // Try URL parameters
                    try {
                        const urlObj = new URL(decodedUrl);
                        const params = urlObj.searchParams;

                        const qParam = params.get('q');
                        if (qParam) {
                            const qMatch = qParam.match(/(-?\d+\.?\d*),(-?\d+\.?\d*)/);
                            if (qMatch) {
                                const lat = parseFloat(qMatch[1]);
                                const lng = parseFloat(qMatch[2]);
                                if (lat >= 39 && lat <= 43 && lng >= 68 && lng <= 71) {
                                    return [lat, lng];
                                }
                            }
                        }

                        const llParam = params.get('ll');
                        if (llParam) {
                            const llMatch = llParam.match(/(-?\d+\.?\d*),(-?\d+\.?\d*)/);
                            if (llMatch) {
                                const lng = parseFloat(llMatch[1]);
                                const lat = parseFloat(llMatch[2]);
                                if (lat >= 39 && lat <= 43 && lng >= 68 && lng <= 71) {
                                    return [lat, lng];
                                }
                            }
                        }
                    } catch (urlError) {
                        // URL parsing failed, continue
                    }

                    return null;
                } catch (error) {
                    console.error('Error extracting coordinates from URL:', url, error);
                    return null;
                }
            },

            /**
             * Safe get function for object properties
             */
            safeGet(obj, key, defaultValue = 'N/A') {
                return (obj && obj[key] !== undefined && obj[key] !== null && obj[key] !== '') ? obj[key] :
                defaultValue;
            },

            /**
             * Get status information for different project types
             */
            getStatusInfo(item) {
                const type = item['Таклиф_тури_(Реновация,_Инвестиция,_Аукцион)'];
                switch (type) {
                    case 'Реновация':
                        return {
                            text: 'Реновация', class: 'badge-renovation', color: '#8b5cf6'
                        };
                    case 'Инвестиция':
                        return {
                            text: 'Инвестиция', class: 'badge-investment', color: '#0ea5e9'
                        };
                    case 'Аукцион':
                        return {
                            text: 'Аукцион', class: 'badge-auction', color: '#ea580c'
                        };
                    default:
                        return {
                            text: type || 'Белгисиз', class: 'badge-info', color: '#3b82f6'
                        };
                }
            },

            /**
             * Format status for API data
             */
            formatStatus(status) {
                if (!status) return {
                    text: "Статус не указан",
                    class: "badge-info"
                };

                switch (status) {
                    case "9":
                        return {
                            text: "Инвест договор", class: "badge-success"
                        };
                    case "1":
                        return {
                            text: "Ишлаб чиқилмоқда", class: "badge-warning"
                        };
                    case "2":
                        return {
                            text: "Қурилиш жараёнида", class: "badge-info"
                        };
                    default:
                        return {
                            text: "Статус: " + status, class: "badge-info"
                        };
                }
            },

            /**
             * Create marker icon based on project type
             */
            createMarkerIcon(item) {
                const status = this.getStatusInfo(item);
                return L.divIcon({
                    html: `<div style="background-color: ${status.color}; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"></div>`,
                    className: 'custom-marker',
                    iconSize: [16, 16],
                    iconAnchor: [8, 8]
                });
            },

            /**
             * Show toast notification
             */
            showToast(message, type = 'info') {
                const toast = document.createElement('div');
                toast.className = 'toast ' + type;
                toast.innerHTML =
                    `<i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : type === 'warning' ? 'exclamation-triangle' : 'info-circle'}"></i> ${message}`;
                document.body.appendChild(toast);

                setTimeout(() => {
                    if (toast.parentNode) {
                        toast.parentNode.removeChild(toast);
                    }
                }, 4000);
            },

            /**
             * Parse description data from KML
             */
            parseDescriptionData(description) {
                if (!description) return {};

                const data = {};
                try {
                    const lines = description.split('\n').map(line => line.trim()).filter(line => line);

                    for (const line of lines) {
                        const colonMatch = line.match(/^([^:]+):\s*(.+)$/);
                        const dashMatch = line.match(/^([^-]+)-\s*(.+)$/);

                        let key, value;
                        if (colonMatch) {
                            key = colonMatch[1].trim();
                            value = colonMatch[2].trim();
                        } else if (dashMatch) {
                            key = dashMatch[1].trim();
                            value = dashMatch[2].trim();
                        }

                        if (key && value) {
                            data[key] = value;
                        }
                    }

                    // Handle specific formats
                    if (description.includes('Лот №')) {
                        const lotMatch = description.match(/Лот №\s*([^\n]+)/);
                        if (lotMatch) data['Лот №'] = lotMatch[1].trim();
                    }

                    if (description.includes('Туман')) {
                        const regionMatch = description.match(/Туман\s*-\s*([^\n]+)/);
                        if (regionMatch) data['Туман'] = regionMatch[1].trim();
                    }

                    if (description.includes('Майдони')) {
                        const areaMatch = description.match(/Майдони\s*-\s*([^\n]+)/);
                        if (areaMatch) data['Майдони'] = areaMatch[1].trim();
                    }
                } catch (error) {
                    console.warn('Error parsing description:', error);
                }

                return data;
            }
        };

        // Data Services
        const DataServices = {
            /**
             * Fetch JSON data from local file
             */
            async fetchJsonData() {
                try {
                    const response = await fetch('/assets/data/443_output.json');
                    if (!response.ok) {
                        return {
                            success: false,
                            data: []
                        };
                    }

                    const data = await response.json();
                    return {
                        success: true,
                        data: Array.isArray(data) ? data : []
                    };
                } catch (error) {
                    console.error('Error fetching JSON data:', error);
                    return {
                        success: false,
                        data: []
                    };
                }
            },

            /**
             * Fetch auction data from external API
             */
            async fetchAuctionData() {
                try {
                    const response = await fetch('https://projects.toshkentinvest.uz/api/markersing', {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json'
                        },
                        mode: 'cors'
                    });

                    if (!response.ok) {
                        return {
                            success: false,
                            data: []
                        };
                    }

                    const data = await response.json();
                    return {
                        success: true,
                        data: data?.lots && Array.isArray(data.lots) ? data.lots : []
                    };
                } catch (error) {
                    console.error('Error fetching auction data:', error);
                    return {
                        success: false,
                        data: []
                    };
                }
            },

            /**
             * Fetch API data
             */
            async fetchApiData() {
                try {
                    const response = await fetch(`${APP_CONFIG.apiBaseUrl}/api/aktivs`, {
                        method: 'GET',
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                        },
                        credentials: 'same-origin'
                    });

                    if (!response.ok) {
                        return {
                            success: false,
                            data: []
                        };
                    }

                    const data = await response.json();
                    let lotsData = [];

                    if (data?.lots && Array.isArray(data.lots)) {
                        lotsData = data.lots;
                    } else if (Array.isArray(data)) {
                        lotsData = data;
                    }

                    return {
                        success: true,
                        data: lotsData
                    };
                } catch (error) {
                    console.error('Error fetching API data:', error);
                    return {
                        success: false,
                        data: []
                    };
                }
            },

            /**
             * Process DOP KMZ file
             */
            async processDopKmzFile(fileName) {
                try {
                    const kmzUrl = `/assets/data/DOP_DATA/${fileName}`;
                    const response = await fetch(kmzUrl);
                    if (!response.ok) {
                        throw new Error(`Failed to fetch KMZ file: ${response.statusText}`);
                    }

                    const kmzData = await response.arrayBuffer();
                    const zip = await JSZip.loadAsync(kmzData);

                    let kmlFile = zip.file('doc.kml') ||
                        zip.file(Object.keys(zip.files).find(name =>
                            name.toLowerCase().endsWith('.kml') && !zip.files[name].dir
                        ));

                    if (!kmlFile) {
                        throw new Error('No KML file found in KMZ archive');
                    }

                    const kmlContent = await kmlFile.async('text');
                    const parser = new DOMParser();
                    const kmlDoc = parser.parseFromString(kmlContent, 'text/xml');
                    const geoJson = toGeoJSON.kml(kmlDoc);

                    return {
                        success: true,
                        geoJson,
                        fileName
                    };
                } catch (error) {
                    console.error(`Error processing DOP KMZ file ${fileName}:`, error);
                    return {
                        success: false,
                        error: error.message
                    };
                }
            }
        };

        // Map Management
        const MapManager = {
            /**
             * Initialize the map
             */
            initMap() {
                AppState.map = L.map('map', {
                    center: APP_CONFIG.defaultCenter,
                    zoom: APP_CONFIG.defaultZoom,
                    minZoom: 10,
                    maxZoom: 18,
                    zoomControl: false
                });

                // Add zoom control to bottom right
                L.control.zoom({
                    position: 'bottomright'
                }).addTo(AppState.map);

                // Initialize map layers
                AppState.mapLayers.osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap contributors'
                });

                AppState.mapLayers.satellite = L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        attribution: '© Esri'
                    });

                AppState.mapLayers.hybridBase = L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                        attribution: '© Esri'
                    });

                AppState.mapLayers.hybridLabels = L.tileLayer(
                    'https://server.arcgisonline.com/ArcGIS/rest/services/Reference/World_Boundaries_and_Places/MapServer/tile/{z}/{y}/{x}', {
                        attribution: ''
                    });

                // Set default to hybrid
                AppState.mapLayers.hybridBase.addTo(AppState.map);
                AppState.mapLayers.hybridLabels.addTo(AppState.map);

                // Initialize clusters
                AppState.markerCluster = L.markerClusterGroup({
                    chunkedLoading: true,
                    maxClusterRadius: 50,
                    spiderfyOnMaxZoom: true,
                    showCoverageOnHover: false,
                    disableClusteringAtZoom: 16
                });

                AppState.auctionCluster = L.markerClusterGroup({
                    chunkedLoading: true,
                    maxClusterRadius: 50,
                    spiderfyOnMaxZoom: true,
                    showCoverageOnHover: false,
                    disableClusteringAtZoom: 16
                });

                AppState.jsonDataCluster = L.markerClusterGroup({
                    chunkedLoading: true,
                    maxClusterRadius: 50,
                    spiderfyOnMaxZoom: true,
                    showCoverageOnHover: false,
                    disableClusteringAtZoom: 16
                });

                // Add clusters to map
                AppState.map.addLayer(AppState.markerCluster);
                AppState.map.addLayer(AppState.jsonDataCluster);

                console.log('Map initialized successfully');
            },

            /**
             * Change map style
             */
            changeMapStyle(styleType) {
                if (!AppState.map || AppState.mapLayers.currentLayer === styleType) return;

                // Remove current layers
                if (AppState.mapLayers.currentLayer === 'osm') {
                    AppState.map.removeLayer(AppState.mapLayers.osm);
                } else if (AppState.mapLayers.currentLayer === 'satellite') {
                    AppState.map.removeLayer(AppState.mapLayers.satellite);
                } else if (AppState.mapLayers.currentLayer === 'hybrid') {
                    AppState.map.removeLayer(AppState.mapLayers.hybridBase);
                    AppState.map.removeLayer(AppState.mapLayers.hybridLabels);
                }

                // Add new layers
                if (styleType === 'osm') {
                    AppState.map.addLayer(AppState.mapLayers.osm);
                } else if (styleType === 'satellite') {
                    AppState.map.addLayer(AppState.mapLayers.satellite);
                } else if (styleType === 'hybrid') {
                    AppState.map.addLayer(AppState.mapLayers.hybridBase);
                    AppState.map.addLayer(AppState.mapLayers.hybridLabels);
                }

                AppState.mapLayers.currentLayer = styleType;
                AppState.mapStyle = styleType;

                // Update button states
                document.querySelectorAll('.style-btn').forEach(btn => {
                    btn.classList.remove('active');
                });
                document.querySelector(`[data-style="${styleType}"]`).classList.add('active');
            },

            /**
             * Add JSON data marker to map
             */
            addJsonDataMarker(item) {
                const coordinates = Utils.extractCoordinatesFromUrl(item['Таклиф_Харита']);
                if (!coordinates) return false;

                const itemId = 'json-item-' + item['№'];
                const icon = Utils.createMarkerIcon(item);
                const marker = L.marker(coordinates, {
                    icon
                });

                marker.itemId = itemId;

                const status = Utils.getStatusInfo(item);
                const district = Utils.safeGet(item, 'Туман');
                const address = Utils.safeGet(item, 'Манзил_(МФЙ,_кўча)');
                const area = Utils.safeGet(item, 'Таклиф_Ер_майдони_(га)');

                const popup = `
            <div>
                <div class="popup-header">${district} - ${item['№']}</div>
                <div class="popup-info"><strong>Манзил:</strong> ${address}</div>
                <div class="popup-info"><strong>Майдон:</strong> ${area} га</div>
                <div class="popup-info"><span class="badge ${status.class}">${status.text}</span></div>
                <div class="popup-buttons">
                    <button class="popup-btn details" onclick="UI.showJsonItemModal('${itemId}')">
                        <i class="fas fa-info-circle"></i> Тафсилотлар
                    </button>
                </div>
            </div>
        `;

                marker.bindPopup(popup);
                AppState.jsonDataCluster.addLayer(marker);
                AppState.jsonDataMarkers.push({
                    marker,
                    data: item
                });
                AppState.counts.jsonData++;

                return true;
            },

            /**
             * Add auction marker to map
             */
            addAuctionMarker(lot) {
                if (!lot.lat || !lot.lng) return false;

                const auctionIcon = L.divIcon({
                    html: `<div style="background-color: #e11d48; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"></div>`,
                    className: 'auction-marker-container',
                    iconSize: [16, 16],
                    iconAnchor: [8, 8]
                });

                const marker = L.marker([parseFloat(lot.lat), parseFloat(lot.lng)], {
                    icon: auctionIcon
                });

                const popup = `
            <div>
                <div class="popup-header">${lot.property_name || 'Номсиз объект'}</div>
                <div class="popup-info"><strong>Бошланғич нархи:</strong> ${lot.start_price ? Number(lot.start_price).toLocaleString('uz-UZ') : 'Белгиланмаган'} сўм</div>
                <div class="popup-info"><strong>Аукцион санаси:</strong> ${lot.auction_date || 'Белгиланмаган'}</div>
                <div class="popup-info"><strong>Жойлашуви:</strong> ${lot.region || ''}${lot.area ? ', ' + lot.area : ''}${lot.address ? ', ' + lot.address : ''}</div>
                <div class="popup-info"><strong>Майдони:</strong> ${lot.land_area || 'Белгиланмаган'} га</div>
                <div class="popup-info"><strong>Тури:</strong> ${lot.property_category || 'Белгиланмаган'}</div>
                <div class="popup-info"><strong>Ҳолати:</strong> ${lot.lot_status || 'Белгиланмаган'}</div>
                ${lot.main_image ? `<div class="auction-image" style="margin: 8px 0; border-radius: 4px; overflow: hidden;">
                        <img src="${lot.main_image}" alt="${lot.property_name}" style="width:100%; max-height:150px; object-fit:cover;" onerror="this.style.display='none'">
                    </div>` : ''}
                <div class="popup-buttons">
                    ${lot.lot_link && lot.lot_link !== '#' ? `<a href="${lot.lot_link}" target="_blank" class="popup-btn external">
                            <i class="fas fa-external-link-alt"></i> Батафсил маълумот
                        </a>` : ''}
                </div>
            </div>
        `;

                marker.bindPopup(popup, {
                    maxWidth: 300
                });
                AppState.auctionCluster.addLayer(marker);
                AppState.auctionMarkers.push({
                    marker,
                    data: lot
                });
                AppState.counts.auction++;

                return true;
            },

            /**
             * Add API marker to map
             */
            addApiMarker(lot) {
                if (!lot.lat || !lot.lng) return false;

                const marker = L.marker([parseFloat(lot.lat), parseFloat(lot.lng)], {
                    icon: L.divIcon({
                        html: `<div style="background-color: #3b82f6; width: 12px; height: 12px; border-radius: 50%; border: 2px solid white; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"></div>`,
                        className: 'custom-marker',
                        iconSize: [16, 16],
                        iconAnchor: [8, 8]
                    })
                });

                if (!lot.id) {
                    lot.id = 'lot-' + Math.random().toString(36).substr(2, 9);
                }

                marker.lotId = lot.id;

                const name = Utils.safeGet(lot, 'neighborhood_name', Utils.safeGet(lot, 'name', 'Unnamed'));
                const district = Utils.safeGet(lot, 'district_name', Utils.safeGet(lot, 'district'));
                const area = Utils.safeGet(lot, 'area_hectare', Utils.safeGet(lot, 'area'));
                const statusText = lot.status ? Utils.formatStatus(lot.status).text : 'Статус не указан';

                const popup = `
            <div>
                <div class="popup-header">${name}</div>
                <div class="popup-info">${district}</div>
                <div class="popup-info"><strong>Майдон:</strong> ${area} га</div>
                <div class="popup-info">${statusText}</div>
                <div class="popup-buttons">
                    <button class="popup-btn details" onclick="UI.showDetailsModal('${lot.id}')">
                        <i class="fas fa-info-circle"></i> Тафсилотлар
                    </button>
                </div>
            </div>
        `;

                marker.bindPopup(popup);
                AppState.markerCluster.addLayer(marker);
                AppState.markers.push({
                    marker,
                    data: lot
                });
                AppState.counts.regular++;

                return true;
            },

            /**
             * Add DOP KMZ layer to map
             */
            async addDopKmzLayer(fileName) {
                const result = await DataServices.processDopKmzFile(fileName);
                if (!result.success) return false;

                const {
                    geoJson
                } = result;

                const yellowStyle = {
                    color: '#f59e0b',
                    weight: 3,
                    opacity: 0.8,
                    fillColor: '#fbbf24',
                    fillOpacity: 0.4
                };

                const kmzLayer = L.geoJSON(geoJson, {
                    style: yellowStyle,
                    pointToLayer: function(feature, latlng) {
                        return L.marker(latlng, {
                            icon: L.divIcon({
                                html: '<div style="background-color: #fbbf24; width: 12px; height: 12px; border-radius: 50%; border: 2px solid #f59e0b; box-shadow: 0 2px 4px rgba(0,0,0,0.2);"></div>',
                                className: 'custom-marker',
                                iconSize: [16, 16],
                                iconAnchor: [8, 8]
                            })
                        });
                    },
                    onEachFeature: function(feature, layer) {
                        const fileNameWithoutExt = fileName.replace('.kmz', '');
                        let lotData = {};

                        const fileNameParts = fileNameWithoutExt.split('_');
                        if (fileNameParts.length >= 2) {
                            lotData['Лот №'] = fileNameParts[0];
                            lotData['Номи'] = fileNameParts.slice(1).join('_');
                        }

                        if (feature.properties && feature.properties.description) {
                            const parsedData = Utils.parseDescriptionData(feature.properties
                                .description);
                            lotData = {
                                ...lotData,
                                ...parsedData
                            };
                        }

                        if (feature.properties && feature.properties.name) {
                            lotData['Номи'] = feature.properties.name;
                        }

                        const popupContent = `
                    <div>
                        <div class="popup-header" style="color: #f59e0b;">${lotData['Номи'] || fileNameWithoutExt}</div>
                        ${lotData['Лот №'] ? `<div class="popup-info"><strong>Лот №:</strong> ${lotData['Лот №']}</div>` : ''}
                        ${lotData['Туман'] ? `<div class="popup-info"><strong>Туман:</strong> ${lotData['Туман']}</div>` : ''}
                        ${lotData['Майдони'] ? `<div class="popup-info"><strong>Майдони:</strong> ${lotData['Майдони']}</div>` : ''}
                        <div class="popup-buttons">
                            <button class="popup-btn details" onclick="UI.showDopKmzModal('${fileName}')">
                                <i class="fas fa-info-circle"></i> Тафсилотлар
                            </button>
                            <a href="/assets/data/DOP_DATA/${fileName}" download class="popup-btn download">
                                <i class="fas fa-download"></i> Юклаш
                            </a>
                        </div>
                    </div>
                `;

                        layer.bindPopup(popupContent, {
                            maxWidth: 300
                        });
                        layer.kmzData = lotData;
                        layer.kmzFileName = fileName;

                        if (layer.setStyle) {
                            layer.on('mouseover', function() {
                                this.setStyle({
                                    weight: 5,
                                    fillOpacity: 0.6
                                });
                            });
                            layer.on('mouseout', function() {
                                this.setStyle(yellowStyle);
                            });
                        }

                        layer.on('click', function(e) {
                            UI.showDopKmzModal(fileName);
                            L.DomEvent.stopPropagation(e);
                        });
                    }
                });

                kmzLayer.fileName = fileName;
                kmzLayer.addTo(AppState.map);
                AppState.kmzLayers[`dop_${fileName}`] = kmzLayer;
                AppState.counts.dopKmz++;

                return true;
            },

            /**
             * Toggle layer visibility
             */
            toggleLayerVisibility(layerType) {
                switch (layerType) {
                    case 'jsonData':
                        if (AppState.visibility.jsonData) {
                            AppState.map.removeLayer(AppState.jsonDataCluster);
                        } else {
                            AppState.map.addLayer(AppState.jsonDataCluster);
                        }
                        AppState.visibility.jsonData = !AppState.visibility.jsonData;
                        break;

                    case 'auction':
                        if (AppState.visibility.auction) {
                            AppState.map.removeLayer(AppState.auctionCluster);
                        } else {
                            AppState.map.addLayer(AppState.auctionCluster);
                        }
                        AppState.visibility.auction = !AppState.visibility.auction;
                        break;

                    case 'dopKmz':
                        const visible = Object.keys(AppState.kmzLayers).some(key =>
                            key.startsWith('dop_') && AppState.map.hasLayer(AppState.kmzLayers[key])
                        );

                        Object.keys(AppState.kmzLayers).forEach(key => {
                            if (key.startsWith('dop_')) {
                                if (visible) {
                                    AppState.map.removeLayer(AppState.kmzLayers[key]);
                                } else {
                                    AppState.map.addLayer(AppState.kmzLayers[key]);
                                }
                            }
                        });
                        AppState.visibility.dopKmz = !visible;
                        break;
                }

                UI.updateControls();
            }
        };

        // UI Management
        const UI = {
            /**
             * Initialize UI event handlers
             */
            init() {
                this.setupEventListeners();
                this.updateControls();
            },

            /**
             * Setup all event listeners
             */
            setupEventListeners() {
                // Language switcher
                document.querySelectorAll('.lang-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const lang = e.target.dataset.lang;
                        this.changeLanguage(lang);
                    });
                });

                // Navigation
                document.querySelectorAll('.nav-item').forEach(item => {
                    item.addEventListener('click', (e) => {
                        e.preventDefault();
                        const navId = e.currentTarget.dataset.nav;
                        this.changeNavigation(navId);
                    });
                });

                // Map style controls
                document.querySelectorAll('.style-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const style = e.target.dataset.style;
                        MapManager.changeMapStyle(style);
                    });
                });

                // Map controls
                document.getElementById('toggle-json-btn').addEventListener('click', () => {
                    MapManager.toggleLayerVisibility('jsonData');
                });

                document.getElementById('toggle-auction-btn').addEventListener('click', () => {
                    MapManager.toggleLayerVisibility('auction');
                });

                document.getElementById('toggle-dop-kmz-btn').addEventListener('click', () => {
                    MapManager.toggleLayerVisibility('dopKmz');
                });

                // Modal close
                document.getElementById('modal-close').addEventListener('click', () => {
                    this.closeModal();
                });

                document.getElementById('info-modal').addEventListener('click', (e) => {
                    if (e.target === e.currentTarget) {
                        this.closeModal();
                    }
                });

                // Keyboard events
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape') {
                        this.closeModal();
                    }
                });

                // Window resize
                window.addEventListener('resize', () => {
                    if (AppState.map) {
                        AppState.map.invalidateSize();
                    }
                });
            },

            /**
             * Change language
             */
            changeLanguage(lang) {
                AppState.currentLang = lang;

                document.querySelectorAll('.lang-btn').forEach(btn => {
                    btn.classList.remove('active');
                });

                document.querySelector(`[data-lang="${lang}"]`).classList.add('active');

                console.log('Language changed to:', lang);
                // Add language switching logic here if needed
            },

            /**
             * Change navigation
             */
            changeNavigation(navId) {
                AppState.currentNav = navId;

                document.querySelectorAll('.nav-item').forEach(item => {
                    item.classList.remove('active');
                });

                document.querySelector(`[data-nav="${navId}"]`).classList.add('active');

                console.log('Navigation changed to:', navId);
            },

            /**
             * Update control states and counts
             */
            updateControls() {
                // Update counts
                const updateElement = (id, value) => {
                    const element = document.getElementById(id);
                    if (element) element.textContent = value;
                };

                const updateBadge = (btnId, count) => {
                    const button = document.getElementById(btnId);
                    if (button) {
                        const badge = button.querySelector('.count-badge');
                        if (badge) badge.textContent = count;
                    }
                };

                updateBadge('toggle-json-btn', AppState.counts.jsonData);
                updateBadge('toggle-auction-btn', AppState.counts.auction);
                updateBadge('regular-count-btn', AppState.counts.regular + AppState.counts.kmz);
                updateBadge('toggle-dop-kmz-btn', AppState.counts.dopKmz);

                const total = AppState.counts.regular + AppState.counts.auction + AppState.counts.jsonData + AppState
                    .counts.kmz + AppState.counts.dopKmz;
                const active = (AppState.visibility.jsonData ? AppState.counts.jsonData : 0) +
                    (AppState.visibility.auction ? AppState.counts.auction : 0) +
                    AppState.counts.regular + AppState.counts.kmz +
                    (AppState.visibility.dopKmz ? AppState.counts.dopKmz : 0);

                updateElement('total-count', total);
                updateElement('active-count', active);
                updateElement('dop-count', AppState.counts.dopKmz);
                updateElement('auction-count', AppState.counts.auction);

                // Update button states
                this.updateButtonStates();
            },

            /**
             * Update button states based on visibility
             */
            updateButtonStates() {
                const jsonBtn = document.getElementById('toggle-json-btn');
                const auctionBtn = document.getElementById('toggle-auction-btn');
                const dopKmzBtn = document.getElementById('toggle-dop-kmz-btn');

                if (jsonBtn) {
                    const span = jsonBtn.querySelector('span');
                    if (span) {
                        span.textContent = AppState.visibility.jsonData ? 'JSON маълумотларни яшириш' :
                            'JSON маълумотларни кўрсатиш';
                    }
                    jsonBtn.className = AppState.visibility.jsonData ? 'map-control-btn active' : 'map-control-btn';
                }

                if (auctionBtn) {
                    const span = auctionBtn.querySelector('span');
                    if (span) {
                        span.textContent = AppState.visibility.auction ? 'Аукционларни яшириш' :
                        'Аукционларни кўрсатиш';
                    }
                    auctionBtn.className = AppState.visibility.auction ? 'map-control-btn auction-active' :
                        'map-control-btn';
                }

                if (dopKmzBtn) {
                    const span = dopKmzBtn.querySelector('span');
                    if (span) {
                        span.textContent = AppState.visibility.dopKmz ? 'DOP KMZ яшириш' : 'DOP KMZ кўрсатиш';
                    }
                    dopKmzBtn.className = AppState.visibility.dopKmz ? 'map-control-btn active' : 'map-control-btn';
                }
            },

            /**
             * Show loading state
             */
            showLoading() {
                AppState.isLoading = true;
                document.getElementById('loading').style.display = 'flex';
            },

            /**
             * Hide loading state
             */
            hideLoading() {
                AppState.isLoading = false;
                document.getElementById('loading').style.display = 'none';
            },

            /**
             * Show modal
             */
            showModal(title, content) {
                AppState.modal.isOpen = true;
                AppState.modal.title = title;
                AppState.modal.content = content;

                document.getElementById('modal-title').textContent = title;
                document.getElementById('modal-body').innerHTML = content;
                document.getElementById('info-modal').classList.add('show');
            },

            /**
             * Close modal
             */
            closeModal() {
                AppState.modal.isOpen = false;
                document.getElementById('info-modal').classList.remove('show');
            },

            /**
             * Show JSON item details modal
             */
            showJsonItemModal(itemId) {
                let item = null;
                for (let i = 0; i < AppState.jsonDataMarkers.length; i++) {
                    if (AppState.jsonDataMarkers[i].marker.itemId === itemId) {
                        item = AppState.jsonDataMarkers[i].data;
                        break;
                    }
                }

                if (!item) {
                    console.error(`Item with ID ${itemId} not found`);
                    return;
                }

                const status = Utils.getStatusInfo(item);
                const displayName = `${Utils.safeGet(item, 'Туман')} - ${Utils.safeGet(item, '№')}`;

                const modalContent = `
            <div class="section-title">
                <i class="fas fa-info-circle"></i> Асосий маълумотлар
            </div>
            <table class="details-table">
                <tr><td><i class="fas fa-hashtag"></i> №:</td><td>${Utils.safeGet(item, '№')}</td></tr>
                <tr><td><i class="fas fa-map-marker-alt"></i> Туман:</td><td>${Utils.safeGet(item, 'Туман')}</td></tr>
                <tr><td><i class="fas fa-home"></i> Манзил:</td><td>${Utils.safeGet(item, 'Манзил_(МФЙ,_кўча)')}</td></tr>
                <tr><td><i class="fas fa-tag"></i> Тури:</td><td><span class="badge ${status.class}">${status.text}</span></td></tr>
                <tr><td><i class="fas fa-ruler-combined"></i> Майдон:</td><td>${Utils.safeGet(item, 'Таклиф_Ер_майдони_(га)')} га</td></tr>
            </table>

            <div class="section-title">
                <i class="fas fa-project-diagram"></i> Лойиҳа тафсилотлари
            </div>
            <table class="details-table">
                <tr><td><i class="fas fa-building"></i> Бош режа ҳолати:</td><td>${Utils.safeGet(item, 'Бош_режадаги_ҳолати_ва_қавати')}</td></tr>
                <tr><td><i class="fas fa-layer-group"></i> Таклиф қават:</td><td>${Utils.safeGet(item, 'Таклиф_қавати_ва_ҳудуд')}</td></tr>
                <tr><td><i class="fas fa-briefcase"></i> Фаолият тури:</td><td>${Utils.safeGet(item, 'Таклиф_Фаолият_тури')}</td></tr>
            </table>

            ${item['Таклиф_Харита'] ? `
                    <div class="section-title">
                        <i class="fas fa-external-link-alt"></i> Қўшимча ҳаволалар
                    </div>
                    <a href="${item['Таклиф_Харита']}" target="_blank" class="document-link">
                        <i class="fas fa-map"></i> Харитада кўриш
                    </a>
                ` : ''}
        `;

                this.showModal(displayName, modalContent);
            },

            /**
             * Show details modal for API data
             */
            showDetailsModal(lotId) {
                let lot = null;
                for (let i = 0; i < AppState.markers.length; i++) {
                    if (AppState.markers[i].data && AppState.markers[i].data.id === lotId) {
                        lot = AppState.markers[i].data;
                        break;
                    }
                }

                if (!lot && AppState.polygons[lotId]) {
                    lot = AppState.polygons[lotId].data;
                }

                if (!lot) {
                    console.error(`Lot with ID ${lotId} not found`);
                    return;
                }

                const status = lot.status ? Utils.formatStatus(lot.status) : {
                    text: "Статус не указан",
                    class: "badge-info"
                };

                const name = Utils.safeGet(lot, 'neighborhood_name', Utils.safeGet(lot, 'name', 'Unnamed'));
                const district = Utils.safeGet(lot, 'district_name', Utils.safeGet(lot, 'district'));
                const area = Utils.safeGet(lot, 'area_hectare', Utils.safeGet(lot, 'area'));

                const modalContent = `
            <div class="section-title">
                <i class="fas fa-info-circle"></i> Асосий маълумотлар
            </div>
            <table class="details-table">
                <tr><td><i class="fas fa-map-marker-alt"></i> Туман:</td><td>${district}</td></tr>
                <tr><td><i class="fas fa-ruler-combined"></i> Майдон:</td><td>${area} га</td></tr>
                <tr><td><i class="fas fa-flag"></i> Ҳолати:</td><td><span class="badge ${status.class}">${status.text}</span></td></tr>
                <tr><td><i class="fas fa-user"></i> Инвестор:</td><td>${Utils.safeGet(lot, 'investor')}</td></tr>
                <tr><td><i class="fas fa-file-alt"></i> Қарор рақами:</td><td>${Utils.safeGet(lot, 'decision_number')}</td></tr>
            </table>

            <div class="section-title">
                <i class="fas fa-cog"></i> Техник параметрлар
            </div>
            <table class="details-table">
                <tr><td><i class="fas fa-building"></i> Мавжуд қаватлар:</td><td>${Utils.safeGet(lot, 'designated_floors')}</td></tr>
                <tr><td><i class="fas fa-layer-group"></i> Таклиф қилинган қаватлар:</td><td>${Utils.safeGet(lot, 'proposed_floors')}</td></tr>
                <tr><td><i class="fas fa-calculator"></i> УМН коэффициенти:</td><td>${Utils.safeGet(lot, 'umn_coefficient')}</td></tr>
                <tr><td><i class="fas fa-home"></i> Умумий майдон:</td><td>${Utils.safeGet(lot, 'total_building_area')} м²</td></tr>
            </table>
        `;

                this.showModal(name, modalContent);
            },

            /**
             * Show DOP KMZ details modal
             */
            showDopKmzModal(fileName) {
                const kmzLayer = AppState.kmzLayers[`dop_${fileName}`];
                if (!kmzLayer) {
                    console.error(`DOP KMZ layer ${fileName} not found`);
                    return;
                }

                let lotData = {};
                kmzLayer.eachLayer(function(layer) {
                    if (layer.kmzData) {
                        lotData = layer.kmzData;
                        return false;
                    }
                });

                const displayName = lotData['Номи'] || fileName.replace('.kmz', '');

                let modalContent = `
            <div class="section-title">
                <i class="fas fa-file-archive"></i> KMZ файл маълумотлари
            </div>
            <table class="details-table">
                <tr><td><i class="fas fa-file"></i> Файл номи:</td><td>${fileName}</td></tr>
        `;

                Object.keys(lotData).forEach(key => {
                    if (key !== 'Номи') {
                        modalContent +=
                            `<tr><td><i class="fas fa-info"></i> ${key}:</td><td>${lotData[key]}</td></tr>`;
                    }
                });

                modalContent += `
            </table>

            <div class="section-title">
                <i class="fas fa-download"></i> Файл операциялари
            </div>
            <a href="/assets/data/DOP_DATA/${fileName}" download class="document-link">
                <i class="fas fa-download"></i> Файлни юклаб олиш
            </a>
        `;

                this.showModal(displayName, modalContent);
            }
        };

        // Data Loading and Management
        const DataLoader = {
            /**
             * Load all data sources
             */
            async loadAllData() {
                UI.showLoading();

                try {
                    // Reset counts
                    AppState.counts = {
                        regular: 0,
                        auction: 0,
                        jsonData: 0,
                        kmz: 0,
                        dopKmz: 0
                    };

                    // Load JSON Data
                    const jsonResult = await DataServices.fetchJsonData();
                    if (jsonResult.success && jsonResult.data.length > 0) {
                        let processedJson = 0;
                        for (const item of jsonResult.data) {
                            if (MapManager.addJsonDataMarker(item)) {
                                processedJson++;
                            }
                        }

                        if (processedJson > 0) {
                            Utils.showToast(`Юкланди ${processedJson} та JSON маълумот`, 'success');
                        }
                    }

                    // Load Auction Data
                    const auctionResult = await DataServices.fetchAuctionData();
                    if (auctionResult.success && auctionResult.data.length > 0) {
                        let processedAuction = 0;
                        for (const lot of auctionResult.data) {
                            if (MapManager.addAuctionMarker(lot)) {
                                processedAuction++;
                            }
                        }

                        if (processedAuction > 0) {
                            Utils.showToast(`Юкланди ${processedAuction} та аукцион`, 'success');
                        }
                    }

                    // Load API Data
                    const apiResult = await DataServices.fetchApiData();
                    if (apiResult.success && apiResult.data.length > 0) {
                        let processedApi = 0;
                        for (const lot of apiResult.data) {
                            if (MapManager.addApiMarker(lot)) {
                                processedApi++;
                            }
                        }

                        if (processedApi > 0) {
                            Utils.showToast(`Юкланди ${processedApi} та API маълумот`, 'success');
                        }
                    }

                    // Load DOP KMZ Files
                    let processedDopKmz = 0;
                    for (const fileName of APP_CONFIG.dopKmzFiles) {
                        if (await MapManager.addDopKmzLayer(fileName)) {
                            processedDopKmz++;
                        }
                        await new Promise(resolve => setTimeout(resolve, 100));
                    }

                    if (processedDopKmz > 0) {
                        Utils.showToast(`Юкланди ${processedDopKmz} та DOP KMZ файл`, 'success');
                    }

                    // Update UI
                    UI.updateControls();

                    // Fit bounds to show all data
                    this.fitMapBounds();

                    const total = Object.values(AppState.counts).reduce((sum, count) => sum + count, 0);
                    if (total > 0) {
                        Utils.showToast(`Жами юкланди: ${total} та маълумот`, 'success');
                    } else {
                        Utils.showToast('Ҳеч қандай маълумот юкланмади', 'warning');
                    }

                } catch (error) {
                    console.error('Error loading data:', error);
                    Utils.showToast('Маълумотларни юклашда хатолик: ' + error.message, 'error');
                } finally {
                    UI.hideLoading();
                }
            },

            /**
             * Fit map bounds to show all data
             */
            fitMapBounds() {
                const allMarkers = [];

                if (AppState.jsonDataMarkers.length > 0) {
                    allMarkers.push(...AppState.jsonDataMarkers.map(m => m.marker));
                }

                if (AppState.markers.length > 0) {
                    allMarkers.push(...AppState.markers.map(m => m.marker));
                }

                if (allMarkers.length > 0 || Object.keys(AppState.kmzLayers).length > 0) {
                    const allLayers = [...allMarkers];

                    Object.values(AppState.kmzLayers).forEach(kmzLayer => {
                        if (kmzLayer.getBounds) {
                            allLayers.push(kmzLayer);
                        }
                    });

                    if (allLayers.length > 0) {
                        const group = L.featureGroup(allLayers);
                        AppState.map.fitBounds(group.getBounds(), {
                            padding: [50, 50]
                        });
                    }
                } else {
                    AppState.map.setView(APP_CONFIG.defaultCenter, APP_CONFIG.defaultZoom);
                }
            }
        };

        // Application Initialization
        const App = {
            /**
             * Initialize the application
             */
            async init() {
                try {
                    console.log('Initializing InvestUz Application...');

                    // Initialize map
                    MapManager.initMap();

                    // Initialize UI
                    UI.init();

                    // Load all data
                    await DataLoader.loadAllData();

                    // Setup error handling
                    this.setupErrorHandling();

                    console.log('Application initialized successfully');

                } catch (error) {
                    console.error('Application initialization failed:', error);
                    Utils.showToast('Дастурни ишга туширишда хатолик: ' + error.message, 'error');
                }
            },

            /**
             * Setup global error handling
             */
            setupErrorHandling() {
                window.addEventListener('error', (event) => {
                    console.error('Global error:', event.error);
                    Utils.showToast('Дастурда хатолик юз берди: ' + event.error.message, 'error');
                });

                window.addEventListener('unhandledrejection', (event) => {
                    console.error('Unhandled promise rejection:', event.reason);
                    Utils.showToast('Асинхрон операцияда хатолик: ' + event.reason, 'error');
                    event.preventDefault();
                });

                // Handle page visibility change for performance
                document.addEventListener('visibilitychange', () => {
                    if (document.hidden) {
                        // Page is hidden, pause any animations or heavy operations
                        console.log('Page hidden, pausing operations');
                    } else {
                        // Page is visible again, resume operations
                        console.log('Page visible, resuming operations');
                        if (AppState.map) {
                            AppState.map.invalidateSize();
                        }
                    }
                });
            }
        };

        // Make UI functions globally available for popup buttons
        window.UI = UI;

        // Initialize application when DOM is ready
        document.addEventListener('DOMContentLoaded', () => {
            App.init();
        });

        // Export for potential external use
        if (typeof module !== 'undefined' && module.exports) {
            module.exports = {
                App,
                AppState,
                Utils,
                MapManager,
                UI,
                DataServices,
                DataLoader
            };
        }
    </script>
</body>

</html>
