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
        
        
//        SELECT * FROM Orders
//JOIN Orders_items ON Orders.order_id=Orders_items.order_id;
//JOIN Products ON Products.id=Orders_items.product_id;
//        

        