<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ocorrência Registrada</title>
</head>
<body style="margin:0; padding:0; background-color:#f4f4f4; font-family: Arial, sans-serif;">

    <table width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 4px 8px rgba(0,0,0,0.05);">
                    <!-- Cabeçalho -->
                    <tr>
                        <td style="background-color:#2E86C1; padding:20px; text-align:center; color:#ffffff;">
                            <h1 style="margin:0; font-size:24px;">Ticket Registrado com Sucesso</h1>
                        </td>
                    </tr>

                    <!-- Corpo -->
                    <tr>
                        <td style="padding: 30px; color:#333;">
                            <p style="font-size:16px;">Olá,</p>
                            <p style="font-size:16px; line-height:1.5;">
                                Seu ticket foi registrado com sucesso em nosso sistema. Seguem os detalhes:
                            </p>

                            <table cellpadding="0" cellspacing="0" style="width:100%; margin-top:15px;">
                                <tr>
                                    <td style="padding:8px 0; font-weight:bold; width:150px;">Código do Ticket:</td>
                                    <td style="padding:8px 0; color:#555;">{{ $dados['codigo_ocorrencia'] }}</td>
                                <tr>
                                    <td style="padding:8px 0; font-weight:bold; width:150px;">Descrição:</td>
                                    <td style="padding:8px 0; color:#555;">{{ $dados['descricao'] }}</td>
                                </tr>

                                <tr>
                                    <td style="padding:8px 0; font-weight:bold;">E-mail:</td>
                                    <td style="padding:8px 0; color:#555;">{{ $dados['email'] }}</td>
                                </tr>
                            </table>

                            <p style="font-size:16px; margin-top:20px;">
                                Nossa equipe entrará em contato em breve para dar andamento à sua solicitação.
                            </p>
                        </td>
                    </tr>

                    <!-- Rodapé -->
                    <tr>
                        <td style="background-color:#f4f4f4; padding:15px; text-align:center; font-size:12px; color:#888;">
                            &copy; {{ date('Y') }} Administração do Talatona. Todos os direitos reservados.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>

</body>
</html>

