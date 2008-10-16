CREATE TABLE games (id INTEGER PRIMARY KEY AUTOINCREMENT, title VARCHAR(255), genre VARCHAR(255), online INTEGER, multiplayer INTEGER, blurb TEXT, howto TEXT, created_at DATETIME, updated_at DATETIME, slug VARCHAR(255));
CREATE TABLE news (id INTEGER PRIMARY KEY AUTOINCREMENT, title VARCHAR(255), body TEXT, created_at DATETIME, updated_at DATETIME, slug VARCHAR(255));
CREATE INDEX games_slug_idx ON games (slug);
CREATE INDEX news_slug_idx ON news (slug);
