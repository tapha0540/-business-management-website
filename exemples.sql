USE gestion_commerciale;

INSERT INTO utilisateurs (prenom, nom, email, mot_de_passe, role) VALUES
('Ali','Diop','ali@site.com','pass','vendeur'),
('Moussa','Fall','moussa@site.com','pass','vendeur'),
('Awa','Ndiaye','awa@site.com','pass','vendeur'),
('Fatou','Ba','fatou@site.com','pass','vendeur'),
('Ibra','Sarr','ibra@site.com','pass','vendeur'),
('Admin','Root','admin@site.com','pass','admin'),
('Cheikh','Dia','cheikh@site.com','pass','vendeur'),
('Omar','Kane','omar@site.com','pass','vendeur'),
('Seynabou','Sy','seyna@site.com','pass','vendeur'),
('Abdou','Lo','abdou@site.com','pass','vendeur');

INSERT INTO categories (nom, description) VALUES
('Téléphones','Smartphones'),('Ordinateurs','PC'),
('Accessoires','Divers'),('Électroménager','Maison'),
('TV','Télévisions'),('Audio','Son'),
('Réseau','Modems'),('Gaming','Jeux'),
('Stockage','Disques'),('Bureautique','Bureau');

INSERT INTO produits (nom, description, imgUrl, categorie_id, prix_vente, quantite, seuil_critique) VALUES
('iPhone 13','Apple','img1.jpg',1,500000,20,5),
('Samsung S21','Samsung','img2.jpg',1,400000,15,5),
('HP ProBook','HP','img3.jpg',2,600000,10,3),
('Souris','Logitech','img4.jpg',3,5000,100,20),
('Clavier','HP','img5.jpg',3,7000,80,20),
('TV LG','LG','img6.jpg',5,350000,8,2),
('Casque','Sony','img7.jpg',6,45000,25,5),
('SSD','Kingston','img8.jpg',9,60000,30,5),
('Imprimante','Canon','img9.jpg',10,120000,5,2),
('Manette','Xbox','img10.jpg',8,35000,40,10);

INSERT INTO fournisseurs (nom, email) VALUES
('Fournisseur A','fa@mail.com'),('Fournisseur B','fb@mail.com'),
('Fournisseur C','fc@mail.com'),('Fournisseur D','fd@mail.com'),
('Fournisseur E','fe@mail.com'),('Fournisseur F','ff@mail.com'),
('Fournisseur G','fg@mail.com'),('Fournisseur H','fh@mail.com'),
('Fournisseur I','fi@mail.com'),('Fournisseur J','fj@mail.com');

INSERT INTO approvisionnements (fournisseur_id) VALUES
(1),(2),(3),(4),(5),(6),(7),(8),(9),(10);

INSERT INTO details_approvisionnement (approvisionnement_id, produit_id, quantite, prix_achat) VALUES
(1,1,5,450000),(2,2,5,350000),(3,3,3,550000),
(4,4,50,4000),(5,5,40,6000),(6,6,2,300000),
(7,7,10,40000),(8,8,15,50000),(9,9,2,100000),(10,10,20,30000);

INSERT INTO clients (prenom, nom) VALUES
('Jean','Dupont'),('Marie','Durand'),('Paul','Martin'),
('Sara','Diallo'),('Amadou','Ba'),('Ndeye','Fall'),
('Ibra','Ndiaye'),('Mame','Sy'),('Aliou','Sow'),('Fatima','Lo');

INSERT INTO commandes (vendeur_id, client_id, etat) VALUES
(1,1,'cloturee'),(2,2,'cloturee'),(3,3,'cloturee'),
(4,4,'en_cours'),(5,5,'cloturee'),(6,6,'annulee'),
(7,7,'cloturee'),(8,8,'cloturee'),(9,9,'en_cours'),(10,10,'cloturee');

INSERT INTO details_commande (commande_id, produit_id, quantite, prix_vente) VALUES
(1,1,1,500000),(2,2,1,400000),(3,3,1,600000),
(4,4,2,5000),(5,5,2,7000),(6,6,1,350000),
(7,7,1,45000),(8,8,1,60000),(9,9,1,120000),(10,10,1,35000);

INSERT INTO factures (commande_id, montant_total) VALUES
(1,500000),(2,400000),(3,600000),(4,10000),(5,14000),
(6,350000),(7,45000),(8,60000),(9,120000),(10,35000);
