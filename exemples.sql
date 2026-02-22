INSERT INTO utilisateurs (prenom, nom, email, mot_de_passe, role, imgUrl, created_at) VALUES
('Admin1','Fall','admin1@mail.com','123','admin','admin1.png', NOW() - INTERVAL 2 MONTH),
('Admin2','Diop','admin2@mail.com','123','admin','admin2.png', NOW() - INTERVAL 2 MONTH),
('Admin3','Ndiaye','admin3@mail.com','123','admin','admin3.png', NOW() - INTERVAL 1 MONTH),
('Vendeur1','Diop','v1@mail.com','123','vendeur','vendeur1.png', NOW() - INTERVAL 1 MONTH),
('Vendeur2','Ba','v2@mail.com','123','vendeur','vendeur2.png', NOW() - INTERVAL 1 MONTH),
('Vendeur3','Ndiaye','v3@mail.com','123','vendeur','vendeur3.png', NOW() - INTERVAL 20 DAY),
('Vendeur4','Sow','v4@mail.com','123','vendeur','vendeur4.png', NOW() - INTERVAL 15 DAY),
('Vendeur5','Kane','v5@mail.com','123','vendeur','vendeur5.png', NOW() - INTERVAL 10 DAY),
('Vendeur6','Diallo','v6@mail.com','123','vendeur','vendeur6.png', NOW() - INTERVAL 7 DAY),
('Vendeur7','Sy','v7@mail.com','123','vendeur','vendeur7.png', NOW() - INTERVAL 5 DAY),
('Vendeur8','Gueye','v8@mail.com','123','vendeur','vendeur8.png', NOW() - INTERVAL 2 DAY),
('Vendeur9','Seck','v9@mail.com','123','vendeur','vendeur9.png', NOW());

INSERT INTO
    categories (nom, description)
VALUES
    ('Electronique', 'Appareils electroniques'),
    ('Informatique', 'Materiel informatique'),
    ('Telephone', 'Smartphones'),
    ('Accessoires', 'Accessoires divers'),
    ('Maison', 'Produits maison'),
    ('Cuisine', 'Ustensiles'),
    ('Sport', 'Articles sport'),
    ('Mode', 'Vetements'),
    ('Beaute', 'Cosmetiques'),
    ('Auto', 'Accessoires auto');

INSERT INTO
    produits (
        nom,
        description,
        imgUrl,
        categorie_id,
        prix_vente,
        quantite,
        seuil_critique
    )
VALUES
    ('PC HP', 'Laptop', 'pc_hp.png', 2, 350000, 10, 2),
    (
        'iPhone 13',
        'Smartphone',
        'iphone13.png',
        3,
        500000,
        8,
        2
    ),
    (
        'Clavier',
        'Clavier USB',
        'clavier.png',
        2,
        15000,
        30,
        5
    ),
    (
        'Souris',
        'Souris USB',
        'souris.png',
        2,
        10000,
        40,
        5
    ),
    (
        'TV Samsung',
        'Smart TV',
        'tv_samsung.png',
        1,
        250000,
        5,
        1
    ),
    (
        'Mixeur',
        'Mixeur cuisine',
        'mixeur.png',
        6,
        45000,
        12,
        3
    ),
    (
        'Ballon',
        'Ballon foot',
        'ballon.png',
        7,
        8000,
        25,
        5
    ),
    (
        'T-shirt',
        'Vetement',
        'tshirt.png',
        8,
        12000,
        50,
        10
    ),
    (
        'Parfum',
        'Parfum homme',
        'parfum.png',
        9,
        30000,
        20,
        4
    ),
    (
        'Autoradio',
        'Radio voiture',
        'autoradio.png',
        10,
        40000,
        7,
        2
    );

INSERT INTO
    fournisseurs (nom, email, telephone, adresse)
VALUES
    ('Fourn1', 'f1@mail.com', '+221770000001', 'Dakar'),
    ('Fourn2', 'f2@mail.com', '+221770000002', 'Thies'),
    ('Fourn3', 'f3@mail.com', '+221770000003', 'Mbour'),
    ('Fourn4', 'f4@mail.com', '+221770000004', 'Kaolack'),
    (
        'Fourn5',
        'f5@mail.com',
        '+221770000005',
        'Saint-Louis'
    ),
    (
        'Fourn6',
        'f6@mail.com',
        '+221770000006',
        'Ziguinchor'
    ),
    ('Fourn7', 'f7@mail.com', '+221770000007', 'Louga'),
    (
        'Fourn8',
        'f8@mail.com',
        '+221770000008',
        'Tambacounda'
    ),
    ('Fourn9', 'f9@mail.com', '+221770000009', 'Kolda'),
    ('Fourn10', 'f10@mail.com', '+221770000010', 'Fatick');

