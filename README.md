**Symfony 4 - RabbitMQ project for technical test at MindGeek**  
See file 'Symfony-RabbitMQ project.docx' for readme info with images.  

**Project request**

You will need to write a program that downloads all the items in https://mgtechtest.blob.core.windows.net/files/showcase.json  and cache images within each asset. To make it efficient, it is desired to only call the URLs in the JSON file only once. Demonstrate, by using a framework of your choice, your software architectural skills. How you use the framework will be highly important in the evaluation. 

How you display the feed and how many layers/pages you use is up to you, but please ensure that we can see the complete list and the details of every item. You will likely hit some road blocks and errors along the way, please use your own initiative to deal with these issues, it’s part of the test.

Please ensure all code is tested before sending it back, it would be good to also see unit tests too. Ideally, alongside supplying the code base and all packages/libraries required to deploy, you will also have to supply deployment instructions too.
 
 **Overview**
 
 The solution consists in offloading the processing of each image to a taks queue (using RabbitMQ).
 The setup uses Docker(docker-compose) to create an environment with the following services:
 - php – running PHP-FPM for the interface
 - nginx – web server
 - db  - database server with MariaDB
 - rabbitmq  - RabbitMQ service
 - php-consume   - PHP CLI app that will consume the tasks from RabbitMQ queue and actually download the images
 There is just one project using Symfony Framework for both front and consumers.
 
**Deployement**
 
 1 -  Change directory to where the project archive is decompressed and use docker-compose to create and start containers:
 
 $ docker-compose up -d  
 Creating network "project_symfony" with the default driver  
 Creating project_nginx_1       ... done  
 Creating project_db_1          ... done  
 Creating project_rabbitmq_1    ... done  
 Creating project_php-consume_1 ... done  
 Creating project_php_1         ... done  
 
 
 2 -  Create database and tables using command:  
 	$ ./database_setup.sh
 	
 	
 3 - You can choose to have more than one consumer service by running:
 
 $ docker-compose up -d --scale php-consume=2  
 project_db_1 is up-to-date  
 Starting project_php-consume_1 ...  
 Starting project_php-consume_1 ... done  
 project_php_1 is up-to-date  
 project_nginx_1 is up-to-date  
 Creating project_php-consume_2 ... done  
 
 
 **How to use**
 
 Access in your browser  `http://localhost:8005/`
 Press Import data button in order to:
 1.  Synchronously read the JSON found  at https://mgtechtest.blob.core.windows.net/files/showcase.json
 and insert data into two database tables:  record and image resulting 45 records and 450 images(cardImage and keyArtImage). After saving to DB each image a message is sent to RabbitMQ “messeges” queue. You can access RabbitMQ instance in your browser at http://localhost:15672 with user guest and password guest
 
 2.  By using a log table (caching_log) and AJAX requests the interface will be updated with the progress regarding caching each image:
 Each time the consumer finishes processing an image the info in image table is updated and also a new row is inserted in caching_log table in order to keep track of the progress.
 
 After all 450 images are processed please press View data button.
 
 Click on any of the rows representing a movie in order to see detailed view.
 
 For demonstration purpose I’ve chose to display the HTTP code/error message for each image which was not processed successfully.
 
 **Automated testing**
 
 I’ve wrote unit tests for parts of the code that seem more in danger of having issues: 
 -  reading and parsing the input JSON file
 -  downloading and saving images
 
 Run tests with these command:
 $ docker-compose exec php /bin/bash -c './vendor/bin/phpunit ./tests/'
 
 …
 Time: 00:00.013, Memory: 6.00 MB
 
 OK (7 tests, 9 assertions)

**References**
For Docker setup with RabbitMQ: https://www.nielsvandermolen.com/tutorial-symfony-4-messenger/