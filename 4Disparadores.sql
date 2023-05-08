/* Fichero .sql con el código de la creación de disparadores.*/


/*Fichero .sql con el código de la creación de disparadores.*/
/*Al actualizar el rol del jugador, añadirle el equipamiento correspondiente */
CREATE OR REPLACE TRIGGER Disparador_Rol
AFTER INSERT ON TablaJugador
FOR EACH ROW

BEGIN

IF (:NEW.rol = 'Caballero' ) THEN
    /* Items de caballero, espada de hierro, escudo de madera y armadura pesada */
	INSERT INTO TablaInventario (IdJugador, IdObjeto, Cantidad)
	VALUES (Seq_Jugador.CURRVAL, 2, 1);
	INSERT INTO TablaInventario (IdJugador, IdObjeto, Cantidad)
	VALUES (Seq_Jugador.CURRVAL, 5 , 1);
    INSERT INTO TablaInventario (IdJugador, IdObjeto, Cantidad)
	VALUES (Seq_Jugador.CURRVAL, 8 , 1);
	
ELSIF (:NEW.rol = 'Arquero' ) THEN
	/* Items de arquero, arco y armadura media */
	INSERT INTO TablaInventario (IdJugador, IdObjeto, Cantidad)
	VALUES (Seq_Jugador.CURRVAL, 3, 1);
	INSERT INTO TablaInventario (IdJugador, IdObjeto, Cantidad)
	VALUES (Seq_Jugador.CURRVAL, 6, 1);

ELSIF (:NEW.rol = 'Mago' ) THEN
	/* Items de mago, bastón y armadura ligera */
	INSERT INTO TablaInventario (IdJugador, IdObjeto, Cantidad)
	VALUES (Seq_Jugador.CURRVAL, 4, 1);
	INSERT INTO TablaInventario (IdJugador, IdObjeto, Cantidad)
	VALUES (Seq_Jugador.CURRVAL, 7, 1);

END IF;
END Disparador_Rol;
