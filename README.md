# Web Technologies Project: Advanced Approaches  
## Full Stack Development of a Dynamic Gym Management System

This repository contains the implementation of a **dynamic and interactive gym management system**, developed using a **full stack web development** approach.  
It serves as an enhancement and continuation of a previous university project, available at the following link:  
[Original Project – GymSite and Management System](https://github.com/FrancescoLazzarotto/GymSite-and-Managment-System)

---

## Project Goals

The objective is to provide an **interactive platform** for both gym members and administrators, built on a **modular, scalable, and secure architecture**.  
The system enables **course registration**, **support ticketing**, **course management**, and real-time data updates through RESTful communication.

---

## Technologies and Architecture

### Front-End
- **Languages & Libraries**: HTML, CSS, JavaScript, jQuery  
- **Features**:
  - Responsive and intuitive interface
  - Dynamic form validation and event handling
  - Real-time content updates via AJAX

### Back-End
- **Language**: PHP (Object-Oriented)
- **Security**:
  - Password hashing
  - Input sanitization
  - Authentication management
- **RESTful API**:
  - Facilitates communication between front-end and back-end
  - Allows asynchronous operations using AJAX

### Database
- **Engine**: MySQL
- **Access**: PHP Data Objects (PDO)
- **Security**:
  - Prevention of SQL injection via prepared statements
  - Separation of concerns for queries and logic

---

## System Overview

### User Interface (Gym Members)
- **Course Registration & Management**:  
  Users can browse, register for, and manage gym course enrollments with live feedback on availability.
- **Real-Time Data**:  
  Information is dynamically loaded from the database using AJAX.
- **Support Ticket System**:  
  Users can submit queries or issues directly to staff via structured support requests.

### Admin Interface (Gym Staff)
- **Course Management**:  
  Create, update, and delete gym courses, including instructor assignment and time scheduling.
- **Enrollment Monitoring**:  
  Administrators can manage user registrations and enforce course capacity limits.
- **Support Handling**:  
  Admins can view, manage, and respond to support tickets submitted by users.

---

## Dynamic Features & Optimization

- **Database-Driven Architecture**:  
  All content—course listings, schedules, user enrollments—is dynamically generated and updated in sync with the database.
- **Performance Optimization**:  
  Efficient SQL queries and modular API design ensure a responsive and scalable system even under high usage.

---

## Author

Francesco Lazzarotto  
Contact: francesco.lazzarotto@edu.unito.it
