<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réinitialisation de votre mot de passe</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f6f4; font-family:Arial, Helvetica, sans-serif; color:#2e3d2f;">
    <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f4; padding:32px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellpadding="0" cellspacing="0" style="max-width:600px; width:100%; background-color:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 2px 8px rgba(0,0,0,0.05);">

                    <!-- En-tête -->
                    <tr>
                        <td style="background-color:#2e7d32; padding:28px 32px; text-align:center;">
                            <h1 style="margin:0; color:#ffffff; font-size:24px; font-weight:700;">EcoTrack</h1>
                        </td>
                    </tr>

                    <!-- Contenu -->
                    <tr>
                        <td style="padding:32px;">
                            <h2 style="margin:0 0 16px; font-size:20px; color:#2e3d2f;">Réinitialisation de votre mot de passe</h2>
                            <p style="margin:0 0 16px; font-size:15px; line-height:1.6; color:#4a5a4b;">
                                Bonjour,
                            </p>
                            <p style="margin:0 0 24px; font-size:15px; line-height:1.6; color:#4a5a4b;">
                                Vous avez demandé la réinitialisation de votre mot de passe. Cliquez sur le bouton ci-dessous pour en choisir un nouveau.
                            </p>

                            <table role="presentation" cellpadding="0" cellspacing="0" style="margin:0 auto 24px;">
                                <tr>
                                    <td align="center" style="border-radius:8px; background-color:#2e7d32;">
                                        <a href="<?= htmlspecialchars($resetLink) ?>" target="_blank"
                                           style="display:inline-block; padding:14px 32px; font-size:15px; font-weight:600; color:#ffffff; text-decoration:none; border-radius:8px;">
                                            Réinitialiser mon mot de passe
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 8px; font-size:13px; line-height:1.6; color:#7a8a7b;">
                                Si le bouton ne fonctionne pas, copiez-collez ce lien dans votre navigateur :
                            </p>
                            <p style="margin:0 0 24px; font-size:13px; line-height:1.6; word-break:break-all;">
                                <a href="<?= htmlspecialchars($resetLink) ?>" style="color:#2e7d32;"><?= htmlspecialchars($resetLink) ?></a>
                            </p>

                            <p style="margin:0; font-size:13px; line-height:1.6; color:#7a8a7b;">
                                Si vous n'êtes pas à l'origine de cette demande, vous pouvez ignorer cet email en toute sécurité.
                            </p>
                        </td>
                    </tr>

                    <!-- Pied de page -->
                    <tr>
                        <td style="background-color:#f4f6f4; padding:20px 32px; text-align:center;">
                            <p style="margin:0; font-size:12px; color:#9aa89b;">
                                &copy; <?= date('Y') ?> EcoTrack — Votre parcours écologique
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>

</html>
