CREATE DATABASE IF NOT EXISTS meetech;

USE meetech;

-- User

CREATE TABLE IF NOT EXISTS language(
    lang VARCHAR(32) PRIMARY KEY,
    icon CHAR(16),
    label CHAR(2)
);

CREATE TABLE IF NOT EXISTS users(
    id_u INTEGER AUTO_INCREMENT PRIMARY KEY,
    prefered_language VARCHAR(32) REFERENCES language(lang),

    username VARCHAR(32),
    password VARCHAR(255),
    avatar VARCHAR(32),
    bio TEXT,
    location VARCHAR(32),
    note INT,
    email VARCHAR(127),
    display_email BOOLEAN DEFAULT FALSE,
    code_verif INTEGER,
    token CHAR(13),
    verified BOOLEAN DEFAULT FALSE,
    last_ip VARCHAR(15),
    last_connection DATETIME
);

-- Messages

CREATE TABLE IF NOT EXISTS category(
    name VARCHAR(32) PRIMARY KEY,
    description TEXT
);

CREATE TABLE IF NOT EXISTS message (
    id_m INTEGER AUTO_INCREMENT PRIMARY KEY,
    author INTEGER NOT NULL REFERENCES users (id_user),
    default_language VARCHAR(32) NOT NULL REFERENCES language (lang),
    category VARCHAR(32) NOT NULL REFERENCES category (name),
    title VARCHAR(255),
    content TEXT,
    date_published DATETIME,
    date_edited DATETIME,
    signaled BOOLEAN DEFAULT FALSE
);

CREATE TABLE IF NOT EXISTS vote_message (
    message INTEGER REFERENCES message (id_m),
    user INTEGER REFERENCES users (id_u),
    PRIMARY KEY (message, user)
);

CREATE TABLE IF NOT EXISTS translation (
    id_t INTEGER AUTO_INCREMENT PRIMARY KEY,
    language VARCHAR(32) NOT NULL REFERENCES language (lang),
    translator INTEGER NOT NULL REFERENCES users (id_user),
    original_message INTEGER NOT NULL REFERENCES message (id_m),

    title VARCHAR(255),
    content TEXT,
    date_translated DATETIME
);

CREATE TABLE IF NOT EXISTS comment (
    id_c INTEGER AUTO_INCREMENT PRIMARY KEY,
    author INTEGER NOT NULL REFERENCES users (id_user),
    parent_message INTEGER NOT NULL REFERENCES messages (id_m),
    parent_comment INTEGER REFERENCES comment (id_c),
    content TEXT,
    date_published DATETIME,
    date_edited DATETIME
    signaled BOOLEAN DEFAULT FALSE
);

CREATE TABLE IF NOT EXISTS vote_comment (
    comment INTEGER REFERENCES comment (id_c),
    user INTEGER REFERENCES users (id_u),
    PRIMARY KEY (comment, user)
);


CREATE TABLE IF NOT EXISTS file(
    added_by INTEGER NOT NULL REFERENCES user(id_user),
    message INTEGER NOT NULL REFERENCES message(id_m),
    file_name VARCHAR(32),
    PRIMARY KEY(message, file_name),
    extension VARCHAR(4)
);

-- Badges

CREATE TABLE IF NOT EXISTS badge(
    name VARCHAR(16) PRIMARY KEY,
    description VARCHAR(255),
    global_permissions INTEGER,
    obtention INT,
    img_badge VARCHAR(32)
);

CREATE TABLE IF NOT EXISTS badged(
    user INTEGER NOT NULL REFERENCES user(id_user),
    badge VARCHAR(16) NOT NULL REFERENCES badge(name),

    PRIMARY KEY(user, badge)
);

CREATE TABLE IF NOT EXISTS category_permission(
    badge VARCHAR(16) REFERENCES badge(name),
    category VARCHAR(32) REFERENCES category(name),

    permissions INTEGER,

    PRIMARY KEY(badge, category)
);

-- Private messages

