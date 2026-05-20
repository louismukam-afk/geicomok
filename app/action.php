<?php

namespace GEICOM;

use Illuminate\Database\Eloquent\Model;

class action extends Model
{
    const ACTION_ALMOST_ALL = 'ACTION_ALMOST_ALL';
    const ACTION_ALL = 'ACTION_ALL';

    /* ADMINISTRATION */
    const ACTION_ADMIN = 'ACTION_ADMIN';



    //USERS & ROLES


    const ACTION_ADMIN_USER = 'ACTION_ADMIN_USER';
    const ACTION_ADMIN_USER_ACTIVATE_DEACTIVATE = 'ACTION_ADMIN_USER_ACTIVATE_DEACTIVATE';
    const ACTION_ADMIN_USER_DELETE = 'ACTION_ADMIN_USER_DELETE';
    const ACTION_ADMIN_USER_ACTION = 'ACTION_ADMIN_USER_ACTION';
    const ACTION_ADMIN_USER_ACTION_CU = 'ACTION_ADMIN_USER_ACTION_CU';
    const ACTION_TEACHER = 'ACTION_TEACHER';


    //produit et roles
    const ACTION_ADMIN_PRODUIT_ACTION_FIND  = 'ACTION_ADMIN_PRODUIT_ACTION_FIND';
    const ACTION_ADMIN_PRODUIT_ACTION_FIND_BY_M  = 'ACTION_ADMIN_PRODUIT_ACTION_FIND_BY_M';
    const ACTION_ADMIN_STOKS_ACTION_GET_STOCKS_ADMIN = 'ACTION_ADMIN_STOCKS_ACION_GET_STOCKS_ADMIN';

     //BOUTIQUE

    const ACTION_ADMIN_BOUTIQUE_ACTION_BOUTIQUE_LIST = 'ACTION_ADMIN_BOUTIQUE_ACION_BOUTIQUE_LIST_ADMIN';
    const ACTION_ADMIN_BOUTIQUE_ACTION_BOUTIQUE_SET = 'ACTION_ADMIN_BOUTIQUE_ACION_BOUTIQUE_SET_ADMIN';
    const ACTION_ADMIN_USER_ACTION_USER_EDIT_PASS = 'ACTION_ADMIN_USER_ACION_USER_EDIT_PASS';



    //Les logs
    const ACTION_ADMIN_LOGS_ACTION_SHOW_LOG = 'ACTION_ADMIN_LOGS_ACION_SHOW_LOG';

    ///////////////////// Les boutiques

    const ACTION_ADMIN_BOUTIQUE_ACTION_INDEX = 'ACTION_ADMIN_BOUTIQUE_ACION_BOUTIQUE_MANAGEMENT';
    const ACTION_ADMIN_BOUTIQUE_ACTION_STORE = 'ACTION_ADMIN_BOUTIQUE_ACION_STORE_BOUTIQUE';
    const ACTION_ADMIN_BOUTIQUE_ACTION_UPDATE = 'ACTION_ADMIN_BOUTIQUE_ACION_UPDATE_BOUTIQUE';
    const ACTION_ADMIN_BOUTIQUE_ACTION_DELETE = 'ACTION_ADMIN_BOUTIQUE_ACION_MDELETE_BOUTIQUE';
    const ACTION_ADMIN_CAISSE_ACTION_INDEX = 'ACTION_ADMIN_CAISSE_ACTION_INDEX';
    const ACTION_ADMIN_CAISSE_ACTION_STORE = 'ACTION_ADMIN_CAISSE_ACTION_STORE';
    const ACTION_ADMIN_CAISSE_ACTION_AFFECT = 'ACTION_ADMIN_CAISSE_ACTION_AFFECT';
    const ACTION_ADMIN_CAISSE_ACTION_TRANSFERT = 'ACTION_ADMIN_CAISSE_ACTION_TRANSFERT';
    const ACTION_CAISSE_ACTION_ETAT = 'ACTION_CAISSE_ACTION_ETAT';

    //Gestion
    const ACTION_ADMIN_MANAGEMENT_ACTION_INDEX = 'ACTION_ADMIN_MANAGEMENT';
    const ACTION_ADMIN_MANAGEMENT_ACTION_GET_INFOS_MAN = 'ACTION_ADMIN_MANAGEMENT_ACION_GET_INFOS_MAN';

// CATEGORIE

    const ACTION_ADMIN_CATEGORIE_ACTION_INDEX = 'ACTION_ADMIN_CATEGORIE_ACION_CATEGORIE_MANAGEMENT';
    const ACTION_ADMIN_CATEGORIE_ACTION_STORE = 'ACTION_ADMIN_CATEGORIE_ACION_STORE_CATEGORIE';
    const ACTION_ADMIN_CATEGORIE_ACTION_UPDATE = 'ACTION_ADMIN_CATEGORIE_ACION_UPDATE_CATEGORIE';
    const ACTION_ADMIN_CATEGORIE_ACTION_DELETE = 'ACTION_ADMIN_CATEGORIE_ACION_MDELETE_CATEGORIE';

