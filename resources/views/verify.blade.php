<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Verificação SMS</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-r from-green-100 to-emerald-200">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">📱 Confirme o Código SMS</h2>

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 mb-4 rounded-md text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('verify.sms') }}" class="space-y-4">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user_id }}">

            <div>
                <label class="block font-medium text-sm text-gray-700">Código SMS</label>
                <input type="text" name="code" class="w-full border p-2 rounded-lg focus:ring focus:ring-green-300" placeholder="Digite o código recebido">
            </div>

            <button class="bg-green-600 hover:bg-green-700 text-white w-full p-2 rounded-lg font-semibold transition">
                Validar SMS
            </button>
        </form>
    </div>

</body>
</html>
