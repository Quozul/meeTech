CREATE DATABASE IF NOT EXISTS meetech;

USE meetech;

-- user

CREATE TABLE language(
    lang VARCHAR(32) PRIMARY KEY
);

CREATE TABLE users(
    id_u INTEGER AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(32),
    password VARCHAR(255),
    avatar VARCHAR(32),
    bio TEXT,
    location VARCHAR(32),
    prefered_language VARCHAR(32) REFERENCES language(lang),
    note INT,
    email VARCHAR(127)
);

-- messages

CREATE TABLE category(
    name VARCHAR(32) PRIMARY KEY,
    description TEXT
);

CREATE TABLE message (
    id_m INTEGER AUTO_INCREMENT PRIMARY KEY,
    author INTEGER NOT NULL REFERENCES users (id_user),
    title VARCHAR(255),
    content TEXT,
    date_published DATE,
    date_edited DATE,
    default_language VARCHAR(32) NOT NULL REFERENCES language (lang),
    category VARCHAR(32) NOT NULL REFERENCES category (name),
    note INTEGER,
    signaled BOOLEAN
);

CREATE TABLE translation (
    id_t INTEGER AUTO_INCREMENT PRIMARY KEY,
    language VARCHAR(32) NOT NULL REFERENCES language (lang),
    title VARCHAR(255),
    content TEXT,
    translator INTEGER NOT NULL REFERENCES users (id_user),
    original_message INTEGER NOT NULL REFERENCES message (id_m),
    date_translated DATE
);

CREATE TABLE comment (
    id_c INTEGER AUTO_INCREMENT PRIMARY KEY,
    author INTEGER NOT NULL REFERENCES users (id_user),
    parent_message INTEGER NOT NULL REFERENCES messages (id_m),
    parent_comment INTEGER REFERENCES comment (id_c),
    content TEXT,
    date_published DATE,
    date_edited DATE,
    note INTEGER
);

CREATE TABLE file(
    file_number INTEGER PRIMARY KEY,
    extension VARCHAR(4),
    added_by INTEGER NOT NULL REFERENCES user(id_user),
    message INTEGER NOT NULL REFERENCES message(id_m)
   );

-- badges

CREATE TABLE badge(
    name VARCHAR(16) PRIMARY KEY,
    description VARCHAR(255),
    global_permissions INTEGER,
    obtention INT
);

CREATE TABLE badged(
    user INTEGER NOT NULL REFERENCES user(id_user),
    badge VARCHAR(16) NOT NULL REFERENCES badge(name),

    PRIMARY KEY(user, badge)
);

CREATE TABLE category_permission(
    badge VARCHAR(16) REFERENCES badge(name),
    category VARCHAR(32) REFERENCES category(name),
    permissions INTEGER,

    PRIMARY KEY(badge, category)
);

-- private messages

CREATE TABLE channel(
    id_c INTEGER PRIMARY KEY AUTO_INCREMENT,
    first_message INTEGER REFERENCES message(id_m)
);

CREATE TABLE private_message(
    id_pm INTEGER PRIMARY KEY,
    author INTEGER NOT NULL REFERENCES user(id_user),
    channel INTEGER NOT NULL REFERENCES channel(id_m),
    date_published DATE
);

CREATE TABLE recipient(
    channel INTEGER NOT NULL REFERENCES channel(id_m),
    author INTEGER NOT NULL REFERENCES user(id_user)
);

-- event

CREATE TABLE event(
    id_e INTEGER PRIMARY KEY,
    name VARCHAR(25),
    description TEXT,
    date DATE
);

CREATE TABLE participant(
    user INTEGER NOT NULL REFERENCES user(id_user),
    event INTEGER NOT NULL REFERENCES event(id_e),

    PRIMARY KEY(user, event)
);

-- polls

CREATE TABLE poll(
    id_p INTEGER PRIMARY KEY
);

CREATE TABLE question(
    id_q INTEGER PRIMARY KEY,
    poll INTEGER NOT NULL REFERENCES poll(id_p),
    question VARCHAR(255)
);

CREATE TABLE answer(
    poll INTEGER NOT NULL REFERENCES poll(id_p),
    user INTEGER NOT NULL REFERENCES user(id_user), 

    PRIMARY KEY(poll, user) 
);

-- components

CREATE TABLE component(
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    validated BOOLEAN DEFAULT FALSE,
    added_date DATETIME,
    added_by INTEGER REFERENCES user(id_user),
    type VARCHAR(3),
    score INTEGER DEFAULT 0,

    specifications TEXT,
    sources TEXT
);

CREATE TABLE component_comment(
    id_c INTEGER AUTO_INCREMENT PRIMARY KEY,
    author INTEGER NOT NULL REFERENCES users (id_user),
    component INTEGER NOT NULL REFERENCES component (id),
    parent_comment INTEGER REFERENCES comment (id_c),
    content TEXT,
    date_published DATETIME,
    date_edited DATETIME,
    note INTEGER
);

-- statistics

CREATE TABLE page_visit(
    page VARCHAR(127) PRIMARY KEY,
    visits INTEGER
);