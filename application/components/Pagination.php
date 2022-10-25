<?php


namespace application\components;


class Pagination
{
    private $max = 10;
    private $route;
    private $index = '';
    private $current_page;
    private $total;
    private $limit;
    private $nameQueryParam;
    private $queryParams = [];
    private $generateUrl;

    /**
     * Pagination constructor.
     * @param $route
     * @param $total
     * @param int $limit
     * @param string $nameQueryParam
     */
    public function __construct($route, $total, $limit = 10, $nameQueryParam = 'page')
    {
        $this->route = $route;
        $this->total = $total;
        $this->limit = $limit;
        $this->nameQueryParam = $nameQueryParam;
        $this->amount = $this->amount();
        $this->setCurrentPage();

        $this->queryParams = $this->route['queryParams'];
        if (array_key_exists($nameQueryParam, $this->queryParams)) {
            unset($this->queryParams[$nameQueryParam]);
        }
        $this->generateUrl = $route['matchUrl'] . '?' . http_build_query($this->queryParams);
    }

    public function get()
    {
        $links = null;
        $limits = $this->limits();

        $html = '<nav><ul class="pagination">';
        for ($page = $limits[0]; $page <= $limits[1]; $page++) {
            if ($page == $this->current_page) {
                $links .= '<li class="page-item active"><span class="page-link">' . $page . '</span></li>';
            } else {
                $links .= $this->generateItem($page);
            }
        }
        if (!is_null($links)) {
            if ($this->current_page > 1) {
                $links = $this->generateItem(1, 'В начало') . $links;
            }
            if ($this->current_page < $this->amount) {
                $links .= $this->generateItem($this->amount, 'В конец');
            }
        }
        $html .= $links . ' </ul></nav>';
        return $html;
    }

    private function generateItem($page, $text = null)
    {
        if (!$text) {
            $text = $page;
        }
        $separateParams = (count($this->queryParams) > 0) ? '&' : '';

        return '<li class="page-item"><a class="page-link" href="/' . $this->generateUrl . $separateParams . $this->nameQueryParam . '=' . $page . '">' . $text . '</a></li>';
    }

    private function limits()
    {
        $left = $this->current_page - round($this->max / 2);
        $start = $left > 0 ? $left : 1;
        if ($start + $this->max <= $this->amount) {
            $end = $start > 1 ? $start + $this->max : $this->max;
        } else {
            $end = $this->amount;
            $start = $this->amount - $this->max > 0 ? $this->amount - $this->max : 1;
        }
        return array($start, $end);
    }

    private function setCurrentPage()
    {
        if (isset($this->route['queryParams'][$this->nameQueryParam])) {
            $currentPage = $this->route['queryParams'][$this->nameQueryParam];
        } else {
            $currentPage = 1;
        }
        $this->current_page = $currentPage;
        if ($this->current_page > 0) {
            if ($this->current_page > $this->amount) {
                $this->current_page = $this->amount;
            }
        } else {
            $this->current_page = 1;
        }
    }

    private function amount()
    {
        return ceil($this->total / $this->limit);
    }
}