    //produits

    const ACTION_ADMIN_PRODUIT_ACTION_INDEX = 'ACTION_ADMIN_PRODUIT_ACION_PRODUIT_MANAGEMENT';
    const ACTION_ADMIN_PRODUIT_ACTION_STORE = 'ACTION_ADMIN_PRODUIT_ACION_STORE_PRODUIT';
    const ACTION_ADMIN_PRODUIT_ACTION_UPDATE = 'ACTION_ADMIN_PRODUIT_ACION_UPDATE_PRODUIT';
    const ACTION_ADMIN_PRODUIT_ACTION_DELETE = 'ACTION_ADMIN_PRODUIT_ACION_MDELETE_PRODUIT';

    const ACTION_ADMIN_PRODUIT_ACTION_INDEX1 = 'ACTION_ADMIN_PRODUIT_ACION_PRODUIT_MANAGEMENT1';
    const ACTION_ADMIN_PRODUIT_ACTION_STORE1 = 'ACTION_ADMIN_PRODUIT_ACION_STORE_PRODUIT1';
    const ACTION_ADMIN_PRODUIT_ACTION_UPDATE1 = 'ACTION_ADMIN_PRODUIT_ACION_UPDATE_PRODUIT1';
    const ACTION_ADMIN_PRODUIT_ACTION_DELETE1 = 'ACTION_ADMIN_PRODUIT_ACION_MDELETE_PRODUIT1';


    // PRODUITS LIES

    const ACTION_ADMIN_PRODUIT_LIES_ACTION_INDEX_LIES = 'ACTION_ADMIN_PRODUIT_LIES_ACION_PRODUIT_LIES_MANAGEMENT';
    const ACTION_ADMIN_PRODUIT_LIES_ACTION_STORE = 'ACTION_ADMIN_PRODUIT_LIES_ACION_STORE_PRODUIT_LIES';
    const ACTION_ADMIN_PRODUIT_LIES_ACTION_DELETE = 'ACTION_ADMIN_PRODUIT_LIES_ACION_MDELETE_PRODUIT_LIES';
    ////////////////////Les pays

    const ACTION_ADMIN_PAYS_ACTION_INDEX = 'ACTION_ADMIN_PAYS_ACION_PAYS_MANAGEMENT';
    const ACTION_ADMIN_PAYS_ACTION_STORE = 'ACTION_ADMIN_PAYS_ACION_STORE_PAYS';
    const ACTION_ADMIN_PAYS_ACTION_UPDATE = 'ACTION_ADMIN_PAYS_ACION_UPDATE_PAYS';
    const ACTION_ADMIN_PAYS_ACTION_DELETE = 'ACTION_ADMIN_PAYS_ACION_MDELETE_PAYS';

    ////////////////////Les personnel

    const ACTION_ADMIN_PERSONNEL_ACTION_INDEX = 'ACTION_ADMIN_PERSONNEL_ACION_PERSONNEL_MANAGEMENT';
    const ACTION_ADMIN_PERSONNEL_ACTION_STORE = 'ACTION_ADMIN_PERSONNEL_ACION_STORE_PERSONNEL';
    const ACTION_ADMIN_PERSONNEL_ACTION_UPDATE = 'ACTION_ADMIN_PERSONNEL_ACION_UPDATE_PERSONNEL';
    const ACTION_ADMIN_PERSONNEL_ACTION_DELETE = 'ACTION_ADMIN_PERSONNEL_ACION_MDELETE_PERSONNEL';

    ///////////////////Les clients

    const ACTION_ADMIN_CLIENT_ACTION_INDEX = 'ACTION_ADMIN_CLIENT_ACION_CLIENT_MANAGEMENT';
    const ACTION_ADMIN_CLIENT_ACTION_STORE = 'ACTION_ADMIN_CLIENT_ACION_STORE_CLIENT';
    const ACTION_ADMIN_CLIENT_ACTION_STORE1 = 'ACTION_ADMIN_CLIENT_ACION_STORE_CLIENT1';
    const ACTION_ADMIN_CLIENT_ACTION_UPDATE = 'ACTION_ADMIN_CLIENT_ACION_UPDATE_CLIENT';
    const ACTION_ADMIN_CLIENT_ACTION_DELETE = 'ACTION_ADMIN_CLIENT_ACION_MDELETE_CLIENT';

