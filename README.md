# Coursework Project: UoN QMC Hospital Database + web interface project

## TESTING CREDENTIALS (LOGIN DETAILS):

To test the Role-Based Access Control, please use the following accounts:
### 1. ADMINISTRATOR ACCOUNT
   Username: jelina
   Password: iron99
   This gives access to: Manage Parking, Add Doctors, Audit Trail, New Patient Registration.

### 2. DOCTORS ACCOUNT
   Username: mceards
   Password: lord456
   Username: moorland
   Password: buzz48
   This gives access to: Patient Search, Admissions, Prescriptions, Request Parking Permit.

## INSTALLATION INSTRUCTIONS:

### 1. Database Setup:
   - Import the provided 'hospital.sql' file into your MySQL/MariaDB server.
   - The database should be named 'hospital'.

### 2. Configuration:
   - Check 'db.inc.php' to ensure the database hostname, username, and 
     password match your local environment.
   - Current settings:
     Host: mariadb
     User: root
     Pass: rootpwd

### 3. Deployment:
   - Place all PHP, CSS, and asset files in your web server's public folder.
   - Open 'index.php' in your browser to begin.<br>
     ACCESS URLs:<br>
     Application Login:   http://localhost/cw/index.php <br>
     Database Management: http://localhost:8081/  (phpMyAdmin)
