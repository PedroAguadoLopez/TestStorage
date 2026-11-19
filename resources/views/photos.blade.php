<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Gestión de fotos</title>
    </head>
    <body>
        <nav>
            <a href="{{ route('photos.index') }}">Galería de imágenes</a> |
            <a href="{{ route('docs.index') }}">Gestor de documentos</a>
        </nav>

        <h1>Subir nueva imagen</h1>

        @if(session('success'))
            <p>{{ session('success') }}</p>
        @endif

        @if(session('error'))
            <p>{{ session('error') }}</p>
        @endif

        <form action="{{ route('photos.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="name">Nombre de la foto</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div>
                <label for="image">Selecciona una imagen</label>
                <input type="file" name="image" id="image" accept="image/*" required>
            </div>
            <br>
            <button type="submit">Subir imagen</button>
        </form>

        <h2>Galería</h2>

        @foreach($photos as $photo)
            <div>
                <h3>{{ $photo->name }}</h3>
                <img src="{{ asset('storage/' . $photo->image_path) }}" alt="{{ $photo->name }}" width="250">
                <p>Ruta en BBDD: {{ $photo->image_path }}</p>

                <form action="{{ route('photos.update', $photo) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="image-{{ $photo->id }}">Selecciona una imagen</label>
                        <input type="file" name="image" id="image-{{ $photo->id }}" accept="image/*" required>
                    </div>
                    <br>
                    <button type="submit">Actualizar imagen</button>
                </form>

                <form action="{{ route('photos.destroy', $photo) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Eliminar imagen</button>
                </form>
            </div>
        @endforeach
    </body>
</html>