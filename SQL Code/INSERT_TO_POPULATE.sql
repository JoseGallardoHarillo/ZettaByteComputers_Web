--PARA REINICIAR LA INSEERCIÓN SE OBJETOS HACER DROP DE LAS TABLAS Y SECUENCIAS



--USUARIOS: OID_USUARIO, email, contrasenya, nombre, apellidos, fechaNacimiento, activo
--EJEMPLO: INSERT INTO t(fecha) VALUES(TO_DATE('17/12/2015', 'DD/MM/YYYY'));
INSERT INTO USUARIOS (email,contrasenya,nombre,apellidos,fechaNacimiento,activo) 
    VALUES ('pepeguis9@gmail.com','prueba123','Rodrigo','Gonzalez Zapata',to_date('18-03-1992','DD-MM-YYYY'),'S'); --CLIENTE
INSERT INTO USUARIOS (email,contrasenya,nombre,apellidos,fechaNacimiento,activo) 
    VALUES ('cristi96@gmail.com','tienda123','Cristina','Hernandez Gil',to_date('15-08-1996','DD-MM-YYYY'),'S'); --CLIENTE

/
--PONER CLIENTES:
INSERT INTO CLIENTES(OID_USUARIO,NICK) VALUES ('0','poide');
INSERT INTO CLIENTES(OID_USUARIO,NICK) VALUES ('1','cristi');
/
INSERT INTO TELEFONOS(OID_CLIENTE,PREFIJO,NUMERO) VALUES (0,'34','656398745');
INSERT INTO TELEFONOS(OID_CLIENTE,PREFIJO,NUMERO) VALUES (0,'34','656347895');

INSERT INTO TELEFONOS(OID_CLIENTE,PREFIJO,NUMERO) VALUES (1,'34','656398745');

/
--PRODUCTOS: OID_PRODUCTO, nombre, precioBase, iva, marca, categoria, stockMinimo, descripcion, inventariable, stock
INSERT INTO PRODUCTOS (nombre,precioBase,iva,marca,categoria,stockMinimo,descripcion,inventariable, stock) 
    VALUES ('PICTEK - Ratón Gaming Programable',18.9521,DEFAULT,'Picket','COMPONENTES Y ORDENADORES',100,'Ratón Gaming Totalmente Programable: 8 Botón programable, personalice las funciones existentes de los botones o macros para definir otras funciones. Un mouse para satisfacer sus múltiples necesidades. Siéntase libre de configurar el mouse que desee.','N', 200);
INSERT INTO PRODUCTOS (nombre,precioBase,iva,marca,categoria,stockMinimo,descripcion,inventariable, stock) 
    VALUES ('Nintendo Switch - Consola ',296.9215,DEFAULT,'Nintendo','ELECTRONICA',100,'La nueva Switch añade dos horas más de batería en comparación al modelo antiguo, para pasar de entre 3 y 6 horas de duración, a entre 4 y 9 horas','N', 200);
INSERT INTO PRODUCTOS (nombre,precioBase,iva,marca,categoria,stockMinimo,descripcion,inventariable, stock) 
    VALUES ('Xiaomi Redmi Note 8 RAM 4GB ROM 64GB Android 9.0',119.6692,DEFAULT,'Xiaomi','SMARTPHONES',3,'El Xiaomi Redmi Note 8 marca la octava generación de la serie Redmi Note, esta vez con una pantalla Full HD+ de 6.3 pulgadas y potenciado por un procesador Snapdragon 665 de ocho núcleos.','N', 200);
INSERT INTO PRODUCTOS (nombre,precioBase,iva,marca,categoria,stockMinimo,descripcion,inventariable, stock) 
    VALUES ('HP 15s-fq1075ns - Ordenador portátil de 15.6" HD',315.9921,DEFAULT,'HP','COMPONENTES Y ORDENADORES',2,'Intel Core i3-1005G1, 8 GB RAM, 256 GB SSD, gráficos Intel UHD, sin Sistema operativo, gris - Teclado QWERTY Español','N', 200);