    /////////Les fournisseurs
    const ACTION_ADMIN_FOURNISSEUR_ACTION_INDEX = 'ACTION_ADMIN_PFOURNISSEUR_ACION_CLIENT_MANAGEMENT';
    const ACTION_ADMIN_FOURNISSEUR_ACTION_STORE = 'ACTION_ADMIN_FOURNISSEUR_ACION_STORE_FOURNISSEUR';
    const ACTION_ADMIN_FOURNISSEUR_ACTION_STORE1 = 'ACTION_ADMIN_FOURNISSEUR_ACION_STORE_FOURNISSEUR1';
    const ACTION_ADMIN_FOURNISSEUR_ACTION_UPDATE = 'ACTION_ADMIN_FOURNISSEUR_ACION_UPDATE_FOURNISSEUR';
    const ACTION_ADMIN_FOURNISSEUR_ACTION_DELETE = 'ACTION_ADMIN_FOURNISSEUR_ACION_MDELETE_FOURNISSEUR';



    ////////Les Salaires

    const ACTION_ADMIN_SALAIRE_ACTION_INDEX = 'ACTION_ADMIN_SALAIRE_ACION_SALAIRE_MANAGEMENT';
    const ACTION_ADMIN_SALAIRE_ACTION_STORE = 'ACTION_ADMIN_SALAIRE_ACION_STORE_SALAIRE';
    const ACTION_ADMIN_SALAIRE_ACTION_UPDATE = 'ACTION_ADMIN_SALAIRE_ACION_UPDATE_SALAIRE';
    const ACTION_ADMIN_SALAIRE_ACTION_DELETE = 'ACTION_ADMIN_SALAIRE_ACION_MDELETE_SALAIRE';

    ////////Les Stocks

    const ACTION_ADMIN_STOCKS_ACTION_INDEX = 'ACTION_ADMIN_STOCKS_ACION_STOCKS_MANAGEMENT';
    const ACTION_ADMIN_STOCKS_ACTION_UPDATE = 'ACTION_ADMIN_STOCKS_ACION_UPDATE_STOCKS';
    const ACTION_ADMIN_STOCKS_ACTION_UPDATE_STOCK_AJAX = 'ACTION_ADMIN_STOCKS_ACION_UPDATE_STOCK_AJAX';

    ///// Autres
    ///
    const ACTION_PARAMETRES_ACTION_INDEX_PARAMETRES = 'ACTION_PARAMETRES_ACION_PARAMETRES_MANAGEMENT';
    const ACTION_PARAMETRES_ACTION_STORE = 'ACTION_PARAMETRES_ACION_STORES_PARAMETRES';

    ///emails

    const ACTION_ADMIN_EMAIL_ACTION_INDEX = 'ACTION_ADMIN_EMAIL_ACION_EMAIL_MANAGEMENT';
    const ACTION_ADMIN_EMAIL_ACTION_STORE = 'ACTION_ADMIN_EMAIL_ACION_STORE_EMAIL';
    const ACTION_ADMIN_EMAIL_ACTION_DESTROY = 'ACTION_ADMIN_EMAIL_ACION_DESTROY_EMAIL';


    /////GESTION DES VENTES


    const ACTION_VENTES_ACTION_INDEX_VENTES = 'ACTION_VENTES_ACION_INDEX_VENTES';
    const ACTION_VENTES_ACTION_CREATE_VENTE = 'ACTION_VENTES_ACION_CREATE_VENTES';
    const ACTION_VENTES_ACTION_CHANGE_STATE_VENTE = 'ACTION_VENTES_ACION_CHANGE_FACTURE_STATE_VENTE';
    const ACTION_VENTES_ACTION_FACTURE_VENTE = 'ACTION_VENTES_ACION_FACTURE_VENTE';
    const ACTION_VENTES_ACTION_LIST_CLIENTS_VENTE = 'ACTION_VENTES_ACION_LIST_CLIENTS_VENTE';
    const ACTION_VENTES_ACTION_LIST_VENTES = 'ACTION_VENTES_ACION_LIST_VENTES';
    const ACTION_VENTES_ACTION_LIST_VENTES_CONNECTED_USER = 'ACTION_VENTES_ACION_LIST_VENTES_CONNECTED_USER';
    const ACTION_VENTES_ACTION_LIST_VENTES_PRODUIT = 'ACTION_VENTES_ACION_LIST_VENTES_PRODUIT';
    const ACTION_VENTES_ACTION_LIST_VENTES_USER = 'ACTION_VENTES_ACION_LIST_VENTES_USER';
    const ACTION_VENTES_ACTION_LIST_VENTES_CLIENT = 'ACTION_VENTES_ACION_LIST_VENTES_CLIENT';
    const ACTION_VENTES_ACTION_DELETE_VENTES = 'ACTION_VENTES_ACION_DELETE_VENTES';
    const ACTION_VENTES_ACTION_DETAILS_VENTES = 'ACTION_VENTES_ACION_DETAILS_VENTES';
    const ACTION_VENTES_ACTION_STORE_VENTES = 'ACTION_VENTES_ACION_STORE_VENTES';
    const ACTION_VENTES_ACTION_LIST_PRODUIT_VENTES = 'ACTION_VENTES_ACION_LIST_PRODUIT_VENTES';
    const ACTION_VENTES_ACTION_BON_CREDIT = 'ACTION_VENTES_ACION_BON_CREDIT';
    const ACTION_VENTES_ACTION_BON_CREDIT_STORE = 'ACTION_VENTES_ACION_BON_CREDIT_STORE';
    const ACTION_VENTES_ACTION_BON_CREDIT_UPDATE = 'ACTION_VENTES_ACION_BON_CREDIT_UPDATE';
    const ACTION_VENTES_ACTION_BON_CREDIT_DELETE = 'ACTION_VENTES_ACION_BON_CREDIT_DELETE';
    const ACTION_VENTES_ACTION_BON_CREDIT_VENTE = 'ACTION_VENTES_ACION_BON_CREDIT_VENTE';
    const ACTION_VENTES_ACTION_BON_CREDIT_REMBOURSEMENT = 'ACTION_VENTES_ACION_BON_CREDIT_REMBOURSEMENT';

//GESTION  DES STOCKS

