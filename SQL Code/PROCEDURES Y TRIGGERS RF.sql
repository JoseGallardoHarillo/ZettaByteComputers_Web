--PROCEDURES PARA LOS RF DE USUARIO
   
 --RF 01_01:CREACION DE USUARIOS

CREATE OR REPLACE PROCEDURE creacion_usuario(w_OID_USUARIO IN USUARIOS.OID_USUARIO%TYPE,
    w_email IN USUARIOS.email%TYPE, w_contrasenya IN USUARIOS.contrasenya%TYPE, w_nombre IN USUARIOS.nombre%TYPE,
    w_apellidos IN USUARIOS.apellidos%TYPE, w_fechaNacimiento IN USUARIOS.fechaNacimiento%TYPE, w_activo IN USUARIOS.activo%TYPE) IS
    BEGIN 
        INSERT INTO USUARIOS VALUES (NULL, w_email, w_contrasenya, w_nombre, w_apellidos, w_fechaNacimiento, w_activo);
        COMMIT WORK;
    END creacion_usuario;
/

--EXECUTE creacion_usuario(NULL, 'antpergom2@us.es', 'toor', 'Antonio', 'Pérez', TODATE('2018-02-02 00:00:00'), 'S');
--EXECUTE creacion_usuario(NULL, 'josguisim@us.es', 'root', 'José', 'Guisado', TODATE('2014-10-02 00:00:00'), 'S');
--EXECUTE creacion_usuario(NULL, 'juagalmen@us.es', 'toor', 'Juan', 'Gálvez', TODATE('2017-02-28 00:00:00'), 'N');



--RF 01_02: MODIFICACIÓN DE DATOS

CREATE OR REPLACE PROCEDURE modificacion_usuario(w_OID_USUARIO IN USUARIOS.OID_USUARIO%TYPE ,
    w_email IN USUARIOS.email%TYPE, w_contrasenya IN USUARIOS.contrasenya%TYPE, w_nombre IN USUARIOS.nombre%TYPE,
    w_apellidos IN USUARIOS.apellidos%TYPE, w_fechaNacimiento IN USUARIOS.fechaNacimiento%TYPE, w_activo IN USUARIOS.activo%TYPE) IS
        BEGIN 

UPDATE USUARIOS SET email=w_email, contrasenya=w_contrasenya, nombre=w_nombre, apellidos=w_apellidos, fechaNacimiento=w_fechaNacimiento  WHERE OID_USUARIO=w_OID_USUARIO;
       COMMIT WORK;
        END modificacion_usuario;



/

    --RF 01_03: LOGIN -> Se hace en la parte de desarrollo web
        
        
    --RF 01_04: ELIMINACIÓN DE USUARIO (¡OJO! No se elimina, se pasa a inactivo)
CREATE OR REPLACE PROCEDURE elimina_usuario(w_OID_USUARIO IN USUARIOS.OID_USUARIO%TYPE) AS
    BEGIN
        UPDATE USUARIOS SET activo=('N') WHERE (USUARIOS.OID_USUARIO = w_OID_USUARIO);
        COMMIT WORK;
    END elimina_usuario;
/

-- RF 02 01:Creacion de pedidos

CREATE OR REPLACE PROCEDURE creacion_Pedido(w_OID_PED IN PEDIDOS.OID_PED%TYPE ,
    w_OID_PROVEEDOR IN PEDIDOS.OID_PROVEEDOR%TYPE, w_fechaPedido IN PEDIDOS.fechaPedido%TYPE, w_fechaEnvio IN PEDIDOS.fechaEnvio%TYPE,
    w_direccionEnvio IN PEDIDOS.direccionEnvio%TYPE, w_fechaEntrega IN PEDIDOS.fechaEntrega%TYPE) IS
        BEGIN 

INSERT INTO PEDIDOS VALUES (NULL, w_OID_PROVEEDOR, w_fechaPedido, w_fechaEnvio, w_direccionEnvio, w_fechaEntrega);
       COMMIT WORK;
        END creacion_Pedido;
/

--RF 02 02 Actualización de stock cuando se vende un producto y notificación 

--CREATE OR REPLACE TRIGGER ventaActualizaNotificaStock
--BEFORE INSERT ON VENTAS FOR EACH ROW

--DECLARE stockVar NUMBER;
--minStock NUMBER;
--oidProducto NUMBER;
--cantidadvar NUMBER;
--BEGIN
--SELECT OID_PRODUCTO INTO cantidadvar FROM ASOC_V_P WHERE OID_V=:NEW.OID_V;
--SELECT cantidad INTO oidProducto FROM ASOC_V_P WHERE OID_V=:NEW.OID_V;
--SELECT stockMinimo INTO minStock FROM PRODUCTOS WHERE OID_PRODUCTO=oidProducto;
--SELECT stock INTO stockVar FROM PRODUCTOS WHERE OID_PRODUCTO=oidProducto;
--IF (stockVar-cantidadvar<0)
--THEN raise_application_error(-20600,oidProducto||'No hay suficientes productos en stock');
--END IF;
--UPDATE PRODUCTOS SET STOCK = STOCK - cantidadvar;
--IF (stockVar- cantidadvar<=minStock)
--AQUÍ IRÍA LO DEL EMAIL QUE NO SABEMOS COMO IMPLEMENTARLO DE MOMENTO
--END IF;

