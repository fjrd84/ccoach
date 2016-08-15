CREATE TABLE LoginTable (
  id int(11) NOT NULL auto_increment,
  user_name varchar(100) NOT NULL,
  pass_word varchar(100) NOT NULL,
  PRIMARY KEY (id)
);

INSERT INTO LoginTable (user_name, pass_word)
    VALUES  ('javi',  'e10adc3949ba59abbe56e057f20f883e');
