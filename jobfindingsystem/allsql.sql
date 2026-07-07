CREATE TABLE jobapplication (
  id INT AUTO_INCREMENT PRIMARY KEY,
  job_id INT NOT NULL,
  username VARCHAR(100) NOT NULL,
  fullname VARCHAR(150),
  email VARCHAR(100),
  phone VARCHAR(30),
  address TEXT,
  skills TEXT,
  experience TEXT,
  cv VARCHAR(255),
  photo VARCHAR(255),
  applied_date DATETIME,
  FOREIGN KEY (job_id) REFERENCES jobs(id)
);
-- Company table
CREATE TABLE company (
    cid INT(3) NOT NULL AUTO_INCREMENT,
    company_name VARCHAR(40) NOT NULL,
    username VARCHAR(10) NOT NULL,
    password VARCHAR(60) NOT NULL,
    email VARCHAR(30) NOT NULL,
    address VARCHAR(50) NOT NULL,
    company_pan VARCHAR(20) NOT NULL,
    company_license VARCHAR(20) NOT NULL,
    company_type VARCHAR(50) NOT NULL,
    datecreated DATETIME NOT NULL,
    PRIMARY KEY (cid)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

-- Jobs table
CREATE TABLE jobs (
    id INT(11) NOT NULL AUTO_INCREMENT,
    title VARCHAR(30) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(50) NOT NULL,
    qualification VARCHAR(100) NOT NULL,
    salary VARCHAR(100) NOT NULL,
    image VARCHAR(255) NOT NULL,
    username VARCHAR(20) NOT NULL,
    openeddate DATETIME NOT NULL,
    expirydate DATE NOT NULL,
    category VARCHAR(50) NOT NULL,
    PRIMARY KEY (id)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

CREATE TABLE users (
    uid INT(11) NOT NULL AUTO_INCREMENT,
    fname VARCHAR(20) NOT NULL,
    lname VARCHAR(20) NOT NULL,
    username VARCHAR(20) NOT NULL,
    password VARCHAR(60) NOT NULL,
    email VARCHAR(60) NOT NULL,
    qualification VARCHAR(50) NOT NULL,
    skills VARCHAR(100) NOT NULL,
    gender VARCHAR(10) NOT NULL,
    datecreated DATETIME NOT NULL,
    PRIMARY KEY (uid)
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;


CREATE TABLE admin (
    username VARCHAR(20) NOT NULL,
    password VARCHAR(60) NOT NULL
) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;