    const ACTION_STOCKS_ACTION_INDEX_STOCKS = 'ACTION_STOCKS_ACION_INDEX_STOCKS';
    const ACTION_STOCKS_ACTION_HISTORIQUE_STOCKS = 'ACTION_STOCKS_ACION_HISTORIQUE_STOCKS';
    const ACTION_STOCKS_ACTION_HISTORIQUE_GENERAL = 'ACTION_STOCKS_ACION_HISTORIQUE_GENERAL';
    const ACTION_STOCKS_ACTION_CHECK_STOCKS = 'ACTION_STOCKS_ACION_CHECK_STOCKS';
    const ACTION_STOCKS_ACTION_GET_STOCKS_AJAX = 'ACTION_STOCKS_ACION_GET_STOCKS_AJAX';
    const ACTION_STOCKS_ACTION_DETAILLER_PRODUIT_STOCK = 'ACTION_STOCKS_ACION_DETAILLER_PRODUIT_STOCK';
    const ACTION_STOCKS_ACTION_INVENTAIRE = 'ACTION_STOCKS_ACION_INVENTAIRE';
    const ACTION_STOCKS_ACTION_INVENTAIRE_CONSOLIDER = 'ACTION_STOCKS_ACION_INVENTAIRE_CONSOLIDER';
    const ACTION_STOCKS_ACTION_INVENTAIRE_RAPPORT = 'ACTION_STOCKS_ACION_INVENTAIRE_RAPPORT';
    const ACTION_VENTES_ACTION_LIST_FOURNISSEURS_VENTES = 'ACTION_VENTES_ACION_LIST_FOURNISSEURS_VENTES';



    //ACHATS
    const ACTION_ACHATS_ACTION_CREATE_ACHATS = 'ACTION_ACHATS_ACION_CREATE_ACHATS';
    const ACTION_ACHATS_ACTION_STORE_ACHATS = 'ACTION_ACHATS_ACION_STORE_ACHATS';
    const ACTION_ACHATS_ACTION_LISTE_ACHATS = 'ACTION_ACHATS_ACION_LISTE_ACHATS';
    const ACTION_ACHATS_ACTION_DETAILS_ACHATS = 'ACTION_ACHATS_ACION_DETAILS_ACHATS';


    // APROVISIONNEMENTS

    const ACTION_APPROVS_ACTION_CREATE_APPROVS = 'ACTION_APPROVS_ACION_CREATE_APPROVS';
    const ACTION_APPROVS_ACTION_STORE_APPROVS = 'ACTION_APPROVS_ACION_STORE_APPROVS';
    const ACTION_APPROVS_ACTION_LISTE_APPROVS = 'ACTION_APPROVS_ACION_LISTE_APPROVS';
    const ACTION_APPROVS_ACTION_DETAILS_APPROVS = 'ACTION_APPROVS_ACION_DETAILS_APPROVS';

//              GESTION DES RAPPORTS

    const ACTION_RAPPORTS_ACTION_INDEX_RAPPORTS = 'ACTION_RAPPORTS_ACION_INDEX_RAPPORTS';
    const ACTION_RAPPORTS_ACTION_VENTES_RAPPORTS = 'ACTION_RAPPORTS_ACION_VENTES_RAPPORTS';
    const ACTION_RAPPORTS_ACTION_STOCKS_RAPPORTS = 'ACTION_RAPPORTS_ACION_STOCKS_RAPPORTS';
    const ACTION_RAPPORTS_ACTION_STOCKS_RAPPORTS_AJAX = 'ACTION_RAPPORTS_ACION_STOCKS_RAPPORTS_AJAX';
    const ACTION_RAPPORTS_ACTION_SEND_RAPPORTS_VENTES = 'ACTION_RAPPORTS_ACION_SEND_RAPPORTS_VENTES';
    const ACTION_RAPPORTS_ACTION_SEND_RAPPORTS_STOCKS = 'ACTION_RAPPORTS_ACION_SEND_RAPPORTS_STOCKS';

