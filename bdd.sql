CREATE DATABASE IF NOT EXISTS meetech;

USE meetech;

-- user

CREATE TABLE language(
    lang VARCHAR(32) PRIMARY KEY
);

CREATE TABLE users(
    id_user INTEGER AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(32),
    password VARCHAR(255),
    avatar VARCHAR(32),
    bio TEXT,
    location VARCHAR(32),
    prefered_language VARCHAR(32) REFERENCES language(lang),
    note INT
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
    default_langugage VARCHAR(32) NOT NULL REFERENCES language (lang),
    category VARCHAR(32) NOT NULL REFERENCES category (name),
    note INTEGER
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
    content TEXT,
    date_published DATE,
    date_edited DATE,
    note INTEGER
);

CREATE TABLE file(
    added_by INTEGER NOT NULL REFERENCES user(id_user),
    message INTEGER NOT NULL REFERENCES message(id_m),
    file_name VARCHAR(32),

    PRIMARY KEY(message, file_name)
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
    added_date TIMESTAMP,
    added_by INTEGER REFERENCES user(id_user),
    type VARCHAR(3),
    score INTEGER DEFAULT 0,

    specifications TEXT
);

-- statistics

CREATE TABLE page_visit(
    page VARCHAR(127) PRIMARY KEY,
    visits INTEGER
);

-- old component tables
/*CREATE TABLE cpu(
    name VARCHAR(25),
    brand VARCHAR(25),
    release_date DATE,
    validated BOOLEAN DEFAULT FALSE,
    added_by INTEGER REFERENCES user(id_user),

    score_single_thread INT,
    score_multi_thread INT,

    chipset CHAR(4),
    frequency VARCHAR(15),
    boost_frequency VARCHAR(20),
    cores INTEGER,
    threads INTEGER,
    max_frequency VARCHAR(20),
    
    PRIMARY KEY(name, brand)
);

CREATE TABLE memory(
    name VARCHAR(25),
    brand VARCHAR(25),
    release_date DATE,
    validated BOOLEAN DEFAULT FALSE,
    added_by INTEGER REFERENCES user(id_user),

    score INT,

    type VARCHAR(5),
    modules INTEGER,
    capacity INTEGER,
    frequency VARCHAR(15),
    
    PRIMARY KEY(name, brand)
);

CREATE TABLE hdd(
    name VARCHAR(25),
    brand VARCHAR(25),
    release_date DATE,
    validated BOOLEAN DEFAULT FALSE,
    added_by INTEGER REFERENCES user(id_user),

    score INT,

    capacity INTEGER,
    speed VARCHAR(15),
    
    PRIMARY KEY(name, brand)
);

CREATE TABLE ssd(
    name VARCHAR(15),
    brand VARCHAR(15),
    release_date DATE,
    validated BOOLEAN DEFAULT FALSE,
    added_by INTEGER REFERENCES user(id_user),

    score INT,

    capacity INTEGER,
    write_speed VARCHAR(15),
    read_speed VARCHAR(15),
    type VARCHAR(8),
    
    PRIMARY KEY(name, brand)
);

CREATE TABLE gpu(
    name VARCHAR(255),
    brand VARCHAR(15),
    release_date DATE,
    validated BOOLEAN DEFAULT FALSE,
    added_by INTEGER REFERENCES user(id_user),

    score INT,

    core_frequency VARCHAR(15),
    core_cores VARCHAR(15),
    memory_type VARCHAR(10),
    memory_capacity VARCHAR(10),
    memory_frequency VARCHAR(10),
    
    PRIMARY KEY(name, brand)
);

CREATE TABLE motherboard(
    name VARCHAR(255) ,
    brand VARCHAR(15),
    release_date DATE,
    validated BOOLEAN DEFAULT FALSE,
    added_by INTEGER REFERENCES user(id_user),

    score INT,

    chipset CHAR(4),
    socket CHAR(3),
    m2_slots INTEGER,
    sata_slots INTEGER,
    pcie VARCHAR(10),
    wifi VARCHAR(10),

    PRIMARY KEY(name, brand)
);*/

--old message table
/*CREATE TABLE translation(
    id_t INTEGER AUTO_INCREMENT PRIMARY KEY,
    language VARCHAR(32) NOT NULL REFERENCES language(lang),
    translated_message INTEGER NOT NULL REFERENCES message(id_m),
    title VARCHAR(255),
    content TEXT,
    date_edition DATE,
    date_translated DATE
);

CREATE TABLE message(
    id_m INTEGER AUTO_INCREMENT PRIMARY KEY,
    default_translation INTEGER NOT NULL REFERENCES translation(id_t),
    category VARCHAR(32) NOT NULL REFERENCES category(name),
    parent_message INTEGER REFERENCES message(id_m),
    note INTEGER
);

CREATE TABLE translated(
    user INTEGER NOT NULL REFERENCES user(id_user),
    translation INTEGER NOT NULL REFERENCES translation(id_t),

    PRIMARY KEY(user, translation)
);*/