# 📌 Todo API – Laravel 10

API REST de gestión de tareas (**CRUD de Tasks**) desarrollada con **Laravel 10** y **PHP 8.1**, protegida con **Basic Auth**.  
Incluye **paginación**, **SoftDeletes** y **documentación Swagger**.

---

## 🚀 Requisitos

- PHP **8.1+**
- Composer **2.x**
- MySQL o SQLite
- Apache2 (para VirtualHost)
- Git

---

## 📥 Instalación

### 1. Clonar el repositorio

``` bash
git clone https://github.com/dasaam/todo-api.git
cd todo-api
```

### 2. Instalar dependencias y permisos
``` bash
sudo chmod 775 -R todo-api
sudo chmod www-data:tuusuario -R todo-api
composer install
```


### 3. Crear y configurar .env
``` bash
cp .env.example .env
```

#### Edita .env y ajusta (ejemplo con MySQL):
``` bash
APP_NAME=TodoAPI
APP_ENV=local
APP_KEY=
APP_DEBUG=true
APP_URL=http://todo-api

# Base de datos MySQL
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=todo_db
DB_USERNAME=root
DB_PASSWORD=

# Basic Auth (obligatorio)
BASIC_USER=user
BASIC_PASS=password
```


### 4. Generar APP_KEY
``` bash
php artisan key:generate
```

### 5. Migraciones 
``` bash
php artisan migrate
```

#### ▶️ Ejecución
Apache con VirtualHost (recomendado para http://todo-api)

Crear VirtualHost /etc/apache2/sites-available/todo-api.conf:
``` bash
<VirtualHost *:80>
    ServerName todo-api
    DocumentRoot /var/www/html/todo-api/public

    <Directory /var/www/html/todo-api/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/todo-api_error.log
    CustomLog ${APACHE_LOG_DIR}/todo-api_access.log combined
</VirtualHost>
```

Habilitar sitio y mod_rewrite:
``` bash
sudo a2ensite todo-api.conf
sudo a2enmod rewrite
sudo systemctl restart apache2
```

Registrar host local en /etc/hosts:
``` bash
127.0.0.1   todo-api
```

#### 🔒 Autenticación (Basic Auth)
Todos los endpoints están protegidos. Debes enviar: (aqui puedes cambiar el usuario y contraseña por el deseado)
``` bash
Authorization: Basic base64(user:password)
BASIC_USER=user
BASIC_PASS=password
```

#### 📚 Documentación de la API (Swagger)

La documentación Swagger está disponible en:

👉 http://todo-api/api/documentation

La documentacion tambien requiere el usuario y contraseña

#### 🧪 Tests
El proyecto tiene pruebas de la api las puedes correr de esta manera

``` bash
php artisan test --filter=TaskApiTest
```

#### Limite por ip
El proyecto valida que por usuario solo se hagan 60 peticiones por minuto si se pasa mostrar el error HTTP 429 Too Many Requests
