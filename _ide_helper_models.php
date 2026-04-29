<?php
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace GEICOM{
/**
 * GEICOM\Achat
 *
 * @property int $id
 * @property int $id_produit
 * @property int $quantite
 * @property float $prix
 * @property int $id_livraison
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\Livraison $livraison
 * @property-read \GEICOM\Produit $produit
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Achat whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Achat whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Achat whereIdLivraison($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Achat whereIdProduit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Achat wherePrix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Achat whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Achat whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property float $total
 * @property string $date_achat
 * @property int|null $id_boutique
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Achat whereDateAchat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Achat whereIdBoutique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Achat whereTotal($value)
 */
	class Achat extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\Approvisionnement
 *
 * @property int $id
 * @property int $id_magasin
 * @property string|null $numero
 * @property string $date_approv
 * @property float $total
 * @property float $tva
 * @property int $paye
 * @property int $id_boutique
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\Fournisseur $magasin
 * @property-read \Illuminate\Database\Eloquent\Collection|\GEICOM\ProduitApprov[] $produits
 * @property-read \GEICOM\User $utilisateur
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Approvisionnement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Approvisionnement whereDateApprov($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Approvisionnement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Approvisionnement whereIdBoutique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Approvisionnement whereIdMagasin($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Approvisionnement whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Approvisionnement wherePaye($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Approvisionnement whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Approvisionnement whereTva($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Approvisionnement whereUpdatedAt($value)
 */
	class Approvisionnement extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\BonCommande
 *
 * @property int $id
 * @property int $id_fournisseur
 * @property string|null $numero
 * @property int $valide
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\Fournisseur $fournisseur
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\BonCommande whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\BonCommande whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\BonCommande whereIdFournisseur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\BonCommande whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\BonCommande whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\BonCommande whereValide($value)
 * @mixin \Eloquent
 */
	class BonCommande extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\Boutique
 *
 * @property int $id
 * @property string $nom
 * @property string|null $adresse
 * @property string|null $boite_postale
 * @property string|null $telephone
 * @property string|null $email
 * @property int $active
 * @property int $type
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Boutique whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Boutique whereAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Boutique whereBoitePostale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Boutique whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Boutique whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Boutique whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Boutique whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Boutique whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Boutique whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Boutique whereUpdatedAt($value)
 */
	class Boutique extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\Categorie
 *
 * @property int $id
 * @property string $libelle
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\GEICOM\Produit[] $produits
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Categorie whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Categorie whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Categorie whereLibelle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Categorie whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Categorie extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\Client
 *
 * @property int $id
 * @property string|null $nom
 * @property string|null $telephone
 * @property string|null $ville
 * @property int $id_pays
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\GEICOM\Facture[] $factures
 * @property-read \GEICOM\Pays $pays
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Client whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Client whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Client whereIdPays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Client whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Client whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Client whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Client whereVille($value)
 * @mixin \Eloquent
 * @property string|null $email
 * @property string|null $adresse
 * @property string|null $boite_postale
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Client whereAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Client whereBoitePostale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Client whereEmail($value)
 */
	class Client extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\Commande
 *
 * @property int $id
 * @property int $id_produit
 * @property int $quantite
 * @property int $id_bon_commande
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\BonCommande $BonCommande
 * @property-read \GEICOM\Produit $produit
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Commande whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Commande whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Commande whereIdBonCommande($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Commande whereIdProduit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Commande whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Commande whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Commande extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\EmailList
 *
 * @property int $id
 * @property string $email
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\EmailList whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\EmailList whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\EmailList whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\EmailList whereUpdatedAt($value)
 */
	class EmailList extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\Facture
 *
 * @property int $id
 * @property string|null $numero
 * @property string $date_vente
 * @property int $id_client
 * @property float $total
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\Client $client
 * @property-read \Illuminate\Database\Eloquent\Collection|\GEICOM\Vente[] $ventes
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereDateVente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereIdClient($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property float|null $reduction
 * @property float $tva
 * @property int $paye
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture wherePaye($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereReduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereTva($value)
 * @property int|null $id_boutique
 * @property float|null $verse
 * @property int $id_user
 * @property-read \GEICOM\User $vendeur
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereIdBoutique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Facture whereVerse($value)
 */
	class Facture extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\Fournisseur
 *
 * @property int $id
 * @property string $nom
 * @property string|null $adresse
 * @property string|null $ville
 * @property int $id_pays
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\GEICOM\Livraison[] $livraisons
 * @property-read \GEICOM\Pays $pays
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Fournisseur whereAdresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Fournisseur whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Fournisseur whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Fournisseur whereIdPays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Fournisseur whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Fournisseur whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Fournisseur whereVille($value)
 * @mixin \Eloquent
 * @property string|null $email
 * @property string|null $telephone
 * @property string|null $boite_postale
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Fournisseur whereBoitePostale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Fournisseur whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Fournisseur whereTelephone($value)
 */
	class Fournisseur extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\Functions
 *
 */
	class Functions extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\Livraison
 *
 * @property int $id
 * @property int $id_fournisseur
 * @property string|null $numero
 * @property string $date_approv
 * @property float $total
 * @property int $id_commande
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\GEICOM\Achat[] $achats
 * @property-read \GEICOM\Commande $commande
 * @property-read \GEICOM\Fournisseur $fournisseur
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Livraison whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Livraison whereDateApprov($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Livraison whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Livraison whereIdCommande($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Livraison whereIdFournisseur($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Livraison whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Livraison whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Livraison whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property float $tva
 * @property int $paye
 * @property int|null $id_boutique
 * @property int $id_user
 * @property-read \GEICOM\User $utilisateur
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Livraison whereIdBoutique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Livraison whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Livraison wherePaye($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Livraison whereTva($value)
 */
	class Livraison extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\Log
 *
 * @property int $id
 * @property string $message
 * @property int $id_boutique
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Log whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Log whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Log whereIdBoutique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Log whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Log whereUpdatedAt($value)
 */
	class Log extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\Paiement
 *
 * @property int $id
 * @property int $id_personnel
 * @property int $id_user
 * @property float $montant
 * @property string $date_p
 * @property string|null $numero
 * @property string $mois
 * @property float $primes
 * @property float $acomptes
 * @property float $cnps
 * @property float $cas_social
 * @property float $assurance
 * @property float $autre_retenues
 * @property float $total
 * @property float $total_retenu
 * @property float $net_a_payer
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\Personnel $personnel
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereAcomptes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereAssurance($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereAutreRetenues($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereCasSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereCnps($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereDateP($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereIdPersonnel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereMois($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereNetAPayer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereNumero($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement wherePrimes($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereTotalRetenu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Paiement whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Paiement extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\Parametre
 *
 * @property int $id
 * @property string $nom
 * @property string $valeur
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Parametre whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Parametre whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Parametre whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Parametre whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Parametre whereValeur($value)
 * @mixin \Eloquent
 */
	class Parametre extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\Pays
 *
 * @property int $id
 * @property string $nom
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Pays whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Pays whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Pays whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Pays whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Pays extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\Personnel
 *
 * @property int $id
 * @property string $nom
 * @property string|null $date_naiss
 * @property string|null $lieu_naiss
 * @property int $id_pays
 * @property string $sexe
 * @property string|null $date_entree
 * @property string $telephone
 * @property string $addresse
 * @property string|null $autres
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\Pays $pays
 * @property-read \GEICOM\Salaire $salaire
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereAddresse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereAutres($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereDateEntree($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereDateNaiss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereIdPays($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereLieuNaiss($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereNom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereSexe($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereTelephone($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $email
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Personnel whereEmail($value)
 */
	class Personnel extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\Produit
 *
 * @property int $id
 * @property string $libelle
 * @property string|null $description
 * @property int $quantite_minimale
 * @property string|null $reference
 * @property int $id_categorie
 * @property float $prix
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\Categorie $categorie
 * @property-read \GEICOM\Stock $stock
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit whereIdCategorie($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit whereLibelle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit wherePrix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit whereQuantiteMinimale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit whereReference($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property float $prix_achat
 * @property-read \Illuminate\Database\Eloquent\Collection|\GEICOM\Vente[] $ventes
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit wherePrixAchat($value)
 * @property float $prix_minimum
 * @property-read \Illuminate\Database\Eloquent\Collection|\GEICOM\Achat[] $achats
 * @property-read \GEICOM\ProduitLies $produit_lie
 * @property-read \Illuminate\Database\Eloquent\Collection|\GEICOM\ProduitLies[] $produit_lies
 * @property-read \Illuminate\Database\Eloquent\Collection|\GEICOM\ProduitLies[] $produit_parents
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Produit wherePrixMinimum($value)
 */
	class Produit extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\ProduitApprov
 *
 * @property int $id
 * @property int $id_produit
 * @property int $quantite
 * @property float $prix
 * @property float $total
 * @property int $id_approvisionnement
 * @property string $date_approv
 * @property int $id_boutique
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\Approvisionnement $approvisionnememt
 * @property-read \GEICOM\Produit $produit
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\ProduitApprov whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\ProduitApprov whereDateApprov($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\ProduitApprov whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\ProduitApprov whereIdApprovisionnement($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\ProduitApprov whereIdBoutique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\ProduitApprov whereIdProduit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\ProduitApprov wherePrix($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\ProduitApprov whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\ProduitApprov whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\ProduitApprov whereUpdatedAt($value)
 */
	class ProduitApprov extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\ProduitLies
 *
 * @property int $id
 * @property int $id_produit_parent
 * @property int $id_produit
 * @property int $quantite
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\Produit $produit_c
 * @property-read \GEICOM\Produit $produit_p
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\ProduitLies whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\ProduitLies whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\ProduitLies whereIdProduit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\ProduitLies whereIdProduitParent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\ProduitLies whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\ProduitLies whereUpdatedAt($value)
 */
	class ProduitLies extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\RapportState
 *
 * @property int $id
 * @property string $date_rapport
 * @property int $state
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\RapportState whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\RapportState whereDateRapport($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\RapportState whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\RapportState whereState($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\RapportState whereUpdatedAt($value)
 */
	class RapportState extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\Role
 *
 * @property int $id
 * @property int $id_user
 * @property float $value
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Role whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Role whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Role whereIdUser($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Role whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Role whereValue($value)
 */
	class Role extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\Salaire
 *
 * @property int $id
 * @property int $id_personnel
 * @property float $montant
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Salaire whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Salaire whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Salaire whereIdPersonnel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Salaire whereMontant($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Salaire whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	class Salaire extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\Stock
 *
 * @property int $id
 * @property int $id_produit
 * @property int $quantite
 * @property string $dernier_achat
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\Produit $produit
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Stock whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Stock whereDernierAchat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Stock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Stock whereIdProduit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Stock whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Stock whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property int|null $id_boutique
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Stock whereIdBoutique($value)
 */
	class Stock extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\Usage
 *
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_produit
 * @property string|null $details
 * @property string $date_utilisation
 * @property int $stock
 * @property int $quantite
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property int|null $id_boutique
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Usage whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Usage whereDateUtilisation($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Usage whereDetails($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Usage whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Usage whereIdBoutique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Usage whereIdProduit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Usage whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Usage whereStock($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Usage whereUpdatedAt($value)
 */
	class Usage extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\User
 *
 * @property int $id
 * @property string $username
 * @property string $name
 * @property string $password
 * @property int $role
 * @property int $active
 * @property string|null $remember_token
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\User whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\User whereUsername($value)
 * @mixin \Eloquent
 * @property int $id_boutique
 * @property-read \GEICOM\Boutique $boutique
 * @property-read \Illuminate\Database\Eloquent\Collection|\GEICOM\Role[] $roles
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\User whereIdBoutique($value)
 */
	class User extends \Eloquent {}
}

namespace GEICOM{
/**
 * GEICOM\Vente
 *
 * @property int $id
 * @property float $prix_unitaire
 * @property int $quantite
 * @property int $id_produit
 * @property int $id_facture
 * @property float $reduction
 * @property float $total
 * @property \Carbon\Carbon|null $created_at
 * @property \Carbon\Carbon|null $updated_at
 * @property-read \GEICOM\Facture $facture
 * @property-read \GEICOM\Produit $produit
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente whereIdFacture($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente whereIdProduit($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente wherePrixUnitaire($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente whereQuantite($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente whereReduction($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $date_vente
 * @property int|null $id_boutique
 * @property float|null $prix_achat
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente whereDateVente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente whereIdBoutique($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\GEICOM\Vente wherePrixAchat($value)
 */
	class Vente extends \Eloquent {}
}

