/* Fichero .sql con el código de la creación de tablas. */

CREATE TABLE TablaJugador OF TipoJugador(
constraint PK_Tabla_Jugador PRIMARY KEY(IdJugador),
constraint UQ_Tabla_Jugador_Nombre UNIQUE(Nombre));


CREATE TABLE TablaObjeto OF TipoObjeto(
constraint PK_Tabla_Objeto PRIMARY KEY(IdObjeto));


CREATE TABLE TablaEscenario OF TipoEscenario(
constraint PK_Tabla_Escenario PRIMARY KEY(IdEscenario));


CREATE TABLE TablaEnemigo OF TipoEnemigo(
constraint PK_Tabla_Enemigo PRIMARY KEY(IdEnemigo),
constraint FK_Tabla_EnemigoEscenario FOREIGN KEY(IdEscenario) REFERENCES TablaEscenario(IdEscenario));


CREATE TABLE TablaEvento OF TipoEvento(
constraint PK_Tabla_Evento PRIMARY KEY(IdEvento));


CREATE TABLE TablaEventoConcreto OF TipoEventoConcreto(
constraint PK_Tabla_EventoConcreto PRIMARY KEY(IdEvento));


CREATE TABLE TablaEventoGlobal OF TipoEventoGlobal(
constraint PK_Tabla_EventoGlobal PRIMARY KEY(IdEvento));


CREATE TABLE TablaInventario OF TipoInventario(
constraint PK_Tabla_Inventario PRIMARY KEY(IdJugador, IdObjeto),
constraint FK_Tabla_InventarioJugador FOREIGN KEY(IdJugador) REFERENCES TablaJugador(IdJugador),
constraint FK_Tabla_InventarioObjeto FOREIGN KEY(IdObjeto) REFERENCES TablaObjeto(IdObjeto)
)
