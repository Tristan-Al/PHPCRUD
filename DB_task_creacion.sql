CREATE DATABASE task;
USE task;

CREATE TABLE sessions
( session_id int(11)
, session_userid int(11)
, session_token varchar(32)
, session_serial varchar(40)
, session_date date
, CONSTRAINT pk_sessions PRIMARY KEY (session_id)
);

CREATE TABLE users
(
  user_id int(11) AUTO_INCREMENT
, user_username varchar(15) NOT NULL
, user_pass varchar(40) NOT NULL
, first_name varchar(15)
, last_name varchar(25)
, email varchar(40) NOT NULL
, address varchar(35)
, postcode varchar(5)
, city varchar(20)
, edulevel tinyint(1) DEFAULT 0
, degree varchar(30)
, year year(4)
, langlevel tinyint(1) DEFAULT 0
, status tinyint(1) DEFAULT 0
, trash tinyint(1) DEFAULT 0
, member_from date
, CONSTRAINT pk_users PRIMARY KEY (user_id)
);


INSERT INTO users VALUES(
  1
  , 'admin'
  , SHA('admin')
  , 'Tristan'
  , 'Alonso'
  , 'tristanalonso09@gmail.com'
  , 'Mi Casa 21'
  , '19203'
  , 'Huelva'
  , 0
  , 'Computer Science'
  , 2003
  , 1
  , 0
  , 0
  , now()
);

INSERT INTO users VALUES(
  2
  , 'mode'
  , SHA('mode')
  , 'Modesto'
  , 'Martinez'
  , 'neomode@gmail.com'
  , 'Corral 74'
  , '29640'
  , 'Malaga'
  , 0
  , 'Graduate in Archeology'
  , 1987
  , 1
  , 0
  , 0
  , now()
);