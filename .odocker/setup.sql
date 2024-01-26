SET @schema = 'fin';

DELETE FROM core_config_data WHERE path LIKE 'catalog/search/elasticsearch7_server_hostname';
INSERT INTO `core_config_data` (`config_id`, `scope`, `scope_id`, `path`, `value`, `updated_at`) VALUES (NULL, 'default', '0', 'catalog/search/elasticsearch7_server_hostname', 'elasticsearch', current_timestamp());
INSERT INTO `core_config_data` (`config_id`, `scope`, `scope_id`, `path`, `value`, `updated_at`) VALUES (NULL, 'default', '0', 'catalog/search/engine', 'elasticsearch7', current_timestamp());
