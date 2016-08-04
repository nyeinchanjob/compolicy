select 
	lg.username, lg.password, lg.user_id, lg.login_status, 
    ur.first_name, ur.last_name, ur.position, rl.role_name 
from 
	login as lg 
inner join 
	user as ur on lg.user_id = ur.id 
inner join 
	role as rl on ur.role_id = rl.id 
where 
	lg.username = 'admin' and 
    lg.password = 'admin' and 
    lg.login_status = 1 
limit 0,1;

select * from role;

create table if not exists config(id integer not null primary key auto_increment, config_code varchar(100), config_value varchar(254), config_type varchar(254), config_status boolean default True);
create table if not exists menu(id integer not null primary key auto_increment, role_id integer, menu_code varchar(100), menu_description varchar(254), menu_status boolean default True);
create table if not exists control(id integer not null primary key auto_increment, role_id integer, control_code varchar(100), control_description varchar(254), control_status boolean default True);
describe menu;

insert into menu(role_id, menu_code, menu_description, menu_status) values
(1, 'user', 'Account', 1),
(1, 'role', 'Role', 1),
(1, 'survey', 'Survey', 1),
(1, 'config', 'Configuration', 1);

insert into control(role_id, control_code, control_description, control_status) values
(1, 'c', 'Create', 1),
(1, 'r', 'Read', 1),
(1, 'u', 'Update', 1),
(1, 'd', 'Delete', 1);

alter table product 
add column brand varchar(100),
add column size text,
add column type varchar(100);

describe product;
alter table product drop column `type`;
alter table product
add column `type` text;

describe config;
insert into config(config_code, config_value, config_type, config_status) values
('CLA', 'Coca-Cola', 'brand', 1),
('MAX', 'Max', 'brand', 1),
('BRN', 'Burn', 'brand', 1),
('MMD', 'Nutriboost', 'brand', 1);


insert into config(config_code, config_value, config_type, config_status) values
('1l', '1 Litre', 'size', 1),
('1.5l', '1.5 Litre', 'size', 1),
('550ml', '550 ML', 'size', 1),
('425ml', '425 ML', 'size', 1),
('330ml', '330 ML', 'size', 1),
('285ml', '285 ML', 'size', 1);

insert into config(config_code, config_value, config_type, config_status) values
('PET', 'Plastic', 'type', 1),
('CAN', 'Can', 'type', 1),
('RGB', 'Returnable Glass Bottle', 'type',1);

select * from config;

SELECT
						`id`, `config_code`, `config_value`, `config_type`, `config_status`
			FROM
			 `config`
			WHERE
				`config_type` = 'brand' AND
				`config_status` = 1
			ORDER BY
				`config_value`;
                
truncate table product;

SELECT 
	lg.id, lg.username, lg.`password`, lg.user_id, lg.login_status, 
    ur.first_name, ur.last_name, ur.`position`, ur.role_id, 
    rl.role_name 
FROM 
	login as lg 
INNER JOIN 
	`user` AS ur 
ON 
	lg.user_id = ur.id 
INNER JOIN 
	role AS rl 
ON 
	ur.role_id = rl.id 
WHERE 
	lg.username = 'asd' AND 
    lg.password = 'asd' AND 
    lg.login_status = 1 
LIMIT 
	0,1;
    
describe product;
alter table product
change column brand brand_id integer,
change column size size_id integer,
change column `type` type_id integer;
    
create table if not exists product_brand(
	id integer not null primary key auto_increment,
    product_id integer,
    type_id integer
);

alter table product_brand
rename to product_type;

alter table product
drop column type_id;

alter table product
add column type_other_status boolean,
add column type_other_text text;

alter table product
add column size_other_status boolean,
add column size_other_text text;

select `id` FROM `product` ORDER BY `id` DESC LIMIT 0,1;

describe product_type;

describe product;

truncate table product;
truncate table product_type;
select * from product;
select * from product_type;

insert into config(config_code, config_value, config_type, config_status) values
('oth', 'Others', 'size',1),
('oth', 'Others', 'type', 1);

alter table product
drop column type_other_status,
drop column size_other_status;