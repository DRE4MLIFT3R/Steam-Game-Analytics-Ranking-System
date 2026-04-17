# 🎮 Steam Game Analytics & Ranking System

A complete web-based analytics platform for Steam games that tracks live player counts, manages sales & discounts, calculates popularity rankings, and provides rich admin dashboards.

![Steam Analytics](https://img.shields.io/badge/Steam-Game_Analytics-blue)
![PHP](https://img.shields.io/badge/PHP-8.0+-777BB4?logo=php)
![MySQL](https://img.shields.io/badge/MySQL-8.0+-4479A1?logo=mysql)



# Project Members
1. Chetan Malik              --->      RA2411030030073
2. Ankesh Ahir               --->      RA2411030030072

## 📁 Project Documents

| Sr | Description                                  | Link |
|----|----------------------------------------------|------|
| 1  | Project Code                                | [View](#) |
| 2  | Project Report                              | [View](#) |
| 3  | Final PPT                                   | [View](#) |
| 4  | RA2411030030072_Certificate                 | [View](#) |
| 5  | RA2411030030073_Certificate                 | [View](#) |
| 6  | RA2411030030072_CourseReport                | [View](#) |
| 7  | RA2411030030073_CourseReport                | [View](#) |

## 📋 Table of Contents

- [Features](#features)
- [Tech Stack](#tech-stack)
- [Project Objectives](#project-objectives)
- [Core Capabilities](#core-capabilities)
- [Ranking System](#ranking-system)
- [Installation](#installation)
- [Running the Application](#running-the-application)
- [Project Structure](#project-structure)
- [DBMS Concepts Demonstrated](#dbms-concepts-demonstrated)
- [Screenshots](#screenshots)

## ✨ Features

### Admin Features
- 🔐 Secure Admin Login with Role-based Access
- ➕ Add New Steam Games (with Steam App ID, Crack Status, etc.)
- 💰 Manage Sales & Discounts
- 📈 Fetch Live Player Counts from Steam API
- 🏆 Automatic Popularity Ranking Calculation
- 📊 View Player Count Rankings
- 🏅 View Popularity Score Rankings
- 📉 Full Popularity Breakdown Dashboard

### Key Functionalities
- Real-time player count tracking via Steam API
- Weighted popularity score calculation
- Clean, modern Steam-themed UI with glassmorphism effect
- Responsive design with Poppins font
- Notifications system (basic)
- Logout functionality

### 💻 Installation
- Step 1: Clone the Repository
    Bashgit clone https://github.com/divykumar0707-bot/Fit-Track-DBMS.git
    cd Fit-Track-DBMS
- Step 2: Install Dependencies
    Bashpip install flask

## 🛠 Tech Stack

### Backend
- **PHP 8+** - Server-side scripting
- **MySQL** - Relational database
- **MySQLi + Prepared Statements** - Secure database operations

### Frontend
- **HTML5 + CSS3** - Structure and styling
- **Google Fonts (Poppins)** - Typography
- **Responsive Design** - Mobile-friendly interface

### External Integration
- **Steam Web API** - Fetch current player counts

## 🎯 Project Objectives

1. **Game Popularity Analysis** — Analyze player count, sales, and ratings to determine trending games
2. **Marketing Strategy Optimization** — Study the impact of discounts on player activity
3. **Live Ranking System** — Compute and display real-time popularity rankings
4. **Admin Dashboard** — Provide powerful tools for managing games and analytics

## ⚙️ Core Capabilities

- Admin-protected dashboard
- Add games with Steam App ID, developer, price, rating & crack status
- Fetch live player data from Steam API
- Calculate popularity score using weighted formula
- View rankings by player count and popularity score
- Clean and modern UI inspired by Steam design

## 🏅 Ranking System

**Popularity Formula:**
```php
$popularity = ($players * 0.7) + ($sale_percentage * 0.3);
