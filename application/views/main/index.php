<?php
/* @var $this core\View */
/* @var $pagination */
/* @var bool $refreshTable */
/* @var $data */
$this->title = 'Главная';

use application\components\GridView;
use application\models\User;

?>

<?php if (!$refreshTable): ?>
    <!-- Button trigger modal -->
    <button type="button" class="btn btn-primary" data-request="/task/add" data-loadcontenttarget="#loadFormContent"
            data-toggle="modal" data-target="#modalForm">
        Добавить задачу
    </button>

    <!-- Modal -->
    <div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Описание задачи</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="wrapper_ajax" id="loadFormContent" data-ajax="ajax_content">

                    </div>
                </div>
            </div>
        </div>
    </div>

<?php endif; ?>

<?php if (!$refreshTable) echo '<div class="table-section" id="TableSection">' ?>
<?php
$columns = [
    'text' => [
        'label' => 'Текст задачи',
        'type' => 'field'
    ],
    'username' => [
        'label' => 'Имя',
        'type' => 'field'
    ],
    'email' => [
        'label' => 'Email',
        'type' => 'field'
    ],
    'status' => [
        'label' => 'Статус',
        'type' => 'field',
        'default' => [
            0 => 'Не проверено',
            1 => 'Проверено администратором'
        ]
    ],
];
if (User::isRole('admin')) {
    array_push($columns, [
        'label' => '',
        'type' => 'action',
        'action' => '/task/edit',
        'param' => 'id'
    ]);
}
echo GridView::render(['nameQuerySortedParam' => 'sort',
    'data' => $data,
    'columns' => $columns,
]) ?>
<?= $pagination; ?>
<?php if (!$refreshTable) echo '</div>' ?>