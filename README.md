# 🍕 FoodExpress - Premium Online Food Ordering System Prototype

FoodExpress is a high-fidelity, fully functional web application prototype designed to streamline online food ordering for customers and operation pipelines for restaurants. It features a responsive grid interface, instant session-based shopping cart management with interactive item removal, security checks, and a real-time tracking workspace dashboard.

---

## 👨‍💻 Author & Project Recognition
* **Project Lead:** Sevabrata Dutta
* **Role:** Lead UI/UX & Backend Engineer
* **Location:** West Bengal, India
* **Implementation Phase:** Phase 1 Operational Prototype MVP (Minimum Viable Product)

---

## ✨ Features

### 👤 Customer Module
* **Frosted Glass Authentication:** Secure, beautiful login and registration panels using modern CSS glassmorphism overlay mechanics.
* **Single-Row Curated Grid:** A responsive dashboard displaying all menu options seamlessly side-by-side with magnetic hover pointer effects.
* **Smart Shopping Cart:** Live bill calculation in Indian Rupees (₹) with real-time item removal confirmation dialog filters.
* **Visual Progress Tracker:** A live order execution pipeline highlighting states (*Placed ➔ Preparing ➔ Out for Delivery ➔ Delivered*).

### 📊 Restaurant Backend/Operations Module
* **Operations Center:** Real-time summary metric tracking detailing lifetime order loads, active backlogs, and total generated revenues.
* **Pipeline Dispatch Controls:** One-click order state updates changing structural data mappings instantly via a drop-down utility.

---

## 🛠️ Technology Stack (Option A Setup)
* **Backend:** PHP 8.x (Structured Server-Side Architecture)
* **Database:** MySQL / MariaDB (Relational Mapping Infrastructure)
* **Frontend:** HTML5, CSS3 (Modern Flexbox/Grid Layouts, Google Fonts), JavaScript ES6 (Client-side actions & state logic)
* **Server Environment:** XAMPP Local Web Apache Server Engine

---

## 🗄️ Database Architecture
The application runs on a relational schema (`food_ordering_db`) across 4 primary entities:
1. `users` — Manages customer records and secure system admin authorization layers.
2. `food_items` — Manages menu catalogs, localized pricing metrics, and manual assets.
3. `orders` — Manages the high-level transactional data pipelines and current delivery statuses.
4. `order_details` — Map individual food items to their corresponding parent orders.

---

## 🚀 Local Installation & Deployment Guide

Follow these steps to deploy this repository inside your local machine environment:

### Prerequisites
* Ensure you have [XAMPP Control Panel](https://apachefriends.org) installed on your computer.

### Step 1: Clone the Project Directory
Clone this repository directly into your local XAMPP webserver root folder (`htdocs`):
```bash
cd C:\xampp\htdocs\
git clone https://github.com food-ordering-system
```

### Step 2: Configure the MySQL Database
1. Open the XAMPP Control Panel and start the **Apache** and **MySQL** services.
2. Open your web browser and navigate to `http://localhost/phpmyadmin/`.
3. Click on the **SQL** tab.
4. Open the SQL initialization schema provided within this project repository, paste it into the textbox editor layout, and click **Go**.

### Step 3: Run the Local Environment Application
Open your browser and navigate to the project homepage:
👉 `http://localhost/food-ordering-system/login.php`

### Step 4: Login with System Access Credentials
* **Customer Access:** Click "Create an account" to quickly register a fresh profile.
* **Administrator Portal Access:**
  * **Email:** `admin@food.com`
  * **Password:** `admin123`

---

## 📱 Mobile Network Deployment (Local Network Access)
To present or preview this prototype instantly on a mobile screen over your local network:
1. Open your computer command line tool, run `ipconfig`, and take note of your **IPv4 Address** (e.g., `192.168.29.45`).
2. Ensure both your computer and mobile phone rest on the **same Wi-Fi router network**.
3. Open your mobile phone browser and navigate to: `http://YOUR_COMPUTER_IP/food-ordering-system/`
