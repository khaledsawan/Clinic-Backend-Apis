# Clinic Management System

The Clinic Management System is a web application that allows clinics to manage their appointments, patients, and departments efficiently. It provides a user-friendly interface for clinic staff to perform various tasks such as scheduling appointments, managing patient records, and organizing different departments.

## Features

- Appointment Management: Create, update, and delete appointments. Track appointment details such as date, time, patient information, and assigned staff.
- Patient Management: Maintain patient records including personal information, medical history, and contact details. Easily search and retrieve patient information.
- Department Management: Manage different departments within the clinic. Add, edit, and delete departments based on the clinic's needs.
- User Roles and Permissions: Assign different roles to staff members and define their permissions. Control access to specific features and functionalities based on user roles.
- Dashboard and Reporting: View informative dashboards with key metrics and generate reports on appointment statistics, patient demographics, and department performance.
- Notifications and Reminders: Send automated notifications and reminders to patients and staff members for upcoming appointments and important updates.
- (soon) Integration with External Systems: Integrate with external systems such as payment gateways, electronic medical record systems, or laboratory systems to streamline workflows and enhance efficiency.

## Technologies Used

- Laravel: A popular PHP framework used for building web applications. Provides a robust foundation for developing secure and scalable applications.
- MySQL: A relational database management system for storing and retrieving data efficiently.
- Laravel Passport: A Laravel package used for API authentication, enabling secure access to the application's endpoints.
- PHPUnit: A testing framework for PHP used for writing and executing unit tests to ensure the application's functionality is working as expected.

## Installation

1. Clone the repository: "git clone git@github.com:khaledsawan/Clinic-Backend-Apis.git"
2. Install the dependencies using Composer: "composer install" or "composer update"
3. Create a copy of the `.env.example` file and rename it to `.env`. Update the database and other configuration settings in the `.env` file.
4. Generate the application key: "php artisan key:generate"
5. Run the database migrations: "php artisan migrate"
6. Run project: "php artisan serve"
