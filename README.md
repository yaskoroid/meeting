# meeting
Meeting application

This is application that is like school or study service. It has entities like: user, task, lesson, textbook, file and email. It
already has derivative entities like task_type, task_source, task_target_type, task_date_type_source_comment and user_type. Entity, that
maintains changes confirmation by email - change_confirm. These entities directly depends from services, which handling interaction with them.

Aplication legacing MVC princeples, so you will see Model, View, Controller folders in application/core directory. Project is very simple, so you will understand many features by yourself. JS files is situated in /js folder and used in Twig templates, that handle by TemplateService. All libraries, that I use situated in vendor directoty.
