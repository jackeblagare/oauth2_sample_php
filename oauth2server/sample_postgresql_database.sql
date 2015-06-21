--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: api; Type: SCHEMA; Schema: -; Owner: postgres
--

CREATE SCHEMA api;


ALTER SCHEMA api OWNER TO postgres;

--
-- Name: SCHEMA api; Type: COMMENT; Schema: -; Owner: postgres
--

COMMENT ON SCHEMA api IS 'Application programming interface for BIMS web services';


--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = api, pg_catalog;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: oauth_access_tokens; Type: TABLE; Schema: api; Owner: postgres; Tablespace: 
--

CREATE TABLE oauth_access_tokens (
    access_token character varying(40) NOT NULL,
    client_id character varying(80) NOT NULL,
    user_id character varying(255),
    expires timestamp without time zone NOT NULL,
    scope character varying(2000)
);


ALTER TABLE api.oauth_access_tokens OWNER TO postgres;

--
-- Name: oauth_authorization_codes; Type: TABLE; Schema: api; Owner: postgres; Tablespace: 
--

CREATE TABLE oauth_authorization_codes (
    authorization_code character varying(40) NOT NULL,
    client_id character varying(80) NOT NULL,
    user_id character varying(255),
    redirect_uri character varying(2000),
    expires timestamp without time zone NOT NULL,
    scope character varying(2000),
    access_token character varying(250),
    session_id integer
);


ALTER TABLE api.oauth_authorization_codes OWNER TO postgres;

--
-- Name: oauth_clients; Type: TABLE; Schema: api; Owner: postgres; Tablespace: 
--

CREATE TABLE oauth_clients (
    client_id character varying(80) NOT NULL,
    client_secret character varying(80) NOT NULL,
    redirect_uri character varying(2000) NOT NULL,
    grant_types character varying(80),
    scope character varying(100),
    user_id character varying(80)
);


ALTER TABLE api.oauth_clients OWNER TO postgres;

--
-- Name: oauth_jwt; Type: TABLE; Schema: api; Owner: postgres; Tablespace: 
--

CREATE TABLE oauth_jwt (
    client_id character varying(80) NOT NULL,
    subject character varying(80),
    public_key character varying(2000)
);


ALTER TABLE api.oauth_jwt OWNER TO postgres;

--
-- Name: oauth_refresh_tokens; Type: TABLE; Schema: api; Owner: postgres; Tablespace: 
--

CREATE TABLE oauth_refresh_tokens (
    refresh_token character varying(40) NOT NULL,
    client_id character varying(80) NOT NULL,
    user_id character varying(255),
    expires timestamp without time zone NOT NULL,
    scope character varying(2000),
    access_token character varying(50)
);


ALTER TABLE api.oauth_refresh_tokens OWNER TO postgres;

--
-- Name: oauth_scopes; Type: TABLE; Schema: api; Owner: postgres; Tablespace: 
--

CREATE TABLE oauth_scopes (
    scope text,
    is_default boolean
);


ALTER TABLE api.oauth_scopes OWNER TO postgres;

--
-- Name: oauth_sessions; Type: TABLE; Schema: api; Owner: postgres; Tablespace: 
--

CREATE TABLE oauth_sessions (
    id integer NOT NULL,
    owner_type character varying(250),
    owner_id character varying(250),
    client_id character varying(250)
);


ALTER TABLE api.oauth_sessions OWNER TO postgres;

--
-- Name: oauth_sessions_id_seq; Type: SEQUENCE; Schema: api; Owner: postgres
--

CREATE SEQUENCE oauth_sessions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE api.oauth_sessions_id_seq OWNER TO postgres;

--
-- Name: oauth_sessions_id_seq; Type: SEQUENCE OWNED BY; Schema: api; Owner: postgres
--

ALTER SEQUENCE oauth_sessions_id_seq OWNED BY oauth_sessions.id;


--
-- Name: id; Type: DEFAULT; Schema: api; Owner: postgres
--

ALTER TABLE ONLY oauth_sessions ALTER COLUMN id SET DEFAULT nextval('oauth_sessions_id_seq'::regclass);


--
-- Data for Name: oauth_access_tokens; Type: TABLE DATA; Schema: api; Owner: postgres
--

COPY oauth_access_tokens (access_token, client_id, user_id, expires, scope) FROM stdin;
7b7e071584e14fc2fe1df41257df61f72ac9689d	jack	3	2018-08-07 16:31:00	\N
\.


--
-- Data for Name: oauth_authorization_codes; Type: TABLE DATA; Schema: api; Owner: postgres
--

COPY oauth_authorization_codes (authorization_code, client_id, user_id, redirect_uri, expires, scope, access_token, session_id) FROM stdin;
\.


--
-- Data for Name: oauth_clients; Type: TABLE DATA; Schema: api; Owner: postgres
--

COPY oauth_clients (client_id, client_secret, redirect_uri, grant_types, scope, user_id) FROM stdin;
jack	jack	http://sample.com	\N	\N	\N
\.


--
-- Data for Name: oauth_jwt; Type: TABLE DATA; Schema: api; Owner: postgres
--

COPY oauth_jwt (client_id, subject, public_key) FROM stdin;
\.


--
-- Data for Name: oauth_refresh_tokens; Type: TABLE DATA; Schema: api; Owner: postgres
--

COPY oauth_refresh_tokens (refresh_token, client_id, user_id, expires, scope, access_token) FROM stdin;
\.


--
-- Data for Name: oauth_scopes; Type: TABLE DATA; Schema: api; Owner: postgres
--

COPY oauth_scopes (scope, is_default) FROM stdin;
\.


--
-- Data for Name: oauth_sessions; Type: TABLE DATA; Schema: api; Owner: postgres
--

COPY oauth_sessions (id, owner_type, owner_id, client_id) FROM stdin;
\.


--
-- Name: oauth_sessions_id_seq; Type: SEQUENCE SET; Schema: api; Owner: postgres
--

SELECT pg_catalog.setval('oauth_sessions_id_seq', 1, false);


--
-- Name: oauth_access_tokens_access_token_pkey; Type: CONSTRAINT; Schema: api; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY oauth_access_tokens
    ADD CONSTRAINT oauth_access_tokens_access_token_pkey PRIMARY KEY (access_token);


--
-- Name: oauth_authorization_codes_authorization_code_pkey; Type: CONSTRAINT; Schema: api; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY oauth_authorization_codes
    ADD CONSTRAINT oauth_authorization_codes_authorization_code_pkey PRIMARY KEY (authorization_code);


--
-- Name: oauth_clients_client_id_pkey; Type: CONSTRAINT; Schema: api; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY oauth_clients
    ADD CONSTRAINT oauth_clients_client_id_pkey PRIMARY KEY (client_id);


--
-- Name: oauth_jwt_client_id_pkey; Type: CONSTRAINT; Schema: api; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY oauth_jwt
    ADD CONSTRAINT oauth_jwt_client_id_pkey PRIMARY KEY (client_id);


--
-- Name: oauth_refresh_tokens_refresh_token_pkey; Type: CONSTRAINT; Schema: api; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY oauth_refresh_tokens
    ADD CONSTRAINT oauth_refresh_tokens_refresh_token_pkey PRIMARY KEY (refresh_token);


--
-- Name: oauth_sessions_pkey; Type: CONSTRAINT; Schema: api; Owner: postgres; Tablespace: 
--

ALTER TABLE ONLY oauth_sessions
    ADD CONSTRAINT oauth_sessions_pkey PRIMARY KEY (id);


--
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


--
-- PostgreSQL database dump complete
--

