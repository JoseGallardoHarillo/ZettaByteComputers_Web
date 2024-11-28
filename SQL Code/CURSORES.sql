DECLARE 
  CURSOR c IS
  SELECT stock, nombre FROM PRODUCTOS
  ORDER BY nombre;
BEGIN
  DBMS_OUTPUT.PUT_LINE('Los 3 primeros productos por orden de stock');
    FOR fila IN c LOOP
        EXIT WHEN C%ROWCOUNT > 3;
          DBMS_OUTPUT.PUT_LINE(fila.stock || '' || fila.nombre);
    END LOOP;
END;
/
DECLARE 
  CURSOR c IS
  SELECT nombre, email FROM PROVEEDORES
  ORDER BY nombre;
BEGIN
  DBMS_OUTPUT.PUT_LINE('Los 3 primeros proveedores ordenados por su nombre');
    FOR fila IN c LOOP
        EXIT WHEN C%ROWCOUNT > 3;
          DBMS_OUTPUT.PUT_LINE(fila.nombre || '' || fila.email);
    END LOOP;
END;
/
DECLARE 
  CURSOR c IS
  SELECT email, activo FROM USUARIOS
  ORDER BY activo;
BEGIN
  DBMS_OUTPUT.PUT_LINE('Los 10 primeros email ordenados por si están activos');
    FOR fila IN c LOOP
        EXIT WHEN C%ROWCOUNT > 10;
          DBMS_OUTPUT.PUT_LINE(fila.email || '' || fila.activo);
    END LOOP;
END;
/
DECLARE 
  CURSOR c IS
  SELECT fechaEnvio, fechaEntrega FROM PEDIDOS
  ORDER BY fechaEnvio;
BEGIN
  DBMS_OUTPUT.PUT_LINE('Los 5 primeros envios ordenados por su fecha de envío');
    FOR fila IN c LOOP
        EXIT WHEN C%ROWCOUNT > 5;
          DBMS_OUTPUT.PUT_LINE(fila.fechaEnvio || '' || fila.fechaEntrega);
    END LOOP;
END;
/
DECLARE 
  CURSOR c IS
  SELECT tarjetaCredito, fechaCaducidadTarjeta FROM CLIENTES
  ORDER BY fechaCaducidadTarjeta;
BEGIN
  DBMS_OUTPUT.PUT_LINE('Las 5 primeras tarjetas de credito por orden de su caducidad');
    FOR fila IN c LOOP
        EXIT WHEN C%ROWCOUNT > 5;
          DBMS_OUTPUT.PUT_LINE(fila.tarjetaCredito || '' || fila.fechaCaducidadTarjeta);
    END LOOP;
END;
/
DECLARE 
  CURSOR c IS
  SELECT importe, fechaGasto FROM GASTOS
  ORDER BY importe;
BEGIN
  DBMS_OUTPUT.PUT_LINE('Los 3 primeros gastos ordenados por su importe');
    FOR fila IN c LOOP
        EXIT WHEN C%ROWCOUNT > 3;
          DBMS_OUTPUT.PUT_LINE(fila.importe || '' || fila.fechaGasto);
    END LOOP;
END;
/