---END;
---/

--RF 02 03: Actualización Stock cuando se compra un producto a proveedores

CREATE OR REPLACE TRIGGER ActualizaStockCompra
AFTER INSERT ON PEDIDOS
FOR EACH ROW
DECLARE oid_p NUMBER;
cantidadtemp NUMBER;

BEGIN
SELECT OID_PRODUCTO INTO oid_p FROM ASOC_P_P WHERE OID_PED=:NEW.OID_PED;
SELECT CANTIDAD INTO cantidadtemp FROM ASOC_P_P WHERE OID_PED=:NEW.OID_PED;
UPDATE PRODUCTOS SET STOCK = STOCK + cantidadtemp;
END;
/

    --RF 03_01 Crear una compra
CREATE OR REPLACE PROCEDURE crea_venta(w_OID_CLIENTE IN VENTAS.OID_CLIENTE%TYPE, w_importe IN VENTAS.importe%TYPE, w_ventaOnline IN VENTAS.ventaOnline%TYPE,
    w_tipoEnvio IN VENTAS.tipoEnvio%TYPE, w_fechaEnvio IN VENTAS.fechaEnvio%TYPE, w_fechaVenta IN VENTAS.fechaVenta%TYPE,
    w_direccionEnvio IN VENTAS.direccionEnvio%TYPE, w_poblacion IN VENTAS.poblacion%TYPE, w_codigoPostal IN VENTAS.codigoPostal%TYPE,
    w_fechaEntrega IN VENTAS.fechaEntrega%TYPE) AS
        --venta VENTAS%ROWTYPE;
    BEGIN
        INSERT INTO VENTAS(OID_CLIENTE, ventaOnline, tipoEnvio, fechaEnvio, fechaVenta, direccionEnvio, poblacion, codigoPostal, fechaEntrega)
            VALUES(w_OID_CLIENTE, w_importe, w_ventaOnline, w_tipoEnvio, w_fechaEnvio, w_fechaVenta, w_direccionEnvio, w_poblacion, w_fechaEntrega);

        COMMIT WORK;
    END crea_venta;
/
    
    --RF 03_02 Crear devolución
CREATE OR REPLACE PROCEDURE crea_devolucion (w_OID_V_P IN DEVOLUCIONES.OID_V_P%TYPE, w_cantidad IN DEVOLUCIONES.cantidad%TYPE,
    w_motivo IN DEVOLUCIONES.motivo%TYPE, w_descripcion IN DEVOLUCIONES.descripcion%TYPE) AS
    BEGIN
        INSERT INTO DEVOLUCIONES(OID_V_P, cantidad, fechaDevolucion, motivo, descripcion) VALUES(w_OID_V_P, w_cantidad, SYSDATE, w_motivo, w_descripcion);
        COMMIT WORK;
    END crea_devolucion;
/
    
--RF 03 03 Calculo de los precios de envio

CREATE OR REPLACE TRIGGER calculaPrecioEnvio
BEFORE INSERT ON VENTAS
FOR EACH ROW
BEGIN
IF :NEW.importe<50
THEN 
CASE
WHEN :NEW.tipoEnvio='24HORAS' THEN :NEW.importe := :NEW.importe+10;
WHEN :NEW.tipoEnvio='ESTANDAR' THEN :NEW.importe := :NEW.importe+7;
WHEN :NEW.tipoEnvio='EXPRES' THEN :NEW.importe := :NEW.importe+5;
WHEN :NEW.tipoEnvio='ECONOMICO' THEN :NEW.importe := :NEW.importe+2;
END CASE;

END IF;

END;
/


--RF 04 01 check pago nomina

CREATE OR REPLACE TRIGGER pagoNomina
BEFORE INSERT ON GASTOS
FOR EACH ROW
Declare salbase Number;
complemento number;
BEGIN
Select salarioBase INTO salbase FROM NOMINAS WHERE OID_EMPLEADO=TO_NUMBER(:new.descripcion);
Select complementoSalarial INTO complemento FROM NOMINAS WHERE OID_EMPLEADO=TO_NUMBER(:new.descripcion);
if(:new.IMPORTE<salbase+complemento OR :new.IMPORTE>salbase+complemento)
then raise_application_error(-20600,:new.descripcion||'El salario de ese empleado no es el correcto');
END IF;
END;
    /
    
    

    
    --RF 04 02 procedure pa crear gastos ANTONIO
CREATE OR REPLACE PROCEDURE crea_gasto(w_OID_EMPLEADO IN GASTOS.descripcion%TYPE, w_descripcion IN GASTOS.descripcion%TYPE, w_importe IN GASTOS.importe%TYPE,
    w_fechaGasto IN GASTOs.fechaGasto%TYPE) AS
    rolvar varchar(32);
    BEGIN
        SELECT rol INTO rolvar FROM EMPLEADOS WHERE OID_EMPLEADO = w_OID_EMPLEADO;
        IF(rolvar = 'GESTOR DE PERSONAL') THEN
            INSERT INTO GASTOS(OID_EMPLEADO, descripcion, importe, fechaGasto) VALUES (w_OID_EMPLEADO, w_descripcion, w_importe, w_fechaGasto);
        END IF;
    END crea_gasto;
    





