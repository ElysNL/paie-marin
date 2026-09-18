--
-- PostgreSQL database dump
--

\restrict 3vd1mCgffTpSpKD2jxOzw0RX8TOUEd2T6wvDTz110WCziNwCCesMCAdZWYLljCX

-- Dumped from database version 17.11 (Debian 17.11-1.pgdg13+2)
-- Dumped by pg_dump version 18.6

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: affectations_marin; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.affectations_marin (
    id bigint NOT NULL,
    employe_id bigint NOT NULL,
    navire_id bigint NOT NULL,
    fonction_id bigint NOT NULL,
    contrat_armateur_id bigint NOT NULL,
    date_embt date NOT NULL,
    date_debt date,
    taux_journalier numeric(12,2) NOT NULL,
    devise_id bigint NOT NULL,
    statut character varying(255) DEFAULT 'actif'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT affectations_marin_statut_check CHECK (((statut)::text = ANY ((ARRAY['actif'::character varying, 'termine'::character varying, 'annule'::character varying])::text[])))
);


--
-- Name: affectations_marin_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.affectations_marin_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: affectations_marin_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.affectations_marin_id_seq OWNED BY public.affectations_marin.id;


--
-- Name: agences; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.agences (
    id bigint NOT NULL,
    code character varying(20) NOT NULL,
    nom character varying(100) NOT NULL,
    banque_id bigint NOT NULL,
    adresse character varying(255),
    telephone character varying(255),
    actif boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: agences_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.agences_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: agences_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.agences_id_seq OWNED BY public.agences.id;


--
-- Name: armateurs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.armateurs (
    id bigint NOT NULL,
    code character varying(20) NOT NULL,
    nom character varying(100) NOT NULL,
    adresse character varying(255),
    telephone character varying(255),
    email character varying(255),
    pays_id bigint,
    actif boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: armateurs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.armateurs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: armateurs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.armateurs_id_seq OWNED BY public.armateurs.id;


--
-- Name: avances; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.avances (
    id bigint NOT NULL,
    employe_id bigint NOT NULL,
    date_avance date NOT NULL,
    montant numeric(12,2) NOT NULL,
    devise_id bigint,
    motif character varying(255),
    statut character varying(255) DEFAULT 'en_cours'::character varying NOT NULL,
    solde numeric(12,2),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT avances_statut_check CHECK (((statut)::text = ANY ((ARRAY['en_cours'::character varying, 'remboursee'::character varying, 'annulee'::character varying])::text[])))
);


--
-- Name: avances_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.avances_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: avances_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.avances_id_seq OWNED BY public.avances.id;


--
-- Name: banques; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.banques (
    id bigint NOT NULL,
    code character varying(20) NOT NULL,
    nom character varying(100) NOT NULL,
    pays_id bigint,
    actif boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: banques_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.banques_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: banques_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.banques_id_seq OWNED BY public.banques.id;


--
-- Name: bulletin_cotisation; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bulletin_cotisation (
    id bigint NOT NULL,
    bulletin_id bigint NOT NULL,
    cotisation_id bigint NOT NULL,
    assiette numeric(12,2) NOT NULL,
    taux_salarial numeric(8,4),
    montant_salarial numeric(12,2),
    taux_patronal numeric(8,4),
    montant_patronal numeric(12,2),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: bulletin_cotisation_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bulletin_cotisation_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: bulletin_cotisation_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bulletin_cotisation_id_seq OWNED BY public.bulletin_cotisation.id;


--
-- Name: bulletins_delegation; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bulletins_delegation (
    id bigint NOT NULL,
    bulletin_id bigint NOT NULL,
    delegation_id bigint NOT NULL,
    montant numeric(12,2) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: bulletins_delegation_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bulletins_delegation_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: bulletins_delegation_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bulletins_delegation_id_seq OWNED BY public.bulletins_delegation.id;


--
-- Name: bulletins_elem_paie; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bulletins_elem_paie (
    id bigint NOT NULL,
    bulletin_id bigint NOT NULL,
    elem_paie_id bigint NOT NULL,
    quantite numeric(12,2),
    unite character varying(20),
    base numeric(12,2),
    taux numeric(12,2),
    montant numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    devise_id bigint,
    description text,
    ordre integer DEFAULT 0 NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: bulletins_elem_paie_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bulletins_elem_paie_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: bulletins_elem_paie_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bulletins_elem_paie_id_seq OWNED BY public.bulletins_elem_paie.id;


--
-- Name: bulletins_jour; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bulletins_jour (
    id bigint NOT NULL,
    bulletin_id bigint NOT NULL,
    date date NOT NULL,
    type_jour character varying(255) NOT NULL,
    nombre numeric(8,2) DEFAULT '1'::numeric NOT NULL,
    taux numeric(12,2),
    montant numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT bulletins_jour_type_jour_check CHECK (((type_jour)::text = ANY ((ARRAY['NORMAL'::character varying, 'FERIE'::character varying, 'CONGE'::character varying, 'REPOS'::character varying, 'ABSENCE'::character varying, 'MALADIE'::character varying, 'AUTRE'::character varying])::text[])))
);


--
-- Name: bulletins_jour_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bulletins_jour_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: bulletins_jour_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bulletins_jour_id_seq OWNED BY public.bulletins_jour.id;


--
-- Name: bulletins_paie; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.bulletins_paie (
    id bigint NOT NULL,
    paie_id bigint NOT NULL,
    employe_id bigint NOT NULL,
    affectation_id bigint NOT NULL,
    navire_id bigint NOT NULL,
    devise_source_id bigint,
    devise_paiement_id bigint,
    taux_change numeric(14,6),
    date_taux_change date,
    source_taux_change character varying(100),
    total_jours numeric(8,2) DEFAULT '0'::numeric NOT NULL,
    total_gains numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    total_brut numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    total_cotisations_salariales numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    total_retenues numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    total_cotisations_patronales numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    net_a_payer numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    cout_total_employeur numeric(12,2) DEFAULT '0'::numeric NOT NULL,
    statut character varying(255) DEFAULT 'brouillon'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT bulletins_paie_statut_check CHECK (((statut)::text = ANY ((ARRAY['brouillon'::character varying, 'calcule'::character varying, 'valide'::character varying, 'cloture'::character varying])::text[])))
);


--
-- Name: bulletins_paie_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.bulletins_paie_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: bulletins_paie_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.bulletins_paie_id_seq OWNED BY public.bulletins_paie.id;


--
-- Name: cache; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache (
    key character varying(255) NOT NULL,
    value text NOT NULL,
    expiration bigint NOT NULL
);


--
-- Name: cache_locks; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cache_locks (
    key character varying(255) NOT NULL,
    owner character varying(255) NOT NULL,
    expiration bigint NOT NULL
);


--
-- Name: classifications; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.classifications (
    id bigint NOT NULL,
    code character varying(20) NOT NULL,
    libelle character varying(100) NOT NULL,
    description text,
    actif boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: classifications_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.classifications_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: classifications_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.classifications_id_seq OWNED BY public.classifications.id;


--
-- Name: compagnies; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.compagnies (
    id bigint NOT NULL,
    code character varying(20) NOT NULL,
    nom character varying(100) NOT NULL,
    pays_id bigint,
    actif boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: compagnies_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.compagnies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: compagnies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.compagnies_id_seq OWNED BY public.compagnies.id;


--
-- Name: contrat_armateurs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.contrat_armateurs (
    id bigint NOT NULL,
    armateur_id bigint NOT NULL,
    code character varying(20) NOT NULL,
    libelle character varying(100) NOT NULL,
    devise_id bigint NOT NULL,
    date_debut date NOT NULL,
    date_fin date,
    taux_base numeric(10,2),
    conditions text,
    actif boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: contrat_armateurs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.contrat_armateurs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: contrat_armateurs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.contrat_armateurs_id_seq OWNED BY public.contrat_armateurs.id;


--
-- Name: cotisations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.cotisations (
    id bigint NOT NULL,
    code character varying(20) NOT NULL,
    libelle character varying(100) NOT NULL,
    organisme character varying(100),
    taux_salarial numeric(8,4),
    plafond_salarial numeric(12,2),
    taux_patronal numeric(8,4),
    plafond_patronal numeric(12,2),
    date_debut date NOT NULL,
    date_fin date,
    actif boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: cotisations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.cotisations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: cotisations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.cotisations_id_seq OWNED BY public.cotisations.id;


--
-- Name: delegations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.delegations (
    id bigint NOT NULL,
    employe_id bigint NOT NULL,
    beneficiaire character varying(200) NOT NULL,
    montant numeric(12,2) NOT NULL,
    devise_id bigint,
    date_debut date NOT NULL,
    date_fin date,
    frequence character varying(20) DEFAULT 'mensuelle'::character varying NOT NULL,
    statut character varying(255) DEFAULT 'actif'::character varying NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT delegations_statut_check CHECK (((statut)::text = ANY ((ARRAY['actif'::character varying, 'termine'::character varying, 'annule'::character varying])::text[])))
);


--
-- Name: delegations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.delegations_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: delegations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.delegations_id_seq OWNED BY public.delegations.id;


--
-- Name: devises; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.devises (
    id bigint NOT NULL,
    code character varying(3) NOT NULL,
    libelle character varying(50) NOT NULL,
    symbole character varying(10),
    nb_decimales smallint DEFAULT '2'::smallint NOT NULL,
    actif boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: devises_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.devises_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: devises_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.devises_id_seq OWNED BY public.devises.id;


--
-- Name: elem_paies; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.elem_paies (
    id bigint NOT NULL,
    code character varying(20) NOT NULL,
    libelle character varying(100) NOT NULL,
    est_variable boolean DEFAULT false NOT NULL,
    type character varying(255) NOT NULL,
    modalite character varying(255),
    affichee boolean DEFAULT true NOT NULL,
    imposable boolean DEFAULT true NOT NULL,
    cotisable boolean DEFAULT false NOT NULL,
    ordre integer DEFAULT 0 NOT NULL,
    actif boolean DEFAULT false NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT elem_paies_type_check CHECK (((type)::text = ANY ((ARRAY['GAIN'::character varying, 'RETENUE'::character varying, 'COTISATION'::character varying])::text[])))
);


--
-- Name: elem_paies_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.elem_paies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: elem_paies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.elem_paies_id_seq OWNED BY public.elem_paies.id;


--
-- Name: employes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.employes (
    id bigint NOT NULL,
    matricule character varying(50) NOT NULL,
    nom character varying(100) NOT NULL,
    prenom character varying(100) NOT NULL,
    date_naissance date,
    lieu_naissance character varying(255),
    nationalite_id bigint,
    adresse character varying(255),
    telephone character varying(255),
    email character varying(255),
    cin character varying(255),
    banque_id bigint,
    compte_bancaire character varying(255),
    date_embauche date,
    date_depart date,
    actif boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    nbre_charges integer DEFAULT 0 NOT NULL
);


--
-- Name: employes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.employes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: employes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.employes_id_seq OWNED BY public.employes.id;


--
-- Name: failed_jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.failed_jobs (
    id bigint NOT NULL,
    uuid character varying(255) NOT NULL,
    connection character varying(255) NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    exception text NOT NULL,
    failed_at timestamp(0) without time zone DEFAULT CURRENT_TIMESTAMP NOT NULL
);


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.failed_jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: failed_jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.failed_jobs_id_seq OWNED BY public.failed_jobs.id;


--
-- Name: fonctions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.fonctions (
    id bigint NOT NULL,
    code character varying(20) NOT NULL,
    libelle character varying(100) NOT NULL,
    description text,
    actif boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: fonctions_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.fonctions_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: fonctions_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.fonctions_id_seq OWNED BY public.fonctions.id;


--
-- Name: igr_parametres; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.igr_parametres (
    id bigint NOT NULL,
    libelle character varying(100) NOT NULL,
    tranche_inf numeric(12,2) NOT NULL,
    tranche_sup numeric(12,2),
    taux_igr numeric(8,4) NOT NULL,
    date_debut date NOT NULL,
    date_fin date,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: igr_parametres_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.igr_parametres_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: igr_parametres_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.igr_parametres_id_seq OWNED BY public.igr_parametres.id;


--
-- Name: job_batches; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.job_batches (
    id character varying(255) NOT NULL,
    name character varying(255) NOT NULL,
    total_jobs integer NOT NULL,
    pending_jobs integer NOT NULL,
    failed_jobs integer NOT NULL,
    failed_job_ids text NOT NULL,
    options text,
    cancelled_at integer,
    created_at integer NOT NULL,
    finished_at integer
);


--
-- Name: jobs; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.jobs (
    id bigint NOT NULL,
    queue character varying(255) NOT NULL,
    payload text NOT NULL,
    attempts smallint NOT NULL,
    reserved_at integer,
    available_at integer NOT NULL,
    created_at integer NOT NULL
);


--
-- Name: jobs_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.jobs_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: jobs_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.jobs_id_seq OWNED BY public.jobs.id;


--
-- Name: migrations; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.migrations (
    id integer NOT NULL,
    migration character varying(255) NOT NULL,
    batch integer NOT NULL
);


--
-- Name: migrations_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.migrations_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: migrations_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.migrations_id_seq OWNED BY public.migrations.id;


--
-- Name: navires; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.navires (
    id bigint NOT NULL,
    armateur_id bigint NOT NULL,
    compagnie_id bigint,
    code character varying(20) NOT NULL,
    nom character varying(100) NOT NULL,
    immatriculation character varying(50),
    pavillon_id bigint,
    type character varying(50),
    actif boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: navires_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.navires_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: navires_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.navires_id_seq OWNED BY public.navires.id;


--
-- Name: paies; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.paies (
    id bigint NOT NULL,
    num_paie character varying(20) NOT NULL,
    libelle character varying(100) NOT NULL,
    periode character varying(20) NOT NULL,
    date_debut date NOT NULL,
    date_fin date NOT NULL,
    statut character varying(255) DEFAULT 'brouillon'::character varying NOT NULL,
    date_validation date,
    date_cloture date,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone,
    CONSTRAINT paies_statut_check CHECK (((statut)::text = ANY ((ARRAY['brouillon'::character varying, 'calcule'::character varying, 'controle'::character varying, 'valide'::character varying, 'cloture'::character varying])::text[])))
);


--
-- Name: paies_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.paies_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: paies_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.paies_id_seq OWNED BY public.paies.id;


--
-- Name: password_reset_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.password_reset_tokens (
    email character varying(255) NOT NULL,
    token character varying(255) NOT NULL,
    created_at timestamp(0) without time zone
);


--
-- Name: pays; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.pays (
    id bigint NOT NULL,
    code character varying(3) NOT NULL,
    nom character varying(100) NOT NULL,
    nationalite character varying(100),
    actif boolean DEFAULT true NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: pays_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.pays_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: pays_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.pays_id_seq OWNED BY public.pays.id;


--
-- Name: personal_access_tokens; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.personal_access_tokens (
    id bigint NOT NULL,
    tokenable_type character varying(255) NOT NULL,
    tokenable_id bigint NOT NULL,
    name text NOT NULL,
    token character varying(64) NOT NULL,
    abilities text,
    last_used_at timestamp(0) without time zone,
    expires_at timestamp(0) without time zone,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.personal_access_tokens_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: personal_access_tokens_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.personal_access_tokens_id_seq OWNED BY public.personal_access_tokens.id;


--
-- Name: remboursements_avance; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.remboursements_avance (
    id bigint NOT NULL,
    avance_id bigint NOT NULL,
    bulletin_id bigint NOT NULL,
    montant numeric(12,2) NOT NULL,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: remboursements_avance_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.remboursements_avance_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: remboursements_avance_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.remboursements_avance_id_seq OWNED BY public.remboursements_avance.id;


--
-- Name: sessions; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.sessions (
    id character varying(255) NOT NULL,
    user_id bigint,
    ip_address character varying(45),
    user_agent text,
    payload text NOT NULL,
    last_activity integer NOT NULL
);


--
-- Name: specificites_armateur; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.specificites_armateur (
    id bigint NOT NULL,
    armateur_id bigint NOT NULL,
    cle character varying(50) NOT NULL,
    valeur text,
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: specificites_armateur_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.specificites_armateur_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: specificites_armateur_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.specificites_armateur_id_seq OWNED BY public.specificites_armateur.id;


--
-- Name: taux_changes; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.taux_changes (
    id bigint NOT NULL,
    devise_source_id bigint NOT NULL,
    devise_cible_id bigint NOT NULL,
    taux numeric(14,6) NOT NULL,
    date_taux date NOT NULL,
    source character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: taux_changes_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.taux_changes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: taux_changes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.taux_changes_id_seq OWNED BY public.taux_changes.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: -
--

CREATE TABLE public.users (
    id bigint NOT NULL,
    name character varying(255) NOT NULL,
    email character varying(255) NOT NULL,
    email_verified_at timestamp(0) without time zone,
    password character varying(255) NOT NULL,
    remember_token character varying(100),
    created_at timestamp(0) without time zone,
    updated_at timestamp(0) without time zone
);


--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: -
--

CREATE SEQUENCE public.users_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: -
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: affectations_marin id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.affectations_marin ALTER COLUMN id SET DEFAULT nextval('public.affectations_marin_id_seq'::regclass);


--
-- Name: agences id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.agences ALTER COLUMN id SET DEFAULT nextval('public.agences_id_seq'::regclass);


--
-- Name: armateurs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.armateurs ALTER COLUMN id SET DEFAULT nextval('public.armateurs_id_seq'::regclass);


--
-- Name: avances id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.avances ALTER COLUMN id SET DEFAULT nextval('public.avances_id_seq'::regclass);


--
-- Name: banques id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.banques ALTER COLUMN id SET DEFAULT nextval('public.banques_id_seq'::regclass);


--
-- Name: bulletin_cotisation id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletin_cotisation ALTER COLUMN id SET DEFAULT nextval('public.bulletin_cotisation_id_seq'::regclass);


--
-- Name: bulletins_delegation id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletins_delegation ALTER COLUMN id SET DEFAULT nextval('public.bulletins_delegation_id_seq'::regclass);


--
-- Name: bulletins_elem_paie id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletins_elem_paie ALTER COLUMN id SET DEFAULT nextval('public.bulletins_elem_paie_id_seq'::regclass);


--
-- Name: bulletins_jour id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletins_jour ALTER COLUMN id SET DEFAULT nextval('public.bulletins_jour_id_seq'::regclass);


--
-- Name: bulletins_paie id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletins_paie ALTER COLUMN id SET DEFAULT nextval('public.bulletins_paie_id_seq'::regclass);


--
-- Name: classifications id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.classifications ALTER COLUMN id SET DEFAULT nextval('public.classifications_id_seq'::regclass);


--
-- Name: compagnies id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.compagnies ALTER COLUMN id SET DEFAULT nextval('public.compagnies_id_seq'::regclass);


--
-- Name: contrat_armateurs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.contrat_armateurs ALTER COLUMN id SET DEFAULT nextval('public.contrat_armateurs_id_seq'::regclass);


--
-- Name: cotisations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cotisations ALTER COLUMN id SET DEFAULT nextval('public.cotisations_id_seq'::regclass);


--
-- Name: delegations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.delegations ALTER COLUMN id SET DEFAULT nextval('public.delegations_id_seq'::regclass);


--
-- Name: devises id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.devises ALTER COLUMN id SET DEFAULT nextval('public.devises_id_seq'::regclass);


--
-- Name: elem_paies id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.elem_paies ALTER COLUMN id SET DEFAULT nextval('public.elem_paies_id_seq'::regclass);


--
-- Name: employes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employes ALTER COLUMN id SET DEFAULT nextval('public.employes_id_seq'::regclass);


--
-- Name: failed_jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs ALTER COLUMN id SET DEFAULT nextval('public.failed_jobs_id_seq'::regclass);


--
-- Name: fonctions id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fonctions ALTER COLUMN id SET DEFAULT nextval('public.fonctions_id_seq'::regclass);


--
-- Name: igr_parametres id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.igr_parametres ALTER COLUMN id SET DEFAULT nextval('public.igr_parametres_id_seq'::regclass);


--
-- Name: jobs id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs ALTER COLUMN id SET DEFAULT nextval('public.jobs_id_seq'::regclass);


--
-- Name: migrations id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations ALTER COLUMN id SET DEFAULT nextval('public.migrations_id_seq'::regclass);


--
-- Name: navires id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.navires ALTER COLUMN id SET DEFAULT nextval('public.navires_id_seq'::regclass);


--
-- Name: paies id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.paies ALTER COLUMN id SET DEFAULT nextval('public.paies_id_seq'::regclass);


--
-- Name: pays id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pays ALTER COLUMN id SET DEFAULT nextval('public.pays_id_seq'::regclass);


--
-- Name: personal_access_tokens id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens ALTER COLUMN id SET DEFAULT nextval('public.personal_access_tokens_id_seq'::regclass);


--
-- Name: remboursements_avance id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.remboursements_avance ALTER COLUMN id SET DEFAULT nextval('public.remboursements_avance_id_seq'::regclass);


--
-- Name: specificites_armateur id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.specificites_armateur ALTER COLUMN id SET DEFAULT nextval('public.specificites_armateur_id_seq'::regclass);


--
-- Name: taux_changes id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.taux_changes ALTER COLUMN id SET DEFAULT nextval('public.taux_changes_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Name: affectations_marin affectations_marin_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.affectations_marin
    ADD CONSTRAINT affectations_marin_pkey PRIMARY KEY (id);


--
-- Name: agences agences_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.agences
    ADD CONSTRAINT agences_pkey PRIMARY KEY (id);


--
-- Name: armateurs armateurs_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.armateurs
    ADD CONSTRAINT armateurs_code_unique UNIQUE (code);


--
-- Name: armateurs armateurs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.armateurs
    ADD CONSTRAINT armateurs_pkey PRIMARY KEY (id);


--
-- Name: avances avances_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.avances
    ADD CONSTRAINT avances_pkey PRIMARY KEY (id);


--
-- Name: banques banques_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.banques
    ADD CONSTRAINT banques_code_unique UNIQUE (code);


--
-- Name: banques banques_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.banques
    ADD CONSTRAINT banques_pkey PRIMARY KEY (id);


--
-- Name: bulletin_cotisation bulletin_cotisation_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletin_cotisation
    ADD CONSTRAINT bulletin_cotisation_pkey PRIMARY KEY (id);


--
-- Name: bulletins_delegation bulletins_delegation_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletins_delegation
    ADD CONSTRAINT bulletins_delegation_pkey PRIMARY KEY (id);


--
-- Name: bulletins_elem_paie bulletins_elem_paie_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletins_elem_paie
    ADD CONSTRAINT bulletins_elem_paie_pkey PRIMARY KEY (id);


--
-- Name: bulletins_jour bulletins_jour_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletins_jour
    ADD CONSTRAINT bulletins_jour_pkey PRIMARY KEY (id);


--
-- Name: bulletins_paie bulletins_paie_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletins_paie
    ADD CONSTRAINT bulletins_paie_pkey PRIMARY KEY (id);


--
-- Name: cache_locks cache_locks_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache_locks
    ADD CONSTRAINT cache_locks_pkey PRIMARY KEY (key);


--
-- Name: cache cache_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cache
    ADD CONSTRAINT cache_pkey PRIMARY KEY (key);


--
-- Name: classifications classifications_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.classifications
    ADD CONSTRAINT classifications_code_unique UNIQUE (code);


--
-- Name: classifications classifications_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.classifications
    ADD CONSTRAINT classifications_pkey PRIMARY KEY (id);


--
-- Name: compagnies compagnies_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.compagnies
    ADD CONSTRAINT compagnies_code_unique UNIQUE (code);


--
-- Name: compagnies compagnies_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.compagnies
    ADD CONSTRAINT compagnies_pkey PRIMARY KEY (id);


--
-- Name: contrat_armateurs contrat_armateurs_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.contrat_armateurs
    ADD CONSTRAINT contrat_armateurs_code_unique UNIQUE (code);


--
-- Name: contrat_armateurs contrat_armateurs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.contrat_armateurs
    ADD CONSTRAINT contrat_armateurs_pkey PRIMARY KEY (id);


--
-- Name: cotisations cotisations_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cotisations
    ADD CONSTRAINT cotisations_code_unique UNIQUE (code);


--
-- Name: cotisations cotisations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.cotisations
    ADD CONSTRAINT cotisations_pkey PRIMARY KEY (id);


--
-- Name: delegations delegations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.delegations
    ADD CONSTRAINT delegations_pkey PRIMARY KEY (id);


--
-- Name: devises devises_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.devises
    ADD CONSTRAINT devises_code_unique UNIQUE (code);


--
-- Name: devises devises_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.devises
    ADD CONSTRAINT devises_pkey PRIMARY KEY (id);


--
-- Name: elem_paies elem_paies_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.elem_paies
    ADD CONSTRAINT elem_paies_code_unique UNIQUE (code);


--
-- Name: elem_paies elem_paies_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.elem_paies
    ADD CONSTRAINT elem_paies_pkey PRIMARY KEY (id);


--
-- Name: employes employes_matricule_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employes
    ADD CONSTRAINT employes_matricule_unique UNIQUE (matricule);


--
-- Name: employes employes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employes
    ADD CONSTRAINT employes_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_pkey PRIMARY KEY (id);


--
-- Name: failed_jobs failed_jobs_uuid_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.failed_jobs
    ADD CONSTRAINT failed_jobs_uuid_unique UNIQUE (uuid);


--
-- Name: fonctions fonctions_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fonctions
    ADD CONSTRAINT fonctions_code_unique UNIQUE (code);


--
-- Name: fonctions fonctions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.fonctions
    ADD CONSTRAINT fonctions_pkey PRIMARY KEY (id);


--
-- Name: igr_parametres igr_parametres_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.igr_parametres
    ADD CONSTRAINT igr_parametres_pkey PRIMARY KEY (id);


--
-- Name: job_batches job_batches_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.job_batches
    ADD CONSTRAINT job_batches_pkey PRIMARY KEY (id);


--
-- Name: jobs jobs_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.jobs
    ADD CONSTRAINT jobs_pkey PRIMARY KEY (id);


--
-- Name: migrations migrations_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.migrations
    ADD CONSTRAINT migrations_pkey PRIMARY KEY (id);


--
-- Name: navires navires_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.navires
    ADD CONSTRAINT navires_code_unique UNIQUE (code);


--
-- Name: navires navires_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.navires
    ADD CONSTRAINT navires_pkey PRIMARY KEY (id);


--
-- Name: paies paies_num_paie_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.paies
    ADD CONSTRAINT paies_num_paie_unique UNIQUE (num_paie);


--
-- Name: paies paies_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.paies
    ADD CONSTRAINT paies_pkey PRIMARY KEY (id);


--
-- Name: password_reset_tokens password_reset_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.password_reset_tokens
    ADD CONSTRAINT password_reset_tokens_pkey PRIMARY KEY (email);


--
-- Name: pays pays_code_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pays
    ADD CONSTRAINT pays_code_unique UNIQUE (code);


--
-- Name: pays pays_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.pays
    ADD CONSTRAINT pays_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_pkey PRIMARY KEY (id);


--
-- Name: personal_access_tokens personal_access_tokens_token_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.personal_access_tokens
    ADD CONSTRAINT personal_access_tokens_token_unique UNIQUE (token);


--
-- Name: remboursements_avance remboursements_avance_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.remboursements_avance
    ADD CONSTRAINT remboursements_avance_pkey PRIMARY KEY (id);


--
-- Name: sessions sessions_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.sessions
    ADD CONSTRAINT sessions_pkey PRIMARY KEY (id);


--
-- Name: specificites_armateur specificites_armateur_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.specificites_armateur
    ADD CONSTRAINT specificites_armateur_pkey PRIMARY KEY (id);


--
-- Name: taux_changes taux_changes_devise_source_id_devise_cible_id_date_taux_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.taux_changes
    ADD CONSTRAINT taux_changes_devise_source_id_devise_cible_id_date_taux_unique UNIQUE (devise_source_id, devise_cible_id, date_taux);


--
-- Name: taux_changes taux_changes_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.taux_changes
    ADD CONSTRAINT taux_changes_pkey PRIMARY KEY (id);


--
-- Name: users users_email_unique; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_email_unique UNIQUE (email);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: affectations_marin_employe_id_navire_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX affectations_marin_employe_id_navire_id_index ON public.affectations_marin USING btree (employe_id, navire_id);


--
-- Name: bulletins_paie_paie_id_employe_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX bulletins_paie_paie_id_employe_id_index ON public.bulletins_paie USING btree (paie_id, employe_id);


--
-- Name: cache_expiration_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cache_expiration_index ON public.cache USING btree (expiration);


--
-- Name: cache_locks_expiration_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX cache_locks_expiration_index ON public.cache_locks USING btree (expiration);


--
-- Name: failed_jobs_connection_queue_failed_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX failed_jobs_connection_queue_failed_at_index ON public.failed_jobs USING btree (connection, queue, failed_at);


--
-- Name: jobs_queue_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX jobs_queue_index ON public.jobs USING btree (queue);


--
-- Name: personal_access_tokens_expires_at_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX personal_access_tokens_expires_at_index ON public.personal_access_tokens USING btree (expires_at);


--
-- Name: personal_access_tokens_tokenable_type_tokenable_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX personal_access_tokens_tokenable_type_tokenable_id_index ON public.personal_access_tokens USING btree (tokenable_type, tokenable_id);


--
-- Name: sessions_last_activity_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_last_activity_index ON public.sessions USING btree (last_activity);


--
-- Name: sessions_user_id_index; Type: INDEX; Schema: public; Owner: -
--

CREATE INDEX sessions_user_id_index ON public.sessions USING btree (user_id);


--
-- Name: affectations_marin affectations_marin_contrat_armateur_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.affectations_marin
    ADD CONSTRAINT affectations_marin_contrat_armateur_id_foreign FOREIGN KEY (contrat_armateur_id) REFERENCES public.contrat_armateurs(id) ON DELETE RESTRICT;


--
-- Name: affectations_marin affectations_marin_devise_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.affectations_marin
    ADD CONSTRAINT affectations_marin_devise_id_foreign FOREIGN KEY (devise_id) REFERENCES public.devises(id) ON DELETE RESTRICT;


--
-- Name: affectations_marin affectations_marin_employe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.affectations_marin
    ADD CONSTRAINT affectations_marin_employe_id_foreign FOREIGN KEY (employe_id) REFERENCES public.employes(id) ON DELETE CASCADE;


--
-- Name: affectations_marin affectations_marin_fonction_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.affectations_marin
    ADD CONSTRAINT affectations_marin_fonction_id_foreign FOREIGN KEY (fonction_id) REFERENCES public.fonctions(id) ON DELETE RESTRICT;


--
-- Name: affectations_marin affectations_marin_navire_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.affectations_marin
    ADD CONSTRAINT affectations_marin_navire_id_foreign FOREIGN KEY (navire_id) REFERENCES public.navires(id) ON DELETE CASCADE;


--
-- Name: agences agences_banque_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.agences
    ADD CONSTRAINT agences_banque_id_foreign FOREIGN KEY (banque_id) REFERENCES public.banques(id) ON DELETE CASCADE;


--
-- Name: armateurs armateurs_pays_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.armateurs
    ADD CONSTRAINT armateurs_pays_id_foreign FOREIGN KEY (pays_id) REFERENCES public.pays(id) ON DELETE SET NULL;


--
-- Name: avances avances_devise_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.avances
    ADD CONSTRAINT avances_devise_id_foreign FOREIGN KEY (devise_id) REFERENCES public.devises(id) ON DELETE RESTRICT;


--
-- Name: avances avances_employe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.avances
    ADD CONSTRAINT avances_employe_id_foreign FOREIGN KEY (employe_id) REFERENCES public.employes(id) ON DELETE CASCADE;


--
-- Name: banques banques_pays_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.banques
    ADD CONSTRAINT banques_pays_id_foreign FOREIGN KEY (pays_id) REFERENCES public.pays(id) ON DELETE SET NULL;


--
-- Name: bulletin_cotisation bulletin_cotisation_bulletin_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletin_cotisation
    ADD CONSTRAINT bulletin_cotisation_bulletin_id_foreign FOREIGN KEY (bulletin_id) REFERENCES public.bulletins_paie(id) ON DELETE CASCADE;


--
-- Name: bulletin_cotisation bulletin_cotisation_cotisation_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletin_cotisation
    ADD CONSTRAINT bulletin_cotisation_cotisation_id_foreign FOREIGN KEY (cotisation_id) REFERENCES public.cotisations(id) ON DELETE RESTRICT;


--
-- Name: bulletins_delegation bulletins_delegation_bulletin_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletins_delegation
    ADD CONSTRAINT bulletins_delegation_bulletin_id_foreign FOREIGN KEY (bulletin_id) REFERENCES public.bulletins_paie(id) ON DELETE CASCADE;


--
-- Name: bulletins_delegation bulletins_delegation_delegation_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletins_delegation
    ADD CONSTRAINT bulletins_delegation_delegation_id_foreign FOREIGN KEY (delegation_id) REFERENCES public.delegations(id) ON DELETE CASCADE;


--
-- Name: bulletins_elem_paie bulletins_elem_paie_bulletin_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletins_elem_paie
    ADD CONSTRAINT bulletins_elem_paie_bulletin_id_foreign FOREIGN KEY (bulletin_id) REFERENCES public.bulletins_paie(id) ON DELETE CASCADE;


--
-- Name: bulletins_elem_paie bulletins_elem_paie_devise_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletins_elem_paie
    ADD CONSTRAINT bulletins_elem_paie_devise_id_foreign FOREIGN KEY (devise_id) REFERENCES public.devises(id) ON DELETE SET NULL;


--
-- Name: bulletins_elem_paie bulletins_elem_paie_elem_paie_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletins_elem_paie
    ADD CONSTRAINT bulletins_elem_paie_elem_paie_id_foreign FOREIGN KEY (elem_paie_id) REFERENCES public.elem_paies(id) ON DELETE RESTRICT;


--
-- Name: bulletins_jour bulletins_jour_bulletin_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletins_jour
    ADD CONSTRAINT bulletins_jour_bulletin_id_foreign FOREIGN KEY (bulletin_id) REFERENCES public.bulletins_paie(id) ON DELETE CASCADE;


--
-- Name: bulletins_paie bulletins_paie_affectation_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletins_paie
    ADD CONSTRAINT bulletins_paie_affectation_id_foreign FOREIGN KEY (affectation_id) REFERENCES public.affectations_marin(id) ON DELETE RESTRICT;


--
-- Name: bulletins_paie bulletins_paie_devise_paiement_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletins_paie
    ADD CONSTRAINT bulletins_paie_devise_paiement_id_foreign FOREIGN KEY (devise_paiement_id) REFERENCES public.devises(id) ON DELETE RESTRICT;


--
-- Name: bulletins_paie bulletins_paie_devise_source_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletins_paie
    ADD CONSTRAINT bulletins_paie_devise_source_id_foreign FOREIGN KEY (devise_source_id) REFERENCES public.devises(id) ON DELETE RESTRICT;


--
-- Name: bulletins_paie bulletins_paie_employe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletins_paie
    ADD CONSTRAINT bulletins_paie_employe_id_foreign FOREIGN KEY (employe_id) REFERENCES public.employes(id) ON DELETE RESTRICT;


--
-- Name: bulletins_paie bulletins_paie_navire_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletins_paie
    ADD CONSTRAINT bulletins_paie_navire_id_foreign FOREIGN KEY (navire_id) REFERENCES public.navires(id) ON DELETE RESTRICT;


--
-- Name: bulletins_paie bulletins_paie_paie_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.bulletins_paie
    ADD CONSTRAINT bulletins_paie_paie_id_foreign FOREIGN KEY (paie_id) REFERENCES public.paies(id) ON DELETE CASCADE;


--
-- Name: compagnies compagnies_pays_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.compagnies
    ADD CONSTRAINT compagnies_pays_id_foreign FOREIGN KEY (pays_id) REFERENCES public.pays(id) ON DELETE SET NULL;


--
-- Name: contrat_armateurs contrat_armateurs_armateur_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.contrat_armateurs
    ADD CONSTRAINT contrat_armateurs_armateur_id_foreign FOREIGN KEY (armateur_id) REFERENCES public.armateurs(id) ON DELETE CASCADE;


--
-- Name: contrat_armateurs contrat_armateurs_devise_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.contrat_armateurs
    ADD CONSTRAINT contrat_armateurs_devise_id_foreign FOREIGN KEY (devise_id) REFERENCES public.devises(id) ON DELETE RESTRICT;


--
-- Name: delegations delegations_devise_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.delegations
    ADD CONSTRAINT delegations_devise_id_foreign FOREIGN KEY (devise_id) REFERENCES public.devises(id) ON DELETE RESTRICT;


--
-- Name: delegations delegations_employe_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.delegations
    ADD CONSTRAINT delegations_employe_id_foreign FOREIGN KEY (employe_id) REFERENCES public.employes(id) ON DELETE CASCADE;


--
-- Name: employes employes_banque_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employes
    ADD CONSTRAINT employes_banque_id_foreign FOREIGN KEY (banque_id) REFERENCES public.banques(id) ON DELETE SET NULL;


--
-- Name: employes employes_nationalite_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.employes
    ADD CONSTRAINT employes_nationalite_id_foreign FOREIGN KEY (nationalite_id) REFERENCES public.pays(id) ON DELETE SET NULL;


--
-- Name: navires navires_armateur_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.navires
    ADD CONSTRAINT navires_armateur_id_foreign FOREIGN KEY (armateur_id) REFERENCES public.armateurs(id) ON DELETE CASCADE;


--
-- Name: navires navires_compagnie_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.navires
    ADD CONSTRAINT navires_compagnie_id_foreign FOREIGN KEY (compagnie_id) REFERENCES public.compagnies(id) ON DELETE SET NULL;


--
-- Name: navires navires_pavillon_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.navires
    ADD CONSTRAINT navires_pavillon_id_foreign FOREIGN KEY (pavillon_id) REFERENCES public.pays(id) ON DELETE SET NULL;


--
-- Name: remboursements_avance remboursements_avance_avance_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.remboursements_avance
    ADD CONSTRAINT remboursements_avance_avance_id_foreign FOREIGN KEY (avance_id) REFERENCES public.avances(id) ON DELETE CASCADE;


--
-- Name: remboursements_avance remboursements_avance_bulletin_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.remboursements_avance
    ADD CONSTRAINT remboursements_avance_bulletin_id_foreign FOREIGN KEY (bulletin_id) REFERENCES public.bulletins_paie(id) ON DELETE CASCADE;


--
-- Name: specificites_armateur specificites_armateur_armateur_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.specificites_armateur
    ADD CONSTRAINT specificites_armateur_armateur_id_foreign FOREIGN KEY (armateur_id) REFERENCES public.armateurs(id) ON DELETE CASCADE;


--
-- Name: taux_changes taux_changes_devise_cible_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.taux_changes
    ADD CONSTRAINT taux_changes_devise_cible_id_foreign FOREIGN KEY (devise_cible_id) REFERENCES public.devises(id) ON DELETE RESTRICT;


--
-- Name: taux_changes taux_changes_devise_source_id_foreign; Type: FK CONSTRAINT; Schema: public; Owner: -
--

ALTER TABLE ONLY public.taux_changes
    ADD CONSTRAINT taux_changes_devise_source_id_foreign FOREIGN KEY (devise_source_id) REFERENCES public.devises(id) ON DELETE RESTRICT;


--
-- PostgreSQL database dump complete
--

\unrestrict 3vd1mCgffTpSpKD2jxOzw0RX8TOUEd2T6wvDTz110WCziNwCCesMCAdZWYLljCX

--
-- PostgreSQL database dump
--

\restrict jYD0sXYhmjeyfbzE7JtWpov7DErNw6uPwkjLGFIdzlv2cAAwyfbsWY9slK40mgm

-- Dumped from database version 17.11 (Debian 17.11-1.pgdg13+2)
-- Dumped by pg_dump version 18.6

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Data for Name: migrations; Type: TABLE DATA; Schema: public; Owner: -
--

COPY public.migrations (id, migration, batch) FROM stdin;
1	0001_01_01_000000_create_users_table	1
2	0001_01_01_000001_create_cache_table	1
3	0001_01_01_000002_create_jobs_table	1
4	2026_08_31_175735_create_personal_access_tokens_table	1
5	2026_08_31_182304_create_pays_table	1
6	2026_08_31_182324_create_devise_table	1
7	2026_08_31_182602_create_banques_table	1
8	2026_08_31_182619_create_agence_table	1
9	2026_08_31_182838_create_fonction_table	1
10	2026_08_31_182855_create_classification_table	1
11	2026_08_31_182907_create_compagnie_table	1
12	2026_08_31_183104_create_armateur_table	1
13	2026_08_31_183150_create_navire_table	1
14	2026_08_31_183435_create_contrat_armateur_table	1
15	2026_08_31_183637_create_specificite_armateur_table	1
16	2026_08_31_183904_create_employe_table	1
17	2026_08_31_184443_create_affectation_marin_table	1
18	2026_08_31_184611_create_elem_paie_table	1
19	2026_08_31_184720_create_cotisation_table	1
20	2026_08_31_184925_create_igr_bareme_table	1
21	2026_08_31_185112_create_taux_change_table	1
22	2026_08_31_185414_create_paie_table	1
23	2026_08_31_185516_create_bulletin_paie_table	1
24	2026_08_31_185558_create_bulletin_jour_table	1
25	2026_08_31_185656_create_bulletin_elem_paie_table	1
26	2026_08_31_185757_create_bulletin_cotisation_table	1
27	2026_08_31_185935_create_avance_table	1
28	2026_08_31_190010_create_remboursement_avance_table	1
29	2026_08_31_190042_create_delegation_table	1
30	2026_08_31_190238_create_bulletin_delegation_table	1
31	2026_09_04_083642_fresh	1
32	2026_09_09_053206_add_nbre_charges_to_employes_table	2
33	2026_09_09_053520_make_devises_on_bulletins_nullable	2
34	2026_09_09_060000_make_devise_nullable_on_avances_delegations	2
\.


--
-- Name: migrations_id_seq; Type: SEQUENCE SET; Schema: public; Owner: -
--

SELECT pg_catalog.setval('public.migrations_id_seq', 34, true);


--
-- PostgreSQL database dump complete
--

\unrestrict jYD0sXYhmjeyfbzE7JtWpov7DErNw6uPwkjLGFIdzlv2cAAwyfbsWY9slK40mgm

