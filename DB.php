<?php
$companies = 'CREATE TABLE companies(
id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
name varchar(50) NOT NULL,
telephone varchar(20),
address varchar(150),
representative varchar(100),
description varchar(500)
)';

$contracts = 'CREATE TABLE contracts(
id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
company_id int,
start_date date,
end_date date,
description varchar(500),
FOREIGN KEY (company_id) REFERENCES companies(id)
)';

$practice_types = 'CREATE TABLE practice_types(
id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
name varchar(50)
)';

$applications = 'CREATE TABLE applications(
id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
contract_id int,
start_date date,
end_date date,
practice_type_id int,
FOREIGN KEY (contract_id) REFERENCES contracts(id),
FOREIGN KEY (practice_type_id) REFERENCES practice_types(id)
)';

$departments = 'CREATE TABLE departments(
id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
name varchar(50)
)';

$faculties = 'CREATE TABLE faculties(
id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
name varchar(50)
)';

$curricula = 'CREATE TABLE curricula(
id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
name varchar(50),
department_id int,
FOREIGN KEY (department_id) REFERENCES departments(id)
)';

$student_groups = 'CREATE TABLE student_groups(
id int PRIMARY KEY AUTO_INCREMENT NOT NULL,
name varchar(50),
year SMALLINT,
curricilum_id int,
faculty_id int,
FOREIGN KEY (curricilum_id) REFERENCES curricula(id),
FOREIGN KEY (faculty_id) REFERENCES faculties(id)
)';

$students = 'CREATE TABLE students(
login int PRIMARY KEY AUTO_INCREMENT NOT NULL,
name varchar(100),
group_id int,
FOREIGN KEY (group_id) REFERENCES student_groups(id)
)';

$sql = new mysqli('localhost', 'root', 'root', 'opts');
$sql->query($students);
echo $sql->error;