CREATE TABLE IF NOT EXISTS notes (
    id INT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    user_id INT UNSIGNED NOT NULL,
    folder_id INT UNSIGNED NOT NULL,
    title VARCHAR(100) NOT NULL,

    content TEXT,
    pinned BOOL DEFAULT false,
    completed BOOL DEFAULT false,

    created_at DATETIME DEFAULT NOW(),
    updated_at DATETIME DEFAULT NOW(),

    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (folder_id) REFERENCES folders(id)
);