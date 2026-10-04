# Interactive Resume / CV Builder

A full-stack web development practical project built using **HTML5**, **CSS3**, **JavaScript (ES6)**, **PHP**, and **MySQL (SQL)**.

This application allows users to create, dynamically preview, edit, customize, manage, and print/export professional resumes with multiple themes.

---

## ⚡ Quick Start: How to Run (Fast & Clear)

### 🚀 Option A: Using XAMPP (Recommended)

1. **Start Apache & MySQL**:
   - Open **XAMPP Control Panel**.
   - Click **Start** for **Apache** and **MySQL**.

2. **Ensure Folder is in `htdocs`**:
   - Place this project folder inside your XAMPP web root:
     ```
     C:\xampp\htdocs\resume_builder
     ```

3. **Import Database (1-time setup)**:
   - Open [http://localhost/phpmyadmin/](http://localhost/phpmyadmin/) in your browser.
   - Click **Import** > Choose file: `database/database.sql` > Click **Go**.
   - *(Or via terminal)*:
     ```powershell
     C:\xampp\mysql\bin\mysql.exe -u root < database\database.sql
     ```

4. **Open in Browser**:
   - 🌐 **Landing Page**: [http://localhost/resume_builder/](http://localhost/resume_builder/)
   - 📊 **Dashboard**: [http://localhost/resume_builder/pages/dashboard.html](http://localhost/resume_builder/pages/dashboard.html)
   - 📝 **Create Resume**: [http://localhost/resume_builder/pages/resume_form.php](http://localhost/resume_builder/pages/resume_form.php)

---

### ⚡ Option B: Using PHP Built-in Server (Without Apache)

If you don't want to use Apache, you only need MySQL running in XAMPP:

1. **Start MySQL** in XAMPP Control Panel (or make sure MySQL service is running).
2. **Open Terminal / PowerShell** inside this project folder:
   ```powershell
   cd resume_builder
   ```
3. **Start the local PHP server**:
   ```powershell
   C:\xampp\php\php.exe -S localhost:8000
   ```
   *(Or just `php -S localhost:8000` if PHP is in your system PATH)*
4. **Open in Browser**:
   - 🌐 **Landing Page**: [http://localhost:8000/](http://localhost:8000/)
   - 📊 **Dashboard**: [http://localhost:8000/pages/dashboard.html](http://localhost:8000/pages/dashboard.html)
   - 📝 **Create Resume**: [http://localhost:8000/pages/resume_form.php](http://localhost:8000/pages/resume_form.php)

---

## 📌 Technology Stack & Practical Mapping

This project explicitly satisfies and demonstrates all 5 core web technologies with a professional modular folder structure:

| Technology      | Files Used                                                                      | Role & Purpose                                                                                                                             |
| :-------------- | :------------------------------------------------------------------------------ | :----------------------------------------------------------------------------------------------------------------------------------------- |
| **HTML5**       | `index.html`, `pages/dashboard.html`, `pages/resume_form.php`, `pages/view_resume.php` | Semantic page structure, forms, inputs, data cards, and resume layouts.                                                                    |
| **CSS3**        | `assets/css/style.css`                                                          | Flexbox, CSS Grid, custom themes, media queries, and print stylesheets (`@media print`).                                                   |
| **JavaScript**  | `assets/js/script.js`, `pages/dashboard.html`                                   | Real-time live preview, dynamic row additions (education/projects), input validation, and asynchronous data fetching via `fetch()` (AJAX). |
| **PHP**         | `config/config.php`, `api/get_resumes.php`, `actions/save_resume.php`, `actions/delete_resume.php` | Server-side routing, RESTful JSON API generation, database operations via MySQLi prepared statements.                                      |
| **MySQL (SQL)** | `database/database.sql`                                                         | Relational table schema design, data persistence, and CRUD queries (`INSERT`, `SELECT`, `UPDATE`, `DELETE`).                               |

---

## ✨ Key Features

- **Project Landing Page (`index.html`)**: Clean overview of the system with direct navigation to the dashboard and resume builder.
- **Pure HTML & AJAX Dashboard (`pages/dashboard.html`)**: Fetches saved resumes from the database asynchronously using JavaScript's Fetch API (`api/get_resumes.php`), eliminating page reloads.
- **Dynamic Form Repeater**: Easily add and remove multiple educational qualifications and project entries dynamically.
- **Real-Time Live Preview**: Watch the resume update instantly in an A4-style preview pane as you type into form fields.
- **Multiple CV Templates**: Switch between **Modern Blue**, **Creative Purple**, and **Classic Minimalist** layouts on the fly.
- **Print & PDF Export**: Dedicated print styles strip browser toolbars and margins to generate clean, professional PDFs or printed documents.
- **Full CRUD Support**: Complete ability to **C**reate, **R**ead, **U**pdate, and **D**elete resumes from the database.
- **Professional Architecture**: Clean separation of configuration, backend APIs, server actions, public pages, database schemas, and static assets.

---

## 📂 Professional Project Structure

```text
resume_builder/
│
├── actions/                  # Server-side controllers & request handlers
│   ├── delete_resume.php     # Deletes a resume record from database
│   └── save_resume.php       # Handles resume form submission (Insert & Update)
│
├── api/                      # RESTful JSON backend APIs
│   └── get_resumes.php       # Returns resumes list as JSON for AJAX requests
│
├── assets/                   # Public static assets
│   ├── css/
│   │   └── style.css         # Central stylesheet (themes, layouts, print styling)
│   └── js/
│       └── script.js         # Client-side logic, live preview, form repeaters
│
├── config/                   # Configuration files
│   └── config.php            # MySQL database connection configuration
│
├── database/                 # Database schema and migration scripts
│   └── database.sql          # MySQL table schema
│
├── pages/                    # Application user interfaces & views
│   ├── dashboard.html        # Client-side HTML dashboard (AJAX powered)
│   ├── resume_form.php       # Resume creation & editing form interface
│   └── view_resume.php       # Single resume view and print/PDF layout
│
├── index.html                # Project landing and main application entry point
└── README.md                 # Project documentation
```

---

## ⚙️ Detailed Setup & Configuration

### Database Credentials (`config/config.php`)
Default settings for standard local environments (XAMPP default):
```php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "resume_builder";
```
If your MySQL has a password or a different port, update `config/config.php` accordingly.

---

## 🔌 API Endpoint

### `GET /api/get_resumes.php`

Returns all saved resumes sorted by the last updated timestamp in JSON format.

**Sample Response**:

```json
{
  "status": "success",
  "count": 1,
  "data": [
    {
      "id": "1",
      "name": "Jane Doe",
      "email": "jane@example.com",
      "template": "template1",
      "updated_at": "2026-10-04 18:40:00"
    }
  ]
}
```

---

## 🎓 Viva & Presentation Talking Points

If presenting this project for an academic evaluation or practical exam, highlight the following:

1. **Modular Architecture & Separation of Concerns**:
   - Configuration is isolated in `config/`.
   - Business logic & database modifications are in `actions/`.
   - Asynchronous endpoints are in `api/`.
   - User interfaces reside in `pages/`.
   - Static resources are structured in `assets/`.
2. **Frontend-Backend Decoupling**: The dashboard (`pages/dashboard.html`) is separated from the server logic and communicates via asynchronous JavaScript (`fetch("../api/get_resumes.php")`).
3. **Security**: All SQL operations (`SELECT`, `INSERT`, `UPDATE`, `DELETE`) utilize **prepared statements** (`bind_param`) to prevent SQL injection vulnerabilities.
4. **Structured Storage**: Complex repeating fields (such as multiple Education milestones and Projects) are serialized and stored as JSON strings inside MySQL and decoded dynamically.
5. **CSS Print Media Queries**: Clean separation between screen styling and print layouts using `@media print` rules for browser PDF generation.
