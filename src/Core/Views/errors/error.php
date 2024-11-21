<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kesalahan - <?= $message ?? 'Terjadi kesalahan' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(-45deg, #0f172a, #1e293b, #334155, #1e293b);
            background-size: 400% 400%;
            animation: gradientBG 15s ease infinite;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #e2e8f0;
            line-height: 1.6;
            padding: 1rem;
        }

        @keyframes gradientBG {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        .error-container {
            width: 100%;
            max-width: 850px;
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(12px);
            border-radius: 1.5rem;
            overflow: hidden;
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            transform: translateY(0);
            transition: transform 0.3s ease;
            animation: fadeInUp 0.5s ease-out forwards;
        }

        @keyframes fadeInUp {
            from { 
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .error-header {
            background: linear-gradient(135deg, #dc2626 0%, #ef4444 100%);
            padding: 2rem;
            position: relative;
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
        }

        .error-header-content {
            flex: 1;
        }

        .error-title {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            position: relative;
            letter-spacing: -0.02em;
        }

        .error-subtitle {
            font-size: 1rem;
            opacity: 0.9;
            position: relative;
        }

        .home-button {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.9rem;
            transition: all 0.2s ease;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-left: 1rem;
        }

        .home-button:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-1px);
        }

        .error-content {
            padding: 2rem;
            background: rgba(0, 0, 0, 0.2);
        }

        .error-section {
            margin-bottom: 1.5rem;
            padding: 1.25rem;
            background: rgba(255, 255, 255, 0.03);
            border-radius: 1rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .error-section:hover {
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.1);
            transform: translateX(5px);
        }

        .error-section:last-child {
            margin-bottom: 0;
        }

        .error-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 0.75rem;
            color: #f87171;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .error-section-title::before {
            content: '';
            display: block;
            width: 4px;
            height: 1em;
            background: #f87171;
            border-radius: 2px;
        }

        .code-block {
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 0.9rem;
            padding: 1.25rem;
            background: rgba(0, 0, 0, 0.4);
            border-radius: 0.75rem;
            overflow-x: auto;
            color: #e2e8f0;
            margin-top: 0.75rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
        }

        .technical-info {
            display: grid;
            gap: 0.75rem;
            font-size: 0.95rem;
        }

        .technical-info span {
            color: #f87171;
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            padding: 0.25rem 0.5rem;
            background: rgba(248, 113, 113, 0.1);
            border-radius: 0.25rem;
            font-size: 0.9rem;
        }

        .error-actions {
            padding: 1.5rem 2rem;
            background: rgba(0, 0, 0, 0.2);
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to right, rgba(255,255,255,0) 0%, rgba(255,255,255,0.1) 50%, rgba(255,255,255,0) 100%);
            transform: translateX(-100%);
            transition: transform 0.5s ease;
        }

        .btn:hover::before {
            transform: translateX(100%);
        }

        .btn-secondary {
            background: rgba(255, 255, 255, 0.05);
            color: white;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-secondary:hover {
            background: rgba(255, 255, 255, 0.1);
            border-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
        }

        .dev-notice {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.85rem;
            color: rgba(255, 255, 255, 0.6);
            padding: 0.75rem 1.5rem;
            background: rgba(0, 0, 0, 0.2);
            border-radius: 1rem;
            display: inline-block;
        }

        /* Scrollbar styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.2);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }

        /* Responsive adjustments */
        @media (max-width: 640px) {
            body {
                padding: 1rem;
            }
            
            .error-container {
                margin: 0;
                border-radius: 1rem;
            }

            .error-header {
                padding: 1.5rem;
                flex-direction: column;
                gap: 1rem;
                align-items: stretch;
            }

            .home-button {
                margin-left: 0;
                justify-content: center;
            }

            .error-title {
                font-size: 1.5rem;
            }

            .error-content {
                padding: 1.5rem;
            }

            .error-actions {
                padding: 1.25rem;
            }

            .btn {
                width: 100%;
                justify-content: center;
                padding: 0.875rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <div class="error-header">
            <div class="error-header-content">
                <h1 class="error-title">Ups! Terjadi Kesalahan</h1>
                <p class="error-subtitle">Kami mengalami masalah yang tidak terduga</p>
            </div>
            <a href="<?= rtrim((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://') . $_SERVER['HTTP_HOST'] . dirname($_SERVER['SCRIPT_NAME']), '/') ?>/" class="home-button">Kembali ke Beranda</a>
        </div>

        <div class="error-content">
            <div class="error-section">
                <h2 class="error-section-title">Pesan Kesalahan</h2>
                <div class="code-block">
                    <?= htmlspecialchars($message ?? 'Terjadi kesalahan yang tidak diketahui') ?>
                </div>
            </div>

            <?php if (isset($file) && isset($line)): ?>
            <div class="error-section">
                <h2 class="error-section-title">Lokasi</h2>
                <div class="technical-info">
                    <p>File: <span><?= htmlspecialchars($file) ?></span></p>
                    <p>Baris: <span><?= $line ?></span></p>
                </div>
            </div>
            <?php endif; ?>

            <?php if (isset($trace)): ?>
            <div class="error-section">
                <h2 class="error-section-title">Jejak Stack</h2>
                <div class="code-block">
                    <?= htmlspecialchars($trace) ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <?php if (isset($_SERVER['HTTP_REFERER'])): ?>
        <div class="error-actions">
            <button onclick="history.back()" class="btn btn-secondary">Kembali</button>
        </div>
        <?php endif; ?>
    </div>

    <?php if (defined('DEVELOPMENT_MODE') && DEVELOPMENT_MODE): ?>
    <div class="dev-notice">
        <p>Anda melihat kesalahan ini karena aplikasi dalam mode pengembangan.</p>
    </div>
    <?php endif; ?>
</body>
</html>
