-- we don't know how to generate schema MDA (class Schema) :(
create sequence ID_SEQ
/

create table L_COLLECTION_SLIP_TYPES
(
	ID NUMBER not null
		primary key,
	NAME VARCHAR2(100),
	CREATED_AT DATE,
	UPDATED_AT DATE,
	CREATED_BY NUMBER,
	UPDATED_BY NUMBER,
	STATUS VARCHAR2(10) default 'A'
)
/

create table COLLECTION_SLIPS
(
	ID NUMBER not null
		primary key,
	FORM_NO NUMBER,
	COLLECTION_DATE DATE,
	COLLECTED_BY NUMBER,
	SLIP_TYPE_ID NUMBER
		constraint COLLECTION_SLIP_TYPES__FK
			references L_COLLECTION_SLIP_TYPES,
	LOCAL_VESSEL_ID NUMBER,
	PORT_DUES_AMOUNT FLOAT,
	RIVER_DUES_AMOUNT FLOAT,
	VAT_AMOUNT FLOAT,
	OTHER_DUES_TITLE VARCHAR2(100),
	OTHER_DUES_AMOUNT NUMBER,
	PERIOD_FROM DATE,
	PERIOD_TO DATE,
	CREATED_AT DATE,
	UPDATED_AT DATE,
	CREATED_BY NUMBER,
	UPDATED_BY NUMBER,
	STATUS VARCHAR2(5) default 'A' not null
)
/

create table L_CPA_VESSEL_TYPE
(
	ID NUMBER not null
		primary key,
	NAME VARCHAR2(100),
	CREATED_AT DATE,
	UPDATED_AT DATE,
	CREATED_BY NUMBER,
	UPDATED_BY NUMBER,
	STATUS VARCHAR2(10) default 'A' not null
)
/

create table CPA_VESSELS
(
	ID NUMBER not null
		primary key,
	NAME VARCHAR2(100),
	VESSEL_TYPE_ID NUMBER
		constraint CPA_VESSEL_TYPE_ID_FK
			references L_CPA_VESSEL_TYPE,
	CREATED_AT DATE,
	UPDATED_AT DATE,
	CREATED_BY NUMBER,
	UPDATED_BY NUMBER,
	STATUS VARCHAR2(5) default 'A' not null
)
/

create table LOCAL_VESSELS
(
	ID NUMBER not null
		primary key,
	NAME VARCHAR2(100) not null,
	CALL_SIGN VARCHAR2(200),
	FLAG VARCHAR2(200),
	GRT NUMBER,
	NRT NUMBER,
	LOA NUMBER,
	MAX_DRAUGHT FLOAT,
	TOTAL_CREW_OFFICER NUMBER,
	OWNER_NAME VARCHAR2(200),
	OWNER_ADDRESS VARCHAR2(400),
	STATUS VARCHAR2(5) default 'A' not null,
	REG_NO VARCHAR2(100),
	REG_EXP_DATE DATE,
	REG_ISSUED_BY NUMBER,
	REG_FILE VARCHAR2(200)
)
/

create table SWING_MOORINGS
(
	ID NUMBER not null
		primary key,
	SERIAL_NO NUMBER,
	NAME VARCHAR2(100),
	DETAILS VARCHAR2(200),
	CREATED_AT DATE,
	UPDATED_AT DATE,
	CREATE_BY NUMBER,
	UPDATED_BY NUMBER,
	STATUS VARCHAR2(10) default 'A'
)
/

create table MOORING_VISITS
(
	ID NUMBER not null
		primary key,
	CPA_VESSEL_ID NUMBER
		constraint MV_CPA_VESSELS_ID_FK
			references CPA_VESSELS,
	LOCAL_VESSEL_ID NUMBER
		constraint MV_LOCAL_VESSELS_ID_FK
			references LOCAL_VESSELS,
	SWING_MOORING_ID NUMBER
		constraint MV_SWING_MOORINGS_ID_FK
			references SWING_MOORINGS,
	LM_REP VARCHAR2(100),
	VISIT_DATE DATE,
	SL_NO NUMBER,
	INSPECTOR_ID NUMBER,
	INSPECTOR_DATE DATE,
	CREATED_AT DATE,
	UPDATED_AT DATE,
	CREATED_BY NUMBER,
	UPDATED_BY NUMBER,
	STATUS VARCHAR2(10) default 'P'
)
/

create table L_TUG_TYPES
(
	ID NUMBER not null
		primary key,
	NAME VARCHAR2(100),
	CREATED_AT DATE,
	UPDATED_AT DATE,
	CREATED_BY NUMBER,
	UPDATED_BY NUMBER,
	STATUS VARCHAR2(10) default 'P'
)
/

create table TUGS
(
	ID NUMBER not null
		primary key,
	NAME VARCHAR2(200),
	TUG_TYPE_ID NUMBER
		constraint TUG_TYPES_ID_FK
			references L_TUG_TYPES,
	CAPACITY VARCHAR2(100),
	CREATED_AT DATE,
	UPDATED_AT DATE,
	CREATED_BY NUMBER,
	UPDATED_BY NUMBER,
	STATUS VARCHAR2(10) default 'P'
)
/

create table VESSELS
(
	ID NUMBER not null
		primary key,
	NAME VARCHAR2(100)
)
/

create table PILOTS
(
	ID NUMBER not null
		constraint PILOTS_PK
			primary key,
	NAME VARCHAR2(100)
)
/

create table L_VESSEL_CONDITIONS
(
	ID NUMBER not null
		constraint VESSEL_CONDITIONS_PK
			primary key,
	TITLE VARCHAR2(200),
	CREATED_AT DATE,
	UPDATED_AT DATE,
	CREATED_BY NUMBER,
	UPDATED_BY NUMBER,
	STATUS VARCHAR2(10) default 'A',
	VALUE_TYPE VARCHAR2(100) default 'RADIO'
)
/

create table L_VESSEL_WORKING_TYPES
(
	ID NUMBER not null
		constraint L_VESSEL_WORKING_TYPES_PK
			primary key,
	NAME VARCHAR2(100),
	DESCRIPTION VARCHAR2(200),
	CREATED_BY NUMBER,
	UPDATED_BY NUMBER,
	CREATED_AT DATE,
	UPDATED_AT DATE,
	STATUS VARCHAR2(10) default 'A'
)
/

create table L_PILOTAGE_TYPES
(
	ID NUMBER not null
		constraint L_PILOTAGE_TYPES_PK
			primary key,
	NAME VARCHAR2(100),
	DESCRIPTION VARCHAR2(200),
	CREATED_BY NUMBER,
	UPDATED_BY NUMBER,
	CREATED_AT DATE,
	UPDATED_AT DATE,
	STATUS VARCHAR2(10) default 'A'
)
/

