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
            Route::get('/produit/find','admin\produitController@find')->name('find_produit');
            Route::get('/produit/magasin/find/','admin\produitController@find_by_m')->name('find_produit_by_m');
            Route::get('admin/stocks/get','admin\StockController@get_stock_admin')->name('get_stock_admin');


            Route::get('/logout',function(){
                \Illuminate\Support\Facades\Auth::logout();
                return redirect()->route('login');
            })->name('logout');


            Route::group(['middleware'=>'auth2'],function () {

                Route::get('/', function () {
                    return view('index')->with(['big_title'=>'Accueil']);
                })->name('home');

                Route::get('/logout',function(){
                    \Illuminate\Support\Facades\Auth::logout();
                    \Illuminate\Support\Facades\Session::flush();
                    return redirect()->route('login');
                })->name('logout');

               Route::get('admin/get-boutique-list','admin\BoutiqueController@getList')->name('get_boutique_list');
                Route::get('admin/set-boutique','admin\BoutiqueController@setBoutique')->name('set_boutique');
                Route::post('utilisateur/edit-password','admin\UserController@edit_password')->name('edit_password');

              //  Route::post('/inscriptions/payement/update', ['middleware' => 'action', 'action' => Action::ACTION_ADMIN_STUDENT_PAYMENT_UPDATE,'uses'=>'inscriptions\payementController@update','as'=>'update_payement']);


              //  Route::group(['middleware'=>'g_admin'],function () {
                    //utilisateurs

                    Route::get('admin/utilisateurs/', 'admin\UserController@index')->name('user_management');
                    Route::get('admin/utilisateurs/activer', 'admin\UserController@activate')->name('activate_user');
                    Route::get('admin/utilisateurs/desactiver/{id}', 'admin\UserController@deactivate')->name('deactivate_user');

                    //routes pour les roles
                    Route::get('admin/roles/{id}',['uses'=>'admin\UserController@index_role','as'=>'role_management']);
                    Route::post('admin/roles/store',['uses'=>'admin\UserController@store_role','as'=>'store_role']);
                    Route::get('admin/roles/delete/{id}',['uses'=>'admin\UserController@destroy_role','as'=>'delete_role']);

                    //Les logs
                    Route::get('admin/logs/',['uses'=>'admin\UserController@logs','as'=>'show_log']);

                    ///////////////////// Les boutiques
                    Route::get('admin/boutique','admin\BoutiqueController@index')->name('boutique_management');
                    Route::post('admin/boutique/store','admin\BoutiqueController@store')->name('store_boutique');
                    Route::post('admin/boutique/update','admin\BoutiqueController@update')->name('update_boutique');
                    Route::post('admin/boutique/delete/','admin\BoutiqueController@destroys')->name('mdelete_boutique');

                    //Gestion
                    Route::get('management','ManagementController@index')->name('management');
                    Route::get('management/get-infos','ManagementController@getInfos')->name('get_infos_man');



             //   });



                Route::group(['middleware'=>'g_edit'],function () {

//              ADMINISTRATION

                    Route::get('/admin/dashboard',function(){
                        return view('admin.index')->with(['big_title'=>'Administration']);
                    })->name('dashboard');


                    //////////////////Les categories
                    Route::get('admin/categorie','admin\CategorieController@index')->name('categorie_management');
                    Route::post('admin/categorie/store','admin\CategorieController@store')->name('store_categorie');
                    Route::post('admin/categorie/update','admin\CategorieController@update')->name('update_categorie');
                    Route::post('admin/categorie/delete/','admin\CategorieController@destroys')->name('mdelete_categorie');
                    ///////////////////////

                    /////////////////////Les produits
                    Route::get('admin/produit','admin\produitController@index')->name('produit_management');
                    Route::get('admin/produit1','admin\produitController@index1')->name('produit_management1');
                    Route::post('admin/produit/store','admin\produitController@store')->name('store_produit');
                    Route::post('admin/produit/store1','admin\produitController@store1')->name('store_produit1');
                    Route::post('admin/produit/update','admin\produitController@update')->name('update_produit');
                    Route::post('admin/produit/update1','admin\produitController@update1')->name('update_produit1');
                    Route::post('admin/produit/delete/','admin\produitController@destroys')->name('mdelete_produit');

                    Route::get('admin/produit/lies/{id}','admin\produitController@index_lies')->name('produit_lies_management');
                    Route::post('admin/produit/lies/store','admin\produitController@store_lies')->name('store_produit_lies');
                    Route::get('admin/produit/lies/delete/{id}','admin\produitController@destroy_lies')->name('delete_produit_lies');


                    ////////////////////Les pays
                    Route::get('admin/pays','admin\paysController@index')->name('pays_management');
                    Route::post('admin/pays/store','admin\paysController@store')->name('store_pays');
                    Route::post('admin/pays/update','admin\paysController@update')->name('update_pays');
                    Route::post('admin/pays/delete/','admin\paysController@destroys')->name('mdelete_pays');

                    ////////////////////Les personnel
                    Route::get('admin/personnel','admin\personnelController@index')->name('personnel_management');
                    Route::post('admin/personnel/store','admin\personnelController@store')->name('store_personnel');
                    Route::post('admin/personnel/update','admin\personnelController@update')->name('update_personnel');
                    Route::post('admin/personnel/delete/','admin\personnelController@destroys')->name('mdelete_personnel');

                    ///////////////////Les clients
                    Route::get('admin/client','admin\clientController@index')->name('client_management');
                    Route::post('admin/client/store','admin\clientController@store')->name('store_client');
                    Route::post('admin/client/store1','admin\clientController@store1')->name('store_client1');
                    Route::post('admin/client/update','admin\clientController@update')->name('update_client');
                    Route::post('admin/client/delete/','admin\clientController@destroys')->name('mdelete_client');

                    /////////Les fournisseurs
                    Route::get('admin/fournisseur','admin\fournisseurController@index')->name('fournisseur_management');
                    Route::post('admin/fournisseur/store','admin\fournisseurController@store')->name('store_fournisseur');
                    Route::post('admin/fournisseur/store1','admin\fournisseurController@store1')->name('store_fournisseur1');
                    Route::post('admin/fournisseur/update','admin\fournisseurController@update')->name('update_fournisseur');
                    Route::post('admin/fournisseur/delete/','admin\fournisseurController@destroys')->name('mdelete_fournisseur');

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
                    Route::get('admin/emails','DefaultController@index_email')->name('email_management');
                    Route::post('admin/email/store','DefaultController@store_email')->name('store_email');
                    Route::get('admin/emails/destroy/{id}','DefaultController@destroy_email')->name('destroy_email');
                    Route::get('/produits/search', [\GEICOM\Http\Controllers\admin\produitController::class, 'search'])->name('produits.search');



                    //categorie budgetaire
                    Route::post('/comptabilite/categorie/update', 'comptabilite\Categorie_budgetairesController@update')->name('update_categorie');
                    Route::post('/comptabilite/categorie/store', 'comptabilite\Categorie_budgetairesController@store')->name('store_categorie');
                    Route::get('/comptabilite/categorie/', 'comptabilite\Categorie_budgetairesController@index')->name('index_categorie');
                    Route::get('/comptabilite/categorie/destroy/{id}', 'comptabilite\Categorie_budgetairesController@destroy')->name('destroy_categorie');;

                    //ligne budgetaire

                    Route::post('/comptabilite/ligne/update', 'comptabilite\LigneBudgetController@update')->name('update_ligne');
                    Route::post('/comptabilite/ligne/store', 'comptabilite\LigneBudgetController@store')->name('store_ligne');
                    Route::get('/comptabilite/ligne/', 'comptabilite\LigneBudgetController@index')->name('index_ligne');
                    Route::get('/comptabilite/ligne/get-data/{id}', 'comptabilite\LigneBudgetController@getLineData')->name('getLingeData_ligne');;
                    Route::get('/comptabilite/ligne/report/{id}', 'comptabilite\LigneBudgetController@report')->name('report_ligne');
                    Route::get('/comptabilite/', 'comptabilite\comptabiliteController@index')->name('comptabilite');


                    Route::any('/comptabilite/bilan/entrees', 'comptabilite\bilanComptaController@index');
                    Route::any('/comptabilite/bilan/sorties', 'comptabilite\bilanComptaController@indexSorties');


                    Route::get('/comptabilite/ligne/destroy/{id}', 'comptabilite\LigneBudgetController@destroy');

                    Route::get('/comptabilite/donnee/ligne/{id}', 'comptabilite\LigneBudgetController@edit_data');
                    Route::post('/comptabilite/donnee/ligne/{id}', 'comptabilite\LigneBudgetController@update_data');


                    Route::post('/admin/retrait/store', 'admin\decaissementController@store');
                    Route::post('/admin/retrait/update-montant',  'admin\decaissementController@update_amount');

                    Route::get('/admin/retrait/','admin\decaissementController@index');
                    Route::get('/search/retrait', 'searchController@retrait');
                    Route::post('/admin/retrait/store', 'admin\decaissementController@store');
                    Route::post('/admin/retrait/update-montant','admin\decaissementController@update_amount')->name('update_retrait_amount');



                    Route::post('/comptabilite/store/', 'comptabilite\personnelController@store');
                    Route::post('/comptabilite/update/{id}', 'comptabilite\personnelController@update');
                    Route::post('/comptabilite/fonction/store', 'comptabilite\fonction_personnelController@store');
                    Route::post('/comptabilite/fonction/update', 'comptabilite\fonction_personnelController@update');
                    Route::get('/comptabilite/destroy/{id}', 'comptabilite\personnelController@destroy');
                    Route::get('/comptabilite/fonction/destroy/{id}', 'comptabilite\fonction_personnelController@destroy');
                    Route::get('/personnel/', 'comptabilite\personnelController@index')->name('personnel');
                });



                Route::group(['middleware'=>'g_vente'],function () {
                    /////GESTION DES VENTES

                    Route::get('/ventes','ventes\VentesController@index')->name('ventes');

                    Route::get('ventes/create','ventes\VentesController@create')->name('new_vente');
                    Route::get('ventes/facture/change-state','ventes\VentesController@changeFactureState')->name('change_facture_state');
                    Route::get('ventes/facture/{id}','ventes\VentesController@facture')->name('show_facture');
                    Route::get('ventes/clients/list','ventes\VentesController@liste_clients')->name('liste_clients');
                    Route::get('ventes/liste','ventes\VentesController@liste_ventes')->name('liste_ventes');
                    Route::get('ventes/delete/{id}','ventes\VentesController@destroyFacture')->name('delete_ventes');
                    Route::get('ventes/liste_p','ventes\VentesController@liste_ventes_produit')->name('liste_ventes_produit');
                    Route::get('ventes/liste_u','ventes\VentesController@liste_ventes_user')->name('liste_ventes_user');
                    Route::get('ventes/liste_clt','ventes\VentesController@liste_ventes_clients')->name('liste_ventes_clients');
                    Route::get('ventes/liste/details/{id}','ventes\VentesController@details_ventes')->name('details_ventes');
                    Route::post('ventes/store','ventes\VentesController@store')->name('store_vente');
                    Route::any('/ventes/produit/list',['uses'=>'stocks\StockController@liste_produits','as'=>'liste_produits']);


                    //GESTION  DES STOCKS
                    Route::get('/stocks','stocks\StockController@index')->name('stocks');

                    Route::any('/stocks/historique/','stocks\StockController@historique')->name('view_historique');
                    Route::any('/stock/check',['uses'=>'stocks\StockController@view_stock','as'=>'view_stock']);
                    Route::any('/stock/get',['uses'=>'stocks\StockController@get_stock_ajax','as'=>'get_stock_ajax']);
                    Route::any('/stock/produit/detailler',['uses'=>'stocks\StockController@detailler_produit','as'=>'detailler_produit']);
                    Route::get('ventes/fournisseurs/list','ventes\VentesController@liste_fournisseurs')->name('liste_fournisseurs');




                    //ACHATS
                    Route::get('achats/create','stocks\StockController@create')->name('nouvel_achat');
                    Route::post('achats/store','stocks\StockController@store')->name('store_achat');
                    Route::get('achats/liste','stocks\StockController@liste_achats')->name('liste_achats');
                    Route::get('achats/liste/details/{id}','stocks\StockController@details_achats')->name('details_achats');

                    // APROVISIONNEMENTS
                    Route::get('approv/create','stocks\StockController@create_approv')->name('new_approv');
                    Route::post('approv/store','stocks\StockController@store_approv')->name('store_approv');
                    Route::get('approv/liste','stocks\StockController@liste_approvs')->name('liste_approvs');
                    Route::get('approv/liste/details/{id}','stocks\StockController@details_approvs')->name('details_approvs');



//              GESTION DES RAPPORTS
                    Route::get('rapports','rapports\RapportController@index')->name('index_rapport');
                    Route::any('rapports/ventes','rapports\RapportController@rapport_ventes')->name('rapport_ventes');
                    Route::any('rapports/stocks','rapports\RapportController@rapport_stocks')->name('rapport_stocks');
                    Route::any('get/rapports/stocks','rapports\RapportController@rapport_stocks_ajax')->name('get_rapport_stocks');

                    Route::any('rapports/ventes/send','rapports\RapportController@send_rapport_ventes')->name('send_rapport_ventes');
                    Route::any('rapports/stocks/send','rapports\RapportController@send_rapport_stocks')->name('send_rapport_stocks');


                });




            });
});



