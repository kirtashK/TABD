/* Fichero .sql con el código de la creación de paquete que contiene procedimientos y funciones almacenados. */

/* Declaración */
CREATE OR REPLACE PACKAGE PaqueteProyecto AS

    FUNCTION FuncionEvento(Nombre_ IN TablaEscenario.Nombre%TYPE, IdJugador_ IN TablaJugador.IdJugador%TYPE) RETURN TablaEvento.Descripcion%TYPE;
    FUNCTION FuncionNombreRol(Nombre_ IN TablaJugador.Nombre%TYPE, Rol_ IN  TablaJugador.Rol%TYPE ) RETURN TablaJugador.IdJugador%TYPE;

END PaqueteProyecto;
/

/* Código */
CREATE OR REPLACE PACKAGE BODY PaqueteProyecto AS

FUNCTION FuncionEvento
(
Nombre_ IN TablaEscenario.Nombre%TYPE,
IdJugador_ IN TablaJugador.IdJugador%TYPE
)
RETURN TablaEvento.Descripcion%TYPE IS

Res VARCHAR2(800);
numero_aleatorio NUMBER(5);
total_filas NUMBER;
efecto_ TablaEvento.Efecto%TYPE;
recompensa_ TablaEventoGlobal.Recompensa%TYPE;
existe_ NUMBER(4);
v_IdEvento TablaEventoConcreto.IdEventoConcreto%TYPE;
v_contador NUMBER;
saludActual_ NUMBER(4);
CURSOR eventosconcretos IS SELECT IdEventoConcreto FROM TablaEventoConcreto WHERE NombreEscenario = Nombre_;


BEGIN
    /* Generar un número aleatorio entre 0 y 100: */
    numero_aleatorio := FLOOR(DBMS_RANDOM.VALUE(0, 101));
   
    /* Si el numero es menor que 75, entonces mostramos un evento concreto, 
    sino, uno global: */
    IF (numero_aleatorio < 75) THEN
        /* Obtener el número de filas: */
        SELECT COUNT(*) INTO total_filas FROM TablaEventoConcreto 
        WHERE NombreEscenario = Nombre_;
        /* Generar un número aleatorio entre 1 y total_filas: */
        /* +1 para incluir la última fila */
        numero_aleatorio := FLOOR(DBMS_RANDOM.VALUE(1, total_filas+1));

        v_contador := 1;
        OPEN eventosconcretos;
        LOOP
            FETCH eventosconcretos INTO v_IdEvento;
            IF(v_contador = numero_aleatorio) THEN 
                EXIT;
            END IF;
            v_contador := v_contador+1;
            EXIT WHEN eventosconcretos%NOTFOUND;
        END LOOP;
        CLOSE eventosconcretos;

        /* Obtener la descripcion del evento con ID = numero_aleatorio: */
        SELECT Descripcion, Efecto, Recompensa INTO res, efecto_, recompensa_ 
        FROM TablaEventoConcreto 
        WHERE IdEventoConcreto = v_IdEvento;
    /* Evento global: */
    ELSE
        /* Obtener el número de filas: */
        SELECT COUNT(*) INTO total_filas FROM TablaEventoGlobal;
         /* Generar un número aleatorio entre 1 y total_filas: */
         /* +1 para incluir la última fila */
        numero_aleatorio := FLOOR(DBMS_RANDOM.VALUE(1, total_filas+1));


        /* Obtener la descripcion del evento con ID = numero_aleatorio: */
        SELECT Descripcion, Efecto, Recompensa INTO res, efecto_, recompensa_ 
        FROM TablaEventoGlobal
        WHERE IdEventoGlobal = numero_aleatorio;
    END IF;


    /* Actualizar salud si el efecto no es 0: */
    IF (efecto_ != 0) THEN
        SELECT Salud INTO saludActual_ FROM TablaJugador 
        WHERE IdJugador = IdJugador_;
        /* Si la salud actual mas el efecto es mas de 20, se pasa de la salud máxima,
        por lo que se cura a 20 máximo: */
        IF saludActual_ + efecto_ <= 20 THEN
            UPDATE TablaJugador SET Salud = Salud + efecto_ 
            WHERE IdJugador = IdJugador_;
        ELSIF saludActual_ + efecto_ > 20 THEN
            UPDATE TablaJugador SET Salud = 20 
            WHERE IdJugador = IdJugador_;
        END IF;
    END IF;
    
    /* Añadir recompensa en caso de que haya: */

    /* Generar un número aleatorio entre 1 y numero de elementos del VARRAY: */
    /* +1 para incluir la última fila */
    numero_aleatorio := FLOOR(DBMS_RANDOM.VALUE(1, recompensa_.LAST+1));


    /* Comprobamos si ya existe tal objeto en TablaInventario */
    SELECT COUNT(*) INTO existe_ FROM TablaInventario 
    WHERE IdObjeto = recompensa_(numero_aleatorio) AND IdJugador = IdJugador_;
    /* Si la recompensa no es 0, y no existe el objeto en la tabla inventario, insertar el objeto: */
    IF (recompensa_(numero_aleatorio) != 0 AND existe_ = 0) 
    THEN
        INSERT INTO TablaInventario (IdJugador, IdObjeto, Cantidad)
        VALUES (IdJugador_, recompensa_(numero_aleatorio), 1);
    /* Si ya existe, aumentar la cantidad en 1: */
    ELSIF (recompensa_(numero_aleatorio) != 0 AND existe_ != 0) THEN
        UPDATE TablaInventario SET Cantidad = Cantidad + 1
        WHERE IdObjeto = recompensa_(numero_aleatorio);        
    END IF;

RETURN Res;
END;



FUNCTION FuncionNombreRol (
    Nombre_ IN TablaJugador.Nombre%TYPE,
    Rol_ IN  TablaJugador.Rol%TYPE )
RETURN TablaJugador.IdJugador%TYPE IS
    res TablaJugador.IdJugador%TYPE;
    salud_ TablaJugador.Salud%TYPE;
BEGIN
    BEGIN
        /* Obtenemos el IdJugador y la salud de la partida con nombre = nombre_ */
        SELECT IdJugador, Salud INTO res, salud_
        FROM TablaJugador
        WHERE Nombre = Nombre_;
        
        /* El nombre ya existe, comprobamos si el jugador esta vivo,
        si esta muerto, devolvemos 0, la web se encarga de tratarlo y mostrar 
        un mensaje de error */
        IF salud_ <= 0 THEN
            res := 0;
        END IF;
    EXCEPTION
        /* El nombre no existía */
        WHEN NO_DATA_FOUND THEN
            INSERT INTO TablaJugador(IdJugador, Nombre, Rol, Salud)
            VALUES (Seq_Jugador.NEXTVAL, Nombre_, Rol_, 20); 
            
            /* Guardar en res el valor de IdJugador */
            SELECT IdJugador INTO res
            FROM TablaJugador
            WHERE Nombre = Nombre_;
    END;
  
    RETURN res;
END;


END PaqueteProyecto;
/