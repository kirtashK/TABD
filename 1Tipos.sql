/* Fichero .sql con el código de la creación de tipos. */

CREATE OR REPLACE TYPE TipoJugador AS OBJECT(
IdJugador NUMBER(5),
Nombre VARCHAR2(50),
Rol VARCHAR2(20),
Salud NUMBER(3)
);
/


CREATE OR REPLACE TYPE TipoObjeto AS OBJECT(
IdObjeto NUMBER(5),
Nombre VARCHAR2(50),
Descripcion VARCHAR2(500)
);
/


CREATE OR REPLACE TYPE TipoInventario AS OBJECT(
IdJugador NUMBER(5),
IdObjeto NUMBER(5),
Cantidad NUMBER(3)
);
/


CREATE OR REPLACE TYPE TipoEscenario AS OBJECT(
IdEscenario NUMBER(5),
Nombre VARCHAR2(50),
Descripcion VARCHAR2(500)
);
/


CREATE OR REPLACE TYPE Tipo_Recompensa AS VARRAY(3) OF NUMBER;
/


CREATE OR REPLACE TYPE TipoEnemigo AS OBJECT(
IdEnemigo NUMBER(5),
Nombre VARCHAR2(50),
Recompensa Tipo_Recompensa,
IdEscenario NUMBER(5)
);
/


CREATE OR REPLACE TYPE TipoEvento AS OBJECT(
IdEvento NUMBER(5),
Descripcion VARCHAR2(800),
Recompensa Tipo_Recompensa,
Efecto NUMBER(3)
) NOT FINAL;
/


CREATE OR REPLACE TYPE TipoEventoConcreto UNDER TipoEvento (
IdEventoConcreto NUMBER(5),
NombreEscenario VARCHAR2(20)
);
/


CREATE OR REPLACE TYPE TipoEventoGlobal UNDER TipoEvento (
IdEventoGlobal NUMBER(5)
);
/
