<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Nuevo post</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f6f8; font-family: Arial, Helvetica, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0" style="background-color:#f4f6f8; padding:40px 0;">
        <tr>
            <td align="center">

                <!-- Contenedor principal -->
                <table width="600" cellpadding="0" cellspacing="0"
                    style="background:#ffffff; border-radius:12px; overflow:hidden; box-shadow:0 4px 15px rgba(0,0,0,0.05);">

                    <tr>
                        <td style="background:#111827; padding:30px; text-align:center; color:#ffffff;">
                            <h2 style="margin:0; font-size:22px; font-weight:600;">
                                {{ $post->user->name }} ha publicado un nuevo post 🚀
                            </h2>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:30px; color:#374151; font-size:16px; line-height:1.6;">

                            <p style="margin-top:0;">
                                Tu usuario seguido ha compartido algo nuevo:
                            </p>

                            <div style="background:#f9fafb; padding:20px; border-radius:8px; margin:20px 0;">
                                <p style="margin:0; font-style:italic;">
                                    "{{ \Illuminate\Support\Str::limit($post->content, 250) }}"
                                </p>
                            </div>

                            <div style="text-align:center; margin-top:30px;">
                                <a href="{{ route('posts.show', $post->id) }}"
                                    style="display:inline-block;
                                        padding:12px 25px;
                                        background:#2563eb;
                                        color:#ffffff;
                                        text-decoration:none;
                                        border-radius:8px;
                                        font-weight:600;">
                                    Ver publicación
                                </a>
                            </div>

                        </td>
                    </tr>

                    <tr>
                        <td style="background:#f9fafb; padding:20px; text-align:center; font-size:13px; color:#9ca3af;">
                            Estás recibiendo este correo porque sigues a {{ $post->user->name }}.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
