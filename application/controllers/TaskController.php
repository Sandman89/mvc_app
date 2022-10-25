<?php

namespace application\controllers;

use application\components\GridView;
use application\components\Pagination;
use application\models\Task;
use core\Controller;

class TaskController extends Controller
{

    public function accessControl()
    {
        return [
            [
                'action'=>'Edit',
                'role'=>'admin'
            ]
        ];
    }

    public function before()
    {
        $this->view->layout = null;
        return parent::before();
    }

    public function actionAdd()
    {
        $task = new Task();
        $targetRefresh = (!empty($_GET['targetRefresh'])) ? $_GET['targetRefresh'] : null;
        if (!empty($_POST)) {
            $task->loadData($_POST);
            if ($task->validate()) {
                $task->save();
                return json_encode(['status' => 'success']);
            }
        }
        $formAfterValidate = $this->view->renderPartial('task/form', [
            'model' => $task,
            'action' => $this->route['action'],
            'urlForm' => '/task/add'
        ]);
        if ($targetRefresh) {
            return json_encode(['status' => 'new', 'data' => $formAfterValidate, 'targetRefresh' => $targetRefresh]);
        } else
            return json_encode(['status' => 'error', 'data' => $formAfterValidate]);

    }

    public function actionEdit()
    {
        $id = null;
        if (!empty($_GET['id'])) {
            //получаем id ресурса через get параметр
            //например по запросу из кнопки в строке таблицы
            $id = $_GET['id'];
        }
        if (empty($id))
            return null;
        $task = Task::findOne(['id' => $id]);

        $targetRefresh = (!empty($_GET['targetRefresh'])) ? $_GET['targetRefresh'] : null;
        if (!empty($_POST)) {
            $task->loadData($_POST);
            if ($task->validate()) {
                $task->save();
                return json_encode(['status' => 'success']);
            }
        }
        $formAfterValidate = $this->view->renderPartial('task/form', [
            'model' => $task,
            'action' => $this->route['action'],
            'urlForm' => '/task/edit?id='.$id
        ]);
        if ($targetRefresh) {
            return json_encode(['status' => 'new', 'data' => $formAfterValidate, 'targetRefresh' => $targetRefresh]);
        } else
            return json_encode(['status' => 'error', 'data' => $formAfterValidate]);

    }

}