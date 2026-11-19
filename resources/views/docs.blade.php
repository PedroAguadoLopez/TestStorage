<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="UTF-8">
        <title>Gestión de documentos</title>
    </head>
    <body>
        <nav>
            <a href="{{ route('photos.index') }}">Galería de imágenes</a> |
            <a href="{{ route('docs.index') }}">Gestor de documentos</a>
        </nav>

        <h1>Subir nuevo documento PDF</h1>

        @if(session('success'))
            <p>{{ session('success') }}</p>
        @endif

        @if(session('error'))
            <p>{{ session('error') }}</p>
        @endif

        <form action="{{ route('docs.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div>
                <label for="name">Título del documento</label>
                <input type="text" name="name" id="name" required>
            </div>
            <div>
                <label for="doc">Selecciona un PDF</label>
                <input type="file" name="doc" id="doc" accept="application/pdf" required>
            </div>
            <br>
            <button type="submit">Subir documento</button>
        </form>

        <h2>Documentos</h2>

        @foreach($docs as $doc)
            <div>
                <h3>
                    <a href="{{ asset('storage/' . $doc->doc_path) }}" target="_blank">{{ $doc->name }}</a>
                </h3>
                <p>Ruta en BBDD: {{ $doc->doc_path }}</p>

                <form action="{{ route('docs.update', $doc) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div>
                        <label for="doc-{{ $doc->id }}">Reemplazar PDF</label>
                        <input type="file" name="doc" id="doc-{{ $doc->id }}" accept="application/pdf" required>
                    </div>
                    <br>
                    <button type="submit">Actualizar documento</button>
                </form>

                <form action="{{ route('docs.destroy', $doc) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit">Eliminar documento</button>
                </form>
            </div>
        @endforeach
    </body>
</html>