CREATE TABLE IF NOT EXISTS channel(
    id_c INTEGER PRIMARY KEY AUTO_INCREMENT,
    first_message INTEGER REFERENCES message(id_m)
);

CREATE TABLE IF NOT EXISTS private_message(
    id_pm INTEGER PRIMARY KEY,
    author INTEGER NOT NULL REFERENCES user(id_user),
    channel INTEGER NOT NULL REFERENCES channel(id_m),

    date_published DATETIME
);

CREATE TABLE IF NOT EXISTS recipient(
    channel INTEGER NOT NULL REFERENCES channel(id_m),
    author INTEGER NOT NULL REFERENCES user(id_user)
);

-- Event

CREATE TABLE IF NOT EXISTS event(
    id_e INTEGER PRIMARY KEY,
    name VARCHAR(25),
    description TEXT,
    date DATETIME
);

CREATE TABLE IF NOT EXISTS participant(
    user INTEGER NOT NULL REFERENCES user(id_user),
    event INTEGER NOT NULL REFERENCES event(id_e),

    PRIMARY KEY(user, event)
);

-- Polls

CREATE TABLE IF NOT EXISTS poll(
    id_p INTEGER PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS question(
    id_q INTEGER PRIMARY KEY,
    poll INTEGER NOT NULL REFERENCES poll(id_p),

    question VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS answer(
    poll INTEGER NOT NULL REFERENCES poll(id_p),
    user INTEGER NOT NULL REFERENCES user(id_user),

    PRIMARY KEY(poll, user)
);

-- Components

CREATE TABLE IF NOT EXISTS component_type(
    id_t INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(30),
    category ENUM('internal', 'external'),
    score_formula TEXT
);

CREATE TABLE IF NOT EXISTS specification_list(
    id_s INTEGER PRIMARY KEY AUTO_INCREMENT,
    type INTEGER REFERENCES type(id_t),

    data_type ENUM('number', 'string', 'list') DEFAULT 'number',
    name VARCHAR(30),
    unit VARCHAR(4)
);

-- This table is used if specification's data type is list
CREATE TABLE IF NOT EXISTS specification_option(
    id_o INTEGER PRIMARY KEY AUTO_INCREMENT,
    specification INTEGER REFERENCES specification_list(id_s),
    option VARCHAR(30)
);

CREATE TABLE IF NOT EXISTS specification(
    component INTEGER REFERENCES component(id_c),
    specification INTEGER REFERENCES specification_list(id_s),
    value VARCHAR(30),

    PRIMARY KEY(component, specification)
);

CREATE TABLE IF NOT EXISTS component(
    id_c INTEGER PRIMARY KEY AUTO_INCREMENT,
    added_by INTEGER REFERENCES user(id_user),
    type INTEGER REFERENCES type(id_t),

    validated BOOLEAN DEFAULT FALSE,
    added_date DATETIME DEFAULT NOW(),
    score INTEGER DEFAULT 0,
    name VARCHAR(32) DEFAULT 'No name',
    brand VARCHAR(32) DEFAULT 'No brand',
    sources TEXT,
    image VARCHAR(127)
);

CREATE TABLE IF NOT EXISTS component_voted(
    component INTEGER REFERENCES component(id_c),
    user INTEGER REFERENCES users(id_u),
    value INTEGER(1)
);

CREATE TABLE IF NOT EXISTS component_comment(
    id_c INTEGER PRIMARY KEY AUTO_INCREMENT,
    author INTEGER NOT NULL REFERENCES users (id_user),
    component INTEGER NOT NULL REFERENCES component (id_c),
    parent_comment INTEGER REFERENCES comment (id_c),
    content TEXT,
    date_published DATETIME,
    date_edited DATETIME,
    note INTEGER,
    signaled BOOLEAN DEFAULT FALSE
);

-- Statistics

CREATE TABLE IF NOT EXISTS page_visit(
    id_pv INTEGER PRIMARY KEY AUTO_INCREMENT,
    page VARCHAR(127),
    date DATETIME,
    visits INTEGER
);
