use outletsurvey;

create table if not exists survey(
id integer not null primary key auto_increment,
area_eng varchar(254),
city_mm varchar(254),
city_eng varchar(254),
township_mm varchar(254),
township_eng varchar(254),
ward_mm varchar(254),
ward_eng varchar(254),
outletcode varchar(100),
outletname_mm varchar(254),
outletname_eng varchar(254),
outlet_type_id integer,
owner_mm varchar(254),
owner_eng varchar(254),
user_id integer,
longi varchar(254),
lati varchar(254),
created_date datetime default now(),
updated_date datetime);

create table if not exists outletPhone(
id integer not null primary key auto_increment,
survey_id integer,
phone_number varchar(100)
);

create table if not exists itemsSalesVolume(
id integer not null primary key auto_increment,
survey_id integer,
items_id integer,
config_volumn integer
);

describe role;
select * from role;

select * from role where role_name <> 'sysadmin' order by id desc;

truncate table config;
describe config;

select * from config;

alter table config
drop column `config_code`;

insert into `config`(`config_value`, `config_type`) values
-- for menu
('Survey','menu'),
('Report','menu'),
('Access','menu'),
('User','menu'),
('Configuration','menu'),
-- for control
('View','control'),
('Create','control'),
('Read','control'),
('Update','control'),
('Delete','control');

select distinct `config_type` from `config` where not find_in_set('menus', 'controls') order by `config_type` desc;