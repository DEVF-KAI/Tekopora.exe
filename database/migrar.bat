@echo off
set DB=tekopora_db
set USER=root

echo =============================
echo INICIANDO MIGRACION TEKOPORA
echo =============================

REM 🔥 Eliminar base de datos si existe
mysql -u %USER% -e "DROP DATABASE IF EXISTS %DB%;"

REM 🔥 Crear base de datos
mysql -u %USER% -e "CREATE DATABASE %DB%;"

REM 🔥 Ejecutar migracion (estructura de tablas)
mysql -u %USER% %DB% < migracion.sql
IF ERRORLEVEL 1 (
    echo [!] ERROR al ejecutar migracion.sql
    pause
    exit /b
)
echo Base de datos y estructura creadas correctamente.

REM 3. Insertar Roles
echo [2/5] Creando roles del sistema...
mysql -u %USER% %DB% -e "INSERT IGNORE INTO rol (nombre) VALUES ('Administrador'), ('Moderador Turismo'), ('Moderador Obra'), ('Personal Alcaldia'), ('Ciudadano');"

REM 4. Generar contraseñas (Hash Bcrypt)
echo [3/5] Encriptando accesos...
for /f "tokens=*" %%i in ('php -r "echo password_hash('admin123', PASSWORD_BCRYPT);"') do set ADMIN_PASS=%%i
for /f "tokens=*" %%i in ('php -r "echo password_hash('teko123', PASSWORD_BCRYPT);"') do set DEFAULT_PASS=%%i

REM 5. Insertar Usuarios con TODOS los campos (Corregido: 11 columnas = 11 valores)
echo [4/5] Registrando usuarios maestros...
mysql -u %USER% %DB% -e "INSERT INTO usuario (codigoUsuario, ci, nombre, appPaterno, appMaterno, fechaNacimiento, email, telefono, passwordHash, karmaTotal, estado) VALUES ('ADM001','0000','Aaron','Vargas','Huarachi','2000-01-01','admin@tekopora.com','70000000','%ADMIN_PASS%',0,'Activo'), ('MOD001','1111','Moderador','Turismo','General','1995-05-15','turismo@tekopora.com','71111111','%DEFAULT_PASS%',0,'Activo'), ('MOD002','2222','Moderador','Obra','Tecnico','1992-10-20','obra@tekopora.com','72222222','%DEFAULT_PASS%',0,'Activo'), ('PER001','3333','Personal','Alcaldia','LP','1988-03-30','alcaldia@tekopora.com','73333333','%DEFAULT_PASS%',0,'Activo'), ('CIU001','4444','Juan','Perez','Mamani','1998-12-25','ciudadano@tekopora.com','74444444','%DEFAULT_PASS%',0,'Activo');"
REM 6. Asignación de Roles (JOIN por Email)
echo [5/5] Vinculando permisos...
mysql -u %USER% %DB% -e "INSERT INTO usuario_rol (idUsuario_FK, idRol_FK) SELECT u.idUsuario, r.idRol FROM usuario u JOIN rol r ON r.nombre='Administrador' WHERE u.email='admin@tekopora.com';"
mysql -u %USER% %DB% -e "INSERT INTO usuario_rol (idUsuario_FK, idRol_FK) SELECT u.idUsuario, r.idRol FROM usuario u JOIN rol r ON r.nombre='Moderador Turismo' WHERE u.email='turismo@tekopora.com';"
mysql -u %USER% %DB% -e "INSERT INTO usuario_rol (idUsuario_FK, idRol_FK) SELECT u.idUsuario, r.idRol FROM usuario u JOIN rol r ON r.nombre='Moderador Obra' WHERE u.email='obra@tekopora.com';"
mysql -u %USER% %DB% -e "INSERT INTO usuario_rol (idUsuario_FK, idRol_FK) SELECT u.idUsuario, r.idRol FROM usuario u JOIN rol r ON r.nombre='Personal Alcaldia' WHERE u.email='alcaldia@tekopora.com';"
mysql -u %USER% %DB% -e "INSERT INTO usuario_rol (idUsuario_FK, idRol_FK) SELECT u.idUsuario, r.idRol FROM usuario u JOIN rol r ON r.nombre='Ciudadano' WHERE u.email='ciudadano@tekopora.com';"

echo =============================
echo MIGRACION COMPLETADA CON EXITO
echo =============================
echo CUENTAS DISPONIBLES:
echo -----------------------------
echo [Administrador]
echo Correo: admin@tekopora.com
echo Pass:   admin123
echo.
echo [Resto de usuarios]
echo Correos: turismo@tekopora.com 
echo          obra@tekopora.com 
echo          alcaldia@tekopora.com 
echo          ciudadano@tekopora.com
echo Pass:    teko123
echo =============================

pause