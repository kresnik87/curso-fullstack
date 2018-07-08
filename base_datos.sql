/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  TrendingPC
 * Created: Jul 5, 2018
 */
CREATE DATABASE IF NO EXISTS videos_app;
USE videos_app

CREATE TABLE users(
id          int(255) auto_increment not null,
role        varchar(20),
name        varchar(255),
surname     varchar(255),
email       varchar(255),
password    varchar(255),
image       varchar(255),
create_at   datetime,
CONSTRAINT pk_users PRIMARY KEY(id)
)ENGINE=Innodb;

CREATE TABLE videos(
id          int(255) auto_increment not null,
user_id     int(255)  not null,
title       varchar(255),
description text,
status       varchar(255),
image       varchar(255),
video_path    varchar(255),
create_at   datetime DEFAULT NULL,
update_at   datetime DEFAULT NULL,
CONSTRAINT pk_videos PRIMARY KEY(id),
CONSTRAINT fk_videos_users FOREIGN KEY(user_id) REFERENCES users(id)
)ENGINE=Innodb;

CREATE TABLE comments(
id          int(255) auto_increment not null,
body        text,
user_id     int(255)  not null,
image       varchar(255),
video_path    varchar(255),
create_at   datetime DEFAULT NULL,
update_at   datetime DEFAULT NULL,
CONSTRAINT pk_videos PRIMARY KEY(id),
CONSTRAINT fk_videos_users FOREIGN KEY(user_id) REFERENCES users(id)
)ENGINE=Innodb;