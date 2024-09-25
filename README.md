# Garage Booking Application (GBA)

### Live Demo

Try out the application here: [mygarageapp.lukekenny.co.uk](http://mygarageapp.lukekenny.co.uk)

> **Note**: As this application is currently set up for demonstration purposes, user account creation and email verification functionality are not fully enabled (no mailing service is connected). Therefore, new users will not be able to verify their accounts.
>
> However, feel free to use the following demo accounts to explore various user-specific functionalities, such as creating services, managing bookings, and viewing the dashboard as different types of users (admin, employee, customer):
>
> - **Admin**:  
>   - Username: `admin@example.com`  
>   - Password: `password`
>
> - **Employee**:  
>   - Username: `employee@example.com`  
>   - Password: `password`
>
> - **Customer**:  
>   - Username: `customer@example.com`  
>   - Password: `password`

---

## Overview

Created as an MSc Computer Science dissertation project, the **Garage Booking Application (GBA)** is a web-based platform designed to streamline the process of booking vehicle maintenance and repair services at a local garage. It enhances the customer experience by simplifying service booking, providing timely reminders, and supporting garage administrators and employees in managing customer information, bookings, and service offerings efficiently.

---

### Features

- **User Registration & Authentication**: Secure user registration and login with email verification.
- **Service Booking**: Customers can book multiple services in a single appointment and manage their bookings.
- **Dynamic Vehicle Management**: Add and manage vehicles easily during the booking process.
- **Dashboards**:
  - **Customer Dashboard**: Manage bookings, vehicles, and account settings.
  - **Admin Dashboard**: Manage services, users, and view bookings on a calendar.
  - **Employee Dashboard**: View and update assigned bookings.
- **Email Notifications**: Automated booking confirmations, reminders, and service updates.
- **Feedback Mechanism**: Customers can leave ratings and reviews for completed services.

---

### Technologies Used

- **Backend**: PHP with Laravel Framework
- **Frontend**: HTML, CSS (Bootstrap), JavaScript
- **Database**: MySQL
- **Email**: Laravel Mailable, Mailtrap for testing
- **Version Control**: Git, GitHub
