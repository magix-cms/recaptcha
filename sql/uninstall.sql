TRUNCATE TABLE `mc_recaptcha`;
DROP TABLE `mc_recaptcha`;

DELETE FROM `mc_admin_access` WHERE `id_module` IN (
    SELECT `id_module` FROM `mc_module` as m WHERE m.name = 'recaptcha'
);