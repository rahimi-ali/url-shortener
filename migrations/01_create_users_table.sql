CREATE TABLE users
(
    id        INTEGER UNSIGNED AUTO_INCREMENT,
    username  VARCHAR(255) NOT NULL,
    password  VARCHAR(255) NOT NULL,
    firstname VARCHAR(255) NOT NULL,
    lastname  VARCHAR(255) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY (username)
);