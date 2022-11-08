CREATE DATABASE books;


CREATE TABLE `react_crud`.`users`
(
    `id` int NOT NULL auto_increment,
    `name` varchar(50),
    `autor` varchar(50),
    `quantidpages` varchar(50),
    `price` varchar(50),
    `flag` varchar(50),
    `data` timestamp,
    `atualizado` timestamp, PRIMARY KEY (id)
);