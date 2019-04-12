CREATE TABLE `tausuario` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `USUARIO` varchar(25) NOT NULL,
  `PASSWORD` varchar(150) NOT NULL,
  `TOKEN` varchar(150) NULL,
  `TIPO_ID` int(11) NOT NULL,
  `EMPLEADO_ID` int(11) NOT NULL,
  `ESTADO_ID` int(1)  NOT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE `gnusuariotipo` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(25) NOT NULL,
  `ESTADO_ID` int(1)  NOT NULL,
  PRIMARY KEY (`ID`)
);
select *from gnusuariotipo
insert into gnusuariotipo values(1,'ASISTENTE',1);
insert into gnusuariotipo values(2,'CAJERO',1);
insert into gnusuariotipo values(3,'SUPERVISOR',1);
insert into gnusuariotipo values(4,'ADMINISTRADOR',1); 

CREATE TABLE `gnestados` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(25) NOT NULL,
  PRIMARY KEY (`ID`)
); 
insert into gnestados values(1,'ACTIVADO');
insert into gnestados values(2,'DESACTIVADO');

CREATE TABLE `tacliente` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(180) NOT NULL,
  `DIRECCION` varchar(250) NOT NULL,
  `DNI` varchar(8) DEFAULT NULL,
  `RUC` varchar(12) DEFAULT NULL,
  `TELEFONO` varchar(12) DEFAULT NULL,
  `ESTADO_ID` int(1)  NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `DNI` (`DNI`),
  UNIQUE KEY `RUC` (`RUC`)
);

CREATE TABLE `taempleado` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `DNI` varchar(8) NOT NULL,
  `NOMBRES` varchar(50) NOT NULL,
  `APATERNO` varchar(50) NOT NULL,
  `AMATERNO` varchar(50) NOT NULL,
  `CORREO` varchar(100) DEFAULT NULL,
  `IMAGEN` varchar(100) DEFAULT NULL,
  `TELEFONO` varchar(100) DEFAULT NULL,
  `ESTADO_ID` int(1) NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `DNI` (`DNI`)  
);

CREATE TABLE `taitem` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `CODIGO` varchar(50) DEFAULT NULL,
  `NOMBRE` varchar(50) NOT NULL,
  `UNIDADES` int(11) DEFAULT NULL,
  `LABORATORIO_ID` int(11) NOT NULL,
  `PROVEEDOR_ID` int(11) NOT NULL,
  `PRESENTACION_ID` int(11) NOT NULL,
  `CATEGORIA_ID` int(11) NOT NULL,
  `FECHA_CREACION` datetime NOT NULL,
  `USUARIO_ID` int(11) NOT NULL,
  `ESTADO_ID` int(1) NOT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE `gnitempresentacion` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(25) NOT NULL,
  `ESTADO_ID` int(1) NOT NULL,
  PRIMARY KEY (`ID`)
); 

CREATE TABLE `gnitemcategoria` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(25) NOT NULL,
  `ESTADO_ID` int(1) NOT NULL,
  PRIMARY KEY (`ID`)
); 

CREATE TABLE `talaboratorio` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `NOMBRE` varchar(50) NOT NULL,
  `ESTADO_ID` int(1) NOT NULL,
  PRIMARY KEY (`ID`)
); 

CREATE TABLE `taproveedor` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,
  `RUC` varchar(12) NOT NULL,
  `NOMBRE` varchar(180) NOT NULL,
  `DIRECCION` varchar(250) NOT NULL,
  `TELEFONO` varchar(12) DEFAULT NULL,
  `EMAIL` varchar(50) DEFAULT NULL,
  `ESTADO_ID` int(1) NOT NULL,
  PRIMARY KEY (`ID`)
);

create TABLE `taingreso`(
  `ID` int(11) NOT NULL AUTO_INCREMENT,   
  `CODIGOBARRA` varchar(80)  DEFAULT NULL,  
  `PRECIOCOMPRA` double NOT NULL,
  `PRECIOVENTA` double NOT NULL,
  `LOTE` varchar(25) DEFAULT NULL,
  `CANTIDAD` int(11) NOT NULL,
  `FACTURA` varchar(25) DEFAULT NULL,
  `FECHAINGRESO` datetime NOT NULL,
  `FECHAVENCIMIENTO` date NOT NULL,
  `FECHACREACION` datetime NOT NULL,
  `ITEM_ID` int(11) NOT NULL,
  `USUARIO_ID` int(11) NOT NULL,
  `ESTADO_ID` int(1) NOT NULL,
  PRIMARY KEY (`ID`)
);

CREATE TABLE `tasalida` (
  `ID` int(11) NOT NULL AUTO_INCREMENT,  
  `PRECIOCOMPRA` double NOT NULL,
  `PRECIOVENTA` double NOT NULL,
  `CANTIDAD` int(11) NOT NULL,
  `FECHA` datetime NOT NULL,
  `FECHACREACION` datetime NOT NULL,  
  `INGRESO_ID` int(11) NOT NULL,
  `USUARIO_ID` int(11) NOT NULL,
  `ESTADO_ID` int(1) NOT NULL,
  PRIMARY KEY (`ID`)
);



