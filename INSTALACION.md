# 🎓 SSA — Sistema de Seguimiento al Aprendiz
### Guía de Instalación y Configuración

---

## ⚡ Requisitos
| Componente | Versión mínima |
|---|---|
| PHP | 8.2+ |
| Composer | 2.x |
| MySQL | 8.0+ / MariaDB 10.6+ |
| Laravel | 11 / 12 |
| Node.js (opcional) | 18+ |

---

## 🚀 Instalación Paso a Paso

### 1. Clonar / copiar el proyecto
```bash
cp -r sistemamejorado/ /var/www/html/ssa
cd /var/www/html/ssa
```

### 2. Instalar dependencias PHP
```bash
composer install
```

### 3. Configurar variables de entorno
```bash
cp .env.example .env
php artisan key:generate
```

Editar `.env`:
```env
APP_NAME="SSA SENA"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=ssa_db
DB_USERNAME=root
DB_PASSWORD=tu_password
```

### 4. Crear la base de datos
```sql
CREATE DATABASE ssa_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Ejecutar migraciones y seeders
```bash
php artisan migrate:fresh --seed
```

### 6. Crear enlace de almacenamiento
```bash
php artisan storage:link
```

### 7. Iniciar el servidor
```bash
php artisan serve
```
Acceder en: **http://127.0.0.1:8000**

---

## 🔑 Credenciales de Prueba

| Rol | Email | Contraseña |
|---|---|---|
| Administrador | admin@sena.edu.co | Admin1234! |
| Instructor | instructor1@sena.edu.co | Inst1234! |
| Instructor | instructor2@sena.edu.co | Inst1234! |
| Aprendiz | ana@aprendiz.sena.edu.co | Apren1234! |
| Aprendiz | luis@aprendiz.sena.edu.co | Apren1234! |

---

## 📦 Dependencias agregadas al proyecto

| Paquete | Función |
|---|---|
| `barryvdh/laravel-dompdf ^3.0` | Generación de PDF para hoja de vida (H18) |

Instalar con:
```bash
composer require barryvdh/laravel-dompdf
```

Publicar config (opcional):
```bash
php artisan vendor:publish --provider="Barryvdh\DomPDF\ServiceProvider"
```

---

## 📋 Módulos del Sistema

### Administrador
- ✅ **Fichas**: Crear, editar, eliminar, asignar instructores
- ✅ **Aprendices**: CRUD completo con foto
- ✅ **Instructores**: CRUD completo
- ✅ **Reportes**: Por ficha y por aprendiz

### Instructor
- ✅ **Actividades**: Crear, asignar automáticamente a ficha
- ✅ **Calificaciones**: Registrar por actividad (tabla masiva)
- ✅ **Evaluación Integral**: 9 criterios con barras de progreso
- ✅ **Observaciones**: Registrar notas sobre el aprendiz
- ✅ **Vista filtrada**: Solo aprendices de sus fichas

### Aprendiz
- ✅ **Dashboard**: Resumen de notas y actividades
- ✅ **Hoja de vida**: Perfil completo académico e integral
- ✅ **Descargar PDF**: Hoja de vida en PDF

---

## ⚙️ Arquitectura de la BD

```
users (id, name, email, password, rol, telefono, foto)
fichas (id, numero, programa_formacion, fecha_inicio, fecha_fin, estado)
instructor_ficha (id, user_id, ficha)
aprendices (id, user_id, documento, programa_formacion, ficha, fecha_inicio, estado)
actividades (id, titulo, descripcion, instructor_id, fecha_limite, estado, porcentaje_peso, ficha_asignada)
actividad_aprendiz (id, actividad_id, aprendiz_id, estado)   ← pivot
calificaciones (id, aprendiz_id, actividad_id, instructor_id, nota, observacion)
observaciones (id, aprendiz_id, instructor_id, contenido, tipo)
evaluaciones_integrales (id, aprendiz_id, instructor_id, responsabilidad...autonomia, observaciones)
seguimientos (id, aprendiz_id, instructor_id, porcentaje, comentario, fecha_seguimiento)
```

---

## 🐛 Solución de Problemas

| Error | Solución |
|---|---|
| `Class not found` | `composer dump-autoload` |
| `Key too long` en migración | Agregar en `AppServiceProvider`: `Schema::defaultStringLength(191)` |
| Fotos no muestran | `php artisan storage:link` |
| PDF no genera | `composer require barryvdh/laravel-dompdf` |
| Permiso denegado en storage | `chmod -R 775 storage bootstrap/cache` |

---

*Proyecto SSA — SENA © 2024*
