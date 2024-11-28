
--RN-02-02
CREATE OR REPLACE TRIGGER restriccionPedidoProveedores
BEFORE INSERT ON PEDIDOS FOR EACH ROW
DECLARE 
idDelProducto NUMBER;
stockActual NUMBER;
stockMinimo NUMBER;
BEGIN
SELECT OID_PRODUCTO INTO idDelProducto FROM ASOC_P_P WHERE OID_PED=:NEW.OID_PED;
SELECT STOCKMINIMO INTO stockMinimo FROM PRODUCTOS WHERE OID_PRODUCTO=idDelProducto;
SELECT STOCK INTO stockActual FROM PRODUCTOS WHERE OID_PRODUCTO=idDelProducto;
IF(stockActual>stockMinimo)
THEN raise_application_error(-20600,:NEW.OID_PED||'El stock de ese producto en el almacén supera el valor mínimo');
END IF;

END;
/
--RN-03-03
/*
CREATE OR REPLACE TRIGGER restriccionDevolucion
BEFORE INSERT ON DEVOLUCIONES FOR EACH ROW
DECLARE idDeLaVenta NUMBER;
 fechadelaventa DATE;

BEGIN
SELECT OID_V INTO idDeLaVenta FROM ASOC_V_P WHERE OID_V_P=:NEW.OID_V_P;
SELECT FECHAVENTA INTO fechadelaventa FROM VENTAS WHERE OID_V=idDeLaVenta;


IF(EXTRACT(YEAR FROM sysdate) - EXTRACT(YEAR FROM fechadelaventa)>=2) 

THEN raise_application_error(-20600,:NEW.OID_D||'El producto ya no está en garantia');
END IF;
END;
/
*/

CREATE OR REPLACE TRIGGER fechaGasto
BEFORE INSERT ON GASTOS FOR EACH ROW
DECLARE varTemp DATE;
BEGIN
SELECT fechaGasto INTO varTemp FROM GASTOS WHERE OID_G= :NEW.OID_G;
IF (varTemp>SYSDATE)
THEN raise_application_error(-20600,:NEW.OID_G||'La fecha introducida no es válida');
END IF;
END;
