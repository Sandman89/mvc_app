<?php


namespace application\components;


class GridView
{

    private static $properties = array();
    private static $generateUrl;
    private static $otherqueryParams;
    private static $sortedParam;
    private static $sortedType;

    public static function render(array $properties = []): string
    {
        static::setProperty($properties);
        static::setUrlParam($properties['nameQuerySortedParam']);
        return self::build(static::$properties['data'], static::$properties['columns']);
    }

    public static function columnAction()
    {
        return 'adasd';
    }

    private static function setProperty(array $properties): void
    {
        static::$properties = $properties;
    }

    private static function setUrlParam(string $nameSortParam)
    {
        $url = ((!empty($_SERVER['HTTPS'])) ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        $urlQuery = parse_url($url, PHP_URL_QUERY);
        $queryParams = [];
        if (!empty($urlQuery)) {
            parse_str(parse_url($url, PHP_URL_QUERY), $queryParams);
        }
        if (array_key_exists($nameSortParam, $queryParams)) {
            if (strpos($queryParams[$nameSortParam], '-') !== false) {
                static::$sortedType = null;
                static::$sortedParam = str_replace('-', '', $queryParams[$nameSortParam]);
            } else {
                static::$sortedType = '-';
                static::$sortedParam = $queryParams[$nameSortParam];
            }
            unset($queryParams[$nameSortParam]);
        }
        static::$generateUrl = trim(parse_url($url, PHP_URL_PATH), '/');
        static::$otherqueryParams = http_build_query($queryParams);

    }


    private static function build(array $data, array $columns): string
    {

        $table = '<table class="table table-bordered table-striped">';
        if (!empty($columns)) {
            //заголовки таблицы
            $table .= '<thead>';
            foreach ($columns as $column => $value) {
                //сортировка от большего к меньшему по умолчанию
                $separateParams = (!empty(static::$otherqueryParams)) ? '&' : '';
                $link = static::$generateUrl . '?' . static::$otherqueryParams . $separateParams . 'sort=' . $column;
                $classLink = '';
                if (static::$sortedParam == $column) {
                    if (static::$sortedType == null) {
                       //если сброс сортировки
                        $classLink = 'desc';
                        $link = (!empty(static::$otherqueryParams)) ? static::$generateUrl . '?' . static::$otherqueryParams : static::$generateUrl;;
                    } else {
                        //если обратный порядок
                        $classLink = 'asc';
                        $link = static::$generateUrl . '?' . static::$otherqueryParams . $separateParams . 'sort=-' . $column;
                    }
                }
                if ($value['type'] == 'field') {
                    $table .= '<th><a class="'.$classLink.'" href="' . $link . '">' . $value['label'] . '</a></th>';
                }
                if ($value['type'] == 'action') {
                    $table .= '<th>' . $value['label'] . '</th>';
                }

            }
            $table .= '</thead>';
        }
        if (!empty($data)) {
            //тело таблицы строки и ячейки
            $table .= '<tbody>';
            foreach ($data as $row) {
                if (is_array($row) && !empty($row)) {
                    $table .= '<tr>';
                    $id_row = 0;
                    foreach ($columns as $column => $value) {
                        if ($value['type'] == 'field'){
                            //простые столбцы
                            foreach ($row as $key => $val) {
                                if ($key == 'id'){
                                    $id_row = $val;
                                }
                                if ($key == $column) {
                                    $td_value = $val;
                                    if (!empty($value['default'] )){
                                        if (array_key_exists($val,$value['default']))
                                            $td_value = $value['default'][$val];
                                    }
                                    $table .= '<td>' . $td_value . '</td>';
                                    break;
                                }
                            }
                        }
                        if ($value['type'] == 'action'){
                            //действия
                            $button = '<button type="button" class="btn btn-primary" 
                                    data-request="'.$value['action'].'?'.$value['param'].'='.$id_row.'" data-loadcontenttarget="#loadFormContent" data-toggle="modal" data-target="#modalForm">
                                         Редактировать
                                     </button>';
                            $table .= '<td>' .$button . '</td>';
                        }

                    }

                    $table .= '</tr>';
                }
            }
            $table .= '</tbody>';
        }
        $table .= '</table>';
        return $table;
    }
}