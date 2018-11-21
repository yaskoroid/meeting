<div class="container">
    <div class="starter-template">
        <!-- Содержимое страницы в форме -->
        <form action="<?php print $viewFormAction ?>" name="formTasks" method="post" enctype="multipart/form-data">
            <!-- Блок с таблицей задач -->
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <tbody>
                    <tr>
                        <td>
                            <div class="my-inline-parent">
                                <div class="my-inline">Пользователь</div>
                                <button type="submit" name="pageHomeSessionSortLoginUp"
                                        class="btn btn-xs btn-primary my-inline">▲</button>
                                <button type="submit" name="pageHomeSessionSortLoginDown"
                                        class="btn btn-xs btn-primary my-inline">▼</button>
                            </div>
                        </td>
                        <td>
                            <div class="my-inline-parent">
                                <div class="my-inline">E-mail</div>
                                <button type="submit" name="pageHomeSessionSortEmailUp"
                                        class="btn btn-xs btn-primary my-inline">▲</button>
                                <button type="submit" name="pageHomeSessionSortEmailDown"
                                        class="btn btn-xs btn-primary my-inline">▼</button>
                            </div>
                        </td>
                        <td>
                            Задача
                        </td>
                        <td>
                            Картинка
                        </td>
                        <td>
                            <div class="my-inline-parent">
                                <div class="my-inline">Выполнил</div>
                                <button type="submit" name="pageHomeSessionSortDoneUp"
                                        class="btn btn-xs btn-primary my-inline">▲</button>
                                <button type="submit" name="pageHomeSessionSortDoneDown"
                                        class="btn btn-xs btn-primary my-inline">▼</button>
                            </div>
                        </td>
                    </tr>
                    <?php foreach ($tasks as $key => $task) { ?>
                        <tr>
                            <td>
                                <?php print $task['login'] ?>
                            </td>
                            <td>
                                <?php print $task['email'] ?>
                            </td>
                            <td id="task<?php print $task['id'] ?>">
                                <div class="my-float-left"
                                     id="taskLoadValue<?php print $task['id'] ?>"><?php print $task['task'] ?></div>
                                <?php if ($_SESSION['level'] == 1) { ?>
                                    <div id="taskEdit<?php print $task['id'] ?>"
                                         class="btn btn-xs btn-success btn-my-xs col-xs-2 my-float-right"
                                         onclick="jsEditTask(this.id)">
                                        <div class="glyphicon glyphicon-pencil"></div>
                                    </div>
                                <?php } ?>
                            </td>
                            <td class="align-center">
                                <img src="<?php print $task['img_link'] ?>" class="img-responsive img-thumbnail"
                                    alt="Картинка задачи">
                            </td>
                            <td id="done<?php print $task['id'] ?>">
                                <div class="my-float-left" id="doneLoadValue<?php print $task['id'] ?>">
                                    <?php print $task['done'] == 1 ? "Да" : "Нет"; ?>
                                </div>
                                <?php if ($_SESSION['level'] == 1) { ?>
                                    <div id="doneEdit<?php print $task['id'] ?>"
                                         class="btn btn-xs btn-success btn-my-xs my-float-right"
                                         onclick="jsEditDone(this.id)">
                                        <div class="glyphicon glyphicon-pencil"></div>
                                    </div>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <!-- Блок добавления и просмотра новых задач -->
            <div>
                <!-- Блок просмотра результата добавления задачи -->
                <?php if (isset($resultOfAddTask)) { ?>
                    <?php if (!isset($error)) { ?>
                        <div class="form-group col-md-12 col-xs-12 bg-success text-success">
                    <?php } else { ?>
                        <div class="form-group col-md-12 col-xs-12 bg-danger text-danger">
                    <?php } print $resultOfAddTask['response']; ?>
                    </div>
                <?php } ?>
                <!-- Кнопка отображения/скрытия добавления задачи -->
                <div class="form-group text-center my-dropdown-block-parent">
                    <label class="my-dropdown-block btn btn-success" for="_1">Добавить</label>

                </div>
                <input id="_1" type="checkbox">
                <!-- Блок добавления задачи -->
                <div id="addTaskBlock">
                    <div class="row container-fluid">
                        <div class="form-group col-md-3 col-xs-12">
                            <select name="idUser" class="form-control" onchange="jsSetPreviewMameAndEmail()" id="addTaskSelect">
                                <?php foreach ($users as $id => $user) { ?>
                                    <option value="<?php print $id ?>"><?php print $user ?></option>
                                <?php } ?>
                            </select>
                        </div>
                        <div class="form-group col-md-3 col-xs-12">
                            <input type="text" name="task" class="form-control" onchange="jsSetPreviewTask()" id="addTaskTask">
                        </div>
                        <div class="form-group col-md-3 col-xs-12">
                            <label class="btn btn-primary my-hidden-owerflow" for="addTaskImage">
                                <input type="file" name="image" class="form-control custom-file-input"
                                       onchange="$('#upload-file-info').html(this.files[0].name); jsSetPreviewImage()" id="addTaskImage"
                                       style="display:none">
                                Select file
                                <span class='label label-info' id="upload-file-info"></span>
                            </label>
                        </div>
                        <div class="form-group col-md-3 col-xs-12">
                            <button id="add_task" type="submit" name="addTask" class="btn btn-success">Добавить
                            </button>
                        </div>
                    </div>
                    <!-- Кнопка отображения/сркытия предварительного просмотра -->
                    <div class="form-group text-center my-dropdown-block-parent">
                        <label class="my-dropdown-block btn btn-success" for="_2">Предварительный просмотр</label>

                    </div>
                    <input id="_2" type="checkbox">
                    <!-- Блок предварительного просмотра -->
                    <div id="previewBlock">
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <tbody>
                                    <tr>
                                        <td>

                                            <div class="my-inline-parent">
                                                <div class="my-inline">Пользователь</div>
                                                <button class="btn btn-xs btn-primary my-inline">▲</button>
                                                <button class="btn btn-xs btn-primary my-inline">▼</button>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="my-inline-parent">
                                                <div class="my-inline">E-mail</div>
                                                <button class="btn btn-xs btn-primary my-inline">▲</button>
                                                <button class="btn btn-xs btn-primary my-inline">▼</button>
                                            </div>
                                        </td>
                                        <td>
                                            Задача
                                        </td>
                                        <td>
                                            Картинка
                                        </td>
                                        <td>
                                            <div class="my-inline-parent">
                                                <div class="my-inline">Выполнил</div>
                                                <button class="btn btn-xs btn-primary my-inline">▲</button>
                                                <button class="btn btn-xs btn-primary my-inline">▼</button>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td id="previewLogin">
                                            <?php print $tasks[0]['login'] ?>
                                        </td>
                                        <td id="previewEmail">
                                            <?php print $tasks[0]['email'] ?>
                                        </td>
                                        <td>
                                            <div id="previewTask" class="my-float-left">
                                                <?php print $tasks[0]['task'] ?>
                                            </div>
                                            <?php if ($_SESSION['level'] == 1) { ?>
                                                <div class="btn btn-xs btn-success btn-my-xs my-float-right">
                                                    <div class="glyphicon glyphicon-pencil"></div>
                                                </div>
                                            <?php } ?>
                                        </td>
                                        <td>
                                            <div class="image-preview-parent">
                                                <img id="previewImage" src="<?php print $tasks[0]['img_link'] ?>"
                                                    class="img-responsive img-thumbnail image-preview"
                                                     alt="Картинка задачи">
                                            </div>
                                        </td>
                                        <td>
                                            <div class="my-float-left">
                                                Нет
                                            </div>
                                            <?php if ($_SESSION['level'] == 1) { ?>
                                                <div class="btn btn-xs btn-success btn-my-xs my-float-right">
                                                    <div class="glyphicon glyphicon-pencil"></div>
                                                </div>
                                            <?php } ?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="col-md-12 col-xs-12 bg-danger" id="previewError">
                            </div>
                            <div class="col-md-12 col-xs-12 bg-success" id="previewWait">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Номер страницы -->
            <div class="text-center">
                Страница
                <?php print $pageNumber ?>
            </div>
            <!-- Навигация (пагинация) -->
            <nav class="text-center" aria-label="Page navigation">
                <ul class="pagination">
                    <?php foreach ($pagesArray as $val) {
                        if ($val != "...") { ?>
                            <li>
                                <button type="submit" name="pageHomeSessionPageNumber<?php print $val ?>"
                                        class="btn btn-xs btn-primary">
                                    <?php print $val ?>
                                </button>
                            </li>
                        <?php } else { ?>
                            <li>
                                ...
                            </li>
                        <?php }
                    } ?>
                </ul>
            </nav>
        </form>
    </div>
</div>
<div class="hidden" id="taskLenght"><?php print $taskLenght ?></div>