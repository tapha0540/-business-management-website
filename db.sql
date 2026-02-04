-- Base de données : gestion_commerciale
CREATE DATABASE IF NOT EXISTS gestion_commerciale;

USE gestion_commerciale;

-- Table des utilisateurs
CREATE TABLE
    utilisateurs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        prenom VARCHAR(100) NOT NULL,
        nom VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        mot_de_passe VARCHAR(255) NOT NULL,
        role ENUM ('admin', 'vendeur') NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

-- Table des catégories de produits
CREATE TABLE
    categories (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

-- Table des produits
CREATE TABLE
    produits (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100) NOT NULL,
        description Text NOT NULL,
        imgUrl VARCHAR(255) NOT NULL,
        categorie_id INT NOT NULL,
        prix_vente DECIMAL(10, 2) NOT NULL,
        quantite INT DEFAULT 0,
        seuil_critique INT DEFAULT 0,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (categorie_id) REFERENCES categories (id) ON DELETE CASCADE
    );

-- Table des fournisseurs
CREATE TABLE
    fournisseurs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100) NOT NULL,
        email VARCHAR(100) UNIQUE NOT NULL,
        telephone VARCHAR(20),
        adresse TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

-- Table des approvisionnements
CREATE TABLE
    approvisionnements (
        id INT AUTO_INCREMENT PRIMARY KEY,
        fournisseur_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (fournisseur_id) REFERENCES fournisseurs (id) ON DELETE CASCADE
    );

-- Détails des approvisionnements
CREATE TABLE
    details_approvisionnement (
        id INT AUTO_INCREMENT PRIMARY KEY,
        approvisionnement_id INT NOT NULL,
        produit_id INT NOT NULL,
        quantite INT NOT NULL,
        prix_achat DECIMAL(10, 2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (approvisionnement_id) REFERENCES approvisionnements (id) ON DELETE CASCADE,
        FOREIGN KEY (produit_id) REFERENCES produits (id) ON DELETE CASCADE
    );

-- Table des clients
CREATE TABLE
    clients (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nom VARCHAR(100) NOT NULL,
        email VARCHAR(100),
        telephone VARCHAR(20),
        adresse TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    );

-- Table des commandes
CREATE TABLE
    commandes (
        id INT AUTO_INCREMENT PRIMARY KEY,
        vendeur_id INT NOT NULL,
        client_id INT NOT NULL,
        etat ENUM ('en_cours', 'cloturee', 'annulee') DEFAULT 'en_cours',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (vendeur_id) REFERENCES utilisateurs (id) ON DELETE CASCADE,
        FOREIGN KEY (client_id) REFERENCES clients (id) ON DELETE CASCADE
    );

-- Détails des commandes
CREATE TABLE
    details_commande (
        id INT AUTO_INCREMENT PRIMARY KEY,
        commande_id INT NOT NULL,
        produit_id INT NOT NULL,
        quantite INT NOT NULL,
        prix_vente DECIMAL(10, 2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (commande_id) REFERENCES commandes (id) ON DELETE CASCADE,
        FOREIGN KEY (produit_id) REFERENCES produits (id) ON DELETE CASCADE
    );

-- Table des factures
CREATE TABLE
    factures (
        id INT AUTO_INCREMENT PRIMARY KEY,
        commande_id INT NOT NULL,
        montant_total DECIMAL(10, 2) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (commande_id) REFERENCES commandes (id) ON DELETE CASCADE
    );