<!DOCTYPE html>
<html>
<head>
    <title>Generowanie kodu kreskowego</title>
</head>
<body>
<form action="{{ url('/barcode/generate') }}" method="post">
    @csrf
    <input type="text" name="barcode" required>
    <button type="submit">Wygeneruj kod kreskowy</button>
</form>

@if (isset($barcode))
    <div>
        <img src="{{ asset(str_replace(public_path(), '', $barcode)) }}" alt="Barcode Image">
    </div>
@endif
</body>
</html>
