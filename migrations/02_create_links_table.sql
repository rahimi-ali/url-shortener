CREATE TABLE links
(
    id        INTEGER UNSIGNED AUTO_INCREMENT,
    userId    INTEGER UNSIGNED NOT NULL,
    url       VARCHAR(1000)    NOT NULL,
    shortLink VARCHAR(32)     NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (userId) REFERENCES users (id),
    UNIQUE KEY (shortLink)
);