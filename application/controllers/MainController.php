<?php

namespace application\controllers;

use application\components\GridView;
use application\components\Pagination;
use application\models\Task;
use core\Controller;

class MainController extends Controller
{
    public function before()
    {
        $this->view->layout = 'default';
        return parent::before();
    }

    public function actionIndex()
    {
        $limitPage = 3;
        $start = 0;
        if (!empty($_GET['page'])) {
            $start = $_GET['page'] > 0 ? $_GET['page'] - 1 : 0;
        }
        $taskQuery = Task::findQuery(
            [],
            '*',
            [
                'paginate' => ['start' => $start * $limitPage, 'max' => $limitPage],
                'sortedField' => (!empty($_GET['sort'])) ? $_GET['sort'] : '',
                'allowedSortedColumns' => ['id', 'username', 'email', 'text', 'status']
            ]
        );
        $count = Task::count([], 'count(*)');
        $pagination = new Pagination($this->route, $count, 3, 'page');
        if (!empty($_POST['refresh'])){
            return $this->view->renderPartial('main/index',
                [
                    'pagination' => $pagination->get(),
                    'data' => $taskQuery,
                    'refreshTable' => true
                ]);
        }
        return $this->view->render('main/index',
            [
                'pagination' => $pagination->get(),
                'data' => $taskQuery,
                'refreshTable' => false
            ]);
    }

    public function actionTest()
    {
        $task = Task::findOne(['id' => 1]);
        $task->username= 'Gogo';
        $task->save();
        return 'test';
    }
}