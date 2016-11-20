create table entry_users (
  id int primary key auto_increment,
  name varchar(32),
  email varchar(255),
  postcode varchar(16),
  address varchar(255),
  created_at datetime,
  password varchar(16),
  result int
);
