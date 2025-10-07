<!DOCTYPE html>
<html lang="pt">
<head>
  <meta charset="UTF-8" />
  <title>API Docs - Swagger Style</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    :root {
      --primary-color: #4990e2;
      --secondary-color: #2c5282;
      --accent-color: #38a169;
      --dark-bg: #1a202c;
      --light-bg: #f7fafc;
      --border-color: #e2e8f0;
    }
    
    body { 
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
      background-color: var(--light-bg);
    }
    
    .sidebar { 
      width: 280px; 
      background: var(--dark-bg); 
      color: #fff; 
      min-height: 100vh;
      position: fixed;
      overflow-y: auto;
      transition: all 0.3s ease;
      z-index: 100;
    }
    
    .sidebar-header { 
      padding: 1.5rem; 
      border-bottom: 1px solid #2d3748; 
      background: rgba(0, 0, 0, 0.2);
    }
    
    .sidebar-header h2 { 
      font-size: 1.3rem; 
      font-weight: 600;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .search-box {
      padding: 0 1.5rem 1rem;
    }
    
    .search-box input {
      width: 100%;
      padding: 0.5rem 1rem;
      border-radius: 4px;
      background: #2d3748;
      border: 1px solid #4a5568;
      color: white;
    }
    
    .sidebar ul { 
      list-style: none; 
      padding: 0; 
    }
    
    .sidebar li { 
      padding: 0.75rem 1.5rem; 
      cursor: pointer; 
      border-left: 3px solid transparent;
      transition: all 0.2s;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    
    .sidebar li:hover { 
      background: #2d3748; 
      border-left-color: var(--primary-color);
    }
    
    .sidebar li.active { 
      background: #2d3748; 
      border-left-color: var(--accent-color);
    }
    
    .content { 
      padding: 2rem; 
      flex: 1; 
      margin-left: 280px;
    }
    
    .header { 
      display: flex; 
      justify-content: space-between; 
      align-items: center; 
      margin-bottom: 2rem; 
      padding-bottom: 1rem;
      border-bottom: 1px solid var(--border-color);
    }
    
    .endpoint { 
      margin-bottom: 2.5rem; 
      border: 1px solid var(--border-color); 
      border-radius: 8px;
      background: white;
      box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
      overflow: hidden;
    }
    
    .endpoint-header { 
      background: #f8fafc; 
      padding: 1rem 1.5rem; 
      border-bottom: 1px solid var(--border-color);
      display: flex;
      align-items: center;
      justify-content: space-between;
    }
    
    .method { 
      display: inline-block; 
      padding: 0.35rem 0.75rem; 
      border-radius: 4px; 
      font-weight: 600; 
      font-size: 0.85rem;
      margin-right: 0.75rem;
    }
    
    .get { background: #ebf8ff; color: #3182ce; }
    .post { background: #e6fffa; color: #38a169; }
    .put { background: #faf5ff; color: #805ad5; }
    .delete { background: #fff5f5; color: #e53e3e; }
    
    .endpoint-path { 
      font-family: monospace; 
      font-size: 1.1rem;
      flex-grow: 1;
    }
    
    .endpoint-description { 
      padding: 1.5rem; 
    }
    
    .field-table {
      width: 100%;
      border-collapse: collapse;
      margin: 1rem 0;
    }
    
    .field-table th, .field-table td {
      padding: 0.75rem;
      text-align: left;
      border-bottom: 1px solid var(--border-color);
    }
    
    .field-table th {
      background: #f8fafc;
      font-weight: 600;
    }
    
    .required::after {
      content: " *";
      color: #e53e3e;
    }
    
    .code-block {
      background: #2d3748;
      color: #e2e8f0;
      padding: 1.25rem;
      border-radius: 6px;
      overflow-x: auto;
      margin: 1rem 0;
      font-family: 'Fira Code', monospace;
      font-size: 0.9rem;
    }
    
    .tag {
      display: inline-block;
      padding: 0.25rem 0.5rem;
      border-radius: 4px;
      font-size: 0.75rem;
      background: #edf2f7;
      color: #4a5568;
    }
    
    .toggle-sidebar {
      display: none;
      position: fixed;
      bottom: 20px;
      left: 20px;
      z-index: 1000;
      background: var(--primary-color);
      color: white;
      width: 40px;
      height: 40px;
      border-radius: 50%;
      display: flex;
      align-items: center;
      justify-content: center;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
    }
    
    @media (max-width: 1024px) {
      .sidebar {
        transform: translateX(-100%);
      }
      
      .sidebar.open {
        transform: translateX(0);
      }
      
      .content {
        margin-left: 0;
      }
      
      .toggle-sidebar {
        display: flex;
      }
    }
    
    .section-title {
      font-size: 1.5rem;
      font-weight: 600;
      margin: 2rem 0 1rem;
      padding-bottom: 0.5rem;
      border-bottom: 2px solid var(--primary-color);
      display: inline-block;
    }
    
    .model-container {
      margin-bottom: 3rem;
    }
  </style>
</head>
<body class="flex">

  <!-- Sidebar Toggle Button -->
  <div class="toggle-sidebar" onclick="toggleSidebar()">
    <i class="fas fa-bars"></i>
  </div>

  <!-- Sidebar -->
  <div class="sidebar" id="sidebar">
    <div class="sidebar-header">
      <h2><i class="fas fa-book"></i> API Documentation</h2>
    </div>
    
    <div class="search-box">
      <input type="text" placeholder="Search endpoints..." onkeyup="filterEndpoints(this.value)">
    </div>
    
    <ul>
      <li class="active" onclick="showSection('users')">
        <i class="fas fa-users"></i> Users
      </li>
      <li onclick="showSection('paginas')">
        <i class="fas fa-file"></i> Paginas
      </li>
      <li onclick="showSection('btn_paginas')">
        <i class="fas fa-link"></i> Btn_Paginas
      </li>
      <li onclick="showSection('destaques')">
        <i class="fas fa-star"></i> Destaques
      </li>
      <li onclick="showSection('categorias')">
        <i class="fas fa-tags"></i> Categorias
      </li>
      <li onclick="showSection('noticias')">
        <i class="fas fa-newspaper"></i> Noticias
      </li>
      <li onclick="showSection('anexo_noticias')">
        <i class="fas fa-paperclip"></i> Anexo_Noticias
      </li>
      <li onclick="showSection('comunas')">
        <i class="fas fa-map-marker-alt"></i> Comunas
      </li>
      <li onclick="showSection('bairros')">
        <i class="fas fa-map"></i> Bairros
      </li>
      <li onclick="showSection('tipo_ocorrencias')">
        <i class="fas fa-exclamation-circle"></i> Tipo_Ocorrencias
      </li>
      <li onclick="showSection('ocorrencias')">
        <i class="fas fa-flag"></i> Ocorrencias
      </li>
      <li onclick="showSection('anexo_ocorrencias')">
        <i class="fas fa-file-upload"></i> Anexo_Ocorrencias
      </li>
      <li onclick="showSection('direccaos')">
        <i class="fas fa-building"></i> Direccaos
      </li>
      <li onclick="showSection('funcionarios')">
        <i class="fas fa-user-tie"></i> Funcionarios
      </li>
      <li onclick="showSection('tickets')">
        <i class="fas fa-ticket-alt"></i> Tickets
      </li>
      <li onclick="showSection('municipes')">
        <i class="fas fa-user-friends"></i> Municipes
      </li>
    </ul>
  </div>

  <!-- Main Content -->
  <div class="content">
    <div class="header">
      <h1 class="text-3xl font-bold text-gray-800">API Documentation</h1>
      <div class="flex items-center gap-2">
        <span class="text-sm text-gray-600">v1.0.0</span>
        <span class="tag">Production</span>
      </div>
    </div>
    <!-- Botão de Logout -->
    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit" 
              class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700">
        <i class="fas fa-sign-out-alt"></i> Sair
      </button>
    </form>
    
    <div id="docs">
      <!-- Content will be loaded here -->
    </div>
  </div>

  <script>
    // Models (baseado nos seus fillable)
    const Models = {
      Users: {
        table: "users",
        fields: {
          name: { type: "string", required: true },
          apelido: { type: "string", required: false },
          foto: { type: "string", required: false },
          telefone: { type: "string", required: false },
          bilhete: { type: "string", required: false, unique: true },
          genero: { type: "string", required: false },
          data_nascimento: { type: "date", required: false },
          endereco: { type: "string", required: false },
          estado: { type: "string", required: false, default: "ativo" },
          papel: { type: "string", required: false, default: "funcionario" },
          email: { type: "string", required: true },
          password: { type: "string", required: true },
          email_verified_at: { type: "timestamp", required: false },
          remember_token: { type: "string", required: false }
        }
      },
      Paginas: {
        table: "paginas",
        fields: {
          titulo: { type: "string", required: true },
          slug: { type: "string", required: true },
          descricao: { type: "string", required: true },
          imagem: { type: "string", required: false },
          ultima_visualizacao: { type: "datetime", required: false },
          visualizacoes: { type: "integer", required: false, default: 0 },
          meta_keywords: { type: "string", required: false },
          estado: { type: "string", required: false, default: "ativo" }
        }
      },
      BtnPaginas: {
        table: "btn_paginas",
        fields: {
          texto: { type: "string", required: true },
          link: { type: "string", required: true },
          icone: { type: "string", required: true },
          target: { type: "string", required: true },
          pagina_id: { type: "integer", required: true }
        }
      },
      Destaques: {
        table: "destaques",
        fields: {
          titulo: { type: "string", required: true },
          descricao: { type: "string", required: true },
          icone: { type: "string", required: true },
          link_text: { type: "string", required: true },
          link: { type: "string", required: true },
          pagina: { type: "string|integer", required: true }
        }
      },
      Categorias: {
        table: "categorias",
        fields: {
          nome: { type: "string", required: true },
          slug: { type: "string", required: true }
        }
      },
      Noticias: {
        table: "noticias",
        fields: {
          titulo: { type: "string", required: true },
          descricao: { type: "text", required: true },
          categoria_id: { type: "integer", required: true },
          link: { type: "string", required: false },
          imagem: { type: "string", required: false },
          status: { type: "string", required: true },
          fonte: { type: "string", required: false },
          slug: { type: "string", required: true },
          meta_titulo: { type: "string", required: false },
          meta_descricao: { type: "string", required: false },
          meta_keywords: { type: "string", required: false },
          meta_imagem: { type: "string", required: false }
        }
      },
      AnexoNoticias: {
        table: "anexo_noticias",
        fields: {
          noticia_id: { type: "integer", required: true },
          anexo: { type: "string", required: true },
          descricao: { type: "string", required: false },
          meta_keywords: { type: "string", required: false }
        }
      },
      Comunas: {
        table: "comunas",
        fields: {
          nome: { type: "string", required: true },
          descricao: { type: "string", required: false }
        }
      },
      Bairros: {
        table: "bairros",
        fields: {
          comuna_id: { type: "integer", required: true },
          nome: { type: "string", required: true },
          slug: { type: "string", required: true },
          ponto_referencia: { type: "string", required: false },
          imagem: { type: "string", required: false },
          descricao: { type: "string", required: false }
        }
      },
      TipoOcorrencias: {
        table: "tipo_ocorrencias",
        fields: {
          nome: { type: "string", required: true },
          descricao: { type: "string", required: true }
        }
      },
      Ocorrencias: {
        table: "ocorrencias",
        fields: {
          codigo_ocorrencia: { type: "string", required: true },
          user_id: { type: "integer", required: true },
          anonimo: { type: "boolean", required: true },
          nome: { type: "string", required: false },
          email: { type: "string", required: false },
          telefone: { type: "string", required: false },
          bairro_id: { type: "integer", required: true },
          tipoOcorrencia_id: { type: "integer", required: true },
          localizacao_especifica: { type: "string", required: false },
          descricao: { type: "text", required: true }
        }
      },
      AnexoOcorrencias: {
        table: "anexo_ocorrencias",
        fields: {
          ocorrencia_id: { type: "integer", required: true },
          anexo: { type: "string", required: true },
          descricao: { type: "string", required: false },
          meta_keywords: { type: "string", required: false }
        }
      },
      Direccaos: {
        table: "direccaos",
        fields: {
          nome: { type: "string", required: true },
          descricao: { type: "string", required: false },
          responsavel_id: { type: "integer", required: true },
          telefone: { type: "string", required: true },
          email: { type: "string", required: true },
          imagem: { type: "string", required: false },
          slug: { type: "string", required: true }
        }
      },
      Funcionarios: {
        table: "funcionarios",
        fields: {
          descricao: { type: "string", required: false },
          user_id: { type: "integer", required: true },
          direccao_id: { type: "integer", required: true },
          slug: { type: "string", required: true }
        }
      },
      Tickets: {
        table: "tickets",
        fields: {
          ocorrencia_id: { type: "integer", required: true },
          direccao_id: { type: "integer", required: true },
          responsavel_id: { type: "integer", required: true },
          status: { type: "string", required: true },
          observacoes: { type: "text", required: false }
        }
      },
      Municipes: {
        table: "municipes",
        fields: {
          user_id: { type: "integer", required: true },
          bairro_id: { type: "integer", required: true },
          funcao: { type: "string", required: true }
        }
      }
    };

    // Endpoints básicos de CRUD para cada model
    const Endpoints = Object.entries(Models).map(([name, model]) => ({
      name,
      table: model.table,
      routes: [
        { method: "GET", path: `/api/${model.table}`, desc: `Listar todos os ${name}` },
        { method: "POST", path: `/api/${model.table}`, desc: `Criar um novo ${name}` },
        { method: "GET", path: `/api/${model.table}/{id}`, desc: `Obter um ${name} pelo ID` },
        { method: "PUT", path: `/api/${model.table}/{id}`, desc: `Atualizar um ${name}` },
        { method: "DELETE", path: `/api/${model.table}/{id}`, desc: `Remover um ${name}` },
      ]
    }));

    // Função para mostrar uma seção específica
    function showSection(section) {
      const docs = document.getElementById("docs");
      const sidebarItems = document.querySelectorAll('.sidebar li');
      
      // Atualizar a classe ativa na sidebar
      sidebarItems.forEach(item => {
        if (item.onclick.toString().includes(section)) {
          item.classList.add('active');
        } else {
          item.classList.remove('active');
        }
      });
      
      // Gerar conteúdo para a seção selecionada
      if (section === 'users') {
        renderModelSection('Users', Models.Users);
      } else if (section === 'paginas') {
        renderModelSection('Paginas', Models.Paginas);
      } else if (section === 'btn_paginas') {
        renderModelSection('BtnPaginas', Models.BtnPaginas);
      } else if (section === 'destaques') {
        renderModelSection('Destaques', Models.Destaques);
      } else if (section === 'categorias') {
        renderModelSection('Categorias', Models.Categorias);
      } else if (section === 'noticias') {
        renderModelSection('Noticias', Models.Noticias);
      } else if (section === 'anexo_noticias') {
        renderModelSection('AnexoNoticias', Models.AnexoNoticias);
      } else if (section === 'comunas') {
        renderModelSection('Comunas', Models.Comunas);
      } else if (section === 'bairros') {
        renderModelSection('Bairros', Models.Bairros);
      } else if (section === 'tipo_ocorrencias') {
        renderModelSection('TipoOcorrencias', Models.TipoOcorrencias);
      } else if (section === 'ocorrencias') {
        renderModelSection('Ocorrencias', Models.Ocorrencias);
      } else if (section === 'anexo_ocorrencias') {
        renderModelSection('AnexoOcorrencias', Models.AnexoOcorrencias);
      } else if (section === 'direccaos') {
        renderModelSection('Direccaos', Models.Direccaos);
      } else if (section === 'funcionarios') {
        renderModelSection('Funcionarios', Models.Funcionarios);
      } else if (section === 'tickets') {
        renderModelSection('Tickets', Models.Tickets);
      } else if (section === 'municipes') {
        renderModelSection('Municipes', Models.Municipes);
      }
      
      // Fechar a sidebar em dispositivos móveis
      if (window.innerWidth < 1024) {
        document.getElementById('sidebar').classList.remove('open');
      }
    }
    
    // Função para renderizar uma seção de modelo
    function renderModelSection(name, model) {
      const docs = document.getElementById("docs");
      
      // Renderizar a estrutura do modelo
      let html = `
        <div class="model-container">
          <h2 class="section-title">${name}</h2>
          <p class="text-gray-600 mb-4">Tabela: <code>${model.table}</code></p>
          
          <h3 class="text-xl font-semibold mt-6 mb-4">Estrutura</h3>
          <div class="bg-white rounded-lg shadow overflow-hidden">
            <table class="field-table">
              <thead>
                <tr>
                  <th>Campo</th>
                  <th>Tipo</th>
                  <th>Obrigatório</th>
                  <th>Descrição</th>
                </tr>
              </thead>
              <tbody>
      `;
      
      // Adicionar linhas para cada campo
      Object.entries(model.fields).forEach(([field, config]) => {
        const isRequired = config.required ? '<span class="required"></span>' : '';
        const defaultValue = config.default ? `<br><span class="text-xs text-gray-500">Padrão: ${config.default}</span>` : '';
        const isUnique = config.unique ? '<br><span class="text-xs text-blue-500">Único</span>' : '';
        
        html += `
          <tr>
            <td><code>${field}</code>${isRequired}</td>
            <td>${config.type}</td>
            <td>${config.required ? 'Sim' : 'Não'}</td>
            <td>${defaultValue}${isUnique}</td>
          </tr>
        `;
      });
      
      html += `
              </tbody>
            </table>
          </div>
      `;
      
      // Renderizar endpoints para este modelo
      const endpoint = Endpoints.find(e => e.name === name);
      if (endpoint) {
        html += `
          <h3 class="text-xl font-semibold mt-8 mb-4">Endpoints</h3>
        `;
        
        endpoint.routes.forEach(route => {
          const methodClass = route.method.toLowerCase();
          
          html += `
            <div class="endpoint">
              <div class="endpoint-header">
                <div class="flex items-center">
                  <span class="method ${methodClass}">${route.method}</span>
                  <span class="endpoint-path">${route.path}</span>
                </div>
              </div>
              <div class="endpoint-description">
                <p>${route.desc}</p>
                
                ${route.method === 'GET' && route.path.includes('{id}') ? `
                  <h4 class="font-semibold mt-4 mb-2">Exemplo de Resposta</h4>
                  <div class="code-block">
// 200 OK
{
  "data": {
    ${Object.entries(model.fields).map(([field]) => `"${field}": "value"`).join(",\n    ")}
  }
}
                  </div>
                ` : ''}
                
                ${route.method === 'POST' ? `
                  <h4 class="font-semibold mt-4 mb-2">Exemplo de Request</h4>
                  <div class="code-block">
// POST ${route.path}
{
  ${Object.entries(model.fields)
    .filter(([_, config]) => config.required)
    .map(([field]) => `"${field}": "value"`)
    .join(",\n  ")}
}
                  </div>
                ` : ''}
              </div>
            </div>
          `;
        });
      }
      
      html += `</div>`;
      docs.innerHTML = html;
    }
    
    // Função para filtrar endpoints na sidebar
    function filterEndpoints(query) {
      const sidebarItems = document.querySelectorAll('.sidebar li');
      const lowercaseQuery = query.toLowerCase();
      
      sidebarItems.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(lowercaseQuery)) {
          item.style.display = 'flex';
        } else {
          item.style.display = 'none';
        }
      });
    }
    
    // Função para alternar a sidebar em dispositivos móveis
    function toggleSidebar() {
      document.getElementById('sidebar').classList.toggle('open');
    }
    
    // Mostrar a seção de Users por padrão ao carregar
    document.addEventListener('DOMContentLoaded', function() {
      showSection('users');
    });
  </script>
</body>
</html>