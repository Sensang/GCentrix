<?php
pg_query($Connection,
"SET statement_timeout = 0;
 SET lock_timeout = 0;
 SET client_encoding = 'UTF8';
 SET standard_conforming_strings = on;
 SET check_function_bodies = false;
 SET client_min_messages = warning;

 SET statement_timeout = 0;
 SET lock_timeout = 0;
 SET client_encoding = 'UTF8';
 SET standard_conforming_strings = on;
 SET check_function_bodies = false;
 SET client_min_messages = warning;
 SET default_with_oids = false;

 CREATE TABLE \"Caption\" (
     \"ID\" bigint NOT NULL,
     \"Caption\" character(50) DEFAULT ''::\"bpchar\" NOT NULL,
     \"German\" character(50) DEFAULT ''::\"bpchar\" NOT NULL
 );

 CREATE SEQUENCE \"Caption_ID_seq\"
     START WITH 1
     INCREMENT BY 1
     NO MINVALUE
     NO MAXVALUE
     CACHE 1;

 ALTER SEQUENCE \"Caption_ID_seq\" OWNED BY \"Caption\".\"ID\";

 CREATE TABLE \"Field\" (
     \"ID\" bigint NOT NULL,
     \"Name\" character(50) DEFAULT ''::\"bpchar\" NOT NULL,
     \"Field Type\" bigint DEFAULT 0 NOT NULL,
     \"Table\" bigint DEFAULT 0 NOT NULL,
     \"Caption\" bigint DEFAULT 0 NOT NULL
 );

 CREATE TABLE \"Field Property\" (
     \"ID\" bigint NOT NULL,
     \"Name\" character(50) DEFAULT ''::\"bpchar\" NOT NULL,
     \"Value\" bigint DEFAULT 0 NOT NULL
 );

 CREATE SEQUENCE \"Field Property_ID_seq\"
     START WITH 1
     INCREMENT BY 1
     NO MINVALUE
     NO MAXVALUE
     CACHE 1;

 ALTER SEQUENCE \"Field Property_ID_seq\" OWNED BY \"Field Property\".\"ID\";

 CREATE TABLE \"Field Type\" (
     \"ID\" bigint NOT NULL,
     \"Name\" character(50) DEFAULT ''::\"bpchar\" NOT NULL,
     \"Value\" character(50) DEFAULT ''::\"bpchar\" NOT NULL
 );

 CREATE SEQUENCE \"Field Type_ID_seq\"
     START WITH 1
     INCREMENT BY 1
     NO MINVALUE
     NO MAXVALUE
     CACHE 1;

 ALTER SEQUENCE \"Field Type_ID_seq\" OWNED BY \"Field Type\".\"ID\";

 CREATE SEQUENCE \"Field_ID_seq\"
     START WITH 1
     INCREMENT BY 1
     NO MINVALUE
     NO MAXVALUE
     CACHE 1;

 ALTER SEQUENCE \"Field_ID_seq\" OWNED BY \"Field\".\"ID\";

 CREATE TABLE \"Language\" (
     \"ID\" bigint NOT NULL,
     \"Caption\" bigint DEFAULT 0 NOT NULL
 );

 CREATE SEQUENCE \"Language_ID_seq\"
     START WITH 1
     INCREMENT BY 1
     NO MINVALUE
     NO MAXVALUE
     CACHE 1;

 ALTER SEQUENCE \"Language_ID_seq\" OWNED BY \"Language\".\"ID\";

 CREATE TABLE \"Setup\" (
     \"ID\" bigint NOT NULL,
     \"Default Language\" bigint DEFAULT 0 NOT NULL
 );

 CREATE SEQUENCE \"Setup_ID_seq\"
     START WITH 1
     INCREMENT BY 1
     NO MINVALUE
     NO MAXVALUE
     CACHE 1;

 ALTER SEQUENCE \"Setup_ID_seq\" OWNED BY \"Setup\".\"ID\";

 CREATE TABLE \"Table\" (
     \"ID\" bigint NOT NULL,
     \"Name\" character(50) DEFAULT ''::\"bpchar\" NOT NULL,
     \"Caption\" bigint DEFAULT 0 NOT NULL,
     \"System Table\" boolean NOT NULL,
     \"Last Updated\" timestamp with time zone DEFAULT \"now\"() NOT NULL
 );

 CREATE TABLE \"Table Relation\" (
     \"ID\" bigint NOT NULL,
     \"From Field\" bigint DEFAULT 0 NOT NULL,
     \"From Table\" bigint DEFAULT 0 NOT NULL,
     \"To Field\" bigint DEFAULT 0 NOT NULL,
     \"To Table\" bigint DEFAULT 0 NOT NULL
 );

 CREATE SEQUENCE \"Table Relation_ID_seq\"
     START WITH 1
     INCREMENT BY 1
     NO MINVALUE
     NO MAXVALUE
     CACHE 1;

 ALTER SEQUENCE \"Table Relation_ID_seq\" OWNED BY \"Table Relation\".\"ID\";

 CREATE SEQUENCE \"Table_ID_seq\"
     START WITH 1
     INCREMENT BY 1
     NO MINVALUE
     NO MAXVALUE
     CACHE 1;

 ALTER SEQUENCE \"Table_ID_seq\" OWNED BY \"Table\".\"ID\";

 ALTER TABLE ONLY \"Caption\" ALTER COLUMN \"ID\" SET DEFAULT \"nextval\"('\"Caption_ID_seq\"'::\"regclass\");

 ALTER TABLE ONLY \"Field\" ALTER COLUMN \"ID\" SET DEFAULT \"nextval\"('\"Field_ID_seq\"'::\"regclass\");

 ALTER TABLE ONLY \"Field Property\" ALTER COLUMN \"ID\" SET DEFAULT \"nextval\"('\"Field Property_ID_seq\"'::\"regclass\");

 ALTER TABLE ONLY \"Field Type\" ALTER COLUMN \"ID\" SET DEFAULT \"nextval\"('\"Field Type_ID_seq\"'::\"regclass\");

 ALTER TABLE ONLY \"Language\" ALTER COLUMN \"ID\" SET DEFAULT \"nextval\"('\"Language_ID_seq\"'::\"regclass\");

 ALTER TABLE ONLY \"Setup\" ALTER COLUMN \"ID\" SET DEFAULT \"nextval\"('\"Setup_ID_seq\"'::\"regclass\");

 ALTER TABLE ONLY \"Table\" ALTER COLUMN \"ID\" SET DEFAULT \"nextval\"('\"Table_ID_seq\"'::\"regclass\");

 ALTER TABLE ONLY \"Table Relation\" ALTER COLUMN \"ID\" SET DEFAULT \"nextval\"('\"Table Relation_ID_seq\"'::\"regclass\");

 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000001, 'Table                                             ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000002, 'Field                                             ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000003, 'Field Type                                        ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000004, 'Field Property                                    ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000005, 'Table Relation                                    ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000006, 'Caption                                           ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000007, 'Setup                                             ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000008, 'Language                                          ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000009, 'ID                                                ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000010, 'Name                                              ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000011, 'Caption                                           ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000012, 'System Table                                      ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000013, 'Last Updated                                      ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000014, 'ID                                                ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000015, 'Name                                              ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000016, 'Field Type                                        ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000017, 'Table                                             ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000018, 'Caption                                           ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000019, 'ID                                                ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000020, 'Caption                                           ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000021, 'Value                                             ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000022, 'ID                                                ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000023, 'Name                                              ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000024, 'Value                                             ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000025, 'ID                                                ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000026, 'From Field                                        ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000027, 'From Table                                        ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000028, 'To Field                                          ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000029, 'To Table                                          ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000030, 'ID                                                ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000031, 'Caption                                           ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000032, 'ID                                                ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000033, 'Default Language                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000034, 'ID                                                ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000035, 'Caption                                           ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000036, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000037, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000038, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000039, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000040, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000041, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000042, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000043, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000044, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000045, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000046, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000047, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000048, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000049, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000050, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000051, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000052, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000053, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000054, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000055, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000056, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000057, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000058, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000059, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000060, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000061, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000062, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000063, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000064, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000065, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000066, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000067, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000068, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000069, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000070, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000071, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000072, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000073, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000074, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000075, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000076, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000077, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000078, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000079, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000080, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000081, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000082, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000083, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000084, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000085, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000086, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000087, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000088, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000089, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000090, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000091, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000092, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000093, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000094, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000095, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000096, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000097, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000098, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000099, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000100, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000101, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000102, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000103, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000104, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000105, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000106, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000107, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000108, '                                                  ', '                                                  ');
 INSERT INTO \"Caption\" (\"ID\", \"Caption\", \"German\") VALUES (10000000000109, 'German                                            ', 'Deutsch                                           ');

 SELECT pg_catalog.setval('\"Caption_ID_seq\"', 10000000000109, true);

 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000017, 'ID                                                ', 1, 1000000000005, 10000000000025);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000018, 'From Field                                        ', 5, 1000000000005, 10000000000026);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000019, 'From Table                                        ', 5, 1000000000005, 10000000000027);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000020, 'To Field                                          ', 5, 1000000000005, 10000000000028);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000021, 'To Table                                          ', 5, 1000000000005, 10000000000029);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000022, 'ID                                                ', 1, 1000000000010, 10000000000030);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000024, 'Caption                                           ', 16, 1000000000010, 10000000000031);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000025, 'ID                                                ', 1, 1000000000100, 10000000000032);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000026, 'Default Language                                  ', 5, 1000000000100, 10000000000033);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000027, 'ID                                                ', 1, 1000000000101, 10000000000034);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000028, 'Caption                                           ', 16, 1000000000101, 10000000000035);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000012, 'Name                                              ', 16, 1000000000003, 10000000000020);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000023, 'German                                            ', 13, 1000000000010, 10000000000109);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000001, 'ID                                                ', 1, 1000000000001, 10000000000009);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000002, 'Name                                              ', 13, 1000000000001, 10000000000010);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000003, 'Caption                                           ', 16, 1000000000001, 10000000000011);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000004, 'System Table                                      ', 10, 1000000000001, 10000000000012);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000005, 'Last Updated                                      ', 17, 1000000000001, 10000000000013);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000006, 'ID                                                ', 1, 1000000000002, 10000000000014);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000007, 'Name                                              ', 13, 1000000000002, 10000000000015);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000008, 'Field Type                                        ', 5, 1000000000002, 10000000000016);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000009, 'Table                                             ', 5, 1000000000002, 10000000000017);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000010, 'Caption                                           ', 16, 1000000000002, 10000000000018);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000011, 'ID                                                ', 1, 1000000000003, 10000000000019);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000013, 'Value                                             ', 13, 1000000000003, 10000000000021);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000014, 'ID                                                ', 1, 1000000000004, 10000000000022);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000015, 'Name                                              ', 13, 1000000000004, 10000000000023);
 INSERT INTO \"Field\" (\"ID\", \"Name\", \"Field Type\", \"Table\", \"Caption\") VALUES (2000000000016, 'Value                                             ', 13, 1000000000004, 10000000000024);

 SELECT pg_catalog.setval('\"Field Property_ID_seq\"', 4000000000000, true);

 INSERT INTO \"Field Type\" (\"ID\", \"Name\", \"Value\") VALUES (3000000000001, 'ID                                                ', '1000010000000000000                               ');
 INSERT INTO \"Field Type\" (\"ID\", \"Name\", \"Value\") VALUES (3000000000002, 'Private ID                                        ', '1000010000000000000                               ');
 INSERT INTO \"Field Type\" (\"ID\", \"Name\", \"Value\") VALUES (3000000000003, 'Big Private ID                                    ', '1000010000000000000                               ');
 INSERT INTO \"Field Type\" (\"ID\", \"Name\", \"Value\") VALUES (3000000000004, 'Manual ID                                         ', '1000010000000000000                               ');
 INSERT INTO \"Field Type\" (\"ID\", \"Name\", \"Value\") VALUES (3000000000005, 'Foreign ID                                        ', '1000010000000000000                               ');
 INSERT INTO \"Field Type\" (\"ID\", \"Name\", \"Value\") VALUES (3000000000006, 'Number                                            ', '1000010000000000000                               ');
 INSERT INTO \"Field Type\" (\"ID\", \"Name\", \"Value\") VALUES (3000000000007, 'Big Number                                        ', '1000010000000000000                               ');
 INSERT INTO \"Field Type\" (\"ID\", \"Name\", \"Value\") VALUES (3000000000008, 'Positive Number                                   ', '1000010000000000000                               ');
 INSERT INTO \"Field Type\" (\"ID\", \"Name\", \"Value\") VALUES (3000000000009, 'Big Positive Number                               ', '1000010000000000000                               ');
 INSERT INTO \"Field Type\" (\"ID\", \"Name\", \"Value\") VALUES (3000000000010, 'Boolean                                           ', '1000010000000000000                               ');
 INSERT INTO \"Field Type\" (\"ID\", \"Name\", \"Value\") VALUES (3000000000011, 'Decimal                                           ', '1000010000000000000                               ');
 INSERT INTO \"Field Type\" (\"ID\", \"Name\", \"Value\") VALUES (3000000000012, 'Big Decimal                                       ', '1000010000000000000                               ');
 INSERT INTO \"Field Type\" (\"ID\", \"Name\", \"Value\") VALUES (3000000000013, 'Text                                              ', '1000010000000000000                               ');
 INSERT INTO \"Field Type\" (\"ID\", \"Name\", \"Value\") VALUES (3000000000014, 'Password                                          ', '1000010000000000000                               ');
 INSERT INTO \"Field Type\" (\"ID\", \"Name\", \"Value\") VALUES (3000000000015, 'Code                                              ', '1000010000000000000                               ');
 INSERT INTO \"Field Type\" (\"ID\", \"Name\", \"Value\") VALUES (3000000000016, 'Caption                                           ', '1000010000000000000                               ');
 INSERT INTO \"Field Type\" (\"ID\", \"Name\", \"Value\") VALUES (3000000000017, 'Timestamp                                         ', '1000010000000000000                               ');
 INSERT INTO \"Field Type\" (\"ID\", \"Name\", \"Value\") VALUES (3000000000018, 'Time                                              ', '1000010000000000000                               ');
 INSERT INTO \"Field Type\" (\"ID\", \"Name\", \"Value\") VALUES (3000000000019, 'Date                                              ', '1000010000000000000                               ');

 SELECT pg_catalog.setval('\"Field Type_ID_seq\"', 3000000000020, true);

 SELECT pg_catalog.setval('\"Field_ID_seq\"', 2000000000028, true);

 SELECT pg_catalog.setval('\"Language_ID_seq\"', 101000000000000, true);

 SELECT pg_catalog.setval('\"Setup_ID_seq\"', 100000000000000, true);

 INSERT INTO \"Table\" (\"ID\", \"Name\", \"Caption\", \"System Table\", \"Last Updated\") VALUES (1000000000001, 'Table                                             ', 10000000000001, true, '2014-05-14 22:45:09.276+02');
 INSERT INTO \"Table\" (\"ID\", \"Name\", \"Caption\", \"System Table\", \"Last Updated\") VALUES (1000000000002, 'Field                                             ', 10000000000002, true, '2014-05-14 22:45:09.276+02');
 INSERT INTO \"Table\" (\"ID\", \"Name\", \"Caption\", \"System Table\", \"Last Updated\") VALUES (1000000000003, 'Field Type                                        ', 10000000000003, true, '2014-05-14 22:45:09.276+02');
 INSERT INTO \"Table\" (\"ID\", \"Name\", \"Caption\", \"System Table\", \"Last Updated\") VALUES (1000000000004, 'Field Property                                    ', 10000000000004, true, '2014-05-14 22:45:09.276+02');
 INSERT INTO \"Table\" (\"ID\", \"Name\", \"Caption\", \"System Table\", \"Last Updated\") VALUES (1000000000005, 'Table Relation                                    ', 10000000000005, true, '2014-05-14 22:45:09.276+02');
 INSERT INTO \"Table\" (\"ID\", \"Name\", \"Caption\", \"System Table\", \"Last Updated\") VALUES (1000000000010, 'Caption                                           ', 10000000000006, true, '2014-05-14 22:45:09.276+02');
 INSERT INTO \"Table\" (\"ID\", \"Name\", \"Caption\", \"System Table\", \"Last Updated\") VALUES (1000000000100, 'Setup                                             ', 10000000000007, true, '2014-05-14 22:45:09.276+02');
 INSERT INTO \"Table\" (\"ID\", \"Name\", \"Caption\", \"System Table\", \"Last Updated\") VALUES (1000000000101, 'Language                                          ', 10000000000008, true, '2014-05-14 22:45:09.276+02');

 INSERT INTO \"Table Relation\" (\"ID\", \"From Field\", \"From Table\", \"To Field\", \"To Table\") VALUES (5000000000011, 2000000000026, 1000000000100, 2000000000027, 1000000000100);
 INSERT INTO \"Table Relation\" (\"ID\", \"From Field\", \"From Table\", \"To Field\", \"To Table\") VALUES (5000000000002, 2000000000008, 1000000000002, 2000000000012, 1000000000003);
 INSERT INTO \"Table Relation\" (\"ID\", \"From Field\", \"From Table\", \"To Field\", \"To Table\") VALUES (5000000000003, 2000000000009, 1000000000002, 2000000000002, 1000000000001);
 INSERT INTO \"Table Relation\" (\"ID\", \"From Field\", \"From Table\", \"To Field\", \"To Table\") VALUES (5000000000013, 2000000000028, 1000000000101, 0, 1000000000010);
 INSERT INTO \"Table Relation\" (\"ID\", \"From Field\", \"From Table\", \"To Field\", \"To Table\") VALUES (5000000000012, 2000000000012, 1000000000003, 0, 1000000000010);
 INSERT INTO \"Table Relation\" (\"ID\", \"From Field\", \"From Table\", \"To Field\", \"To Table\") VALUES (5000000000010, 2000000000024, 1000000000010, 0, 1000000000010);
 INSERT INTO \"Table Relation\" (\"ID\", \"From Field\", \"From Table\", \"To Field\", \"To Table\") VALUES (5000000000004, 2000000000010, 1000000000002, 0, 1000000000010);
 INSERT INTO \"Table Relation\" (\"ID\", \"From Field\", \"From Table\", \"To Field\", \"To Table\") VALUES (5000000000001, 2000000000003, 1000000000001, 0, 1000000000010);
 INSERT INTO \"Table Relation\" (\"ID\", \"From Field\", \"From Table\", \"To Field\", \"To Table\") VALUES (5000000000006, 2000000000019, 1000000000005, 2000000000002, 1000000000001);
 INSERT INTO \"Table Relation\" (\"ID\", \"From Field\", \"From Table\", \"To Field\", \"To Table\") VALUES (5000000000005, 2000000000018, 1000000000005, 2000000000007, 1000000000002);
 INSERT INTO \"Table Relation\" (\"ID\", \"From Field\", \"From Table\", \"To Field\", \"To Table\") VALUES (5000000000007, 2000000000020, 1000000000005, 2000000000007, 1000000000002);
 INSERT INTO \"Table Relation\" (\"ID\", \"From Field\", \"From Table\", \"To Field\", \"To Table\") VALUES (5000000000008, 2000000000021, 1000000000005, 2000000000002, 1000000000001);

 SELECT pg_catalog.setval('\"Table Relation_ID_seq\"', 5000000000013, true);

 SELECT pg_catalog.setval('\"Table_ID_seq\"', 1000000000102, true);

 ALTER TABLE ONLY \"Caption\"
     ADD CONSTRAINT \"Caption_pkey\" PRIMARY KEY (\"ID\");

 ALTER TABLE ONLY \"Field Property\"
     ADD CONSTRAINT \"Field Property_pkey\" PRIMARY KEY (\"ID\");

 ALTER TABLE ONLY \"Field Type\"
     ADD CONSTRAINT \"Field Type_pkey\" PRIMARY KEY (\"ID\");

 ALTER TABLE ONLY \"Field\"
     ADD CONSTRAINT \"Field_pkey\" PRIMARY KEY (\"ID\");

 ALTER TABLE ONLY \"Language\"
     ADD CONSTRAINT \"Language_pkey\" PRIMARY KEY (\"ID\");

 ALTER TABLE ONLY \"Setup\"
     ADD CONSTRAINT \"Setup_pkey\" PRIMARY KEY (\"ID\");

 ALTER TABLE ONLY \"Table Relation\"
     ADD CONSTRAINT \"Table Relation_pkey\" PRIMARY KEY (\"ID\");

 ALTER TABLE ONLY \"Table\"
     ADD CONSTRAINT \"Table_pkey\" PRIMARY KEY (\"ID\");");