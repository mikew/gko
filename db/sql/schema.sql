CREATE TABLE authors (id INTEGER PRIMARY KEY AUTOINCREMENT, name VARCHAR(255), handle VARCHAR(255), password VARCHAR(255), created_at DATETIME, updated_at DATETIME, slug VARCHAR(255));
CREATE TABLE games (id INTEGER PRIMARY KEY AUTOINCREMENT, title VARCHAR(255), genre VARCHAR(255), online INTEGER, multiplayer INTEGER, blurb TEXT, howto TEXT, created_at DATETIME, updated_at DATETIME, slug VARCHAR(255));
CREATE TABLE posts (id INTEGER PRIMARY KEY AUTOINCREMENT, title VARCHAR(255), body TEXT, created_at DATETIME, updated_at DATETIME, slug VARCHAR(255));
CREATE INDEX authors_slug_idx ON authors (slug);
CREATE INDEX games_slug_idx ON games (slug);
CREATE INDEX posts_slug_idx ON posts (slug);