    // COMPTABILITE
    const ACTION_COMPTABILITE_INDEX = 'ACTION_COMPTABILITE_INDEX';
    const ACTION_COMPTABILITE_CATEGORIE_INDEX = 'ACTION_COMPTABILITE_CATEGORIE_INDEX';
    const ACTION_COMPTABILITE_CATEGORIE_STORE = 'ACTION_COMPTABILITE_CATEGORIE_STORE';
    const ACTION_COMPTABILITE_CATEGORIE_UPDATE = 'ACTION_COMPTABILITE_CATEGORIE_UPDATE';
    const ACTION_COMPTABILITE_CATEGORIE_DELETE = 'ACTION_COMPTABILITE_CATEGORIE_DELETE';
    const ACTION_COMPTABILITE_LIGNE_INDEX = 'ACTION_COMPTABILITE_LIGNE_INDEX';
    const ACTION_COMPTABILITE_LIGNE_STORE = 'ACTION_COMPTABILITE_LIGNE_STORE';
    const ACTION_COMPTABILITE_LIGNE_UPDATE = 'ACTION_COMPTABILITE_LIGNE_UPDATE';
    const ACTION_COMPTABILITE_LIGNE_DELETE = 'ACTION_COMPTABILITE_LIGNE_DELETE';
    const ACTION_COMPTABILITE_LIGNE_DATA = 'ACTION_COMPTABILITE_LIGNE_DATA';
    const ACTION_COMPTABILITE_BILAN_ENTREES = 'ACTION_COMPTABILITE_BILAN_ENTREES';
    const ACTION_COMPTABILITE_BILAN_SORTIES = 'ACTION_COMPTABILITE_BILAN_SORTIES';
    const ACTION_COMPTABILITE_RETRAIT_INDEX = 'ACTION_COMPTABILITE_RETRAIT_INDEX';
    const ACTION_COMPTABILITE_RETRAIT_STORE = 'ACTION_COMPTABILITE_RETRAIT_STORE';
    const ACTION_COMPTABILITE_RETRAIT_UPDATE = 'ACTION_COMPTABILITE_RETRAIT_UPDATE';
    const ACTION_COMPTABILITE_PERSONNEL_INDEX = 'ACTION_COMPTABILITE_PERSONNEL_INDEX';
    const ACTION_COMPTABILITE_PERSONNEL_STORE = 'ACTION_COMPTABILITE_PERSONNEL_STORE';
    const ACTION_COMPTABILITE_PERSONNEL_UPDATE = 'ACTION_COMPTABILITE_PERSONNEL_UPDATE';
    const ACTION_COMPTABILITE_PERSONNEL_DELETE = 'ACTION_COMPTABILITE_PERSONNEL_DELETE';
    const ACTION_COMPTABILITE_FONCTION_STORE = 'ACTION_COMPTABILITE_FONCTION_STORE';
    const ACTION_COMPTABILITE_FONCTION_UPDATE = 'ACTION_COMPTABILITE_FONCTION_UPDATE';
    const ACTION_COMPTABILITE_FONCTION_DELETE = 'ACTION_COMPTABILITE_FONCTION_DELETE';

