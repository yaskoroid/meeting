# meeting
Meeting application

This is an application that looks like a school or study service. It contains entities such as: user, task, lesson, tutorial, file, and email. It also has derived entities such as task_type, task_source, task_target_type, task_date_type_source_comment, and user_type. Entity that processes change confirmation by email - change_confirm. These entities are directly dependent on the services that handle the interaction with them.

The application is based on the principles of MVC, so you will see the Model, View, Controller folders in the application/core directory. The project is very simple, so you yourself will understand many of the features. JS files are located in the /js folder and are used in Twig templates that are processed by the TemplateService. All the libraries I use are in the vendor directory.

Now the application works here - meeting.kl.com.ua