INSERT INTO
    approvisionnements (fournisseur_id)
VALUES
    (1),
    (2),
    (3),
    (4),
    (5),
    (6),
    (7),
    (8),
    (9),
    (10);

INSERT INTO
    details_approvisionnement (
        approvisionnement_id,
        produit_id,
        quantite,
        prix_achat
    )
VALUES
    (1, 1, 5, 300000),
    (2, 2, 4, 450000),
    (3, 3, 20, 10000),
    (4, 4, 25, 7000),
    (5, 5, 3, 200000),
    (6, 6, 10, 30000),
    (7, 7, 15, 5000),
    (8, 8, 40, 8000),
    (9, 9, 12, 20000),
    (10, 10, 6, 25000);

INSERT INTO
    clients (prenom, nom, email, telephone, imgUrl)
VALUES
    ('Ali', 'Fall', 'c1@mail.com', '+221780000001', NULL),
    (
        'Moussa',
        'Diop',
        'c2@mail.com',
        '+221780000002',
        NULL
    ),
    ('Fatou', 'Ba', 'c3@mail.com', '+221780000003', NULL),
    ('Awa', 'Ndiaye', 'c4@mail.com', '+221780000004', NULL),
    ('Omar', 'Sow', 'c5@mail.com', '+221780000005', NULL),
    (
        'Mariama',
        'Kane',
        'c6@mail.com',
        '+221780000006',
        NULL
    ),
    (
        'Ibra',
        'Diallo',
        'c7@mail.com',
        '+221780000007',
        NULL
    ),
    (
        'Seynabou',
        'Sy',
        'c8@mail.com',
        '+221780000008',
        NULL
    ),
    (
        'Cheikh',
        'Gueye',
        'c9@mail.com',
        '+221780000009',
        NULL
    ),
    (
        'Amadou',
        'Seck',
        'c10@mail.com',
        '+221780000010',
        NULL
    );

INSERT INTO commandes (vendeur_id, client_id, etat, created_at) VALUES
(2,1,'cloturee', NOW() - INTERVAL 4 MONTH),
(3,2,'en_cours', NOW() - INTERVAL 3 MONTH),
(4,3,'cloturee', NOW() - INTERVAL 2 MONTH),
(5,4,'annulee', NOW() - INTERVAL 1 MONTH),
(6,5,'cloturee', NOW() - INTERVAL 20 DAY),
(7,6,'en_cours', NOW() - INTERVAL 15 DAY),
(8,7,'cloturee', NOW() - INTERVAL 10 DAY),
(9,8,'cloturee', NOW() - INTERVAL 7 DAY),
(10,9,'en_cours', NOW() - INTERVAL 3 DAY),
(2,10,'cloturee', NOW());

INSERT INTO
    details_commande (commande_id, produit_id, quantite, prix_vente)
VALUES
    (1, 1, 1, 350000),
    (2, 2, 1, 500000),
    (3, 3, 2, 15000),
    (4, 4, 1, 10000),
    (5, 5, 1, 250000),
    (6, 6, 1, 45000),
    (7, 7, 3, 8000),
    (8, 8, 2, 12000),
    (9, 9, 1, 30000),
    (10, 10, 1, 40000);

INSERT INTO factures (commande_id, montant_total, created_at) VALUES
(1,350000, NOW() - INTERVAL 2 MONTH),
(2,500000, NOW() - INTERVAL 2 MONTH),
(3,30000, NOW() - INTERVAL 1 MONTH),
(4,10000, NOW() - INTERVAL 1 MONTH),
(5,250000, NOW() - INTERVAL 20 DAY),
(6,45000, NOW() - INTERVAL 15 DAY),
(7,24000, NOW() - INTERVAL 10 DAY),
(8,24000, NOW() - INTERVAL 7 DAY),
(9,30000, NOW() - INTERVAL 3 DAY),
(10,40000, NOW());