create table L_PILOTAGE_SCHEDULE_TYPES
(
	ID NUMBER not null
		constraint L_PILOTAGE_SCHEDULE_TYPES_PK
			primary key,
	NAME VARCHAR2(100),
	DESCRIPTION VARCHAR2(200),
	CREATED_BY NUMBER,
	UPDATED_BY NUMBER,
	CREATED_AT DATE,
	UPDATED_AT DATE,
	STATUS VARCHAR2(10) default 'A',
	START_TIME VARCHAR2(100),
	END_TIME VARCHAR2(100)
)
/

create table L_PILOTAGE_WORK_LOCATIONS
(
	ID NUMBER not null
		constraint L_PILOTAGE_WORK_LOCATIONS_PK
			primary key,
	NAME VARCHAR2(100),
	DESCRIPTION VARCHAR2(200),
	STATUS VARCHAR2(10) default 'A',
	CREATED_AT DATE,
	UPDATED_AT DATE,
	CREATED_BY NUMBER,
	UPDATED_BY NUMBER
)
/

create table PILOTAGES
(
	ID NUMBER not null
		primary key,
	VESSEL_ID NUMBER not null,
	VESSEL_REG_NO NUMBER not null,
	WORKING_TYPE_ID NUMBER not null
		constraint PV_WORKING_TYPES__FK
			references L_VESSEL_WORKING_TYPES,
	MOTHER_VESSEL_ID NUMBER,
	FILE_NO VARCHAR2(100),
	PILOT_ID NUMBER not null,
	PILOTAGE_TYPE_ID NUMBER not null
		constraint PILOTAGE_TYPES__FK
			references L_PILOTAGE_TYPES,
	SCHEDULE_TYPE_ID NUMBER not null
		constraint PILOTAGE_SCHEDULE_TYPES__FK
			references L_PILOTAGE_SCHEDULE_TYPES,
	LOCAL_AGENT VARCHAR2(200),
	LAST_PORT VARCHAR2(200),
	NEXT_PORT VARCHAR2(200),
	PILOT_BORDED_AT DATE,
	PILOT_LEFT_AT DATE,
	PILOTAGE_FROM_TIME DATE,
	PILOTAGE_TO_TIME DATE,
	MOORING_FROM_TIME DATE,
	MOORING_TO_TIME DATE,
	MOORING_LINE_FORD NUMBER,
	MOORING_LINE_AFT NUMBER,
	WORK_LOCATION_ID NUMBER not null
		constraint PILOTAGE_WORK_LOCATIONS__FK
			references L_PILOTAGE_WORK_LOCATIONS,
	SHIFTED_FROM VARCHAR2(200),
	SHIFTED_TO VARCHAR2(200),
	STERN_POWER_AVAIL VARCHAR2(100),
	MASTER_SIGN_DATE DATE,
	REMARKS VARCHAR2(200),
	CREATED_AT DATE,
	UPDATED_AT DATE,
	CREATED_BY NUMBER,
	UPDATED_BY NUMBER,
	STATUS VARCHAR2(10) default 'P'
)
/

create table PILOTAGE_TUGS
(
	ID NUMBER not null
		primary key,
	TUG_ID NUMBER
		constraint PILOTAGE_TUGS_TUGS_ID_FK
			references TUGS,
	PILOTAGE_ID NUMBER
		constraint PILOTAGE_TUGS_PILOTAGES_ID_FK
			references PILOTAGES,
	ASSITANCE_FROM_TIME DATE,
	ASSITANCE_TO_TIME DATE,
	CREATED_AT DATE,
	UPDATED_AT DATE,
	CREATED_BY NUMBER,
	UPDATED_BY NUMBER,
	STATUS VARCHAR2(10) default 'P'
)
/

create table PILOTAGE_VESSEL_CONDOTIONS
(
	ID NUMBER not null
		constraint PILOTAGE_VESSEL_CONDOTIONS_PK
			primary key,
	PILOTAGE_ID NUMBER
		constraint PVC_PILOTAGES_ID_FK
			references PILOTAGES,
	VESSEL_CONDITION_ID NUMBER
		constraint PVC_CONDITIONS_ID_FK
			references L_VESSEL_CONDITIONS,
	ANS_VALUE VARCHAR2(200),
	STATUS VARCHAR2(10) default 'A',
	CREATED_AT DATE,
	UPDATED_AT DATE,
	CREATED_BY NUMBER,
	UPDATED_BY NUMBER
)
/

create table CONFIG_STATUS
(
	ID NUMBER,
	TAG VARCHAR2(100),
	TITLE VARCHAR2(200),
	OPERATION VARCHAR2(200)
)
/

create view FOREIGN_VESSELS as
SELECT ID, NAME
   FROM VESSELS
/

create view CPA_PILOTS as
SELECT ID, NAME
   FROM PILOTS
/

create PACKAGE MDA_CORE_FUNC
AS
   FUNCTION GENERATE_ID RETURN NUMBER;

END MDA_CORE_FUNC;
/

create PACKAGE BODY MDA_CORE_FUNC
AS
 FUNCTION GENERATE_ID
      RETURN NUMBER
   IS
      SEQ_NUM      VARCHAR2 (6);
      YEAR_PART    VARCHAR2 (2) := TO_CHAR (SYSDATE, 'YY');
      MONTH_PART   VARCHAR2 (2) := LPAD (TO_CHAR (SYSDATE, 'MM'), 2, '0');
      DAY_PART     VARCHAR2 (2) := LPAD (TO_CHAR (SYSDATE, 'DD'), 2, '0');
      HOUR_PART    VARCHAR2 (2) := LPAD (TO_CHAR (SYSDATE, 'HH24'), 2, '0');
      SALT_PART    VARCHAR2 (3)
                      := LPAD (FLOOR (DBMS_RANDOM.VALUE * 10), 3, '0');
   BEGIN
      SELECT LPAD (ID_SEQ.NEXTVAL, 6, '0') INTO SEQ_NUM FROM DUAL;

      RETURN TO_NUMBER (YEAR_PART||MONTH_PART||DAY_PART||HOUR_PART ||SALT_PART|| SEQ_NUM);
   EXCEPTION
      WHEN OTHERS
      THEN
         RETURN NULL;
   END GENERATE_ID;
END MDA_CORE_FUNC;
/

