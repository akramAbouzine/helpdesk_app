# 🎫 Application Helpdesk – Gestion des tickets (PHP/MySQL)

## 📌 Description
Application web académique permettant la gestion des tickets (incidents ou demandes).  
Les utilisateurs peuvent créer des tickets, leur attribuer une **priorité** et une **catégorie**, changer leur **statut** (Ouvert/Résolu), et effectuer des recherches ou filtres.  

Ce projet a été réalisé dans le cadre d’un **apprentissage en développement web** et structuré selon une méthodologie **Agile/Scrum** avec suivi Kanban (Trello).

---

## ⚙️ Technologies utilisées
- **Langages** : PHP (PDO), HTML5, CSS3, JavaScript  
- **Framework CSS** : Bootstrap 5  
- **Base de données** : MySQL  
- **Environnement** : XAMPP (Apache, MySQL, PHP)  
- **Outils** : Visual Studio Code, Git/GitHub, Trello  

---

## 🗄️ Base de données

Exécuter le script SQL suivant dans **phpMyAdmin** pour créer la base `helpdesk` et la table `tickets` :

```sql
CREATE DATABASE helpdesk CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE helpdesk;

CREATE TABLE tickets (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(200) NOT NULL,
  description TEXT,
  category VARCHAR(50) NOT NULL DEFAULT 'IT',
  priority ENUM('Basse','Moyenne','Haute') NOT NULL DEFAULT 'Moyenne',
  status ENUM('Ouvert','Résolu') NOT NULL DEFAULT 'Ouvert',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE INDEX idx_tickets_title ON tickets(title);
CREATE INDEX idx_tickets_filters ON tickets(category, priority, status);
