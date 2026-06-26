#!/bin/bash

DB="tekopora_db"
USER="root"
# Ajusta el socket si es necesario para tu entorno Linux
SOCKET="/opt/lampp/var/mysql/mysql.sock"

echo "Iniciando migración en Linux..."

# Generar password en bcrypt usando el PHP del sistema
ADMIN_PASS=$(php -r "echo password_hash('admin123', PASSWORD_BCRYPT);")

# Crear DB y Ejecutar estructura
mysql -u $USER --socket=$SOCKET -e "DROP DATABASE IF EXISTS $DB; CREATE DATABASE $DB;"
mysql -u $USER --socket=$SOCKET $DB < migracion_5.sql

# Insertar usuario administrador con la nueva estructura
mysql -u $USER --socket=$SOCKET $DB -e "
INSERT INTO usuario (google_id, codigoUsuario, ci, nombre, appPaterno, appMaterno, email, passwordHash, estado)
VALUES (NULL, 'ADM001', '0000', 'Admin', 'Sistema', '', 'admin@tekopora.com', '$ADMIN_PASS', 'Activo');
"

# Vincular Rol (Asumiendo que Administrador es ID 1)
mysql -u $USER --socket=$SOCKET $DB -e "
INSERT INTO usuario_rol (idUsuario_FK, idRol_FK) VALUES (1, 1);
"

echo "Migración completa para TekoPorã. Admin: admin@tekopora.com / Pass: admin123"