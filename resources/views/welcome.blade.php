<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <title>Login Seguro</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="flex items-center justify-center min-h-screen bg-gradient-to-r from-blue-100 to-indigo-200">

    <div class="bg-white p-8 rounded-2xl shadow-lg w-full max-w-md">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-800">🔐 Login com 2FA</h2>

        @if(session('message'))
            <div class="bg-green-100 text-green-700 p-3 mb-4 rounded-md text-sm">
                {{ session('message') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 text-red-700 p-3 mb-4 rounded-md text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block font-medium text-sm text-gray-700">Email</label>
                <input type="email" name="email" class="w-full border p-2 rounded-lg focus:ring focus:ring-blue-300" required>
            </div>

            <div>
                <label class="block font-medium text-sm text-gray-700">Senha</label>
                <input type="password" name="password" class="w-full border p-2 rounded-lg focus:ring focus:ring-blue-300" required>
            </div>

            <div>
                <label class="block font-medium text-sm text-gray-700">Telefone</label>
                <input type="text" name="telefone" class="w-full border p-2 rounded-lg focus:ring focus:ring-blue-300">
                <p class="text-xs text-gray-500 mt-1">📱 Preencha se for autenticar via SMS</p>
            </div>

            <div>
                <label class="block font-medium text-sm text-gray-700">Método de autenticação</label>
                <select name="method" class="w-full border p-2 rounded-lg focus:ring focus:ring-blue-300" required>
                    <option value="email">📧 Email</option>
                    <option value="sms">📱 SMS</option>
                </select>
            </div>

            <button class="bg-blue-600 hover:bg-blue-700 text-white w-full p-2 rounded-lg font-semibold transition">
                Entrar
            </button>
        </form>
    </div>

</body>
</html>
