<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 — Page introuvable</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/style/style.css">
    <style>
        .err-wrap {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 48px 24px;
        }

        .err-inner {
            text-align: center;
            max-width: 500px;
        }

        .err-illustration {
            position: relative;
            width: 220px;
            height: 180px;
            margin: 0 auto 36px;
        }

        .err-num {
            font-size: 120px;
            font-weight: 800;
            line-height: 1;
            letter-spacing: -6px;
            color: #edf8f0;
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            white-space: nowrap;
            animation: errFadeUp 0.6s ease both;
        }

        .err-leaf {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            animation: errGrow 0.8s cubic-bezier(0.34, 1.56, 0.64, 1) 0.2s both;
        }

        .err-leaf-anim {
            animation: errSway 3s ease-in-out infinite alternate;
            transform-origin: center bottom;
        }

        .err-title {
            font-size: clamp(20px, 3vw, 26px);
            font-weight: 800;
            letter-spacing: -0.5px;
            color: var(--text-dark);
            margin-bottom: 10px;
            animation: errFadeUp 0.5s ease 0.3s both;
        }

        .err-sub {
            font-size: 15px;
            font-weight: 400;
            color: var(--text-muted);
            line-height: 1.7;
            margin-bottom: 28px;
            animation: errFadeUp 0.5s ease 0.4s both;
        }

        .err-actions {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
            animation: errFadeUp 0.5s ease 0.5s both;
        }

        .err-btn-primary {
            background-color: var(--green);
            color: #fff;
            border: none;
            border-radius: 8px;
            padding: 14px 28px;
            font-size: 15px;
            font-weight: 600;
            font-family: var(--font);
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background 0.2s, transform 0.15s;
        }

        .err-btn-primary:hover {
            background-color: #007040;
            transform: translateY(-1px);
        }

        .err-btn-secondary {
            background: transparent;
            color: var(--text-dark);
            border: 1.5px solid #c0c8b8;
            border-radius: 8px;
            padding: 14px 28px;
            font-size: 15px;
            font-weight: 600;
            font-family: var(--font);
            cursor: pointer;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            transition: border-color 0.2s, color 0.2s, transform 0.15s;
        }

        .err-btn-secondary:hover {
            border-color: var(--green);
            color: var(--green);
            transform: translateY(-1px);
        }

        @keyframes errFadeUp {
            from {
                opacity: 0;
                transform: translateY(12px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes errGrow {
            from {
                opacity: 0;
                transform: translateX(-50%) scale(0.6);
            }

            to {
                opacity: 1;
                transform: translateX(-50%) scale(1);
            }
        }

        @keyframes errSway {
            from {
                transform: rotate(-3deg);
            }

            to {
                transform: rotate(3deg);
            }
        }
    </style>
</head>

<body>

    <div class="err-wrap">
        <div class="err-inner">

            <div class="err-illustration">
                <div class="err-num">404</div>
                <svg class="err-leaf" width="160" height="110" viewBox="0 0 160 110" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g class="err-leaf-anim">
                        <path d="M80 105 Q80 70 50 55 Q30 45 20 20 Q50 10 70 30 Q80 42 80 55" fill="#c8ecd3" />
                        <path d="M80 105 Q80 70 110 55 Q130 45 140 20 Q110 10 90 30 Q80 42 80 55" fill="#a8ddb9" />
                        <path d="M80 55 L80 105" stroke="#008a48" stroke-width="2" stroke-linecap="round" />
                        <path d="M80 70 Q65 62 58 50" stroke="#008a48" stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                        <path d="M80 82 Q95 74 102 62" stroke="#008a48" stroke-width="1.5" stroke-linecap="round" opacity="0.6" />
                        <circle cx="80" cy="108" r="5" fill="#008a48" opacity="0.3" />
                        <ellipse cx="80" cy="109" rx="22" ry="4" fill="#008a48" opacity="0.12" />
                    </g>
                </svg>
            </div>

            <h1 class="err-title">Cette page s'est envolée</h1>
            <p class="err-sub">
                La page que vous cherchez n'existe pas ou a été déplacée.<br>
                Pas de panique — revenons sur nos pas.
            </p>

            <div class="err-actions">
                <a href="/" class="err-btn-primary">
                    <svg width="15" height="15" viewBox="0 0 16 16" fill="none">
                        <path d="M3 8h10M3 8l4-4M3 8l4 4" stroke="white" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    Retour à l'accueil
                </a>
                <a href="/defis" class="err-btn-secondary">Découvrir les défis</a>
            </div>

        </div>
    </div>

</body>

</html>