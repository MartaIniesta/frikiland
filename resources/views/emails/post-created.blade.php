<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Post creado</title>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f5f5f5; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 20px; border-radius: 8px;">
        <h2 style="color: #333;">¡Post creado con éxito! 🎉</h2>

        <p>Hola <strong>{{ $post->user->name }}</strong>,</p>

        <p>
            Tu publicación se ha creado correctamente en la plataforma.
        </p>

        <p><strong>Contenido del post:</strong></p>

        <blockquote style="background:#f0f0f0; padding:10px; border-left:4px solid #21526d; margin:0; word-wrap:break-word; overflow-wrap:break-word; white-space:normal;">
            {{ \Illuminate\Support\Str::limit($post->content, 150) }}
        </blockquote>


        <p style="font-size: 14px; color: #666;">
            Fecha de creación: {{ $post->created_at->format('d/m/Y H:i') }}
        </p>

        <hr>

        <p style="font-size: 13px; color: #999;">
            Este correo es informativo. No es necesario responder.
        </p>
    </div>
</body>

</html>
