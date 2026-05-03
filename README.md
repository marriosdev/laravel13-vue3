## 🥷 Como Rodar o Projeto 

Tudo o que você precisa é do Docker instalado em sua máquina.

1. Clone o repositório
2. Na raiz do projeto, execute:
   ```bash
   docker compose up --build
   ```
3. O sistema estará disponível em:
   - **Frontend:** [http://localhost:5173](http://localhost:5173)
   - **Backend API:** [http://localhost:8000](http://localhost:8000)

---

## 🥷 Tecnologias Utilizadas

### Backend
- **Laravel 11** (PHP 8.5)
- **MySQL 8.0** (Banco de dados)
- **Redis**

### Frontend
- **Vue 3**
- **Vite**
- **Vuetify 3**
- **Vue3-Toastify**
- **Axios**

## Observações
- As variáveis de ambiente são injetadas automaticamente via `docker-compose.yml`.
