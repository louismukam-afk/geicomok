<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

use GEICOM\action as Action;
use GEICOM\Produit;


Route::group(['middleware'=>'start'],function () {

    Route::get('/start/step1','StartController@index')->name('start_step1');
    Route::post('/start/step1/store','StartController@store')->name('store_step1');

    Route::get('/start/step2','StartController@index2')->name('start_step2');
    Route::post('/start/step2/store','StartController@store2')->name('store_step2');

});



Route::group(['middleware'=>'start'],function () {
            Route::get('/login',function(){
                if(Auth::check())
                    return redirect(route('home'));
                return view('auth.login');
            })->name('login');

    Route::get('/unauthorized',function(){

        return view('not_auth');
    })->name('not_auth');



            Route::get('/register',function(){
                if(Auth::check())
                    return redirect(route('home'));
                return view('auth.register');
            })->name('register');

            Route::post('/login/attempt','Auth\AuthController@attempt_login')->name('attempt_login');
            Route::post('/register/store','Auth\AuthController@store')->name('store_user');
            Route::get('/produit/find', ['middleware'=>['auth2','action'], 'action'=>Action::ACTION_ADMIN_PRODUIT_ACTION_FIND, 'uses'=>'admin\produitController@find', 'as'=>'find_produit']);
            Route::get('/produit/magasin/find/', ['middleware'=>['auth2','action'], 'action'=>Action::ACTION_ADMIN_PRODUIT_ACTION_FIND_BY_M, 'uses'=>'admin\produitController@find_by_m', 'as'=>'find_produit_by_m']);
            Route::get('admin/stocks/get', ['middleware'=>['auth2','action'], 'action'=>Action::ACTION_ADMIN_STOKS_ACTION_GET_STOCKS_ADMIN, 'uses'=>'admin\StockController@get_stock_admin', 'as'=>'get_stock_admin']);


            Route::get('/logout','Auth\AuthController@logout')->name('logout');


            Route::group(['middleware'=>'auth2'],function () {

                Route::get('/', function () {
                    return view('index')->with(['big_title'=>'Accueil']);
                })->name('home');

                Route::get('/logout','Auth\AuthController@logout')->name('logout');

               Route::get('admin/get-boutique-list','admin\BoutiqueController@getList')->name('get_boutique_list');
                Route::get('admin/set-boutique','admin\BoutiqueController@setBoutique')->name('set_boutique');
                Route::post('utilisateur/edit-password','admin\UserController@edit_password')->name('edit_password');

              //  Route::post('/inscriptions/payement/update', ['middleware' => 'action', 'action' => Action::ACTION_ADMIN_STUDENT_PAYMENT_UPDATE,'uses'=>'inscriptions\payementController@update','as'=>'update_payement']);


              //  Route::group(['middleware'=>'g_admin'],function () {
                    //utilisateurs

                    Route::get('admin/utilisateurs/', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_USER, 'uses'=>'admin\UserController@index', 'as'=>'user_management']);
                    Route::get('admin/utilisateurs/activer', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_USER_ACTIVATE_DEACTIVATE, 'uses'=>'admin\UserController@activate', 'as'=>'activate_user']);
                    Route::get('admin/utilisateurs/desactiver/{id}', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_USER_ACTIVATE_DEACTIVATE, 'uses'=>'admin\UserController@deactivate', 'as'=>'deactivate_user']);
                    Route::post('admin/utilisateurs/actions/sync/{id}', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_USER_ACTION_CU, 'uses'=>'admin\actionController@sync', 'as'=>'sync_user_actions']);
                    Route::get('admin/utilisateurs/actions/{id}', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_USER_ACTION, 'uses'=>'admin\actionController@index', 'as'=>'action_management']);
                    Route::post('admin/utilisateurs/actions/update', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_USER_ACTION_CU, 'uses'=>'admin\actionController@update', 'as'=>'update_actions']);

                    //routes pour les roles
                    Route::get('admin/roles/{id}',['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_USER, 'uses'=>'admin\UserController@index_role','as'=>'role_management']);
                    Route::post('admin/roles/store',['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_USER_ACTION_CU, 'uses'=>'admin\UserController@store_role','as'=>'store_role']);
                    Route::get('admin/roles/delete/{id}',['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_USER_ACTION_CU, 'uses'=>'admin\UserController@destroy_role','as'=>'delete_role']);

                    //Les logs
                    Route::get('admin/logs/',['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_LOGS_ACTION_SHOW_LOG, 'uses'=>'admin\UserController@logs','as'=>'show_log']);

                    ///////////////////// Les boutiques
                    Route::get('admin/boutique', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_BOUTIQUE_ACTION_INDEX, 'uses'=>'admin\BoutiqueController@index', 'as'=>'boutique_management']);
                    Route::post('admin/boutique/store', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_BOUTIQUE_ACTION_STORE, 'uses'=>'admin\BoutiqueController@store', 'as'=>'store_boutique']);
                    Route::post('admin/boutique/update', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_BOUTIQUE_ACTION_UPDATE, 'uses'=>'admin\BoutiqueController@update', 'as'=>'update_boutique']);
                    Route::post('admin/boutique/delete/', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_BOUTIQUE_ACTION_DELETE, 'uses'=>'admin\BoutiqueController@destroys', 'as'=>'mdelete_boutique']);
                    Route::get('admin/caisses', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_CAISSE_ACTION_INDEX, 'uses'=>'admin\CaisseController@index', 'as'=>'caisses_management']);
                    Route::post('admin/caisses/store', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_CAISSE_ACTION_STORE, 'uses'=>'admin\CaisseController@store', 'as'=>'store_caisse']);
                    Route::get('admin/caisses/affectations', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_CAISSE_ACTION_AFFECT, 'uses'=>'admin\CaisseController@affectations', 'as'=>'caisses_affectations']);
                    Route::post('admin/caisses/affectations/store', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_CAISSE_ACTION_AFFECT, 'uses'=>'admin\CaisseController@storeAffectation', 'as'=>'store_caisse_affectation']);
                    Route::get('admin/caisses/transfert', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_CAISSE_ACTION_TRANSFERT, 'uses'=>'admin\CaisseController@transfert', 'as'=>'caisses_transfert']);
                    Route::post('admin/caisses/transfert/store', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_CAISSE_ACTION_TRANSFERT, 'uses'=>'admin\CaisseController@storeTransfert', 'as'=>'store_caisse_transfert']);
                    Route::get('ma-caisse/etat', ['middleware'=>'action', 'action'=>Action::ACTION_CAISSE_ACTION_ETAT, 'uses'=>'admin\CaisseController@etat', 'as'=>'ma_caisse_etat']);

                    //Gestion
                    Route::get('management', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_MANAGEMENT_ACTION_INDEX, 'uses'=>'ManagementController@index', 'as'=>'management']);
                    Route::get('management/get-infos', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_MANAGEMENT_ACTION_GET_INFOS_MAN, 'uses'=>'ManagementController@getInfos', 'as'=>'get_infos_man']);



             //   });



                Route::group(['middleware'=>'g_edit'],function () {

//              ADMINISTRATION

                    Route::get('/admin/dashboard',function(){
                        return view('admin.index')->with(['big_title'=>'Administration']);
                    })->name('dashboard');


                    //////////////////Les categories
                    Route::get('admin/categorie', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_CATEGORIE_ACTION_INDEX, 'uses'=>'admin\CategorieController@index', 'as'=>'categorie_management']);
                    Route::get('admin/categorie/import', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_CATEGORIE_ACTION_STORE, 'uses'=>'admin\CategorieController@importForm', 'as'=>'import_categorie']);
                    Route::post('admin/categorie/import', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_CATEGORIE_ACTION_STORE, 'uses'=>'admin\CategorieController@import', 'as'=>'import_categorie_store']);
                    Route::get('admin/categorie/import/template', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_CATEGORIE_ACTION_STORE, 'uses'=>'admin\CategorieController@template', 'as'=>'template_categorie_import']);
                    Route::post('admin/categorie/store', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_CATEGORIE_ACTION_STORE, 'uses'=>'admin\CategorieController@store', 'as'=>'store_categorie']);
                    Route::post('admin/categorie/update', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_CATEGORIE_ACTION_UPDATE, 'uses'=>'admin\CategorieController@update', 'as'=>'update_categorie']);
                    Route::post('admin/categorie/delete/', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_CATEGORIE_ACTION_DELETE, 'uses'=>'admin\CategorieController@destroys', 'as'=>'mdelete_categorie']);
                    ///////////////////////

                    /////////////////////Les produits
                    Route::get('admin/produit', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PRODUIT_ACTION_INDEX, 'uses'=>'admin\produitController@index', 'as'=>'produit_management']);
                    Route::get('admin/produit/import', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PRODUIT_ACTION_STORE, 'uses'=>'admin\produitController@importForm', 'as'=>'import_produit']);
                    Route::post('admin/produit/import', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PRODUIT_ACTION_STORE, 'uses'=>'admin\produitController@import', 'as'=>'import_produit_store']);
                    Route::get('admin/produit/import/template', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PRODUIT_ACTION_STORE, 'uses'=>'admin\produitController@template', 'as'=>'template_produit_import']);
                    Route::get('admin/produit1', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PRODUIT_ACTION_INDEX1, 'uses'=>'admin\produitController@index1', 'as'=>'produit_management1']);
                    Route::post('admin/produit/store', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PRODUIT_ACTION_STORE, 'uses'=>'admin\produitController@store', 'as'=>'store_produit']);
                    Route::post('admin/produit/store1', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PRODUIT_ACTION_STORE1, 'uses'=>'admin\produitController@store1', 'as'=>'store_produit1']);
                    Route::post('admin/produit/update', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PRODUIT_ACTION_UPDATE, 'uses'=>'admin\produitController@update', 'as'=>'update_produit']);
                    Route::post('admin/produit/update1', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PRODUIT_ACTION_UPDATE1, 'uses'=>'admin\produitController@update1', 'as'=>'update_produit1']);
                    Route::post('admin/produit/delete/', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PRODUIT_ACTION_DELETE, 'uses'=>'admin\produitController@destroys', 'as'=>'mdelete_produit']);

                    Route::get('admin/produit/lies/{id}', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PRODUIT_LIES_ACTION_INDEX_LIES, 'uses'=>'admin\produitController@index_lies', 'as'=>'produit_lies_management']);
                    Route::post('admin/produit/lies/store', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PRODUIT_LIES_ACTION_STORE, 'uses'=>'admin\produitController@store_lies', 'as'=>'store_produit_lies']);
                    Route::get('admin/produit/lies/delete/{id}', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PRODUIT_LIES_ACTION_DELETE, 'uses'=>'admin\produitController@destroy_lies', 'as'=>'delete_produit_lies']);


                    ////////////////////Les pays
                    Route::get('admin/pays', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PAYS_ACTION_INDEX, 'uses'=>'admin\paysController@index', 'as'=>'pays_management']);
                    Route::post('admin/pays/store', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PAYS_ACTION_STORE, 'uses'=>'admin\paysController@store', 'as'=>'store_pays']);
                    Route::post('admin/pays/update', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PAYS_ACTION_UPDATE, 'uses'=>'admin\paysController@update', 'as'=>'update_pays']);
                    Route::post('admin/pays/delete/', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PAYS_ACTION_DELETE, 'uses'=>'admin\paysController@destroys', 'as'=>'mdelete_pays']);

                    ////////////////////Les personnel
                    Route::get('admin/personnel', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PERSONNEL_ACTION_INDEX, 'uses'=>'admin\personnelController@index', 'as'=>'personnel_management']);
                    Route::post('admin/personnel/store', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PERSONNEL_ACTION_STORE, 'uses'=>'admin\personnelController@store', 'as'=>'store_personnel']);
                    Route::post('admin/personnel/update', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PERSONNEL_ACTION_UPDATE, 'uses'=>'admin\personnelController@update', 'as'=>'update_personnel']);
                    Route::post('admin/personnel/delete/', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PERSONNEL_ACTION_DELETE, 'uses'=>'admin\personnelController@destroys', 'as'=>'mdelete_personnel']);

                    ///////////////////Les clients
                    Route::get('admin/client', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_CLIENT_ACTION_INDEX, 'uses'=>'admin\clientController@index', 'as'=>'client_management']);
                    Route::post('admin/client/store', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_CLIENT_ACTION_STORE, 'uses'=>'admin\clientController@store', 'as'=>'store_client']);
                    Route::post('admin/client/store1', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_CLIENT_ACTION_STORE1, 'uses'=>'admin\clientController@store1', 'as'=>'store_client1']);
                    Route::post('admin/client/update', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_CLIENT_ACTION_UPDATE, 'uses'=>'admin\clientController@update', 'as'=>'update_client']);
                    Route::post('admin/client/delete/', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_CLIENT_ACTION_DELETE, 'uses'=>'admin\clientController@destroys', 'as'=>'mdelete_client']);

                    /////////Les fournisseurs
                    Route::get('admin/fournisseur', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_FOURNISSEUR_ACTION_INDEX, 'uses'=>'admin\fournisseurController@index', 'as'=>'fournisseur_management']);
                    Route::post('admin/fournisseur/store', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_FOURNISSEUR_ACTION_STORE, 'uses'=>'admin\fournisseurController@store', 'as'=>'store_fournisseur']);
                    Route::post('admin/fournisseur/store1', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_FOURNISSEUR_ACTION_STORE1, 'uses'=>'admin\fournisseurController@store1', 'as'=>'store_fournisseur1']);
                    Route::post('admin/fournisseur/update', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_FOURNISSEUR_ACTION_UPDATE, 'uses'=>'admin\fournisseurController@update', 'as'=>'update_fournisseur']);
                    Route::post('admin/fournisseur/delete/', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_FOURNISSEUR_ACTION_DELETE, 'uses'=>'admin\fournisseurController@destroys', 'as'=>'mdelete_fournisseur']);

                    ////////Les Salaires
                    Route::get('admin/salaire','admin\salaireController@index')->name('salaire_management');
                    Route::post('admin/salaire/store','admin\salaireController@store')->name('store_salaire');
                    Route::post('admin/salaire/update','admin\salaireController@update')->name('update_salaire');
                    Route::post('admin/salaire/delete/','admin\salaireController@destroys')->name('mdelete_salaire');





                    ////////Les Stocks
                    Route::get('admin/stocks','admin\StockController@index')->name('stock_management');
                    Route::get('admin/securite','admin\StockController@index_securite')->name('securite_management');
                    Route::get('admin/securite_index','admin\StockController@index_securite1')->name('securite_stock_management');
                    Route::get('admin/securite_minimum','admin\StockController@stock_minimum')->name('stock_minimum_management');
                    Route::post('admin/stock/update','admin\StockController@update')->name('update_stock');
                    Route::get('admin/stock/update/ajax','admin\StockController@update_ajax')->name('update_stock_ajax');
                    Route::get('admin/securite/store/{id}','admin\StockController@securite')->name('securite_store_management');
                    Route::post('admin/securite/update/{id}','admin\StockController@update_securite')->name('securite_update_management');
                    Route::post('admin/securite/update1/{id}','admin\StockController@update_securite1')->name('securite_update_management1');


                    ///// Autres
                    ///
                    Route::get('parametres','DefaultController@index_parametres')->name('parametres_management');
                    Route::post('parametres/store','DefaultController@store_parametres')->name('store_parametres');

                    ///emails
                    Route::get('admin/emails', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_EMAIL_ACTION_INDEX, 'uses'=>'DefaultController@index_email', 'as'=>'email_management']);
                    Route::post('admin/email/store', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_EMAIL_ACTION_STORE, 'uses'=>'DefaultController@store_email', 'as'=>'store_email']);
                    Route::get('admin/emails/destroy/{id}', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_EMAIL_ACTION_DESTROY, 'uses'=>'DefaultController@destroy_email', 'as'=>'destroy_email']);
                    Route::get('/produits/search', ['middleware'=>'action', 'action'=>Action::ACTION_ADMIN_PRODUIT_ACTION_INDEX, 'uses'=>'admin\produitController@search', 'as'=>'produits.search']);



                    //categorie budgetaire
                    Route::post('/comptabilite/categorie/update', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_CATEGORIE_UPDATE, 'uses'=>'comptabilite\Categorie_budgetairesController@update', 'as'=>'comptabilite_update_categorie']);
                    Route::post('/comptabilite/categorie/store', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_CATEGORIE_STORE, 'uses'=>'comptabilite\Categorie_budgetairesController@store', 'as'=>'comptabilite_store_categorie']);
                    Route::get('/comptabilite/categorie/', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_CATEGORIE_INDEX, 'uses'=>'comptabilite\Categorie_budgetairesController@index', 'as'=>'index_categorie']);
                    Route::get('/comptabilite/categorie/destroy/{id}', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_CATEGORIE_DELETE, 'uses'=>'comptabilite\Categorie_budgetairesController@destroy', 'as'=>'destroy_categorie']);

                    //ligne budgetaire

                    Route::post('/comptabilite/ligne/update', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_LIGNE_UPDATE, 'uses'=>'comptabilite\LigneBudgetController@update', 'as'=>'update_ligne']);
                    Route::post('/comptabilite/ligne/store', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_LIGNE_STORE, 'uses'=>'comptabilite\LigneBudgetController@store', 'as'=>'store_ligne']);
                    Route::get('/comptabilite/ligne/', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_LIGNE_INDEX, 'uses'=>'comptabilite\LigneBudgetController@index', 'as'=>'index_ligne']);
                    Route::get('/comptabilite/ligne/get-data/{id}', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_LIGNE_DATA, 'uses'=>'comptabilite\LigneBudgetController@getLineData', 'as'=>'getLingeData_ligne']);
                    Route::get('/comptabilite/ligne/report/{id}', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_LIGNE_DATA, 'uses'=>'comptabilite\LigneBudgetController@report', 'as'=>'report_ligne']);
                    Route::get('/comptabilite/', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_INDEX, 'uses'=>'comptabilite\comptabiliteController@index', 'as'=>'comptabilite']);

                    Route::get('/comptabilite/entrees-speciales', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_ENTREE_SPECIALE_INDEX, 'uses'=>'comptabilite\EntreeSpecialeController@index', 'as'=>'entrees_speciales']);
                    Route::post('/comptabilite/entrees-speciales/store', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_ENTREE_SPECIALE_STORE, 'uses'=>'comptabilite\EntreeSpecialeController@store', 'as'=>'entrees_speciales_store']);
                    Route::get('/comptabilite/entrees-speciales/rappels', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_ENTREE_SPECIALE_RAPPEL, 'uses'=>'comptabilite\EntreeSpecialeController@rappels', 'as'=>'entrees_speciales_rappels']);
                    Route::get('/comptabilite/entrees-speciales/{id}', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_ENTREE_SPECIALE_INDEX, 'uses'=>'comptabilite\EntreeSpecialeController@show', 'as'=>'entrees_speciales_show']);
                    Route::post('/comptabilite/entrees-speciales/{id}/remboursement', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_ENTREE_SPECIALE_REMBOURSEMENT, 'uses'=>'comptabilite\EntreeSpecialeController@storeRemboursement', 'as'=>'entrees_speciales_remboursement']);


                    Route::any('/comptabilite/bilan/entrees', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_BILAN_ENTREES, 'uses'=>'comptabilite\bilanComptaController@index']);
                    Route::any('/comptabilite/bilan/sorties', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_BILAN_SORTIES, 'uses'=>'comptabilite\bilanComptaController@indexSorties']);


                    Route::get('/comptabilite/ligne/destroy/{id}', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_LIGNE_DELETE, 'uses'=>'comptabilite\LigneBudgetController@destroy']);

                    Route::get('/comptabilite/donnee/ligne/{id}', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_LIGNE_DATA, 'uses'=>'comptabilite\LigneBudgetController@edit_data']);
                    Route::post('/comptabilite/donnee/ligne/{id}', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_LIGNE_DATA, 'uses'=>'comptabilite\LigneBudgetController@update_data']);


                    Route::post('/admin/retrait/store', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_RETRAIT_STORE, 'uses'=>'admin\decaissementController@store']);
                    Route::post('/admin/retrait/update-montant', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_RETRAIT_UPDATE, 'uses'=>'admin\decaissementController@update_amount']);

                    Route::get('/admin/retrait/', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_RETRAIT_INDEX, 'uses'=>'admin\decaissementController@index']);
                    Route::get('/search/retrait', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_RETRAIT_INDEX, 'uses'=>'admin\decaissementController@index']);
                    Route::post('/admin/retrait/store', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_RETRAIT_STORE, 'uses'=>'admin\decaissementController@store']);
                    Route::post('/admin/retrait/update-montant', ['middleware'=>'action', 'action'=>Action::ACTION_COMPTABILITE_RETRAIT_UPDATE, 'uses'=>'admin\decaissementController@update_amount', 'as'=>'update_retrait_amount']);

                });



                Route::group(['middleware'=>'g_vente'],function () {
                    /////GESTION DES VENTES

                    Route::get('/ventes', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_INDEX_VENTES, 'uses'=>'ventes\VentesController@index', 'as'=>'ventes']);

                    Route::get('ventes/create', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_CREATE_VENTE, 'uses'=>'ventes\VentesController@create', 'as'=>'new_vente']);
                    Route::get('ventes/facture/change-state', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_CHANGE_STATE_VENTE, 'uses'=>'ventes\VentesController@changeFactureState', 'as'=>'change_facture_state']);
                    Route::get('ventes/facture/{id}', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_FACTURE_VENTE, 'uses'=>'ventes\VentesController@facture', 'as'=>'show_facture']);
                    Route::get('ventes/clients/list', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_LIST_CLIENTS_VENTE, 'uses'=>'ventes\VentesController@liste_clients', 'as'=>'liste_clients']);
                    Route::get('ventes/liste', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_LIST_VENTES, 'uses'=>'ventes\VentesController@liste_ventes', 'as'=>'liste_ventes']);
                    Route::get('ventes/liste-moi', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_LIST_VENTES_CONNECTED_USER, 'uses'=>'ventes\VentesController@liste_ventes_connecte', 'as'=>'liste_ventes_connecte']);
                    Route::get('ventes/delete/{id}', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_DELETE_VENTES, 'uses'=>'ventes\VentesController@destroyFacture', 'as'=>'delete_ventes']);
                    Route::get('ventes/liste_p', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_LIST_VENTES_PRODUIT, 'uses'=>'ventes\VentesController@liste_ventes_produit', 'as'=>'liste_ventes_produit']);
                    Route::get('ventes/liste_u', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_LIST_VENTES_USER, 'uses'=>'ventes\VentesController@liste_ventes_user', 'as'=>'liste_ventes_user']);
                    Route::get('ventes/liste_clt', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_LIST_VENTES_CLIENT, 'uses'=>'ventes\VentesController@liste_ventes_clients', 'as'=>'liste_ventes_clients']);
                    Route::get('ventes/liste/details/{id}', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_DETAILS_VENTES, 'uses'=>'ventes\VentesController@details_ventes', 'as'=>'details_ventes']);
                    Route::post('ventes/store', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_STORE_VENTES, 'uses'=>'ventes\VentesController@store', 'as'=>'store_vente']);
                    Route::get('ventes/credit', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_BON_CREDIT, 'uses'=>'ventes\BonCreditController@index', 'as'=>'bons_credit']);
                    Route::post('ventes/credit/store', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_BON_CREDIT_STORE, 'uses'=>'ventes\BonCreditController@store', 'as'=>'bons_credit_store']);
                    Route::post('ventes/credit/{id}/update', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_BON_CREDIT_UPDATE, 'uses'=>'ventes\BonCreditController@update', 'as'=>'bons_credit_update']);
                    Route::post('ventes/credit/{id}/delete', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_BON_CREDIT_DELETE, 'uses'=>'ventes\BonCreditController@destroy', 'as'=>'bons_credit_delete']);
                    Route::get('ventes/credit/{id}/factures/print', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_BON_CREDIT, 'uses'=>'ventes\BonCreditController@printFactures', 'as'=>'bons_credit_factures_print']);
                    Route::get('ventes/credit/{id}', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_BON_CREDIT, 'uses'=>'ventes\BonCreditController@show', 'as'=>'bons_credit_show']);
                    Route::post('ventes/credit/{id}/vente', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_BON_CREDIT_VENTE, 'uses'=>'ventes\BonCreditController@storeVente', 'as'=>'bons_credit_vente']);
                    Route::post('ventes/credit/{id}/echeance', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_BON_CREDIT_REMBOURSEMENT, 'uses'=>'ventes\BonCreditController@storeEcheance', 'as'=>'bons_credit_echeance']);
                    Route::post('ventes/credit/{id}/remboursement', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_BON_CREDIT_REMBOURSEMENT, 'uses'=>'ventes\BonCreditController@storeRemboursement', 'as'=>'bons_credit_remboursement']);
                    Route::any('/ventes/produit/list', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_LIST_PRODUIT_VENTES, 'uses'=>'stocks\StockController@liste_produits', 'as'=>'liste_produits']);


                    //GESTION  DES STOCKS
                    Route::get('/stocks', ['middleware'=>'action', 'action'=>Action::ACTION_STOCKS_ACTION_INDEX_STOCKS, 'uses'=>'stocks\StockController@index', 'as'=>'stocks']);

                    Route::any('/stocks/historique/', ['middleware'=>'action', 'action'=>Action::ACTION_STOCKS_ACTION_HISTORIQUE_STOCKS, 'uses'=>'stocks\StockController@historique', 'as'=>'view_historique']);
                    Route::get('/stocks/historique-general', ['middleware'=>'action', 'action'=>Action::ACTION_STOCKS_ACTION_HISTORIQUE_GENERAL, 'uses'=>'stocks\StockController@historique_general', 'as'=>'historique_stocks_general']);
                    Route::post('/stocks/historique/synchroniser', ['middleware'=>'action', 'action'=>Action::ACTION_STOCKS_ACTION_HISTORIQUE_STOCKS, 'uses'=>'stocks\StockController@synchroniser_historique', 'as'=>'sync_historique_stock']);
                    Route::any('/stock/check', ['middleware'=>'action', 'action'=>Action::ACTION_STOCKS_ACTION_CHECK_STOCKS, 'uses'=>'stocks\StockController@view_stock', 'as'=>'view_stock']);
                    Route::any('/stock/get', ['middleware'=>'action', 'action'=>Action::ACTION_STOCKS_ACTION_GET_STOCKS_AJAX, 'uses'=>'stocks\StockController@get_stock_ajax', 'as'=>'get_stock_ajax']);
                    Route::any('/stock/produit/detailler', ['middleware'=>'action', 'action'=>Action::ACTION_STOCKS_ACTION_DETAILLER_PRODUIT_STOCK, 'uses'=>'stocks\StockController@detailler_produit', 'as'=>'detailler_produit']);
                    Route::get('/stocks/inventaires', ['middleware'=>'action', 'action'=>Action::ACTION_STOCKS_ACTION_INVENTAIRE, 'uses'=>'stocks\InventaireController@index', 'as'=>'inventaires']);
                    Route::post('/stocks/inventaires/store', ['middleware'=>'action', 'action'=>Action::ACTION_STOCKS_ACTION_INVENTAIRE, 'uses'=>'stocks\InventaireController@store', 'as'=>'inventaires_store']);
                    Route::get('/stocks/inventaires/rapport/periode', ['middleware'=>'action', 'action'=>Action::ACTION_STOCKS_ACTION_INVENTAIRE_RAPPORT, 'uses'=>'stocks\InventaireController@rapport', 'as'=>'inventaires_rapport']);
                    Route::get('/stocks/inventaires/{id}', ['middleware'=>'action', 'action'=>Action::ACTION_STOCKS_ACTION_INVENTAIRE, 'uses'=>'stocks\InventaireController@show', 'as'=>'inventaires_show']);
                    Route::post('/stocks/inventaires/{id}/update', ['middleware'=>'action', 'action'=>Action::ACTION_STOCKS_ACTION_INVENTAIRE, 'uses'=>'stocks\InventaireController@update', 'as'=>'inventaires_update']);
                    Route::post('/stocks/inventaires/{id}/consolider', ['middleware'=>'action', 'action'=>Action::ACTION_STOCKS_ACTION_INVENTAIRE_CONSOLIDER, 'uses'=>'stocks\InventaireController@consolider', 'as'=>'inventaires_consolider']);
                    Route::get('ventes/fournisseurs/list', ['middleware'=>'action', 'action'=>Action::ACTION_VENTES_ACTION_LIST_FOURNISSEURS_VENTES, 'uses'=>'ventes\VentesController@liste_fournisseurs', 'as'=>'liste_fournisseurs']);




                    //ACHATS
                    Route::get('achats/create', ['middleware'=>'action', 'action'=>Action::ACTION_ACHATS_ACTION_CREATE_ACHATS, 'uses'=>'stocks\StockController@create', 'as'=>'nouvel_achat']);
                    Route::post('achats/store', ['middleware'=>'action', 'action'=>Action::ACTION_ACHATS_ACTION_STORE_ACHATS, 'uses'=>'stocks\StockController@store', 'as'=>'store_achat']);
                    Route::get('achats/liste', ['middleware'=>'action', 'action'=>Action::ACTION_ACHATS_ACTION_LISTE_ACHATS, 'uses'=>'stocks\StockController@liste_achats', 'as'=>'liste_achats']);
                    Route::get('achats/liste/details/{id}', ['middleware'=>'action', 'action'=>Action::ACTION_ACHATS_ACTION_DETAILS_ACHATS, 'uses'=>'stocks\StockController@details_achats', 'as'=>'details_achats']);

                    // APROVISIONNEMENTS
                    Route::get('approv/create', ['middleware'=>'action', 'action'=>Action::ACTION_APPROVS_ACTION_CREATE_APPROVS, 'uses'=>'stocks\StockController@create_approv', 'as'=>'new_approv']);
                    Route::post('approv/store', ['middleware'=>'action', 'action'=>Action::ACTION_APPROVS_ACTION_STORE_APPROVS, 'uses'=>'stocks\StockController@store_approv', 'as'=>'store_approv']);
                    Route::get('approv/liste', ['middleware'=>'action', 'action'=>Action::ACTION_APPROVS_ACTION_LISTE_APPROVS, 'uses'=>'stocks\StockController@liste_approvs', 'as'=>'liste_approvs']);
                    Route::get('approv/liste/details/{id}', ['middleware'=>'action', 'action'=>Action::ACTION_APPROVS_ACTION_DETAILS_APPROVS, 'uses'=>'stocks\StockController@details_approvs', 'as'=>'details_approvs']);



