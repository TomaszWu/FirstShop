<?php


//CREATE DATABASE FirstShop


//CREATE TABLE Products (
//        id INT PRIMARY KEY AUTO_INCREMENT,
//        category VARCHAR(200) NOT NULL,
//        name VARCHAR(200) NOT NULL,
//        description TEXT,
//        price DECIMAL(8,2),
//        stock int
//        
//        )


//CREATE DATABASE Users (
//        id INT PRIMARY KEY AUTO_INCREMENT,
//        name VARCHAR(200) NOT NULL,
//        surname VARCHAR(200) NOT NULL,
//        email VARCHAR(200) NOT NULL UNIQUE,
//        password varchar(255)  NOT NULL,
//        address VARCHAR(255) NOT NULL,
//        )
        

CREATE TABLE Pictures (
picture_id INT  AUTO_INCREMENT,
picture_link TEXT NOT NULL, 
product_id INT,
PRIMARY KEY(picture_id),
FOREIGN KEY(product_id)
REFERENCES Products(id)
ON DELETE CASCADE
)

        
        INSERT INTO Pictures (product_id, 'picture_link') VALUES (3, 'https://www.google.pl/url?sa=i&rct=j&q=&esrc=s&source=images&cd=&cad=rja&uact=8&ved=0ahUKEwintdjr15bQAhXHjywKHRbBBkwQjRwIBw&url=http%3A%2F%2Fwww.bmw.pl%2Fpl%2Fall-models.html&psig=AFQjCNHBK-5jua8jO1HFOZMXOkiNx9SvVA&ust=1478609520154821');
        INSERT INTO Pictures (product_id, picture_link) VALUES (3, 'https://www.google.pl/url?sa=i&rct=j&q=&esrc=s&source=images&cd=&cad=rja&uact=8&ved=0ahUKEwi3s-e92JbQAhVDjSwKHfsUBLAQjRwIBw&url=http%3A%2F%2Fwww.bmw.co.kr%2Fko%2Fall-models.html&psig=AFQjCNHBK-5jua8jO1HFOZMXOkiNx9SvVA&ust=1478609520154821);
        INSERT INTO Pictures (product_id, picture_link) VALUES (3, 'https://www.google.pl/url?sa=i&rct=j&q=&esrc=s&source=images&cd=&cad=rja&uact=8&ved=0ahUKEwjV9KzC2JbQAhVH3CwKHUzJCyQQjRwIBw&url=http%3A%2F%2Fwww.bmw.pl%2Fpl%2Fall-models.html&psig=AFQjCNHBK-5jua8jO1HFOZMXOkiNx9SvVA&ust=1478609520154821');