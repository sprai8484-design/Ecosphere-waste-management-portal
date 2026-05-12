-- ============================================================
-- Ecosphere – Challenge Entries Database Setup
-- Compatible with: MySQL 5.7+ / MariaDB (XAMPP)
-- Run this once in phpMyAdmin or MySQL CLI
-- ============================================================

-- Step 1: Create the database (skip if already exists)
CREATE DATABASE IF NOT EXISTS ecosphere_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE ecosphere_db;

-- Step 2: Create the challenge_entries table
CREATE TABLE IF NOT EXISTS challenge_entries (
  id            INT(11)       NOT NULL AUTO_INCREMENT,
  name          VARCHAR(120)  NOT NULL,
  email         VARCHAR(180)  NOT NULL,
  challenge_title VARCHAR(220) NOT NULL,
  description   TEXT          NOT NULL,
  media         VARCHAR(260)  DEFAULT NULL,   -- stored filename
  difficulty    ENUM('easy','medium','hard')  NOT NULL DEFAULT 'easy',
  created_at    DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Optional: seed one demo row so admin view isn't empty
-- ============================================================
INSERT INTO challenge_entries
  (name, email, challenge_title, description, media, difficulty)
VALUES
  (
    'Priya Sharma',
    'priya@example.com',
    'Plastic Bottle Creative Reuse Challenge',
    'I am turning five 2-litre PET bottles into a vertical herb garden mounted on a bamboo frame.',
    NULL,
    'easy'
  );
