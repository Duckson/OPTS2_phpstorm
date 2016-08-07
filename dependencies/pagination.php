<?php

class Pagination
{
    private $items_count;
    private $page;
    private $items_per_page;
    private $GET;
    private $link;

    function __construct($items_per_page, $GET, $current_link){
        $this->items_per_page = $items_per_page;
        $this->GET = $GET; // Сюда передаётся $_GET из создающей страницы, нужен для getHttpQuery()
        $this->page = (!empty($GET['page'])) ? $GET['page'] : 1;
        $this->link = $current_link;
    }

    public function setItemsCount($count){
        $this->items_count = $count;
    }

    public function getLimitStr(){
        return ' LIMIT ' . ($this->page - 1) * $this->items_per_page . ',' . $this->items_per_page;
    }

    private function getHttpQuery(){ /* Эта функция возвращает пригодную для использования в ссылках строку со списком всех $_GET параметров с обнулённым параметром page.
    																		 Нужна для сохранения параметров фильтра
                                         Используется именно она т.к. без неё, в пагинации пришлось бы использовать формы, в которые скрытыми инпутами помещаются параметры
                                         фильтра, для чего пришлось бы передавать информацию о данном фильтре*/
        if (!empty($this->GET)) {
            if (!empty($this->GET['page'])) {
                unset($this->GET['page']);
            }
            return '?' . http_build_query($this->GET) . '&page=';
        } else return '?page=';
    }

    public function getPagesStr(){
        $get = $this->getHttpQuery();
        $result = '';

        $result .=  '<ul class="pagination" id="pagination">';
        for ($i = 0; $i < $this->items_count / $this->items_per_page; $i++){
            $result .= '<li ' .(($this->page == $i + 1) ? "class=\"active\"" : NULL) . '><a
                        href="' . $this->link .  $get . ($i + 1) . '">' . ($i + 1) . '</a></li>';
        }
        $result .= '</ul>';
        return $result;
    }
}