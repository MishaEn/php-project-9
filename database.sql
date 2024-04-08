CREATE TABLE IF NOT EXISTS urls
(
    id   SERIAL PRIMARY KEY,
    name VARCHAR(100),
    created_at  DATE
);

CREATE TABLE IF NOT EXISTS url_checks
(
    id   SERIAL PRIMARY KEY,
    url_id INT REFERENCES urls,
    status_code  VARCHAR(255),
    h1 VARCHAR(255),
    title  VARCHAR(255),
    description  VARCHAR(255),
    created_at  DATE
);