//              GESTION DES RAPPORTS
                    Route::get('rapports', ['middleware'=>'action', 'action'=>Action::ACTION_RAPPORTS_ACTION_INDEX_RAPPORTS, 'uses'=>'rapports\RapportController@index', 'as'=>'index_rapport']);
                    Route::any('rapports/ventes', ['middleware'=>'action', 'action'=>Action::ACTION_RAPPORTS_ACTION_VENTES_RAPPORTS, 'uses'=>'rapports\RapportController@rapport_ventes', 'as'=>'rapport_ventes']);
                    Route::any('rapports/stocks', ['middleware'=>'action', 'action'=>Action::ACTION_RAPPORTS_ACTION_STOCKS_RAPPORTS, 'uses'=>'rapports\RapportController@rapport_stocks', 'as'=>'rapport_stocks']);
                    Route::any('get/rapports/stocks', ['middleware'=>'action', 'action'=>Action::ACTION_RAPPORTS_ACTION_STOCKS_RAPPORTS_AJAX, 'uses'=>'rapports\RapportController@rapport_stocks_ajax', 'as'=>'get_rapport_stocks']);

                    Route::any('rapports/ventes/send', ['middleware'=>'action', 'action'=>Action::ACTION_RAPPORTS_ACTION_SEND_RAPPORTS_VENTES, 'uses'=>'rapports\RapportController@send_rapport_ventes', 'as'=>'send_rapport_ventes']);
                    Route::any('rapports/stocks/send', ['middleware'=>'action', 'action'=>Action::ACTION_RAPPORTS_ACTION_SEND_RAPPORTS_STOCKS, 'uses'=>'rapports\RapportController@send_rapport_stocks', 'as'=>'send_rapport_stocks']);


                });




            });
});



