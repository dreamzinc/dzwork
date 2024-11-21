<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'DzWork Framework' ?></title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(-45deg, #1a0f1f, #2d1f35, #1f1a2d, #2d1f35);
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

        .container {
            width: 100%;
            max-width: 850px;
            text-align: center;
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(12px);
            border-radius: 1.5rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
            box-shadow: 
                0 25px 50px -12px rgba(0, 0, 0, 0.5),
                0 0 0 1px rgba(255, 255, 255, 0.1);
            overflow: hidden;
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

        .content-wrapper {
            padding: 3rem;
            background: rgba(0, 0, 0, 0.2);
        }

        p {
            margin-bottom: 2rem;
            font-size: 1.125rem;
            color: rgba(255, 255, 255, 0.8);
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

        @media (max-width: 640px) {
            body {
                padding: 1rem;
            }
            
            .container {
                margin: 0;
                border-radius: 1rem;
            }

            .content-wrapper {
                padding: 2rem 1.5rem;
            }
        }
    </style>
    <?= $this->renderSection('styles') ?>
</head>
<body>
    <div class="container">
        <div class="content-wrapper">
            <?= $this->renderSection('content') ?>
        </div>
    </div>
    <?= $this->renderSection('scripts') ?>
</body>
</html>
