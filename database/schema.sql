CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(120) NOT NULL,
    phone VARCHAR(25) NULL,
    occupation VARCHAR(120) NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    last_login_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS financial_profiles (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    monthly_income DECIMAL(12,2) NOT NULL DEFAULT 0,
    extra_income DECIMAL(12,2) NOT NULL DEFAULT 0,
    start_date DATE NOT NULL,
    currency CHAR(3) NOT NULL DEFAULT 'MXN',
    spending_media JSON NULL,
    goal_type ENUM('save', 'debt', 'control', 'other') NOT NULL,
    goal_description VARCHAR(255) NULL,
    goal_meta_amount DECIMAL(12,2) NULL,
    goal_meta_months INT NULL,
    debt_total_amount DECIMAL(12,2) NULL,
    debt_plan JSON NULL,
    spending_limit_mode ENUM('manual', 'auto') NOT NULL DEFAULT 'manual',
    spending_limit_amount DECIMAL(12,2) NOT NULL DEFAULT 0,
    auto_limit_ratio DECIMAL(4,2) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_financial_profiles_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS password_resets (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    token VARCHAR(255) NOT NULL UNIQUE,
    expires_at DATETIME NOT NULL,
    consumed_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_password_resets_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS transactions (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    type ENUM('income', 'expense') NOT NULL,
    category VARCHAR(80) NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    payment_method ENUM('efectivo', 'tarjeta', 'transferencia', 'otro') NOT NULL DEFAULT 'efectivo',
    happened_on DATE NOT NULL,
    description VARCHAR(255) NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT fk_transactions_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_transactions_user_date (user_id, happened_on)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS alerts (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NOT NULL,
    type ENUM('limit', 'inactivity', 'goal', 'debt') NOT NULL,
    level ENUM('info', 'warning', 'danger') NOT NULL DEFAULT 'info',
    message VARCHAR(250) NOT NULL,
    payload JSON NULL,
    seen_at DATETIME NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_alerts_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS categories (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNSIGNED NULL,
    name VARCHAR(80) NOT NULL,
    type ENUM('income', 'expense') NOT NULL DEFAULT 'expense',
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_user_category (user_id, name),
    CONSTRAINT fk_categories_user_id FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO categories (user_id, name, type) VALUES
    (NULL, 'Alimentacion', 'expense'),
    (NULL, 'Transporte', 'expense'),
    (NULL, 'Entretenimiento', 'expense'),
    (NULL, 'Vivienda', 'expense'),
    (NULL, 'Salud', 'expense'),
    (NULL, 'Educacion', 'expense'),
    (NULL, 'Servicios', 'expense'),
    (NULL, 'Ahorro', 'expense'),
    (NULL, 'Ingreso principal', 'income'),
    (NULL, 'Ingreso extra', 'income')
ON DUPLICATE KEY UPDATE name = VALUES(name);
