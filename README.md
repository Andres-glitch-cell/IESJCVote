# 🗳️ IESJCVote — Sistema de Votaciones Digitales

**IESJCVote** es una aplicación web desarrollada como **Proyecto de Final de Curso (FCT)** para el **IES Joan Coromines**. Permite gestionar procesos de votación digital de forma segura, con panel de administración, registro de votos y resguardos individuales por elector.

---

## 🚀 Stack Tecnológico

| Capa | Tecnología |
|---|---|
| Backend | PHP 8.5 + Laravel 12 |
| Frontend | JavaScript ES6+ vanilla |
| Estilos | CSS custom (sin framework) |
| Base de datos | MySQL 8 |
| Autenticación | Laravel Auth (guards + middleware) |

---

## ✨ Funcionalidades

- 🔐 **Registro e inicio de sesión** por nombre y DNI
- 🗳️ **Votación en encuestas activas** con protección anti-duplicado
- 🧾 **Resguardo de voto** con hash único por participante
- 📊 **Panel de administración** para crear, activar/desactivar y eliminar encuestas
- 🛡️ **Protección CSRF** y validación de datos en servidor
- 👤 **Perfil e historial** de votaciones por usuario

---

## 🛠️ Instalación en Windows

### Requisitos previos
- PHP 8.5+
- Composer
- MySQL 8
- Git

### Pasos

**1. Clonar el repositorio**
```bash
git clone https://github.com/Andres-glitch-cell/IESJCVote.git
cd IESJCVote/00_Laravel-IESVote
```

**2. Instalar dependencias**
```bash
composer install
```

**3. Configurar el entorno**
```bash
cp .env.example .env
php artisan key:generate
```

**4. Editar `.env` con tus datos de base de datos**
```properties
DB_DATABASE=IESJCVote-db
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
SESSION_DRIVER=file
```

**5. Ejecutar migraciones**
```bash
php artisan migrate
```

**6. Arrancar el servidor**
```bash
php artisan serve
```

Abre `http://127.0.0.1:8000` en el navegador.

---

## 🗂️ Estructura de la Base de Datos

### Entidad — Relación
