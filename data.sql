CREATE TABLE gnestados(
ID INT NOT NULL PRIMARY KEY auto_increment,
NOMBRE VARCHAR(25) NOT NULL,
ESTADO_ID INT(1) NOT NULL
);
INSERT INTO gnestados VALUES(1,'NORMAL',1);
INSERT INTO gnestados VALUES(2,'ELIMINADO',1);

create table taservicio(
ID INT NOT NULL PRIMARY KEY auto_increment,
NOMBRE VARCHAR(50) NOT NULL,
ESTADO_ID INT NOT NULL
);
INSERT INTO taservicio VALUES(1,'AGUA',1);
INSERT INTO taservicio VALUES(2,'LUZ',1);
INSERT INTO taservicio VALUES(3,'VIGILANCIA',1);
INSERT INTO taservicio VALUES(4,'LIMPIEZA',1);
INSERT INTO taservicio VALUES(5,'OTROS',1);

create table tapropietario(
ID INT NOT NULL PRIMARY KEY auto_increment,
DNI VARCHAR(8) NOT NULL UNIQUE,
NOMBRES VARCHAR(25) NOT NULL,
APATERNO VARCHAR(25) NOT NULL,
AMATERNO VARCHAR(25) NOT NULL,
TELEFONO VARCHAR(12) DEFAULT NULL,
EMAIL VARCHAR(12) DEFAULT NULL,
ESTADO_ID INT NOT NULL
);
INSERT INTO tapropietario VALUES(1,'46473154','RULMAN','FERRO','ARONE','992755752','rulman26@gmail.com',1);
select *from tapropietario
create table tabloque(
ID INT NOT NULL PRIMARY KEY auto_increment,
NOMBRE VARCHAR(50) NOT NULL,
ESTADO_ID INT NOT NULL
);
INSERT INTO tabloque VALUES(1,'A',1);
INSERT INTO tabloque VALUES(2,'B',1);
INSERT INTO tabloque VALUES(3,'C',1);
INSERT INTO tabloque VALUES(4,'D',1);
INSERT INTO tabloque VALUES(5,'E',1);

create table tadepartamento(
ID INT NOT NULL PRIMARY KEY auto_increment,
NUMERO INT NOT NULL,
OCUPADO VARCHAR(2) NOT NULL,
BLOQUE_ID INT NOT NULL,
PROPIETARIO_ID INT NOT NULL,
ESTADO_ID INT NOT NULL
);

INSERT INTO tadepartamento values(1,304,'SI',1,1,1);

create table tacaja (
ID INT NOT NULL PRIMARY KEY auto_increment,
NOMBRE VARCHAR(50) NOT NULL,
BANCO VARCHAR(50) DEFAULT NULL,
CUENTA VARCHAR(50) DEFAULT NULL,
SALDO DOUBLE NOT NULL,
BLOQUE_ID INT NOT NULL,
ESTADO_ID INT NOT NULL
);
insert into tacaja values(1,'CAJA CHICA','','',1000,1,1);

CREATE TABLE gngastoestado(
  ID INT NOT NULL PRIMARY KEY auto_increment,
  NOMBRE varchar(25) NOT NULL,
  ESTADO_ID INT(1) NOT NULL
);
insert into gngastoestado values(1,'PENDIENTE',1);
insert into gngastoestado values(2,'PROGRAMADO',1);
insert into gngastoestado values(3,'CANCELADO',1);

CREATE TABLE tagasto(
ID INT NOT NULL PRIMARY KEY auto_increment,
PERIODO VARCHAR(7) NOT NULL,
DESCRIPCION VARCHAR(120) NOT NULL,
MONTO DOUBLE NOT NULL,
SERVICIO_ID INT NOT NULL,
BLOQUE_ID INT NOT NULL,
ESTADO_ID INT(1) NOT NULL
);

INSERT INTO tagasto values(1,'04/2019','VIGILANCIA',614,1,1);
INSERT INTO tagasto values(2,'04/2019','LIMPIEZA',200,1,1);

CREATE TABLE tacuota(
ID INT NOT NULL PRIMARY KEY auto_increment,
PERIODO VARCHAR(7) NOT NULL,
TOTAL double NOT NULL,
CANTIDAD INT NOT NULL,
CUOTA double NOT NULL,
DESCRIPCION VARCHAR(120) NOT NULL,
BLOQUE_ID INT NOT NULL,
ESTADO_ID INT(1) NOT NULL
);

CREATE TABLE tarecibo(
ID INT NOT NULL PRIMARY KEY auto_increment,
FECHA DATE NOT NULL,
NUMERO int(8) NOT NULL ,
DESCRIPCION VARCHAR(120) NOT NULL,
MONTO double NOT NULL,
CUOTA_ID INT DEFAULT NULL,
DEPARTAMENTO_ID INT DEFAULT NULL,
ESTADO_ID INT(1) NOT NULL
);

CREATE TABLE gnreciboestado(
ID INT NOT NULL PRIMARY KEY auto_increment,
NOMBRE VARCHAR(25) NOT NULL,
ESTADO_ID INT(1) NOT NULL
);
INSERT INTO gnreciboestado VALUES(1,'PENDIENTE',1);
INSERT INTO gnreciboestado VALUES(2,'CANCELADO',1);
INSERT INTO gnreciboestado VALUES(3,'ANULADO',1);
 

CREATE TABLE gnmovimientotipo(
ID INT NOT NULL PRIMARY KEY auto_increment,
NOMBRE VARCHAR(25) NOT NULL,
ESTADO_ID INT(1) NOT NULL
);
INSERT INTO gnmovimientotipo VALUES(1,'INGRESO',1);
INSERT INTO gnmovimientotipo VALUES(2,'SALIDA',1);

create table tamovimiento (
ID INT NOT NULL PRIMARY KEY auto_increment,
MONTO DOUBLE NOT NULL,
DESCRIPCION VARCHAR(120) NOT NULL,
FECHA DATE NOT NULL,
OPERACION VARCHAR(50) DEFAULT NULL,
CAJA_ID INT NOT NULL,
TIPO_ID INT NOT NULL,
RECIBO_ID INT DEFAULT NULL,
ESTADO_ID INT(1) NOT NULL
); 

CREATE TABLE gnusuariotipo(
  ID INT NOT NULL PRIMARY KEY auto_increment,
  NOMBRE varchar(25) NOT NULL,
  ESTADO_ID INT(1) NOT NULL
);
insert into gnusuariotipo values(1,'VECINO',1);
insert into gnusuariotipo values(2,'SUPERVISOR',1);
insert into gnusuariotipo values(3,'ADMINISTRADOR',1);


CREATE TABLE tausuario (
  ID int(11) NOT NULL PRIMARY KEY AUTO_INCREMENT,
  USUARIO varchar(25) NOT NULL,
  PASSWORD varchar(150)  NOT NULL,
  TOKEN varchar(150)  DEFAULT NULL,
  TIPO_ID int(1) NOT NULL,
  PROPIETARIO_ID int(11) NOT NULL,
  ESTADO_ID int(1) NOT NULL
);
insert into tausuario values(1,'admin','$2y$10$LOl.mPKEzJV.lw6ezbGDQeNm7SI2E6tc8NvRkm10LoGykr3HkO4Qa','rulman',2,1,1);

select *from tacuota;

select a.ID,b.NOMBRE SERVICIO,a.PERIODO,a.MONTO from tagasto a
JOIN taservicio b on b.ID=a.SERVICIO_ID
where a.ESTADO_ID=1 AND a.BLOQUE_ID=1 AND a.PERIODO='04/2019';

SELECT *FROM tacuota;
