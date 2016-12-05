<?php
?>

/*

CREATE DATABASE FirstShop

CREATE TABLE Products (
id INT PRIMARY KEY AUTO_INCREMENT,
category VARCHAR (200) NOT NULL
name VARCHAR (200) NOT NULL,
description TEXT,
price DECIMAL(8,2)
stock INT
)


CREATE TABLE Users (
id INT PRIMARY KEY AUTO_INCREMENT,
name VARCHAR(200) NOT NULL,         
surname VARCHAR (200) NOT NULL,
email VARCHAR (200) NOT NULL UNIQUE,
password VARCHAR (255) NOT NULL,
address VARCHAR (255) NOT NULL
)


CREATE TABLE Picture2s (
picture_id INT AUTO_INCREMENT,
picture VARCHAR(200) NOT NULL, 
product_id INT,
PRIMARY KEY(picture_id),
FOREIGN KEY(product_id)
REFERENCES Products(id)
ON DELETE CASCADE
)




CREATE TABLE Massages (
massage_id INT AUTO_INCREMENT,
massage VARCHAR(255) NOT NULL,
user_id INT,
user_name VARCHAR(255) NOT NULL,
PRIMARY KEY(massage_id),
FOREIGN KEY (user_id)
REFERENCES Users(id)
)



CREATE TABLE Orders (
id INT AUTO_INCREMENT,
order_id INT,
user_id INT NOT NULL,
order_status INT,
product_quantity INT,
PRIMARY KEY (id),
FOREIGN KEY (user_id)
REFERENCES Users(id)
ON DELETE CASCADE
)

CREATE TABLE Orders_products (
id int AUTO_INCREMENT,
product_id int NOT NULL,
order_id int  NOT NULL,
product_quantity INT NOT NULL,
product_status INT NOT NULL,
PRIMARY KEY(id),
FOREIGN KEY(product_id) REFERENCES Products(id),
FOREIGN KEY (order_id) REFERENCES Orders(order_id)


)

CREATE TABLE Categories(
id INT AUTO_INCREMENT,
category_id INT,
product_id INT,
PRIMARY KEY (id),
FOREIGN KEY (product_id)
REFERENCES Products(id)
);












*/