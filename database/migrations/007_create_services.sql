-- Serviços oferecidos pela Mafedo
CREATE TABLE services (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(160) NOT NULL,
    slug VARCHAR(180) NOT NULL,
    short_description VARCHAR(320) NULL,
    description LONGTEXT NULL,
    image VARCHAR(255) NULL,
    icon VARCHAR(100) NULL,
    featured TINYINT NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    status TINYINT NOT NULL DEFAULT 1,
    seo_title VARCHAR(180) NULL,
    seo_description VARCHAR(320) NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME NULL,
    PRIMARY KEY (id),
    UNIQUE KEY uq_services_slug (slug),
    KEY idx_services_status (status),
    KEY idx_services_featured (featured),
    KEY idx_services_sort (sort_order)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
