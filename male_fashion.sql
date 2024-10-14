create table category (
    ID int auto_increment primary key,
    IDPARENT int null,
    NAME varchar (500) charset utf8mb3 null,
    QUANTITY int null,
    URL varchar (250) charset utf8mb3 null,
    ISACTIVE tinyint null
) engine = InnoDB;
create table user (
    ID int auto_increment primary key,
    NAME varchar (250) charset utf8mb3 null,
    USERNAME varchar (50) charset utf8mb3 null,
    PASSWORD varchar (50) charset utf8mb3 null,
    ROLE varchar (50) charset utf8mb3 null,
    ADDRESS varchar (500) charset utf8mb3 null,
    EMAIL varchar (150) charset utf8mb3 null,
    PHONE varchar (50) charset utf8mb3 null,
    CREATED_DATE timestamp null,
    ISACTIVE tinyint null
) engine = InnoDB;
create table payment_method (
    ID int auto_increment primary key,
    NAME varchar (250) charset utf8mb3 null,
    URL varchar (250) charset utf8mb3 null,
    CREATED_DATE timestamp null,
    UPDATED_DATE timestamp null,
    ISACTIVE tinyint null
) engine = InnoDB;
create table product (
    ID int auto_increment primary key,
    NAME varchar (500) charset utf8mb3 null,
    DESCRIPTION text null,
    IMAGE varchar (550) charset utf8mb3 null,
    IDCATEGORY int null,
    PRICE double null,
    QUATITY int null,
    ISACTIVE tinyint null,
    constraint product_category_ID_fk foreign key (IDCATEGORY) references category (ID)
) engine = InnoDB;
create table transport_method (
    ID int auto_increment primary key,
    NAME varchar (250) charset utf8mb3 null,
    NOTES text null,
    CREATED_DATE timestamp null,
    UPDATED_DATE timestamp null,
    ISACTIVE tinyint null
) engine = InnoDB;
create table orders (
    ID int auto_increment primary key,
    IDPAYMENT int null,
    IDTRANSPORT int null,
    ORDERS_DATE timestamp null,
    IDUSER int null,
    TOTAL_MONEY double null,
    NOTES text null,
    NAME_RECIVER varchar (250) charset utf8mb3 null,
    ADDRESS varchar (500) charset utf8mb3 null,
    PHONE varchar (50) charset utf8mb3 null,
    constraint orders_customer_ID_fk foreign key (IDUSER) references user (ID),
    constraint orders_payment_ID_fk foreign key (IDPAYMENT) references payment_method (ID),
    constraint orders_transport_ID_fk foreign key (IDTRANSPORT) references transport_method (ID)
) engine = InnoDB;
create table orders_details (
    ID int auto_increment primary key,
    IDORD int null,
    IDPRODUCT int null,
    PRICE double null,
    constraint orders_details_orders_ID_fk foreign key (IDORD) references orders (ID),
    constraint orders_details_product_ID_fk foreign key (IDPRODUCT) references product (ID)
) engine = InnoDB;