create PACKAGE     MDA_CORE_PROCE
AS
   PROCEDURE COLLECTION_SLIP_TYPES_CUD (
      P_ACTION_TYPE      IN     VARCHAR2,
      P_ID               IN OUT MDA.L_COLLECTION_SLIP_TYPES.ID%TYPE,
      P_NAME             IN     MDA.L_COLLECTION_SLIP_TYPES.NAME%TYPE,
      P_CREATED_BY       IN     MDA.L_COLLECTION_SLIP_TYPES.CREATED_BY%TYPE,
      P_UPDATED_BY       IN     MDA.L_COLLECTION_SLIP_TYPES.UPDATED_BY%TYPE,
      P_STATUS           IN     MDA.L_COLLECTION_SLIP_TYPES.STATUS%TYPE,
      O_STATUS_CODE         OUT NUMBER,
      O_STATUS_MESSAGE      OUT VARCHAR2);

   PROCEDURE CPA_VESSEL_TYPE_CUD (
      P_ACTION_TYPE      IN     VARCHAR2,
      P_ID               IN OUT MDA.L_CPA_VESSEL_TYPE.ID%TYPE,
      P_NAME             IN     MDA.L_CPA_VESSEL_TYPE.NAME%TYPE,
      P_CREATED_BY       IN     MDA.L_CPA_VESSEL_TYPE.CREATED_BY%TYPE,
      P_UPDATED_BY       IN     MDA.L_CPA_VESSEL_TYPE.UPDATED_BY%TYPE,
      P_STATUS           IN     MDA.L_CPA_VESSEL_TYPE.STATUS%TYPE,
      O_STATUS_CODE         OUT NUMBER,
      O_STATUS_MESSAGE      OUT VARCHAR2);


   PROCEDURE PILOTAGE_SCHEDULE_TYPES_CUD (
      P_ACTION_TYPE      IN     VARCHAR2,
      P_ID               IN OUT MDA.L_PILOTAGE_SCHEDULE_TYPES.ID%TYPE,
      P_NAME             IN     MDA.L_PILOTAGE_SCHEDULE_TYPES.NAME%TYPE,
      P_DESCRIPTION      IN     MDA.L_PILOTAGE_SCHEDULE_TYPES.DESCRIPTION%TYPE,
      P_CREATED_BY       IN     MDA.L_PILOTAGE_SCHEDULE_TYPES.CREATED_BY%TYPE,
      P_UPDATED_BY       IN     MDA.L_PILOTAGE_SCHEDULE_TYPES.UPDATED_BY%TYPE,
      P_STATUS           IN     MDA.L_PILOTAGE_SCHEDULE_TYPES.STATUS%TYPE,
      P_START_TIME       IN     MDA.L_PILOTAGE_SCHEDULE_TYPES.START_TIME%TYPE,
      P_END_TIME         IN     MDA.L_PILOTAGE_SCHEDULE_TYPES.END_TIME%TYPE,
      O_STATUS_CODE         OUT NUMBER,
      O_STATUS_MESSAGE      OUT VARCHAR2);

   PROCEDURE PILOTAGE_TYPES_CUD (
      P_ACTION_TYPE      IN     VARCHAR2,
      P_ID               IN OUT MDA.L_PILOTAGE_TYPES.ID%TYPE,
      P_NAME             IN     MDA.L_PILOTAGE_TYPES.NAME%TYPE,
      P_DESCRIPTION      IN     MDA.L_PILOTAGE_TYPES.DESCRIPTION%TYPE,
      P_CREATED_BY       IN     MDA.L_PILOTAGE_TYPES.CREATED_BY%TYPE,
      P_UPDATED_BY       IN     MDA.L_PILOTAGE_TYPES.UPDATED_BY%TYPE,
      P_STATUS           IN     MDA.L_PILOTAGE_TYPES.STATUS%TYPE,
      O_STATUS_CODE         OUT NUMBER,
      O_STATUS_MESSAGE      OUT VARCHAR2);

   PROCEDURE PILOTAGE_WORK_LOCATIONS_CUD (
      P_ACTION_TYPE      IN     VARCHAR2,
      P_ID               IN OUT MDA.L_PILOTAGE_WORK_LOCATIONS.ID%TYPE,
      P_NAME             IN     MDA.L_PILOTAGE_WORK_LOCATIONS.NAME%TYPE,
      P_DESCRIPTION      IN     MDA.L_PILOTAGE_WORK_LOCATIONS.DESCRIPTION%TYPE,
      P_CREATED_BY       IN     MDA.L_PILOTAGE_WORK_LOCATIONS.CREATED_BY%TYPE,
      P_UPDATED_BY       IN     MDA.L_PILOTAGE_WORK_LOCATIONS.UPDATED_BY%TYPE,
      P_STATUS           IN     MDA.L_PILOTAGE_WORK_LOCATIONS.STATUS%TYPE,
      O_STATUS_CODE         OUT NUMBER,
      O_STATUS_MESSAGE      OUT VARCHAR2);

   PROCEDURE TUG_TYPES_CUD (
      P_ACTION_TYPE      IN     VARCHAR2,
      P_ID               IN OUT MDA.L_TUG_TYPES.ID%TYPE,
      P_NAME             IN     MDA.L_TUG_TYPES.NAME%TYPE,
      P_CREATED_BY       IN     MDA.L_TUG_TYPES.CREATED_BY%TYPE,
      P_UPDATED_BY       IN     MDA.L_TUG_TYPES.UPDATED_BY%TYPE,
      P_STATUS           IN     MDA.L_TUG_TYPES.STATUS%TYPE,
      O_STATUS_CODE         OUT NUMBER,
      O_STATUS_MESSAGE      OUT VARCHAR2);


   PROCEDURE VESSEL_CONDITIONS_CUD (
      P_ACTION_TYPE      IN     VARCHAR2,
      P_ID               IN OUT MDA.L_VESSEL_CONDITIONS.ID%TYPE,
      P_TITLE            IN     MDA.L_VESSEL_CONDITIONS.TITLE%TYPE,
      P_CREATED_BY       IN     MDA.L_VESSEL_CONDITIONS.CREATED_BY%TYPE,
      P_UPDATED_BY       IN     MDA.L_VESSEL_CONDITIONS.UPDATED_BY%TYPE,
      P_STATUS           IN     MDA.L_VESSEL_CONDITIONS.STATUS%TYPE,
      P_VALUE_TYPE       IN     MDA.L_VESSEL_CONDITIONS.VALUE_TYPE%TYPE,
      O_STATUS_CODE         OUT NUMBER,
      O_STATUS_MESSAGE      OUT VARCHAR2);


   PROCEDURE VESSEL_WORKING_TYPES_CUD (
      P_ACTION_TYPE      IN     VARCHAR2,
      P_ID               IN OUT MDA.L_VESSEL_WORKING_TYPES.ID%TYPE,
      P_NAME             IN     MDA.L_VESSEL_WORKING_TYPES.NAME%TYPE,
      P_DESCRIPTION      IN     MDA.L_VESSEL_WORKING_TYPES.DESCRIPTION%TYPE,
      P_CREATED_BY       IN     MDA.L_VESSEL_WORKING_TYPES.CREATED_BY%TYPE,
      P_UPDATED_BY       IN     MDA.L_VESSEL_WORKING_TYPES.UPDATED_BY%TYPE,
      P_STATUS           IN     MDA.L_VESSEL_WORKING_TYPES.STATUS%TYPE,
      O_STATUS_CODE         OUT NUMBER,
      O_STATUS_MESSAGE      OUT VARCHAR2);
END MDA_CORE_PROCE;
/

