<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# ApartMe - Apartment Management

> ### Library management project with feature such as Security, Authencation, Authorization, CRUD user, book, category,... supporting procedures for borrowing and returning books.
>### See more features and details about the source code in [ApartMe](https://github.com/VMDang/ApartMe-ITSSJP1).
### In this project, i used Vue3, Antd Vue 4.0, PHP 8.2, Laravel 10, MySQL

This repo is functionality complete — PRs and issues welcome!

----------

# Getting started

## Installation

Please check the official laravel installation guide for server requirements before you start [Laravel 10 Documentation](https://laravel.com/docs/10.x). Necessary settings before starting as PHP >= 8.1, NodeJS, Composer, Apache2.

Alternative installation is possible without local dependencies relying on [Docker](#docker). 

1. Clone the repository

        git clone https://github.com/VMDang/ApartMe-ITSSJP1.git

2. Switch to the repo folder

        cd ApartMe-ITSSJP1

3. Install all the dependencies using composer and npm

        composer install
        npm install

4. Copy the example env file and make the required configuration changes in the .env file

        cp .env.example .env
> Please contact me via [Mail](mailto:dang.vm205063@sis.hust.edu.vn) to know more about the content in the .env file
5. Generate a new application key

        php artisan key:generate

6. Run the database migrations (**Set the database connection in .env before migrating**)

        php artisan migrate

7. Run database seeders
   
        php artisan db:seed
8. Complie view resources
   
        npm run build
9. Update view changes
		- Open 1st terminal and run 
   
        npm run dev     
10. Start the local development server
		- Open 2nd terminal and run
		
        php artisan serve

You can now access the server at http://localhost:8000

## Database seeding

**Populate the database with seed data with relationships which includes users, roles,  category,... This can help you to quickly start testing the api or couple a frontend and start using it with ready content.**

Run the database seeder and you're done

    php artisan db:seed

***Note*** : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command

    php artisan migrate:refresh
    
----------

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

-------
# Developed team information

## About project
The project is part of the ITSS Japanese 1 (IT3362) 木１ class curriculum
The project is programmed by the Development Team (Team Dev - 開発チーム） at the request of the Project Owner Team (POT - POチーム) with the theme of apartment management.
Team POT - ANH LIEM TEAM: Perform requirements analysis documents, function overviews, screen transition diagrams, database design, screens and create product backlog.
Team Dev - SPMeチーム: Create sprint backlogs for 3 sprints, make progress reports at the end of the sprint.
Detailed design document: [Specification Document](https://docs.google.com/spreadsheets/d/1_hcT-URZkSKU-J9nKDhbmhY9f0gOLpaRlo2ljy3hTXM/edit#gid=2121357451)
## About Team
The project programmed by my development team is SPMe in the ITSS Japanese 1 (IT3362) subject. Team in class 木１(143556)
> Thanks to the whole team for helping me complete this project. It's an honor to be your leader.

This is member in SPMe Team
|       Name        | University    | Role   	| Contact  |
|-------------------|    :----:     |-----------|----------|
| Vu Minh Dang      | HUST          | Leader 	|          |
| Ta Van Hoan   	| HUST          | SubLeader |          |
| Dao Kim Duong     | HUST          | Secretary |          |
| Nguyen Duy Khanh  | HUST          | Member 	|          |
| Mac Van Khanh     | HUST          | Member 	|          |
| Vuong Duy Long 	| HUST          | Member 	|          |
---------
## About me
### **Vu Minh Dang**
#### Hanoi University of Science and Technology - HEDSPI- K65
Mail : [dang.vm205063@sis.hust.edu.vn](mailto:dang.vm205063@sis.hust.edu.vn)
