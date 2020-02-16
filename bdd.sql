CREATE DATABASE IF NOT EXISTS meetech;

USE meetech;

-- user

CREATE TABLE LANGUAGE(
    lang VARCHAR(32) PRIMARY KEY
);

CREATE TABLE USERS(
    id_user INTEGER AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(32),
    password VARCHAR(255),
    avatar VARCHAR(32),
    bio TEXT,
    location VARCHAR(32),
    prefered_language VARCHAR(32) REFERENCES LANGUAGE(lang),
    note INT
);

-- messages

CREATE TABLE CATEGORY(
    name VARCHAR(32) PRIMARY KEY,
    description TEXT
);

CREATE TABLE TRANSLATION(
    id_t INTEGER AUTO_INCREMENT PRIMARY KEY,
    language VARCHAR(32) NOT NULL REFERENCES LANGUAGE(lang),
    translated_message INTEGER NOT NULL REFERENCES MESSAGE(id_m),
    title VARCHAR(255),
    content TEXT,
    date_edition DATE,
    date_translated DATE
);

CREATE TABLE MESSAGE(
    id_m INTEGER AUTO_INCREMENT PRIMARY KEY,
    default_translation INTEGER NOT NULL REFERENCES TRANSLATION(id_t),
    category VARCHAR(32) NOT NULL REFERENCES CATEGORY(name),
    parent_message INTEGER REFERENCES MESSAGE(id_m),
    note INTEGER
);

CREATE TABLE TRANSLATED(
    user INTEGER NOT NULL REFERENCES USER(id_user),
    translation INTEGER NOT NULL REFERENCES TRANSLATION(id_t),

    PRIMARY KEY(user, translation)
);

-- badges

CREATE TABLE BADGE(
    name VARCHAR(16) PRIMARY KEY,
    description VARCHAR(255),
    global_permissions INTEGER,
    obtention INT
);

CREATE TABLE BADGED(
    user INTEGER NOT NULL REFERENCES USER(id_user),
    badge VARCHAR(16) NOT NULL REFERENCES BADGE(name),

    PRIMARY KEY(user, badge)
);

CREATE TABLE CATEGORY_PERMISSIONS(
    badge VARCHAR(16) REFERENCES BADGE(name),
    category VARCHAR(32) REFERENCES CATEGORY(name),
    permissions INTEGER,

    PRIMARY KEY(badge, category)
);

-- private messages

CREATE TABLE CHANNEL(
    id_c INTEGER PRIMARY KEY AUTO_INCREMENT,
    first_message INTEGER REFERENCES MESSAGE(id_m)
);

CREATE TABLE PRIVATE_MESSAGE(
    id_pm INTEGER PRIMARY KEY,
    author INTEGER NOT NULL REFERENCES USER(id_user),
    channel INTEGER NOT NULL REFERENCES CHANNEL(id_m),
    date_published DATE
);

CREATE TABLE RECIPIENT(
    channel INTEGER NOT NULL REFERENCES CHANNEL(id_m),
    author INTEGER NOT NULL REFERENCES USER(id_user)
);

-- event

CREATE TABLE EVENT(
    id_e INTEGER PRIMARY KEY,
    name VARCHAR(25),
    description TEXT,
    date DATE
);

CREATE TABLE PARTICIPANT(
    user INTEGER NOT NULL REFERENCES USER(id_user),
    event INTEGER NOT NULL REFERENCES EVENT(id_e),

    PRIMARY KEY(user, event)
);

-- polls

CREATE TABLE POLL(
    id INTEGER PRIMARY KEY
);

CREATE TABLE QUESTION(
    id INTEGER PRIMARY KEY,
    poll INTEGER NOT NULL REFERENCES POLL(id),
    question VARCHAR(255)
);

CREATE TABLE ANSWER(
    poll INTEGER NOT NULL REFERENCES POLL(id),
    user INTEGER NOT NULL REFERENCES USER(id_user), 

    PRIMARY KEY(poll, user) 
);

-- components

CREATE TABLE CPU(
    name VARCHAR(25),
    brand VARCHAR(25),
    release_date DATE,
    validated BOOLEAN DEFAULT FALSE,

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

CREATE TABLE MEMORY(
    name VARCHAR(25),
    brand VARCHAR(25),
    release_date DATE,
    validated BOOLEAN DEFAULT FALSE,

    score INT,

    type VARCHAR(5),
    capacity INTEGER,
    frequency VARCHAR(15),
    
    PRIMARY KEY(name, brand)
);

CREATE TABLE HDD(
    name VARCHAR(25),
    brand VARCHAR(25),
    release_date DATE,
    validated BOOLEAN DEFAULT FALSE,

    score INT,

    capacity INTEGER,
    speed VARCHAR(15),
    
    PRIMARY KEY(name, brand)
);

CREATE TABLE SSD(
    name VARCHAR(15),
    brand VARCHAR(15),
    release_date DATE,
    validated BOOLEAN DEFAULT FALSE,

    score INT,

    capacity INTEGER,
    write_speed VARCHAR(15),
    read_speed VARCHAR(15),
    
    PRIMARY KEY(name, brand)
);

CREATE TABLE GPU(
    name VARCHAR(15),
    brand VARCHAR(15),
    release_date DATE,
    validated BOOLEAN DEFAULT FALSE,

    score INT,

    core_frequency VARCHAR(15),
    core_cores VARCHAR(15),
    memory_type VARCHAR(10),
    memory_capacity VARCHAR(10),
    
    PRIMARY KEY(name, brand)
);

CREATE TABLE MOTHERBOARD(
    name VARCHAR(15) ,
    brand VARCHAR(15),
    release_date DATE,
    validated BOOLEAN DEFAULT FALSE,

    score INT,

    chipset CHAR(4),
    socket CHAR(3),
    m2_slots INTEGER,
    sata_slots INTEGER,
    pcie VARCHAR(10),
    wifi VARCHAR(10),

    PRIMARY KEY(name, brand)
);