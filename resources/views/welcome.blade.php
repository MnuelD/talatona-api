<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login Seguro</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center h-screen bg-gray-100">
    <div class="bg-white p-6 rounded-lg shadow-lg w-96">
        <h2 class="text-2xl font-bold mb-4 text-center">🔐 Login com 2FA</h2>

        @if(session('message'))
            <div class="bg-green-100 text-green-700 p-2 mb-3 rounded">{{ session('message') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <label>Email</label>
            <input type="email" name="email" class="w-full border p-2 mb-3 rounded" required>

            <label>Senha</label>
            <input type="password" name="password" class="w-full border p-2 mb-3 rounded" required>

            <label>Telefone</label>
            <input type="text" name="phone" class="w-full border p-2 mb-3 rounded" required>

            <button class="bg-blue-600 text-white w-full p-2 rounded">Entrar</button>
        </form>

        <form method="POST" action="{{ route('verify.sms') }}" class="mt-4">
            @csrf
            <label>Código SMS</label>
            <input type="text" name="code" class="w-full border p-2 mb-3 rounded">
            <button class="bg-green-600 text-white w-full p-2 rounded">Validar SMS</button>
        </form>
    </div>
</body>
</html>
