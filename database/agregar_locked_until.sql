-- ==============================================================
-- AGREGAR CAMPO LOCKED_UNTIL PARA BLOQUEO TEMPORAL (10 MINUTOS)
-- ==============================================================
-- Este script agrega la capacidad de bloquear temporalmente
-- una cuenta después de 3 intentos fallidos de login

ALTER TABLE usuario ADD COLUMN IF NOT EXISTS locked_until DATETIME DEFAULT NULL;

-- Verificar si la columna fue agregada correctamente
SELECT * FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_NAME = 'usuario' AND COLUMN_NAME = 'locked_until';
