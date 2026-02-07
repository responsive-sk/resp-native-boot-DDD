-- FORMS DATABASE
CREATE TABLE IF NOT EXISTS forms (
    id TEXT PRIMARY KEY,
    name TEXT NOT NULL,
    slug TEXT UNIQUE NOT NULL,
    description TEXT,
    structure TEXT NOT NULL, -- JSON
    is_active INTEGER DEFAULT 1,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL
);

CREATE TABLE IF NOT EXISTS form_submissions (
    id TEXT PRIMARY KEY,
    form_id TEXT NOT NULL,
    submitted_data TEXT NOT NULL, -- JSON
    submitted_by TEXT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL,
    FOREIGN KEY (form_id) REFERENCES forms(id) ON DELETE CASCADE
);

-- Indexes
CREATE INDEX idx_forms_slug ON forms(slug);
CREATE INDEX idx_forms_is_active ON forms(is_active);
CREATE INDEX idx_form_submissions_form_id ON form_submissions(form_id);
CREATE INDEX idx_form_submissions_submitted_by ON form_submissions(submitted_by);
CREATE INDEX idx_form_submissions_created_at ON form_submissions(created_at);

-- Triggers
CREATE TRIGGER IF NOT EXISTS update_forms_timestamp 
AFTER UPDATE ON forms 
FOR EACH ROW
BEGIN
    UPDATE forms SET updated_at = CURRENT_TIMESTAMP WHERE id = OLD.id;
END;

-- Sample contact form
INSERT OR IGNORE INTO forms (id, name, slug, structure) VALUES (
    '1',
    'Contact Form',
    'contact',
    '{"fields":[{"name":"name","type":"text","label":"Name","required":true},{"name":"email","type":"email","label":"Email","required":true},{"name":"message","type":"textarea","label":"Message","required":true,"rows":5}]}'
);