INSERT INTO PRODUCTOS (nombre,precioBase,iva,marca,categoria,stockMinimo,descripcion,inventariable, stock) 
    VALUES ('HP 15-db0074ns - Ordenador portátil',291.51,DEFAULT,'HP','COMPONENTES Y ORDENADORES',25,'15.6" HD (AMD A6, 8GB de RAM, 256GB SSD, AMD Radeon R4, Windows 10) negro - teclado QWERTY Español','N', 200);
INSERT INTO PRODUCTOS (nombre,precioBase,iva,marca,categoria,stockMinimo,descripcion,inventariable, stock) 
    VALUES ('Lavadora Candy CS 1292D3',325.95,DEFAULT,'Candy','ELECTRODOMESTICOS Y HOGAR',25,'Lavadora carga frontal 9Kgs, 16 programas, 1200rpm, programas rápidos, NFC, 61dba, display digital táctil, clase A+++, color blanco [Clase de eficiencia energética A+++]','N', 200);
INSERT INTO PRODUCTOS (nombre,precioBase,iva,marca,categoria,stockMinimo,descripcion,inventariable, stock) 
    VALUES ('Oral-B PRO 750 CrossAction',25.95,DEFAULT,'ORAL B','OTROS',25,'El modelo de cepillo de dientes eléctrico ORAL B PRO 750 CROSS ACTION posee tecnología 3D de cepillado, oscila, rota y emite pulsaciones para eliminar la placa un 100% más que un cepillo manual.','N', 100);
INSERT INTO PRODUCTOS (nombre,precioBase,iva,marca,categoria,stockMinimo,descripcion,inventariable, stock) 
    VALUES ('Samsung Galaxy M30s',200.95,DEFAULT,'Samsung','SMARTPHONES',25,'Smartphone Dual SIM, pantalla 16.21 cm sAMOLED FHD+, camara 48MP, 4 GB RAM, 64 GB ROM, bateria 6000 mAH, Android, azul [Versión española, Exclusivo Amazon]','N', 200);
/

--FOTOS: OID_FOTO, urlFoto, OID_PRODUCTO
INSERT INTO FOTOS (urlFoto,OID_PRODUCTO) VALUES ('images/foto0.jpg', 0);
INSERT INTO FOTOS (urlFoto,OID_PRODUCTO) VALUES ('images/foto1.jpg', 1);
INSERT INTO FOTOS (urlFoto,OID_PRODUCTO) VALUES ('images/foto2.jpg', 2);
INSERT INTO FOTOS (urlFoto,OID_PRODUCTO) VALUES ('images/foto3.jpg', 3);
INSERT INTO FOTOS (urlFoto,OID_PRODUCTO) VALUES ('images/foto4.jpg', 4);
INSERT INTO FOTOS (urlFoto,OID_PRODUCTO) VALUES ('images/foto5.jpg', 5);
INSERT INTO FOTOS (urlFoto,OID_PRODUCTO) VALUES ('images/foto6.jpg', 6);
INSERT INTO FOTOS (urlFoto,OID_PRODUCTO) VALUES ('images/foto7.jpg', 7);
/


INSERT INTO PROVINCIAS(NOMBRE) VALUES('15A Coruña');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('01Álava');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('02Albacete');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('03Alicante');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('04Almería');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('33Asturias');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('05Ávila');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('06Badajoz');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('07Baleares');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('08Barcelona');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('09Burgos');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('10Cáceres');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('11Cádiz');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('39Cantabria');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('12Castellón');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('13Ciudad Real');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('14Córdoba');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('16Cuenca');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('17Girona');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('18Granada');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('19Guadalajara');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('20Gipuzkoa');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('21Huelva');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('22Huesca');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('23Jaén');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('26La Rioja');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('35Las Palmas');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('24León');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('25Lérida');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('27Lugo');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('28Madrid');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('29Málaga');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('30Murcia');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('31Navarra');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('32Ourense');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('34Palencia');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('36Pontevedra');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('37Salamanca');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('40Segovia');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('41Sevilla');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('42Soria');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('43Tarragona');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('38Santa Cruz de Tenerife');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('44Teruel');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('45Toledo');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('46Valencia');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('47Valladolid');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('48Vizcaya');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('49Zamora');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('50Zaragoza');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('51Ceuta');
INSERT INTO PROVINCIAS(NOMBRE) VALUES('52Melilla');







