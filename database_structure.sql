CREATE DATABASE scrumanager CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE scrumanager;

CREATE TABLE developer (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    profile VARCHAR(255),
    email VARCHAR(255) NOT NULL,
    password CHAR(41) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE (email)
)  ENGINE=INNODB;

CREATE TABLE moa (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    password CHAR(41) NOT NULL,
    PRIMARY KEY (id),
    UNIQUE (email)
)  ENGINE=INNODB;

CREATE TABLE project (
    id INT NOT NULL AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    folder_name VARCHAR(16) NOT NULL,
    created DATETIME NOT NULL,
    status BOOLEAN NOT NULL,
    owner_id INT NOT NULL,
    master_id INT NOT NULL,
    PRIMARY KEY (id),
    UNIQUE (folder_name , master_id)
)  ENGINE=INNODB;

CREATE TABLE user_story (
    text VARCHAR(255) NOT NULL,
    no INT NOT NULL,
    priority TINYINT NOT NULL DEFAULT 1,
    cost SMALLINT NOT NULL DEFAULT 1,
    status BOOLEAN NOT NULL DEFAULT 0,
    project_id INT NOT NULL,
    sprint_no INT,
    PRIMARY KEY (no , project_id)
)  ENGINE=INNODB;

CREATE TABLE sprint (
    no INT NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    project_id INT NOT NULL,
    color INT,
    PRIMARY KEY (no , project_id)
)  ENGINE=INNODB;

CREATE TABLE task (
    text MEDIUMTEXT NOT NULL,
    developer_id INT NOT NULL,
    project_id INT NOT NULL,
    user_story_no INT NOT NULL,
    PRIMARY KEY (developer_id , project_id , user_story_no)
)  ENGINE=INNODB;

CREATE TABLE project_developer (
    joined DATETIME NOT NULL,
    project_id INT NOT NULL,
    developer_id INT NOT NULL,
    PRIMARY KEY (project_id , developer_id)
)  ENGINE=INNODB;

CREATE TABLE color (
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255),
    red SMALLINT NOT NULL,
    green SMALLINT NOT NULL,
    blue SMALLINT NOT NULL,
    alpha SMALLINT
)  ENGINE=INNODB;

ALTER TABLE project
ADD CONSTRAINT FOREIGN KEY (owner_id) REFERENCES moa (id),
ADD CONSTRAINT FOREIGN KEY (master_id) REFERENCES developer (id);

ALTER TABLE user_story
ADD CONSTRAINT FOREIGN KEY (project_id) REFERENCES project (id),
ADD CONSTRAINT FOREIGN KEY (sprint_no) REFERENCES sprint (no);

ALTER TABLE sprint
ADD CONSTRAINT FOREIGN KEY (project_id) REFERENCES project (id);

ALTER TABLE task
ADD CONSTRAINT FOREIGN KEY (developer_id) REFERENCES developer (id),
ADD CONSTRAINT FOREIGN KEY (project_id) REFERENCES project (id),
ADD CONSTRAINT FOREIGN KEY (user_story_no) REFERENCES user_story (no);

ALTER TABLE project_developer
ADD CONSTRAINT FOREIGN KEY (project_id) REFERENCES project (id),
ADD CONSTRAINT FOREIGN KEY (developer_id) REFERENCES developer (id);

ALTER TABLE sprint
ADD CONSTRAINT FOREIGN KEY (color) REFERENCES color(id);