-- ============================================================================
-- Database Setup Script
-- This script creates the database and sets it up for use
-- ============================================================================

-- Create database if it doesn't exist
CREATE DATABASE IF NOT EXISTS my_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

-- Use the database
USE my_db;

-- Note: This creates the database. Next, you need to:
-- 1. Import mentorship_complete_schema.sql to create all tables
-- 2. Optionally import mentorship_dummy_data.sql for sample data