    public static function catalog()
    {
        $sections = [
            'Administration' => [
                'Utilisateurs' => [
                    self::item(self::ACTION_ADMIN_USER, 'Voir'),
                    self::item(self::ACTION_ADMIN_USER_ACTION, 'Voir les fonctions'),
                    self::item(self::ACTION_ADMIN_USER_ACTION_CU, 'Modifier les fonctions', true),
                    self::item(self::ACTION_ADMIN_USER_ACTIVATE_DEACTIVATE, 'Activer / désactiver', true),
                ],
                'Rôles' => [
                    self::item(self::ACTION_ADMIN_USER, 'Voir'),
                    self::item(self::ACTION_ADMIN_USER_ACTION_CU, 'Créer / modifier / supprimer', true),
                ],
                'Boutiques / magasins' => [
                    self::item(self::ACTION_ADMIN_BOUTIQUE_ACTION_INDEX, 'Voir'),
                    self::item(self::ACTION_ADMIN_BOUTIQUE_ACTION_STORE, 'Créer'),
                    self::item(self::ACTION_ADMIN_BOUTIQUE_ACTION_UPDATE, 'Modifier'),
                    self::item(self::ACTION_ADMIN_BOUTIQUE_ACTION_DELETE, 'Supprimer', true),
                ],
                'Caisses' => [
                    self::item(self::ACTION_ADMIN_CAISSE_ACTION_INDEX, 'Voir'),
                    self::item(self::ACTION_ADMIN_CAISSE_ACTION_STORE, 'Créer'),
                    self::item(self::ACTION_ADMIN_CAISSE_ACTION_AFFECT, 'Affecter aux utilisateurs'),
                    self::item(self::ACTION_ADMIN_CAISSE_ACTION_TRANSFERT, 'Transférer', true),
                    self::item(self::ACTION_CAISSE_ACTION_ETAT, 'Voir etat de ma caisse'),
                ],
                'Pays' => [
                    self::item(self::ACTION_ADMIN_PAYS_ACTION_INDEX, 'Voir'),
                    self::item(self::ACTION_ADMIN_PAYS_ACTION_STORE, 'Créer'),
                    self::item(self::ACTION_ADMIN_PAYS_ACTION_UPDATE, 'Modifier'),
                    self::item(self::ACTION_ADMIN_PAYS_ACTION_DELETE, 'Supprimer', true),
                ],
                'Emails de contact' => [
                    self::item(self::ACTION_ADMIN_EMAIL_ACTION_INDEX, 'Voir'),
                    self::item(self::ACTION_ADMIN_EMAIL_ACTION_STORE, 'Créer'),
                    self::item(self::ACTION_ADMIN_EMAIL_ACTION_DESTROY, 'Supprimer', true),
                ],
                'Statistiques' => [
                    self::item(self::ACTION_ADMIN_MANAGEMENT_ACTION_INDEX, 'Voir'),
                    self::item(self::ACTION_ADMIN_MANAGEMENT_ACTION_GET_INFOS_MAN, 'Données statistiques'),
                ],
                'Produits' => [
                    self::item(self::ACTION_ADMIN_PRODUIT_ACTION_INDEX, 'Voir'),
                    self::item(self::ACTION_ADMIN_PRODUIT_ACTION_INDEX1, 'Voir variante'),
                    self::item(self::ACTION_ADMIN_PRODUIT_ACTION_STORE, 'Créer'),
                    self::item(self::ACTION_ADMIN_PRODUIT_ACTION_STORE1, 'Créer variante'),
                    self::item(self::ACTION_ADMIN_PRODUIT_ACTION_UPDATE, 'Modifier'),
                    self::item(self::ACTION_ADMIN_PRODUIT_ACTION_UPDATE1, 'Modifier variante'),
                    self::item(self::ACTION_ADMIN_PRODUIT_ACTION_DELETE, 'Supprimer', true),
                    self::item(self::ACTION_ADMIN_PRODUIT_ACTION_FIND, 'Rechercher'),
                    self::item(self::ACTION_ADMIN_PRODUIT_ACTION_FIND_BY_M, 'Rechercher par magasin'),
                ],
                'Produits liés' => [
                    self::item(self::ACTION_ADMIN_PRODUIT_LIES_ACTION_INDEX_LIES, 'Voir'),
                    self::item(self::ACTION_ADMIN_PRODUIT_LIES_ACTION_STORE, 'Créer'),
                    self::item(self::ACTION_ADMIN_PRODUIT_LIES_ACTION_DELETE, 'Supprimer', true),
                ],
                'Catégories' => [
                    self::item(self::ACTION_ADMIN_CATEGORIE_ACTION_INDEX, 'Voir'),
                    self::item(self::ACTION_ADMIN_CATEGORIE_ACTION_STORE, 'Créer'),
                    self::item(self::ACTION_ADMIN_CATEGORIE_ACTION_UPDATE, 'Modifier'),
                    self::item(self::ACTION_ADMIN_CATEGORIE_ACTION_DELETE, 'Supprimer', true),
                ],
                'Clients' => [
                    self::item(self::ACTION_ADMIN_CLIENT_ACTION_INDEX, 'Voir'),
                    self::item(self::ACTION_ADMIN_CLIENT_ACTION_STORE, 'Créer'),
                    self::item(self::ACTION_ADMIN_CLIENT_ACTION_STORE1, 'Créer variante'),
                    self::item(self::ACTION_ADMIN_CLIENT_ACTION_UPDATE, 'Modifier'),
                    self::item(self::ACTION_ADMIN_CLIENT_ACTION_DELETE, 'Supprimer', true),
                ],
                'Fournisseurs' => [
                    self::item(self::ACTION_ADMIN_FOURNISSEUR_ACTION_INDEX, 'Voir'),
                    self::item(self::ACTION_ADMIN_FOURNISSEUR_ACTION_STORE, 'Créer'),
                    self::item(self::ACTION_ADMIN_FOURNISSEUR_ACTION_STORE1, 'Créer variante'),
                    self::item(self::ACTION_ADMIN_FOURNISSEUR_ACTION_UPDATE, 'Modifier'),
                    self::item(self::ACTION_ADMIN_FOURNISSEUR_ACTION_DELETE, 'Supprimer', true),
                ],
                'Personnel' => [
                    self::item(self::ACTION_ADMIN_PERSONNEL_ACTION_INDEX, 'Voir'),
                    self::item(self::ACTION_ADMIN_PERSONNEL_ACTION_STORE, 'Créer'),
                    self::item(self::ACTION_ADMIN_PERSONNEL_ACTION_UPDATE, 'Modifier'),
                    self::item(self::ACTION_ADMIN_PERSONNEL_ACTION_DELETE, 'Supprimer', true),
                ],
                'Logs' => [
                    self::item(self::ACTION_ADMIN_LOGS_ACTION_SHOW_LOG, 'Voir'),
                ],
            ],
            'Ventes et stocks' => [
                'Ventes' => [
                    self::item(self::ACTION_VENTES_ACTION_INDEX_VENTES, 'Accueil ventes'),
                    self::item(self::ACTION_VENTES_ACTION_CREATE_VENTE, 'Créer'),
                    self::item(self::ACTION_VENTES_ACTION_STORE_VENTES, 'Enregistrer'),
                    self::item(self::ACTION_VENTES_ACTION_LIST_VENTES, 'Voir la liste'),
                    self::item(self::ACTION_VENTES_ACTION_LIST_VENTES_CONNECTED_USER, 'Voir mes ventes'),
                    self::item(self::ACTION_VENTES_ACTION_LIST_VENTES_PRODUIT, 'Voir marges par produit'),
                    self::item(self::ACTION_VENTES_ACTION_LIST_VENTES_USER, 'Voir ventes par utilisateur'),
                    self::item(self::ACTION_VENTES_ACTION_LIST_VENTES_CLIENT, 'Voir ventes par client'),
                    self::item(self::ACTION_VENTES_ACTION_DETAILS_VENTES, 'Voir détails'),
                    self::item(self::ACTION_VENTES_ACTION_FACTURE_VENTE, 'Voir facture'),
                    self::item(self::ACTION_VENTES_ACTION_CHANGE_STATE_VENTE, 'Changer état', true),
                    self::item(self::ACTION_VENTES_ACTION_DELETE_VENTES, 'Supprimer', true),
                    self::item(self::ACTION_VENTES_ACTION_LIST_CLIENTS_VENTE, 'Liste clients'),
                    self::item(self::ACTION_VENTES_ACTION_LIST_FOURNISSEURS_VENTES, 'Liste fournisseurs'),
                    self::item(self::ACTION_VENTES_ACTION_LIST_PRODUIT_VENTES, 'Liste produits'),
                    self::item(self::ACTION_VENTES_ACTION_BON_CREDIT, 'Bons de credit'),
                    self::item(self::ACTION_VENTES_ACTION_BON_CREDIT_STORE, 'Creer bon de credit'),
                    self::item(self::ACTION_VENTES_ACTION_BON_CREDIT_UPDATE, 'Modifier bon de credit'),
                    self::item(self::ACTION_VENTES_ACTION_BON_CREDIT_DELETE, 'Supprimer bon de credit', true),
                    self::item(self::ACTION_VENTES_ACTION_BON_CREDIT_VENTE, 'Vendre sur bon de credit'),
                    self::item(self::ACTION_VENTES_ACTION_BON_CREDIT_REMBOURSEMENT, 'Remboursement credit'),
                ],
                'Stocks' => [
                    self::item(self::ACTION_STOCKS_ACTION_INDEX_STOCKS, 'Voir'),
                    self::item(self::ACTION_STOCKS_ACTION_HISTORIQUE_STOCKS, 'Historique'),
                    self::item(self::ACTION_STOCKS_ACTION_HISTORIQUE_GENERAL, 'Historique par periode'),
                    self::item(self::ACTION_STOCKS_ACTION_CHECK_STOCKS, 'Consulter stock'),
                    self::item(self::ACTION_STOCKS_ACTION_GET_STOCKS_AJAX, 'Recherche stock'),
                    self::item(self::ACTION_STOCKS_ACTION_DETAILLER_PRODUIT_STOCK, 'Détail produit'),
                    self::item(self::ACTION_STOCKS_ACTION_INVENTAIRE, 'Inventaire'),
                    self::item(self::ACTION_STOCKS_ACTION_INVENTAIRE_CONSOLIDER, 'Consolider inventaire', true),
                    self::item(self::ACTION_STOCKS_ACTION_INVENTAIRE_RAPPORT, 'Rapport inventaire'),
                    self::item(self::ACTION_ADMIN_STOKS_ACTION_GET_STOCKS_ADMIN, 'Stock admin'),
                ],
                'Achats' => [
                    self::item(self::ACTION_ACHATS_ACTION_CREATE_ACHATS, 'Créer'),
                    self::item(self::ACTION_ACHATS_ACTION_STORE_ACHATS, 'Enregistrer'),
                    self::item(self::ACTION_ACHATS_ACTION_LISTE_ACHATS, 'Voir la liste'),
                    self::item(self::ACTION_ACHATS_ACTION_DETAILS_ACHATS, 'Voir détails'),
                ],
                'Approvisionnements' => [
                    self::item(self::ACTION_APPROVS_ACTION_CREATE_APPROVS, 'Créer'),
                    self::item(self::ACTION_APPROVS_ACTION_STORE_APPROVS, 'Enregistrer'),
                    self::item(self::ACTION_APPROVS_ACTION_LISTE_APPROVS, 'Voir la liste'),
                    self::item(self::ACTION_APPROVS_ACTION_DETAILS_APPROVS, 'Voir détails'),
                ],
                'Rapports' => [
                    self::item(self::ACTION_RAPPORTS_ACTION_INDEX_RAPPORTS, 'Voir'),
                    self::item(self::ACTION_RAPPORTS_ACTION_VENTES_RAPPORTS, 'Rapport ventes'),
                    self::item(self::ACTION_RAPPORTS_ACTION_STOCKS_RAPPORTS, 'Rapport stocks'),
                    self::item(self::ACTION_RAPPORTS_ACTION_STOCKS_RAPPORTS_AJAX, 'Données rapport stocks'),
                    self::item(self::ACTION_RAPPORTS_ACTION_SEND_RAPPORTS_VENTES, 'Envoyer rapport ventes'),
                    self::item(self::ACTION_RAPPORTS_ACTION_SEND_RAPPORTS_STOCKS, 'Envoyer rapport stocks'),
                ],
            ],
            'Comptabilité' => [
                'Accueil' => [
                    self::item(self::ACTION_COMPTABILITE_INDEX, 'Voir'),
                ],
                'Catégories budgétaires' => [
                    self::item(self::ACTION_COMPTABILITE_CATEGORIE_INDEX, 'Voir'),
                    self::item(self::ACTION_COMPTABILITE_CATEGORIE_STORE, 'Créer'),
                    self::item(self::ACTION_COMPTABILITE_CATEGORIE_UPDATE, 'Modifier'),
                    self::item(self::ACTION_COMPTABILITE_CATEGORIE_DELETE, 'Supprimer', true),
                ],
                'Lignes budgétaires' => [
                    self::item(self::ACTION_COMPTABILITE_LIGNE_INDEX, 'Voir'),
                    self::item(self::ACTION_COMPTABILITE_LIGNE_STORE, 'Créer'),
                    self::item(self::ACTION_COMPTABILITE_LIGNE_UPDATE, 'Modifier'),
                    self::item(self::ACTION_COMPTABILITE_LIGNE_DELETE, 'Supprimer', true),
                    self::item(self::ACTION_COMPTABILITE_LIGNE_DATA, 'Données / rapport'),
                ],
                'Bilans' => [
                    self::item(self::ACTION_COMPTABILITE_BILAN_ENTREES, 'Voir entrées'),
                    self::item(self::ACTION_COMPTABILITE_BILAN_SORTIES, 'Voir sorties'),
                ],
                'Retraits' => [
                    self::item(self::ACTION_COMPTABILITE_RETRAIT_INDEX, 'Voir'),
                    self::item(self::ACTION_COMPTABILITE_RETRAIT_STORE, 'Créer'),
                    self::item(self::ACTION_COMPTABILITE_RETRAIT_UPDATE, 'Modifier montant', true),
                ],
            ],
            'Accès global' => [
                'Super permissions' => [
                    self::item(self::ACTION_ALL, 'Tout autoriser', true),
                    self::item(self::ACTION_ALMOST_ALL, 'Presque tout autoriser', true),
                ],
            ],
        ];

        return self::appendRouteActions($sections);
    }

    public static function routeActionNames()
    {
        $actions = [];

        foreach (\Route::getRoutes() as $route) {
            $routeAction = $route->getAction();
            if (isset($routeAction['action'])) {
                $actions[] = $routeAction['action'];
            }
        }

        return array_values(array_unique($actions));
    }

    public static function item($value, $label, $danger = false)
    {
        $item = ['value' => $value, 'label' => $label];
        if ($danger) {
            $item['danger'] = true;
        }
        return $item;
    }

    private static function appendRouteActions($sections)
    {
        $known = [];
        foreach ($sections as $groups) {
            foreach ($groups as $items) {
                foreach ($items as $item) {
                    $known[] = $item['value'];
                }
            }
        }

        foreach (self::routeActionNames() as $routeAction) {
            if (!in_array($routeAction, $known)) {
                $sections['Synchronisées depuis les routes']['Nouvelles fonctions'][] = self::item(
                    $routeAction,
                    self::labelFromAction($routeAction)
                );
            }
        }

        return $sections;
    }

    private static function labelFromAction($action)
    {
        $label = strtolower(str_replace(['ACTION_', '_ACION_', '_ACTION_'], ['', '_', '_'], $action));
        $label = str_replace('_', ' ', $label);
        return ucfirst($label);
    }

}
