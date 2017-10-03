Añadir
ALTER TABLE `historial` ADD `id_usuario` INT UNSIGNED NULL DEFAULT '0' AFTER `notificacion`, ADD `pagado_at` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00' AFTER `id_usuario`;