create PACKAGE BODY     MDA_CORE_PROCE
AS
   PROCEDURE COLLECTION_SLIP_TYPES_CUD (
      P_ACTION_TYPE      IN     VARCHAR2,
      P_ID               IN OUT MDA.L_COLLECTION_SLIP_TYPES.ID%TYPE,
      P_NAME             IN     MDA.L_COLLECTION_SLIP_TYPES.NAME%TYPE,
      P_CREATED_BY       IN     MDA.L_COLLECTION_SLIP_TYPES.CREATED_BY%TYPE,
      P_UPDATED_BY       IN     MDA.L_COLLECTION_SLIP_TYPES.UPDATED_BY%TYPE,
      P_STATUS           IN     MDA.L_COLLECTION_SLIP_TYPES.STATUS%TYPE,
      O_STATUS_CODE         OUT NUMBER,
      O_STATUS_MESSAGE      OUT VARCHAR2)
   IS
      V_GEN_ID       NUMBER;
      V_EXIST_DATA   NUMBER;

      PROCEDURE SET_ERROR (P_ERROR_CODE      IN NUMBER,
                           P_ERROR_MESSAGE   IN VARCHAR2)
      IS
      BEGIN
         O_STATUS_CODE := P_ERROR_CODE;
         O_STATUS_MESSAGE := P_ERROR_MESSAGE;
      END SET_ERROR;
   BEGIN
      IF P_ACTION_TYPE = 'I'
      THEN
         IF P_NAME IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;


         V_GEN_ID := MDA_CORE_FUNC.GENERATE_ID;

         SELECT COUNT (NAME)
           INTO V_EXIST_DATA
           FROM L_COLLECTION_SLIP_TYPES lcst
          WHERE UPPER (NAME) = UPPER (P_NAME) AND STATUS NOT IN ('D', 'R');

         IF V_EXIST_DATA > 0
         THEN
            SET_ERROR (99, 'THIS SLIP TYPE ALREADY EXIST');

            RETURN;
         END IF;

        <<INSERT_OPERATION>>
         BEGIN
            INSERT INTO L_COLLECTION_SLIP_TYPES (ID,
                                                 NAME,
                                                 STATUS,
                                                 CREATED_AT,
                                                 CREATED_BY)
                 VALUES (V_GEN_ID,
                         UPPER (P_NAME),
                         P_STATUS,
                         SYSDATE,
                         P_CREATED_BY);

            P_ID := V_GEN_ID;
            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'SLIP TYPE CREATED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END INSERT_OPERATION;
      ELSIF P_ACTION_TYPE = 'U'
      THEN
         IF P_NAME IS NULL OR P_ID IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;

         SELECT COUNT (NAME)
           INTO V_EXIST_DATA
           FROM L_COLLECTION_SLIP_TYPES lcst
          WHERE     UPPER (NAME) = UPPER (P_NAME)
                AND STATUS NOT IN ('D', 'R')
                AND ID <> P_ID;

         IF V_EXIST_DATA > 0
         THEN
            SET_ERROR (99, 'THIS SLIP TYPE ALREADY EXIST');

            RETURN;
         END IF;


        <<UPDATE_OPERATION>>
         BEGIN
            UPDATE L_COLLECTION_SLIP_TYPES
               SET NAME = UPPER (P_NAME),
                   STATUS = P_STATUS,
                   UPDATED_AT = SYSDATE,
                   UPDATED_BY = P_UPDATED_BY
             WHERE ID = P_ID;


            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'SLIP TYPE UPDATED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END UPDATE_OPERATION;
      ELSIF P_ACTION_TYPE = 'D'
      THEN
         IF P_ID IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;

        <<DELETE_OPERATION>>
         BEGIN
            UPDATE L_COLLECTION_SLIP_TYPES
               SET STATUS = 'D'
             WHERE ID = P_ID;

            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'SLIP TYPE DELETED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END DELETE_OPERATION;
      ELSE
         SET_ERROR (99, 'INVALID ACTION');
         RETURN;
      END IF;
   EXCEPTION
      WHEN OTHERS
      THEN
         SET_ERROR (99, 'PLEASE TRY AGAIN LATER');
         RETURN;
   END COLLECTION_SLIP_TYPES_CUD;



   PROCEDURE CPA_VESSEL_TYPE_CUD (
      P_ACTION_TYPE      IN     VARCHAR2,
      P_ID               IN OUT MDA.L_CPA_VESSEL_TYPE.ID%TYPE,
      P_NAME             IN     MDA.L_CPA_VESSEL_TYPE.NAME%TYPE,
      P_CREATED_BY       IN     MDA.L_CPA_VESSEL_TYPE.CREATED_BY%TYPE,
      P_UPDATED_BY       IN     MDA.L_CPA_VESSEL_TYPE.UPDATED_BY%TYPE,
      P_STATUS           IN     MDA.L_CPA_VESSEL_TYPE.STATUS%TYPE,
      O_STATUS_CODE         OUT NUMBER,
      O_STATUS_MESSAGE      OUT VARCHAR2)
   IS
      V_GEN_ID       NUMBER;
      V_EXIST_DATA   NUMBER;

      PROCEDURE SET_ERROR (P_ERROR_CODE      IN NUMBER,
                           P_ERROR_MESSAGE   IN VARCHAR2)
      IS
      BEGIN
         O_STATUS_CODE := P_ERROR_CODE;
         O_STATUS_MESSAGE := P_ERROR_MESSAGE;
      END SET_ERROR;
   BEGIN
      IF P_ACTION_TYPE = 'I'
      THEN
         IF P_NAME IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;


         V_GEN_ID := MDA_CORE_FUNC.GENERATE_ID;

         SELECT COUNT (NAME)
           INTO V_EXIST_DATA
           FROM L_CPA_VESSEL_TYPE lcvt
          WHERE UPPER (NAME) = UPPER (P_NAME) AND STATUS NOT IN ('D', 'R');

         IF V_EXIST_DATA > 0
         THEN
            SET_ERROR (99, 'THIS CPA VESSEL TYPE ALREADY EXIST');

            RETURN;
         END IF;

        <<INSERT_OPERATION>>
         BEGIN
            INSERT INTO L_CPA_VESSEL_TYPE (ID,
                                           NAME,
                                           STATUS,
                                           CREATED_AT,
                                           CREATED_BY)
                 VALUES (V_GEN_ID,
                         UPPER (P_NAME),
                         P_STATUS,
                         SYSDATE,
                         P_CREATED_BY);

            P_ID := V_GEN_ID;
            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'CPA VESSEL TYPE CREATED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END INSERT_OPERATION;
      ELSIF P_ACTION_TYPE = 'U'
      THEN
         IF P_NAME IS NULL OR P_ID IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;

         SELECT COUNT (NAME)
           INTO V_EXIST_DATA
           FROM L_CPA_VESSEL_TYPE lcvt
          WHERE     UPPER (NAME) = UPPER (P_NAME)
                AND STATUS NOT IN ('D', 'R')
                AND ID <> P_ID;

         IF V_EXIST_DATA > 0
         THEN
            SET_ERROR (99, 'THIS CPA VESSEL TYPE ALREADY EXIST');

            RETURN;
         END IF;


        <<UPDATE_OPERATION>>
         BEGIN
            UPDATE L_CPA_VESSEL_TYPE
               SET NAME = UPPER (P_NAME),
                   STATUS = P_STATUS,
                   UPDATED_AT = SYSDATE,
                   UPDATED_BY = P_UPDATED_BY
             WHERE ID = P_ID;


            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'CPA VESSEL TYPE UPDATED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END UPDATE_OPERATION;
      ELSIF P_ACTION_TYPE = 'D'
      THEN
         IF P_ID IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;

        <<DELETE_OPERATION>>
         BEGIN
            UPDATE L_CPA_VESSEL_TYPE
               SET STATUS = 'D'
             WHERE ID = P_ID;

            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'CPA VESSEL TYPE DELETED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END DELETE_OPERATION;
      ELSE
         SET_ERROR (99, 'INVALID ACTION');
         RETURN;
      END IF;
   EXCEPTION
      WHEN OTHERS
      THEN
         SET_ERROR (99, 'PLEASE TRY AGAIN LATER');
         RETURN;
   END CPA_VESSEL_TYPE_CUD;



   PROCEDURE PILOTAGE_SCHEDULE_TYPES_CUD (
      P_ACTION_TYPE      IN     VARCHAR2,
      P_ID               IN OUT MDA.L_PILOTAGE_SCHEDULE_TYPES.ID%TYPE,
      P_NAME             IN     MDA.L_PILOTAGE_SCHEDULE_TYPES.NAME%TYPE,
      P_DESCRIPTION      IN     MDA.L_PILOTAGE_SCHEDULE_TYPES.DESCRIPTION%TYPE,
      P_CREATED_BY       IN     MDA.L_PILOTAGE_SCHEDULE_TYPES.CREATED_BY%TYPE,
      P_UPDATED_BY       IN     MDA.L_PILOTAGE_SCHEDULE_TYPES.UPDATED_BY%TYPE,
      P_STATUS           IN     MDA.L_PILOTAGE_SCHEDULE_TYPES.STATUS%TYPE,
      P_START_TIME       IN     MDA.L_PILOTAGE_SCHEDULE_TYPES.START_TIME%TYPE,
      P_END_TIME         IN     MDA.L_PILOTAGE_SCHEDULE_TYPES.END_TIME%TYPE,
      O_STATUS_CODE         OUT NUMBER,
      O_STATUS_MESSAGE      OUT VARCHAR2)
   IS
      V_GEN_ID       NUMBER;
      V_EXIST_DATA   NUMBER;

      PROCEDURE SET_ERROR (P_ERROR_CODE      IN NUMBER,
                           P_ERROR_MESSAGE   IN VARCHAR2)
      IS
      BEGIN
         O_STATUS_CODE := P_ERROR_CODE;
         O_STATUS_MESSAGE := P_ERROR_MESSAGE;
      END SET_ERROR;
   BEGIN
      IF P_ACTION_TYPE = 'I'
      THEN
         IF P_NAME IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;


         V_GEN_ID := MDA_CORE_FUNC.GENERATE_ID;

         SELECT COUNT (NAME)
           INTO V_EXIST_DATA
           FROM L_PILOTAGE_SCHEDULE_TYPES lpst
          WHERE UPPER (NAME) = UPPER (P_NAME) AND STATUS NOT IN ('D', 'R');

         IF V_EXIST_DATA > 0
         THEN
            SET_ERROR (99, 'THIS PILOTAGE SCHEDULE TYPE ALREADY EXIST');

            RETURN;
         END IF;

        <<INSERT_OPERATION>>
         BEGIN
            INSERT INTO L_PILOTAGE_SCHEDULE_TYPES (ID,
                                                   NAME,
                                                   DESCRIPTION,
                                                   STATUS,
                                                   START_TIME,
                                                   END_TIME,
                                                   CREATED_AT,
                                                   CREATED_BY)
                 VALUES (V_GEN_ID,
                         UPPER (P_NAME),
                         P_DESCRIPTION,
                         P_STATUS,
                         P_START_TIME,
                         P_END_TIME,
                         SYSDATE,
                         P_CREATED_BY);

            P_ID := V_GEN_ID;
            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'PILOTAGE SCHEDULE TYPE CREATED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END INSERT_OPERATION;
      ELSIF P_ACTION_TYPE = 'U'
      THEN
         IF P_NAME IS NULL OR P_ID IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;

         SELECT COUNT (NAME)
           INTO V_EXIST_DATA
           FROM L_PILOTAGE_SCHEDULE_TYPES lpst
          WHERE     UPPER (NAME) = UPPER (P_NAME)
                AND STATUS NOT IN ('D', 'R')
                AND ID <> P_ID;

         IF V_EXIST_DATA > 0
         THEN
            SET_ERROR (99, 'THIS PILOTAGE SCHEDULE TYPE ALREADY EXIST');

            RETURN;
         END IF;


        <<UPDATE_OPERATION>>
         BEGIN
            UPDATE L_PILOTAGE_SCHEDULE_TYPES
               SET NAME = UPPER (P_NAME),
                   DESCRIPTION = P_DESCRIPTION,
                   STATUS = P_STATUS,
                   START_TIME = P_START_TIME,
                   END_TIME = P_END_TIME,
                   UPDATED_AT = SYSDATE,
                   UPDATED_BY = P_UPDATED_BY
             WHERE ID = P_ID;


            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'PILOTAGE SCHEDULE TYPE UPDATED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END UPDATE_OPERATION;
      ELSIF P_ACTION_TYPE = 'D'
      THEN
         IF P_ID IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;

        <<DELETE_OPERATION>>
         BEGIN
            UPDATE L_PILOTAGE_SCHEDULE_TYPES
               SET STATUS = 'D'
             WHERE ID = P_ID;

            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'PILOTAGE SCHEDULE TYPE DELETED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END DELETE_OPERATION;
      ELSE
         SET_ERROR (99, 'INVALID ACTION');
         RETURN;
      END IF;
   EXCEPTION
      WHEN OTHERS
      THEN
         SET_ERROR (99, 'PLEASE TRY AGAIN LATER');
         RETURN;
   END PILOTAGE_SCHEDULE_TYPES_CUD;



   PROCEDURE PILOTAGE_TYPES_CUD (
      P_ACTION_TYPE      IN     VARCHAR2,
      P_ID               IN OUT MDA.L_PILOTAGE_TYPES.ID%TYPE,
      P_NAME             IN     MDA.L_PILOTAGE_TYPES.NAME%TYPE,
      P_DESCRIPTION      IN     MDA.L_PILOTAGE_TYPES.DESCRIPTION%TYPE,
      P_CREATED_BY       IN     MDA.L_PILOTAGE_TYPES.CREATED_BY%TYPE,
      P_UPDATED_BY       IN     MDA.L_PILOTAGE_TYPES.UPDATED_BY%TYPE,
      P_STATUS           IN     MDA.L_PILOTAGE_TYPES.STATUS%TYPE,
      O_STATUS_CODE         OUT NUMBER,
      O_STATUS_MESSAGE      OUT VARCHAR2)
   IS
      V_GEN_ID       NUMBER;
      V_EXIST_DATA   NUMBER;

      PROCEDURE SET_ERROR (P_ERROR_CODE      IN NUMBER,
                           P_ERROR_MESSAGE   IN VARCHAR2)
      IS
      BEGIN
         O_STATUS_CODE := P_ERROR_CODE;
         O_STATUS_MESSAGE := P_ERROR_MESSAGE;
      END SET_ERROR;
   BEGIN
      IF P_ACTION_TYPE = 'I'
      THEN
         IF P_NAME IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;


         V_GEN_ID := MDA_CORE_FUNC.GENERATE_ID;

         SELECT COUNT (NAME)
           INTO V_EXIST_DATA
           FROM L_PILOTAGE_TYPES lpt
          WHERE UPPER (NAME) = UPPER (P_NAME) AND STATUS NOT IN ('D', 'R');

         IF V_EXIST_DATA > 0
         THEN
            SET_ERROR (99, 'THIS PILOTAGE TYPE ALREADY EXIST');

            RETURN;
         END IF;

        <<INSERT_OPERATION>>
         BEGIN
            INSERT INTO L_PILOTAGE_TYPES (ID,
                                          NAME,
                                          DESCRIPTION,
                                          STATUS,
                                          CREATED_AT,
                                          CREATED_BY)
                 VALUES (V_GEN_ID,
                         UPPER (P_NAME),
                         P_DESCRIPTION,
                         P_STATUS,
                         SYSDATE,
                         P_CREATED_BY);

            P_ID := V_GEN_ID;
            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'PILOTAGE TYPE CREATED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END INSERT_OPERATION;
      ELSIF P_ACTION_TYPE = 'U'
      THEN
         IF P_NAME IS NULL OR P_ID IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;

         SELECT COUNT (NAME)
           INTO V_EXIST_DATA
           FROM L_PILOTAGE_TYPES lpt
          WHERE     UPPER (NAME) = UPPER (P_NAME)
                AND STATUS NOT IN ('D', 'R')
                AND ID <> P_ID;

         IF V_EXIST_DATA > 0
         THEN
            SET_ERROR (99, 'THIS PILOTAGE TYPE ALREADY EXIST');

            RETURN;
         END IF;


        <<UPDATE_OPERATION>>
         BEGIN
            UPDATE L_PILOTAGE_TYPES
               SET NAME = UPPER (P_NAME),
                   DESCRIPTION = P_DESCRIPTION,
                   STATUS = P_STATUS,
                   UPDATED_AT = SYSDATE,
                   UPDATED_BY = P_UPDATED_BY
             WHERE ID = P_ID;


            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'PILOTAGE TYPE UPDATED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END UPDATE_OPERATION;
      ELSIF P_ACTION_TYPE = 'D'
      THEN
         IF P_ID IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;

        <<DELETE_OPERATION>>
         BEGIN
            UPDATE L_PILOTAGE_TYPES
               SET STATUS = 'D'
             WHERE ID = P_ID;

            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'PILOTAGE TYPE DELETED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END DELETE_OPERATION;
      ELSE
         SET_ERROR (99, 'INVALID ACTION');
         RETURN;
      END IF;
   EXCEPTION
      WHEN OTHERS
      THEN
         SET_ERROR (99, 'PLEASE TRY AGAIN LATER');
         RETURN;
   END PILOTAGE_TYPES_CUD;



   PROCEDURE PILOTAGE_WORK_LOCATIONS_CUD (
      P_ACTION_TYPE      IN     VARCHAR2,
      P_ID               IN OUT MDA.L_PILOTAGE_WORK_LOCATIONS.ID%TYPE,
      P_NAME             IN     MDA.L_PILOTAGE_WORK_LOCATIONS.NAME%TYPE,
      P_DESCRIPTION      IN     MDA.L_PILOTAGE_WORK_LOCATIONS.DESCRIPTION%TYPE,
      P_CREATED_BY       IN     MDA.L_PILOTAGE_WORK_LOCATIONS.CREATED_BY%TYPE,
      P_UPDATED_BY       IN     MDA.L_PILOTAGE_WORK_LOCATIONS.UPDATED_BY%TYPE,
      P_STATUS           IN     MDA.L_PILOTAGE_WORK_LOCATIONS.STATUS%TYPE,
      O_STATUS_CODE         OUT NUMBER,
      O_STATUS_MESSAGE      OUT VARCHAR2)
   IS
      V_GEN_ID       NUMBER;
      V_EXIST_DATA   NUMBER;

      PROCEDURE SET_ERROR (P_ERROR_CODE      IN NUMBER,
                           P_ERROR_MESSAGE   IN VARCHAR2)
      IS
      BEGIN
         O_STATUS_CODE := P_ERROR_CODE;
         O_STATUS_MESSAGE := P_ERROR_MESSAGE;
      END SET_ERROR;
   BEGIN
      IF P_ACTION_TYPE = 'I'
      THEN
         IF P_NAME IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;


         V_GEN_ID := MDA_CORE_FUNC.GENERATE_ID;

         SELECT COUNT (NAME)
           INTO V_EXIST_DATA
           FROM L_PILOTAGE_WORK_LOCATIONS lpwlt
          WHERE UPPER (NAME) = UPPER (P_NAME) AND STATUS NOT IN ('D', 'R');

         IF V_EXIST_DATA > 0
         THEN
            SET_ERROR (99, 'THIS PILOTAGE WORK LOCATION ALREADY EXIST');

            RETURN;
         END IF;

        <<INSERT_OPERATION>>
         BEGIN
            INSERT INTO L_PILOTAGE_WORK_LOCATIONS (ID,
                                                   NAME,
                                                   DESCRIPTION,
                                                   STATUS,
                                                   CREATED_AT,
                                                   CREATED_BY)
                 VALUES (V_GEN_ID,
                         UPPER (P_NAME),
                         P_DESCRIPTION,
                         P_STATUS,
                         SYSDATE,
                         P_CREATED_BY);

            P_ID := V_GEN_ID;
            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'PILOTAGE WORK LOCATION CREATED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END INSERT_OPERATION;
      ELSIF P_ACTION_TYPE = 'U'
      THEN
         IF P_NAME IS NULL OR P_ID IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;

         SELECT COUNT (NAME)
           INTO V_EXIST_DATA
           FROM L_PILOTAGE_WORK_LOCATIONS lpwlt
          WHERE     UPPER (NAME) = UPPER (P_NAME)
                AND STATUS NOT IN ('D', 'R')
                AND ID <> P_ID;

         IF V_EXIST_DATA > 0
         THEN
            SET_ERROR (99, 'THIS PILOTAGE WORK LOCATION ALREADY EXIST');

            RETURN;
         END IF;


        <<UPDATE_OPERATION>>
         BEGIN
            UPDATE L_PILOTAGE_WORK_LOCATIONS
               SET NAME = UPPER (P_NAME),
                   DESCRIPTION = P_DESCRIPTION,
                   STATUS = P_STATUS,
                   UPDATED_AT = SYSDATE,
                   UPDATED_BY = P_UPDATED_BY
             WHERE ID = P_ID;


            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'PILOTAGE WORK LOCATION UPDATED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END UPDATE_OPERATION;
      ELSIF P_ACTION_TYPE = 'D'
      THEN
         IF P_ID IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;

        <<DELETE_OPERATION>>
         BEGIN
            UPDATE L_PILOTAGE_WORK_LOCATIONS
               SET STATUS = 'D'
             WHERE ID = P_ID;

            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'PILOTAGE WORK LOCATION DELETED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END DELETE_OPERATION;
      ELSE
         SET_ERROR (99, 'INVALID ACTION');
         RETURN;
      END IF;
   EXCEPTION
      WHEN OTHERS
      THEN
         SET_ERROR (99, 'PLEASE TRY AGAIN LATER');
         RETURN;
   END PILOTAGE_WORK_LOCATIONS_CUD;



   PROCEDURE TUG_TYPES_CUD (
      P_ACTION_TYPE      IN     VARCHAR2,
      P_ID               IN OUT MDA.L_TUG_TYPES.ID%TYPE,
      P_NAME             IN     MDA.L_TUG_TYPES.NAME%TYPE,
      P_CREATED_BY       IN     MDA.L_TUG_TYPES.CREATED_BY%TYPE,
      P_UPDATED_BY       IN     MDA.L_TUG_TYPES.UPDATED_BY%TYPE,
      P_STATUS           IN     MDA.L_TUG_TYPES.STATUS%TYPE,
      O_STATUS_CODE         OUT NUMBER,
      O_STATUS_MESSAGE      OUT VARCHAR2)
   IS
      V_GEN_ID       NUMBER;
      V_EXIST_DATA   NUMBER;

      PROCEDURE SET_ERROR (P_ERROR_CODE      IN NUMBER,
                           P_ERROR_MESSAGE   IN VARCHAR2)
      IS
      BEGIN
         O_STATUS_CODE := P_ERROR_CODE;
         O_STATUS_MESSAGE := P_ERROR_MESSAGE;
      END SET_ERROR;
   BEGIN
      IF P_ACTION_TYPE = 'I'
      THEN
         IF P_NAME IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;


         V_GEN_ID := MDA_CORE_FUNC.GENERATE_ID;

         SELECT COUNT (NAME)
           INTO V_EXIST_DATA
           FROM L_TUG_TYPES ltt
          WHERE UPPER (NAME) = UPPER (P_NAME) AND STATUS NOT IN ('D', 'R');

         IF V_EXIST_DATA > 0
         THEN
            SET_ERROR (99, 'THIS TUG TYPE ALREADY EXIST');

            RETURN;
         END IF;

        <<INSERT_OPERATION>>
         BEGIN
            INSERT INTO L_TUG_TYPES (ID,
                                     NAME,
                                     STATUS,
                                     CREATED_AT,
                                     CREATED_BY)
                 VALUES (V_GEN_ID,
                         UPPER (P_NAME),
                         P_STATUS,
                         SYSDATE,
                         P_CREATED_BY);

            P_ID := V_GEN_ID;
            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'TUG TYPE CREATED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END INSERT_OPERATION;
      ELSIF P_ACTION_TYPE = 'U'
      THEN
         IF P_NAME IS NULL OR P_ID IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;

         SELECT COUNT (NAME)
           INTO V_EXIST_DATA
           FROM L_TUG_TYPES ltt
          WHERE     UPPER (NAME) = UPPER (P_NAME)
                AND STATUS NOT IN ('D', 'R')
                AND ID <> P_ID;

         IF V_EXIST_DATA > 0
         THEN
            SET_ERROR (99, 'THIS TUG TYPE ALREADY EXIST');

            RETURN;
         END IF;


        <<UPDATE_OPERATION>>
         BEGIN
            UPDATE L_TUG_TYPES
               SET NAME = UPPER (P_NAME),
                   STATUS = P_STATUS,
                   UPDATED_AT = SYSDATE,
                   UPDATED_BY = P_UPDATED_BY
             WHERE ID = P_ID;


            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'TUG TYPE UPDATED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END UPDATE_OPERATION;
      ELSIF P_ACTION_TYPE = 'D'
      THEN
         IF P_ID IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;

        <<DELETE_OPERATION>>
         BEGIN
            UPDATE L_TUG_TYPES
               SET STATUS = 'D'
             WHERE ID = P_ID;

            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'TUG TYPE DELETED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END DELETE_OPERATION;
      ELSE
         SET_ERROR (99, 'INVALID ACTION');
         RETURN;
      END IF;
   EXCEPTION
      WHEN OTHERS
      THEN
         SET_ERROR (99, 'PLEASE TRY AGAIN LATER');
         RETURN;
   END TUG_TYPES_CUD;



   PROCEDURE VESSEL_CONDITIONS_CUD (
      P_ACTION_TYPE      IN     VARCHAR2,
      P_ID               IN OUT MDA.L_VESSEL_CONDITIONS.ID%TYPE,
      P_TITLE            IN     MDA.L_VESSEL_CONDITIONS.TITLE%TYPE,
      P_CREATED_BY       IN     MDA.L_VESSEL_CONDITIONS.CREATED_BY%TYPE,
      P_UPDATED_BY       IN     MDA.L_VESSEL_CONDITIONS.UPDATED_BY%TYPE,
      P_STATUS           IN     MDA.L_VESSEL_CONDITIONS.STATUS%TYPE,
      P_VALUE_TYPE       IN     MDA.L_VESSEL_CONDITIONS.VALUE_TYPE%TYPE,
      O_STATUS_CODE         OUT NUMBER,
      O_STATUS_MESSAGE      OUT VARCHAR2)
   IS
      V_GEN_ID       NUMBER;
      V_EXIST_DATA   NUMBER;

      PROCEDURE SET_ERROR (P_ERROR_CODE      IN NUMBER,
                           P_ERROR_MESSAGE   IN VARCHAR2)
      IS
      BEGIN
         O_STATUS_CODE := P_ERROR_CODE;
         O_STATUS_MESSAGE := P_ERROR_MESSAGE;
      END SET_ERROR;
   BEGIN
      IF P_ACTION_TYPE = 'I'
      THEN
         IF P_TITLE IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;


         V_GEN_ID := MDA_CORE_FUNC.GENERATE_ID;

         SELECT COUNT (TITLE)
           INTO V_EXIST_DATA
           FROM L_VESSEL_CONDITIONS lvc
          WHERE UPPER (TITLE) = UPPER (P_TITLE) AND STATUS NOT IN ('D', 'R');

         IF V_EXIST_DATA > 0
         THEN
            SET_ERROR (99, 'THIS VESSEL CONDITION ALREADY EXIST');

            RETURN;
         END IF;

        <<INSERT_OPERATION>>
         BEGIN
            INSERT INTO L_VESSEL_CONDITIONS (ID,
                                             TITLE,
                                             VALUE_TYPE,
                                             STATUS,
                                             CREATED_AT,
                                             CREATED_BY)
                 VALUES (V_GEN_ID,
                         UPPER (P_TITLE),
                         P_VALUE_TYPE,
                         P_STATUS,
                         SYSDATE,
                         P_CREATED_BY);

            P_ID := V_GEN_ID;
            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'VESSEL CONDITION CREATED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END INSERT_OPERATION;
      ELSIF P_ACTION_TYPE = 'U'
      THEN
         IF P_TITLE IS NULL OR P_ID IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;

         SELECT COUNT (P_TITLE)
           INTO V_EXIST_DATA
           FROM L_VESSEL_CONDITIONS lvc
          WHERE     UPPER (P_TITLE) = UPPER (P_TITLE)
                AND STATUS NOT IN ('D', 'R')
                AND ID <> P_ID;

         IF V_EXIST_DATA > 0
         THEN
            SET_ERROR (99, 'THIS VESSEL CONDITION ALREADY EXIST');

            RETURN;
         END IF;


        <<UPDATE_OPERATION>>
         BEGIN
            UPDATE L_VESSEL_CONDITIONS
               SET TITLE = UPPER (P_TITLE),
                   VALUE_TYPE = P_VALUE_TYPE,
                   STATUS = P_STATUS,
                   UPDATED_AT = SYSDATE,
                   UPDATED_BY = P_UPDATED_BY
             WHERE ID = P_ID;


            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'VESSEL CONDITION UPDATED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END UPDATE_OPERATION;
      ELSIF P_ACTION_TYPE = 'D'
      THEN
         IF P_ID IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;

        <<DELETE_OPERATION>>
         BEGIN
            UPDATE L_VESSEL_CONDITIONS
               SET STATUS = 'D'
             WHERE ID = P_ID;

            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'VESSEL CONDITION DELETED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END DELETE_OPERATION;
      ELSE
         SET_ERROR (99, 'INVALID ACTION');
         RETURN;
      END IF;
   EXCEPTION
      WHEN OTHERS
      THEN
         SET_ERROR (99, 'PLEASE TRY AGAIN LATER');
         RETURN;
   END VESSEL_CONDITIONS_CUD;



   PROCEDURE VESSEL_WORKING_TYPES_CUD (
      P_ACTION_TYPE      IN     VARCHAR2,
      P_ID               IN OUT MDA.L_VESSEL_WORKING_TYPES.ID%TYPE,
      P_NAME             IN     MDA.L_VESSEL_WORKING_TYPES.NAME%TYPE,
      P_DESCRIPTION      IN     MDA.L_VESSEL_WORKING_TYPES.DESCRIPTION%TYPE,
      P_CREATED_BY       IN     MDA.L_VESSEL_WORKING_TYPES.CREATED_BY%TYPE,
      P_UPDATED_BY       IN     MDA.L_VESSEL_WORKING_TYPES.UPDATED_BY%TYPE,
      P_STATUS           IN     MDA.L_VESSEL_WORKING_TYPES.STATUS%TYPE,
      O_STATUS_CODE         OUT NUMBER,
      O_STATUS_MESSAGE      OUT VARCHAR2)
   IS
      V_GEN_ID       NUMBER;
      V_EXIST_DATA   NUMBER;

      PROCEDURE SET_ERROR (P_ERROR_CODE      IN NUMBER,
                           P_ERROR_MESSAGE   IN VARCHAR2)
      IS
      BEGIN
         O_STATUS_CODE := P_ERROR_CODE;
         O_STATUS_MESSAGE := P_ERROR_MESSAGE;
      END SET_ERROR;
   BEGIN
      IF P_ACTION_TYPE = 'I'
      THEN
         IF P_NAME IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;


         V_GEN_ID := MDA_CORE_FUNC.GENERATE_ID;

         SELECT COUNT (NAME)
           INTO V_EXIST_DATA
           FROM L_VESSEL_WORKING_TYPES lvwt
          WHERE UPPER (NAME) = UPPER (P_NAME) AND STATUS NOT IN ('D', 'R');

         IF V_EXIST_DATA > 0
         THEN
            SET_ERROR (99, 'THIS VESSEL WORKING TYPE ALREADY EXIST');

            RETURN;
         END IF;

        <<INSERT_OPERATION>>
         BEGIN
            INSERT INTO L_VESSEL_WORKING_TYPES (ID,
                                                NAME,
                                                DESCRIPTION,
                                                STATUS,
                                                CREATED_AT,
                                                CREATED_BY)
                 VALUES (V_GEN_ID,
                         UPPER (P_NAME),
                         P_DESCRIPTION,
                         P_STATUS,
                         SYSDATE,
                         P_CREATED_BY);

            P_ID := V_GEN_ID;
            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'VESSEL WORKING TYPE CREATED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END INSERT_OPERATION;
      ELSIF P_ACTION_TYPE = 'U'
      THEN
         IF P_NAME IS NULL OR P_ID IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;

         SELECT COUNT (NAME)
           INTO V_EXIST_DATA
           FROM L_VESSEL_WORKING_TYPES lvwt
          WHERE     UPPER (NAME) = UPPER (P_NAME)
                AND STATUS NOT IN ('D', 'R')
                AND ID <> P_ID;

         IF V_EXIST_DATA > 0
         THEN
            SET_ERROR (99, 'THIS VESSEL WORKING TYPE ALREADY EXIST');

            RETURN;
         END IF;


        <<UPDATE_OPERATION>>
         BEGIN
            UPDATE L_VESSEL_WORKING_TYPES
               SET NAME = UPPER (P_NAME),
                   DESCRIPTION = P_DESCRIPTION,
                   STATUS = P_STATUS,
                   UPDATED_AT = SYSDATE,
                   UPDATED_BY = P_UPDATED_BY
             WHERE ID = P_ID;


            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'VESSEL WORKING TYPE UPDATED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END UPDATE_OPERATION;
      ELSIF P_ACTION_TYPE = 'D'
      THEN
         IF P_ID IS NULL
         THEN
            SET_ERROR (99, 'REQUIRED DATA CAN NOT BE NULL !');

            RETURN;
         END IF;

        <<DELETE_OPERATION>>
         BEGIN
            UPDATE L_VESSEL_WORKING_TYPES
               SET STATUS = 'D'
             WHERE ID = P_ID;

            O_STATUS_CODE := 1;
            O_STATUS_MESSAGE := 'VESSEL WORKING TYPE DELETED SUCCESSFULLY';
            COMMIT;
            RETURN;
         EXCEPTION
            WHEN OTHERS
            THEN
               SET_ERROR (SQLCODE, 'EXCEPTION ! ' || SQLERRM);
               RETURN;
         END DELETE_OPERATION;
      ELSE
         SET_ERROR (99, 'INVALID ACTION');
         RETURN;
      END IF;
   EXCEPTION
      WHEN OTHERS
      THEN
         SET_ERROR (99, 'PLEASE TRY AGAIN LATER');
         RETURN;
   END VESSEL_WORKING_TYPES_CUD;
END MDA_CORE_PROCE;
/

